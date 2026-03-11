<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Document;
use App\Models\Academy;
use App\Models\Cohort;
use App\Models\Enrollment;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentSubmission;
use App\Models\AssessmentAnswer;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admins
        \App\Models\Admin::firstOrCreate(
            ['email' => 'admin@orange.jo'],
            ['name' => 'Super Admin', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'super_admin']
        );
        \App\Models\Admin::firstOrCreate(
            ['email' => 'staff@orange.jo'],
            ['name' => 'Admin Staff', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'manager']
        );

        // 1.5 Create Default Document Requirements
        $docReqs = [
            ['name' => 'ID Card', 'description' => 'A valid national ID card.', 'is_required' => true],
            ['name' => 'Personal Photo', 'description' => 'A recent passport-sized photo.', 'is_required' => true],
            ['name' => 'Vaccination Certificate', 'description' => 'Proof of vaccination.', 'is_required' => false],
        ];

        $requirementIds = [];
        foreach ($docReqs as $req) {
            $created = \App\Models\DocumentRequirement::firstOrCreate(['name' => $req['name']], $req);
            $requirementIds[] = $created->id;
        }

        // 2. Create Academies & Cohorts (Orange Jordan specific)
        $academiesData = [
            [
                'name' => 'Amman Coding Academy',
                'location' => 'Amman',
                'description' => 'Intensive 6-month coding bootcamp for aspiring software developers.',
                'cohorts' => [
                    ['name' => 'Cohort 1- Full Stack Web', 'start_date' => Carbon::now()->addMonths(1), 'end_date' => Carbon::now()->addMonths(7), 'status' => 'active'],
                ]
            ],
            [
                'name' => 'Irbid Coding Academy',
                'location' => 'Irbid',
                'description' => 'Empowering the northern region with digital skills.',
                'cohorts' => [
                    ['name' => 'Cohort 1- Full Stack Web', 'start_date' => Carbon::now()->addMonths(1), 'end_date' => Carbon::now()->addMonths(7), 'status' => 'active'],
                ]
            ],
            [
                'name' => 'Zarqa Coding Academy',
                'location' => 'Zarqa',
                'description' => 'A digital fabrication laboratory for hardware innovation.',
                'cohorts' => [
                    ['name' => 'Cohort 1- Full Stack Web', 'start_date' => Carbon::now()->addMonths(1), 'end_date' => Carbon::now()->addMonths(7), 'status' => 'active'],
                ]
            ],

            [
                'name' => 'Balqa  Coding Academy',
                'location' => 'Balqa',
                'description' => 'A digital fabrication laboratory for hardware innovation.',
                'cohorts' => [
                    ['name' => 'Cohort 1- Full Stack Web', 'start_date' => Carbon::now()->addMonths(1), 'end_date' => Carbon::now()->addMonths(7), 'status' => 'active'],
                ]
            ],

        ];

        $cohortList = collect();
        foreach ($academiesData as $acData) {
            $academy = Academy::firstOrCreate(
                ['name' => $acData['name']],
                ['location' => $acData['location']]
            );
            foreach ($acData['cohorts'] as $c) {
                $cohortList->push($academy->cohorts()->create($c));
            }
        }

        // 3. Create Assessments (Exams)
        $assessmentsData = [
            [
                'title' => 'English Proficiency Test',
                'description' => 'A basic test to evaluate English reading and grammar skills.',
                'type' => 'english',
                'max_score' => 50,
                'is_published' => true,
                'questions' => [
                    ['text' => 'What is the correct form: "She ___ to the store yesterday."', 'type' => 'multiple_choice', 'options' => ['go', 'goes', 'went', 'gone'], 'correct' => 'went', 'points' => 10],
                    ['text' => 'Choose the synonym for "Happy"', 'type' => 'multiple_choice', 'options' => ['Sad', 'Joyful', 'Angry', 'Tired'], 'correct' => 'Joyful', 'points' => 10],
                    ['text' => 'Write a short paragraph about your future goals.', 'type' => 'fill_in', 'options' => null, 'correct' => null, 'points' => 30],
                ]
            ],
            [
                'title' => 'Basic Logical Reasoning (IQ)',
                'description' => 'A short test to assess problem-solving skills.',
                'type' => 'iq',
                'max_score' => 40,
                'is_published' => true,
                'questions' => [
                    ['text' => 'If 2x = 10, what is x?', 'type' => 'multiple_choice', 'options' => ['2', '4', '5', '10'], 'correct' => '5', 'points' => 10],
                    ['text' => 'What comes next in the sequence? 2, 4, 8, 16, ___', 'type' => 'fill_in', 'options' => null, 'correct' => '32', 'points' => 10],
                    ['text' => 'Explain how you would sort a deck of cards.', 'type' => 'fill_in', 'options' => null, 'correct' => null, 'points' => 20],
                ]
            ],
            [
                'title' => 'Introductory Coding Challenge',
                'description' => 'Write a basic script in any language you prefer.',
                'type' => 'code',
                'max_score' => 100,
                'is_published' => true,
                'questions' => [
                    ['text' => 'Write a function that returns the sum of two numbers.', 'type' => 'code', 'options' => null, 'correct' => null, 'points' => 100],
                ]
            ]
        ];

        $assessmentList = collect();
        foreach ($assessmentsData as $idx => $aData) {
            $assessment = Assessment::firstOrCreate(
                ['title' => $aData['title']],
                ['description' => $aData['description'], 'type' => $aData['type'], 'max_score' => $aData['max_score'], 'is_published' => $aData['is_published']]
            );
            $assessmentList->push($assessment);
            foreach ($aData['questions'] as $qIdx => $q) {
                $assessment->questions()->firstOrCreate([
                    'question_text' => $q['text']
                ], [
                    'question_type' => $q['type'],
                    'options' => $q['options'],
                    'correct_answer' => $q['correct'],
                    'points' => $q['points'],
                    'order' => $qIdx
                ]);
            }
        }

        // 4. Create Questionnaire
        $questionnaire = Questionnaire::firstOrCreate(
            ['title' => 'Pre-Enrollment Survey'],
            ['description' => 'Please answer these questions honestly.', 'is_published' => true]
        );
        $surveyQuestions = [
            ['text' => 'Do you have previous coding experience?', 'type' => 'boolean'],
            ['text' => 'Are you currently employed?', 'type' => 'boolean'],
            ['text' => 'Why do you want to join Orange Academy?', 'type' => 'text'],
        ];
        foreach ($surveyQuestions as $idx => $sq) {
            $questionnaire->questions()->firstOrCreate(['question_text' => $sq['text']], ['question_type' => $sq['type'], 'order' => $idx]);
        }


        // 5. Generate 100 Fake Students with Profiles, Documents, Enrollments, Assessments, and Questionnaires
        User::factory(100)->create(['role' => 'student'])->each(function ($student) use ($cohortList, $assessmentList, $questionnaire, $requirementIds) {

            // Profile
            Profile::factory()->create(['user_id' => $student->id]);

            // Documents (1 to 3 documents)
            $assignedDocs = (array) array_rand(array_flip($requirementIds), rand(1, count($requirementIds)));
            foreach ($assignedDocs as $reqId) {
                Document::factory()->create([
                    'user_id' => $student->id,
                    'document_requirement_id' => $reqId
                ]);
            }

            // Enrollment (assign to 1 random cohort)
            Enrollment::factory()->create([
                'user_id' => $student->id,
                'cohort_id' => $cohortList->random()->id
            ]);

            // Assessments (randomly take 1 to 3 tests)
            $testsTaken = $assessmentList->random(rand(1, 3));
            foreach ($testsTaken as $test) {
                $submission = AssessmentSubmission::factory()->create([
                    'user_id' => $student->id,
                    'assessment_id' => $test->id,
                    'status' => rand(0, 1) ? 'submitted' : 'graded',
                    'score' => null
                ]);

                $totalEarned = 0;
                // Generate answers for the test's questions
                foreach ($test->questions as $question) {
                    $earned = 0;
                    $answerText = 'Fake automated answer';

                    if ($question->question_type == 'multiple_choice' && $question->options) {
                        $answerText = $question->options[array_rand($question->options)];
                        $earned = ($answerText === $question->correct_answer) ? $question->points : 0;
                    } elseif ($question->question_type == 'code') {
                        $answerText = "function sum(a, b) {\n  return a + b;\n}";
                        $earned = rand(0, $question->points);
                    } else {
                        $earned = rand(0, $question->points);
                    }

                    if ($submission->status === 'graded') {
                        $totalEarned += $earned;
                    } else {
                        $earned = null;
                    }

                    AssessmentAnswer::create([
                        'submission_id' => $submission->id,
                        'question_id' => $question->id,
                        'answer_text' => $answerText,
                        'points_earned' => $earned
                    ]);
                }

                if ($submission->status === 'graded') {
                    $submission->update(['score' => $totalEarned]);
                }
            }

            // Questionnaire Answers
            foreach ($questionnaire->questions as $q) {
                $answerText = $q->question_type === 'boolean' ? (rand(0, 1) ? 'Yes' : 'No') : 'I want to build my career in tech.';
                Answer::create([
                    'user_id' => $student->id,
                    'question_id' => $q->id,
                    'answer_text' => $answerText
                ]);
            }
        });
    }
}
