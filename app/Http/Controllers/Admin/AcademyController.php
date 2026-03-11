<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'location_link' => 'nullable|url|max:2048',
            'registration_rules' => 'nullable|string',
        ], [
            'name.required' => 'Academy name is required.',
            'name.unique' => 'This academy name already exists.',
            'location.required' => 'Location is required.',
            'code.regex' => 'Code can only contain uppercase letters, numbers, and hyphens.',
            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'The image must not be larger than 2MB.',
            'location_link.url' => 'The location link must be a valid URL.',
        ]);

        $data = $request->only('name', 'location', 'code', 'location_link', 'registration_rules');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('academies', 'public');
        }

        Academy::create($data);
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'location_link' => 'nullable|url|max:2048',
            'registration_rules' => 'nullable|string',
        ], [
            'name.required' => 'Academy name is required.',
            'name.unique' => 'This academy name already exists.',
            'location.required' => 'Location is required.',
            'code.regex' => 'Code can only contain uppercase letters, numbers, and hyphens.',
            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'The image must not be larger than 2MB.',
            'location_link.url' => 'The location link must be a valid URL.',
        ]);

        $data = $request->only('name', 'location', 'code', 'location_link', 'registration_rules');

        if ($request->hasFile('image')) {
            if ($academy->image) {
                Storage::disk('public')->delete($academy->image);
            }
            $data['image'] = $request->file('image')->store('academies', 'public');
        }

        $academy->update($data);
        return redirect('/admin/academies')->with('success', 'Academy updated.');
    }

    public function destroy(Academy $academy)
    {
        if ($academy->image) {
            Storage::disk('public')->delete($academy->image);
        }
        $academy->delete();
        return redirect('/admin/academies')->with('success', 'Academy deleted.');
    }
}
