<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academy;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    public function index()
    {
        $academies = Academy::withCount('cohorts')->latest()->paginate(15);
        return view('admin.academies.index', compact('academies'));
    }

    public function create()
    {
        return view('admin.academies.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:academies,name',
            'location' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:academies,code|regex:/^[A-Z0-9\-]+$/',
        ], [
            'name.required' => 'Academy name is required.',
            'name.unique' => 'This academy name already exists.',
            'location.required' => 'Location is required.',
            'code.regex' => 'Code can only contain uppercase letters, numbers, and hyphens.',
        ]);
        Academy::create($request->only('name', 'location', 'code'));
        return redirect('/admin/academies')->with('success', 'Academy created.');
    }

    public function edit(Academy $academy)
    {
        return view('admin.academies.form', compact('academy'));
    }

    public function update(Request $request, Academy $academy)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:academies,name,' . $academy->id,
            'location' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:academies,code,' . $academy->id . '|regex:/^[A-Z0-9\-]+$/',
        ], [
            'name.required' => 'Academy name is required.',
            'name.unique' => 'This academy name already exists.',
            'location.required' => 'Location is required.',
            'code.regex' => 'Code can only contain uppercase letters, numbers, and hyphens.',
        ]);
        $academy->update($request->only('name', 'location', 'code'));
        return redirect('/admin/academies')->with('success', 'Academy updated.');
    }

    public function destroy(Academy $academy)
    {
        $academy->delete();
        return redirect('/admin/academies')->with('success', 'Academy deleted.');
    }
}
