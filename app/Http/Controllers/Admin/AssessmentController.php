<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Assessment, AssessmentQuestion, AssessmentSubmission, AssessmentAnswer};
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::withCount(['questions', 'submissions'])->latest()->paginate(15);
        return view('admin.assessments.index', compact('assessments'));
    }

    public function create()
    {
        return view('admin.assessments.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'type' => 'required|in:code,english,iq',
            'max_score' => 'required|numeric|min:1',
        ]);

        $assessment = Assessment::create($request->only('title', 'description', 'type', 'max_score', 'is_published'));

        $this->saveQuestions($assessment, $request);

        return redirect('/admin/assessments')->with('success', 'Assessment created successfully.');
    }

    public function edit(Assessment $assessment)
    {
        $assessment->load('questions');
        return view('admin.assessments.form', compact('assessment'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $request->validate([
            'title' => 'required|max:255',
            'type' => 'required|in:code,english,iq',
            'max_score' => 'required|numeric|min:1',
        ]);

        $assessment->update($request->only('title', 'description', 'type', 'max_score', 'is_published'));

        // Rebuild questions
        $assessment->questions()->delete();
        $this->saveQuestions($assessment, $request);

        return redirect('/admin/assessments')->with('success', 'Assessment updated.');
    }

    public function togglePublish(Assessment $assessment)
    {
        $assessment->update(['is_published' => !$assessment->is_published]);
        return back()->with('success', ($assessment->is_published ? 'Published' : 'Unpublished') . ' successfully.');
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();
        return redirect('/admin/assessments')->with('success', 'Assessment deleted.');
    }

    // View all student submissions for an assessment
    public function submissions(Assessment $assessment)
    {
        $assessment->load('questions');
        // Retrieve and paginate submissions instead of dynamic loading
        $submissions = $assessment->submissions()
            ->with(['user.profile', 'answers'])
            ->latest()
            ->paginate(15);

        return view('admin.assessments.submissions', compact('assessment', 'submissions'));
    }

    // Grade a specific submission
    public function showGrade(AssessmentSubmission $submission)
    {
        $submission->load(['assessment.questions', 'answers.question', 'user.profile']);
        return view('admin.assessments.grade', compact('submission'));
    }

    public function saveGrade(Request $request, AssessmentSubmission $submission)
    {
        $totalScore = 0;
        if ($request->points) {
            foreach ($request->points as $answerId => $points) {
                $answer = AssessmentAnswer::find($answerId);
                if ($answer) {
                    $answer->update(['points_earned' => $points]);
                    $totalScore += (float) $points;
                }
            }
        }

        $submission->update([
            'score' => $totalScore,
            'status' => 'graded',
        ]);

        return redirect('/admin/assessments/' . $submission->assessment_id . '/submissions')
            ->with('success', 'Submission graded. Total: ' . $totalScore . '/' . $submission->assessment->max_score);
    }

    private function saveQuestions(Assessment $assessment, Request $request)
    {
        if ($request->questions) {
            foreach ($request->questions as $i => $text) {
                if (!$text)
                    continue;

                $options = null;
                if (($request->question_types[$i] ?? 'fill_in') === 'multiple_choice' && isset($request->options[$i])) {
                    $options = array_filter($request->options[$i]);
                }

                $assessment->questions()->create([
                    'question_text' => $text,
                    'question_type' => $request->question_types[$i] ?? 'fill_in',
                    'options' => $options,
                    'correct_answer' => $request->correct_answers[$i] ?? null,
                    'points' => $request->points[$i] ?? 1,
                    'order' => $i,
                ]);
            }
        }
    }
}
