<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\{Academy, Document, Assessment, AssessmentSubmission, AssessmentAnswer, Questionnaire, Activity, CoursatCertificate};
use App\Notifications\RegistrationSubmittedNotification;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $user->load(['profile', 'enrollments.cohort.academy', 'assessmentSubmissions.assessment', 'documents.documentRequirement']);
        $user->loadCount([
            'documents as rejected_docs_count' => fn($q) => $q->where('is_verified', false)->whereNotNull('rejection_reason'),
            'documents as uploaded_required_docs_count' => fn($q) => $q->whereHas('documentRequirement', fn($dq) => $dq->where('is_required', true)),
        ]);

        // Find rejected documents
        $rejectedDocuments = $user->documents()->where('is_verified', false)->whereNotNull('rejection_reason')->get();

        // Dynamic counts for dashboard statistics
        $totalRequiredDocuments = \App\Models\DocumentRequirement::where('is_required', true)->count();
        $totalPublishedAssessments = Assessment::where('is_published', true)->count();

        return view('student.dashboard', compact(
            'user', 
            'rejectedDocuments', 
            'totalRequiredDocuments', 
            'totalPublishedAssessments'
        ));
    }

    public function step($step)
    {
        $user = auth()->user();
        $targetStep = (int) $step;

        // Check if email is verified
        if (!$user->email_verified_at) {
            return redirect('/student/verify-email')->with('error', 'Please verify your email address first.');
        }

        // Check if phone is verified
        if ($user->profile && !$user->profile->phone_verified) {
            return redirect('/student/verify-phone')->with('error', 'Please verify your phone number first.');
        }

        // Eager load counts for progression logic
        $user->loadCount([
            'documents as uploaded_required_docs_count' => fn($q) => $q->whereHas('documentRequirement', fn($dq) => $dq->where('is_required', true)),
            'documents as rejected_docs_count' => fn($q) => $q->where('is_verified', false)->whereNotNull('rejection_reason'),
            'enrollments',
            'assessmentSubmissions as completed_assessments_count' => fn($q) => $q->whereIn('status', ['submitted', 'graded']),
            'answers',
            'coursatCertificates as coursat_count'
        ]);

        $completedSteps = $this->getCompletedSteps($user);

        if ($targetStep > 1) {
            $requiredPreviousStep = $targetStep - 1;
            if (!in_array($requiredPreviousStep, $completedSteps)) {
                $maxAllowed = count($completedSteps) > 0 ? max($completedSteps) + 1 : 1;
                $redirectStep = min($maxAllowed, 7);

                return redirect()->route('student.step', $redirectStep)
                    ->with('error', "Please complete Step $redirectStep first before proceeding.");
            }
        }

        $data = ['step' => $targetStep, 'user' => $user];

        switch ($targetStep) {
            case 1:
                $data['profile'] = $user->profile ?? $user->profile()->create();
                break;
            case 2:
                $data['documents'] = $user->documents;
                $data['requirements'] = \App\Models\DocumentRequirement::all();
                break;
            case 3:
                $data['certificates'] = $user->coursatCertificates->keyBy('course_name');
                break;
            case 4:
                $data['academies'] = Academy::with(['cohorts' => fn($q) => $q->where('status', 'active')])->get();
                $data['enrollment'] = $user->enrollments()->first();
                break;
            case 5:
                $data['assessments'] = Assessment::where('is_published', true)->withCount('questions')->get();
                $data['submissions'] = $user->assessmentSubmissions->keyBy('assessment_id');
                break;
            case 6:
                $data['questionnaire'] = Questionnaire::where('is_published', true)->with('questions')->first();
                $data['answers'] = $user->answers->keyBy('question_id');
                break;
            case 7:
                $data['user'] = $user->load(['profile', 'documents', 'coursatCertificates', 'enrollments.cohort.academy', 'assessmentSubmissions.assessment', 'answers.question']);
                break;
        }

        return view('student.registration.step' . $targetStep, $data);
    }

    private function getCompletedSteps($user): array
    {
        $completed = [0];

        // Step 1: Profile
        if ($user->profile && $user->profile->first_name_en) {
            $completed[] = 1;
        } else {
            return $completed;
        }

        // Step 2: Documents
        $requiredCount = \App\Models\DocumentRequirement::where('is_required', true)->count();
        $uploadedRequiredCount = $user->uploaded_required_docs_count ?? $user->documents()->whereHas('documentRequirement', fn($q) => $q->where('is_required', true))->count();

        if ($uploadedRequiredCount >= $requiredCount) {
            $hasRejection = ($user->rejected_docs_count ?? $user->documents()->where('is_verified', false)->whereNotNull('rejection_reason')->count()) > 0;
            if ($hasRejection) {
                return $completed;
            }
            $completed[] = 2;
        } else {
            return $completed;
        }

        // Step 3: Orange Coursat
        $uploadedCertsCount = $user->coursat_count ?? $user->coursatCertificates()->count();
        if ($uploadedCertsCount >= 3) {
            $completed[] = 3;
        } else {
            return $completed;
        }

        // Step 4: Enrollment
        if (($user->enrollments_count ?? $user->enrollments()->count()) > 0) {
            $completed[] = 4;
        } else {
            return $completed;
        }

        // Step 5: Assessments
        $activeAssessmentsCount = Assessment::where('is_published', true)->count();
        $completedAssessmentsCount = $user->completed_assessments_count ?? $user->assessmentSubmissions()->whereIn('status', ['submitted', 'graded'])->count();

        if ($activeAssessmentsCount === 0 || $completedAssessmentsCount >= $activeAssessmentsCount) {
            $completed[] = 5;
        } else {
            return $completed;
        }

        // Step 6: Questionnaire
        $questionnaire = Questionnaire::where('is_published', true)->first();
        if (!$questionnaire) {
            $completed[] = 6;
        } else {
            $requiredQCount = $questionnaire->questions()->count();
            $answersCount = $user->answers_count ?? $user->answers()->count();
            if ($answersCount >= $requiredQCount) {
                $completed[] = 6;
            }
        }

        return $completed;
    }

    public function saveProfile(Request $request)
    {
        // Normalize phone number before validation
        $request->merge([
            'phone' => \App\Helpers\PhoneHelper::normalize($request->phone),
            'relative1_phone' => \App\Helpers\PhoneHelper::normalize($request->relative1_phone),
            'relative2_phone' => \App\Helpers\PhoneHelper::normalize($request->relative2_phone),
        ]);

        $request->validate([
            'first_name_en' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/',
            'second_name_en' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\-]+$/',
            'third_name_en' => 'nullable|string|max:100|regex:/^[a-zA-Z\s\-]+$/',
            'last_name_en' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/',
            'first_name_ar' => 'nullable|string|max:100|regex:/^[\p{Arabic}\s\-]+$/u',
            'second_name_ar' => 'nullable|string|max:100|regex:/^[\p{Arabic}\s\-]+$/u',
            'third_name_ar' => 'nullable|string|max:100|regex:/^[\p{Arabic}\s\-]+$/u',
            'last_name_ar' => 'nullable|string|max:100|regex:/^[\p{Arabic}\s\-]+$/u',
            'phone' => 'required|string|regex:/^\+9627[789][0-9]{7}$/|unique:profiles,phone,' . auth()->user()->profile->id,
            'id_number' => 'required|numeric|digits:10|unique:profiles,id_number,' . auth()->user()->profile->id,
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before_or_equal:-15 years|after_or_equal:-100 years',
            'nationality' => 'required|string|max:50',
            'country' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'neighborhood' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:100|in:High School,Diploma,Bachelor,Master,PhD,Other',
            'university' => 'nullable|string|max:150',
            'field_of_study' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:100',
            'is_graduated' => 'nullable|boolean',
            'graduation_year' => 'nullable|string|max:4',
            'expected_graduation_year' => 'nullable|string|max:4',
            'gpa_type' => 'nullable|in:percentage,gpa_4,grade',
            'gpa_value' => 'nullable|string|max:20',
            'relative1_name' => 'required|string|max:100',
            'relative1_relation' => 'required|in:father,mother,brother,sister,other',
            'relative1_phone' => 'required|string|regex:/^\+9627[789][0-9]{7}$/',
            'relative2_name' => 'nullable|string|max:100',
            'relative2_relation' => 'nullable|in:father,mother,brother,sister,other',
            'relative2_phone' => 'nullable|string|regex:/^\+9627[789][0-9]{7}$/',
            'has_accessibility_needs' => 'required|boolean',
            'accessibility_details' => 'required_if:has_accessibility_needs,1|nullable|string|max:1000',
            'has_illness' => 'required|boolean',
            'illness_details' => 'required_if:has_illness,1|nullable|string|max:1000',
        ], [
            'first_name_en.required' => 'First name (English) is required.',
            'first_name_en.regex' => 'First name (English) can only contain letters, spaces, and hyphens.',
            'second_name_en.regex' => 'Second name (English) can only contain letters, spaces, and hyphens.',
            'third_name_en.regex' => 'Third name (English) can only contain letters, spaces, and hyphens.',
            'last_name_en.required' => 'Family name (English) is required.',
            'last_name_en.regex' => 'Family name (English) can only contain letters, spaces, and hyphens.',
            'first_name_ar.regex' => 'First name (Arabic) can only contain Arabic letters.',
            'second_name_ar.regex' => 'Second name (Arabic) can only contain Arabic letters.',
            'third_name_ar.regex' => 'Third name (Arabic) can only contain Arabic letters.',
            'last_name_ar.regex' => 'Family name (Arabic) can only contain Arabic letters.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid Jordanian phone number (9 digits starting with 77, 78, or 79).',
            'phone.unique' => 'This phone number is already registered.',
            'relative1_name.required' => 'Emergency contact (1) name is required.',
            'relative1_relation.required' => 'Emergency contact (1) relationship is required.',
            'relative1_phone.required' => 'Emergency contact (1) phone is required.',
            'relative1_phone.regex' => 'Please enter a valid Jordanian phone number for emergency contact (1).',
            'id_number.required' => 'ID number is required.',
            'id_number.numeric' => 'ID number must contain only numbers.',
            'id_number.digits' => 'ID number must be exactly 10 digits.',
            'id_number.unique' => 'This ID number is already registered.',
            'gender.required' => 'Gender is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before_or_equal' => 'You must be at least 15 years old.',
            'date_of_birth.after_or_equal' => 'Please enter a valid date of birth.',
            'nationality.required' => 'Nationality is required.',
            'country.required' => 'Country is required.',
            'city.required' => 'City is required.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'education_level.in' => 'Please select a valid education level.',
            'accessibility_details.required_if' => 'Please provide details about your accessibility needs.',
            'illness_details.required_if' => 'Please provide details about your illness.',
        ]);
        $profile = auth()->user()->profile ?? auth()->user()->profile()->create();
        $profile->update($request->only([
            'first_name_en',
            'second_name_en',
            'third_name_en',
            'last_name_en',
            'first_name_ar',
            'second_name_ar',
            'third_name_ar',
            'last_name_ar',
            'phone',
            'id_number',
            'gender',
            'date_of_birth',
            'nationality',
            'country',
            'city',
            'neighborhood',
            'address',
            'education_level',
            'university',
            'field_of_study',
            'major',
            'is_graduated',
            'graduation_year',
            'expected_graduation_year',
            'gpa_type',
            'gpa_value',
            'relative1_name',
            'relative1_relation',
            'relative1_phone',
            'relative2_name',
            'relative2_relation',
            'relative2_phone',
            'has_accessibility_needs',
            'accessibility_details',
            'has_illness',
            'illness_details',
        ]));

        Activity::log([
            'user_id' => auth()->id(),
            'type' => 'profile',
            'action' => 'updated',
            'title' => 'Profile Updated',
            'description' => 'User updated their personal information',
        ]);

        return redirect('/student/registration/step/2')->with('success', 'Profile saved successfully!');
    }

    public function saveDocuments(Request $request)
    {
        $requirements = \App\Models\DocumentRequirement::all();

        // Build validation rules dynamically
        $rules = [];
        $messages = [];
        foreach ($requirements as $req) {
            // Only require a file if they haven't uploaded it yet AND it is a required document
            $hasUploaded = auth()->user()->documents()->where('document_requirement_id', $req->id)->exists();
            if ($req->is_required && !$hasUploaded) {
                $rules["documents.{$req->id}"] = 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120';
                $messages["documents.{$req->id}.required"] = "The {$req->name} document is required.";
                $messages["documents.{$req->id}.mimes"] = "The {$req->name} must be a PDF, JPG, PNG, or WebP file.";
                $messages["documents.{$req->id}.max"] = "The {$req->name} must not exceed 5MB.";
            } else {
                $rules["documents.{$req->id}"] = 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120';
                $messages["documents.{$req->id}.mimes"] = "The {$req->name} must be a PDF, JPG, PNG, or WebP file.";
                $messages["documents.{$req->id}.max"] = "The {$req->name} must not exceed 5MB.";
            }
        }

        $request->validate($rules, $messages);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $reqId => $file) {
                $path = $file->store('documents/' . auth()->id(), 'public');

                // Overwrite or create
                auth()->user()->documents()->updateOrCreate(
                    ['document_requirement_id' => $reqId],
                    ['file_path' => $path, 'is_verified' => false, 'rejection_reason' => null]
                );
            }

            Activity::log([
                'user_id' => auth()->id(),
                'type' => 'documents',
                'action' => 'uploaded',
                'title' => 'Documents Uploaded',
                'description' => 'User uploaded required documents',
            ]);
        }

        return redirect('/student/registration/step/3')->with('success', 'Documents uploaded successfully!');
    }

    public function saveCoursat(Request $request)
    {
        $user = auth()->user();
        $certificates = ['html', 'css', 'javascript'];
        $rules = [];
        $messages = [];

        foreach ($certificates as $courseName) {
            $rules["certificate_{$courseName}"] = 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120';
            $messages["certificate_{$courseName}.mimes"] = "The {$courseName} certificate must be a PDF, JPG, PNG, or WebP.";
            $messages["certificate_{$courseName}.max"] = "The {$courseName} certificate must not exceed 5MB.";
        }

        $request->validate($rules, $messages);

        $uploadedAny = false;
        foreach ($certificates as $courseName) {
            $inputName = "certificate_{$courseName}";
            if ($request->hasFile($inputName)) {
                $path = $request->file($inputName)->store('coursat_certificates/' . $user->id, 'public');
                $user->coursatCertificates()->updateOrCreate(
                    ['course_name' => $courseName],
                    ['file_path' => $path]
                );
                $uploadedAny = true;
            }
        }

        if ($uploadedAny) {
            Activity::log([
                'user_id' => $user->id,
                'type' => 'coursat',
                'action' => 'uploaded',
                'title' => 'Coursat Certificates Uploaded',
                'description' => 'User uploaded Orange Coursat certificates',
            ]);
        }

        // Check if all exactly required 3 certs are uploaded
        $uploadedCerts = $user->coursatCertificates()->whereIn('course_name', $certificates)->pluck('course_name')->toArray();
        if (count(array_intersect($certificates, $uploadedCerts)) !== count($certificates)) {
            return redirect('/student/registration/step/3')->with('error', 'You must upload all 3 certificates (HTML, CSS, JavaScript) to successfully proceed to the next step.');
        }

        return redirect('/student/registration/step/4')->with('success', 'All 3 certificates validated and uploaded successfully!');
    }

    public function saveEnrollment(Request $request)
    {
        $request->validate([
            'cohort_id' => 'required|exists:cohorts,id',
        ], [
            'cohort_id.required' => 'Please select a cohort.',
            'cohort_id.exists' => 'The selected cohort is invalid.',
        ]);

        $cohort = \App\Models\Cohort::find($request->cohort_id);

        if (!$cohort || $cohort->status !== 'active') {
            return back()->withErrors(['cohort_id' => 'This cohort is not available for enrollment.']);
        }

        auth()->user()->enrollments()->updateOrCreate(
            ['cohort_id' => $request->cohort_id],
            ['status' => 'applied']
        );

        Activity::log([
            'user_id' => auth()->id(),
            'type' => 'enrollment',
            'action' => 'applied',
            'title' => 'Applied for Cohort',
            'description' => 'User applied for cohort: ' . $cohort->name,
            'properties' => ['cohort_id' => $cohort->id, 'academy_id' => $cohort->academy_id],
        ]);

        return redirect('/student/registration/step/5')->with('success', 'Application submitted!');
    }

    // --- Assessments (exam taking) ---
    public function takeAssessment(Assessment $assessment)
    {
        if (!$assessment->is_published)
            abort(404);

        $user = auth()->user();
        $submission = AssessmentSubmission::firstOrCreate(
            ['assessment_id' => $assessment->id, 'user_id' => $user->id],
            ['status' => 'in_progress']
        );

        // If already submitted, show results
        if ($submission->status !== 'in_progress') {
            return view('student.assessments.result', compact('assessment', 'submission'));
        }

        $assessment->load('questions');
        $existingAnswers = $submission->answers->keyBy('question_id');

        return view('student.assessments.take', compact('assessment', 'submission', 'existingAnswers'));
    }

    public function submitAssessment(Request $request, Assessment $assessment)
    {
        $user = auth()->user();
        $submission = AssessmentSubmission::where('assessment_id', $assessment->id)
            ->where('user_id', $user->id)->firstOrFail();

        if ($submission->status !== 'in_progress') {
            return back()->with('error', 'Already submitted.');
        }

        $totalScore = 0;

        // Save and grade all answers
        if ($request->answers) {
            foreach ($request->answers as $questionId => $text) {
                // Convert null to empty string explicitly (due to ConvertEmptyStringsToNull middleware)
                $safeText = (string) $text;

                // Find the question to determine points
                $question = \App\Models\AssessmentQuestion::find($questionId);
                $pointsEarned = 0;

                if ($question) {
                    if ($question->question_type === 'multiple_choice') {
                        // Auto-grade exact match
                        if (trim(strtolower($safeText)) === trim(strtolower((string) $question->correct_answer))) {
                            $pointsEarned = $question->points;
                        }
                    } else {
                        // For written/code questions, we give full marks down to auto-grade requirement
                        $pointsEarned = ltrim($safeText) !== '' ? $question->points : 0;
                    }
                }

                $totalScore += $pointsEarned;

                AssessmentAnswer::updateOrCreate(
                    ['submission_id' => $submission->id, 'question_id' => $questionId],
                    ['answer_text' => $safeText, 'points_earned' => $pointsEarned]
                );
            }
        }

        $submission->update([
            'status' => 'graded',
            'score' => $totalScore,
            'submitted_at' => now()
        ]);

        return redirect('/student/registration/step/5')->with('success', 'Assessment submitted and graded automatically!');
    }

    public function saveQuestionnaire(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|string|max:5000',
        ], [
            'answers.required' => 'Please answer all questions.',
            'answers.*.max' => 'Answer cannot exceed 5000 characters.',
        ]);

        // Validate that all question IDs exist
        $validQuestionIds = \App\Models\Question::pluck('id')->toArray();
        foreach ($request->answers as $questionId => $text) {
            if (!in_array($questionId, $validQuestionIds)) {
                return back()->withErrors(['answers' => 'Invalid question ID.']);
            }
        }

        foreach ($request->answers as $questionId => $text) {
            auth()->user()->answers()->updateOrCreate(
                ['question_id' => $questionId],
                ['answer_text' => $text]
            );
        }

        Activity::log([
            'user_id' => auth()->id(),
            'type' => 'questionnaire',
            'action' => 'completed',
            'title' => 'Questionnaire Completed',
            'description' => 'User completed the questionnaire',
        ]);

        return redirect('/student/registration/step/7')->with('success', 'Questionnaire completed!');
    }

    public function submitRegistration()
    {
        $user = auth()->user();
        $completedSteps = $this->getCompletedSteps($user);

        // To submit, the user must have completed steps 1, 2, 3, 4, 5, and 6
        $requiredSteps = [1, 2, 3, 4, 5, 6];
        $missingSteps = array_diff($requiredSteps, $completedSteps);

        if (!empty($missingSteps)) {
            $firstMissing = min($missingSteps);
            return redirect()->route('student.step', $firstMissing)
                ->with('error', "Cannot submit registration. Please complete Step $firstMissing first.");
        }

        $enrollment = $user->enrollments()->first();
        if ($enrollment) {
            $enrollment->update(['status' => 'applied', 'enrolled_at' => now()]);
        }

        // Log activity
        Activity::log([
            'user_id' => $user->id,
            'type' => 'submission',
            'action' => 'submitted',
            'title' => 'Registration Submitted',
            'description' => 'User submitted their registration application',
            'properties' => [
                'enrollment_id' => $enrollment->id ?? null,
                'cohort_id' => $enrollment->cohort_id ?? null,
            ],
        ]);

        // Send confirmation email
        try {
            $user->notify(new RegistrationSubmittedNotification($user));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info('Registration submitted email failed for: ' . $user->email);
        }

        return redirect()->route('student.registration.success');
    }

    public function registrationSuccess()
    {
        $user = auth()->user()->load(['profile', 'enrollments.cohort.academy', 'documents']);
        $enrollment = $user->enrollments()->first();

        // Check if there are any rejections
        $hasRejection = $user->documents()->where('is_verified', false)->whereNotNull('rejection_reason')->exists();
        if ($hasRejection) {
            return redirect()->route('student.step', 2)->with('warning', 'One or more of your documents were rejected. Please update them to proceed.');
        }

        if (!$enrollment || $enrollment->status === 'pending') {
            return redirect()->route('student.dashboard');
        }

        return view('student.registration.success', compact('user', 'enrollment'));
    }
}
