<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Academy, Cohort};
use Illuminate\Http\Request;

class CohortController extends Controller
{
    public function index()
    {
        $cohorts = Cohort::with('academy')->withCount('enrollments')->latest()->paginate(15);
        return view('admin.cohorts.index', compact('cohorts'));
    }

    public function create()
    {
        $academies = Academy::all();
        return view('admin.cohorts.form', compact('academies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academy_id' => 'required|exists:academies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:planning,active,completed,cancelled',
        ], [
            'academy_id.required' => 'Please select an academy.',
            'academy_id.exists' => 'The selected academy is invalid.',
            'name.required' => 'Cohort name is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after' => 'End date must be after start date.',
            'status.required' => 'Status is required.',
        ]);
        Cohort::create($request->only('academy_id', 'name', 'description', 'start_date', 'end_date', 'status'));
        return redirect('/admin/cohorts')->with('success', 'Cohort created.');
    }

    public function edit(Cohort $cohort)
    {
        $academies = Academy::all();
        return view('admin.cohorts.form', compact('cohort', 'academies'));
    }

    public function update(Request $request, Cohort $cohort)
    {
        $request->validate([
            'academy_id' => 'required|exists:academies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:planning,active,completed,cancelled',
        ], [
            'academy_id.required' => 'Please select an academy.',
            'academy_id.exists' => 'The selected academy is invalid.',
            'name.required' => 'Cohort name is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'end_date.after' => 'End date must be after start date.',
            'status.required' => 'Status is required.',
        ]);
        $cohort->update($request->only('academy_id', 'name', 'description', 'start_date', 'end_date', 'status'));
        return redirect('/admin/cohorts')->with('success', 'Cohort updated.');
    }

    public function destroy(Cohort $cohort)
    {
        $cohort->delete();
        return redirect('/admin/cohorts')->with('success', 'Cohort deleted.');
    }
}
