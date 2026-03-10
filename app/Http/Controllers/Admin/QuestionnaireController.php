<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Questionnaire, Question};
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questionnaires = Questionnaire::withCount('questions')->latest()->paginate(15);
        return view('admin.questionnaires.index', compact('questionnaires'));
    }

    public function create()
    {
        return view('admin.questionnaires.form');
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);
        $q = Questionnaire::create($request->only('title', 'description', 'is_published'));

        if ($request->questions) {
            foreach ($request->questions as $i => $text) {
                if ($text) {
                    $q->questions()->create([
                        'question_text' => $text,
                        'question_type' => $request->question_types[$i] ?? 'text',
                        'order' => $i,
                    ]);
                }
            }
        }
        return redirect('/admin/questionnaires')->with('success', 'Questionnaire created.');
    }

    public function edit(Questionnaire $questionnaire)
    {
        $questionnaire->load('questions');
        return view('admin.questionnaires.form', compact('questionnaire'));
    }

    public function update(Request $request, Questionnaire $questionnaire)
    {
        $request->validate(['title' => 'required|max:255']);
        $questionnaire->update($request->only('title', 'description', 'is_published'));

        $questionnaire->questions()->delete();
        if ($request->questions) {
            foreach ($request->questions as $i => $text) {
                if ($text) {
                    $questionnaire->questions()->create([
                        'question_text' => $text,
                        'question_type' => $request->question_types[$i] ?? 'text',
                        'order' => $i,
                    ]);
                }
            }
        }
        return redirect('/admin/questionnaires')->with('success', 'Questionnaire updated.');
    }

    public function destroy(Questionnaire $questionnaire)
    {
        $questionnaire->delete();
        return redirect('/admin/questionnaires')->with('success', 'Questionnaire deleted.');
    }

    public function answers(Questionnaire $questionnaire)
    {
        $questionnaire->load(['questions.answers.user.profile']);
        return view('admin.questionnaires.answers', compact('questionnaire'));
    }
}
