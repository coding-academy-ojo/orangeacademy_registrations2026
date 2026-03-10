<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequirement;
use Illuminate\Http\Request;

class DocumentRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requirements = DocumentRequirement::all();
        return view('admin.document_requirements.index', compact('requirements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.document_requirements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
        ]);

        DocumentRequirement::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_required' => $request->has('is_required'),
        ]);

        return redirect()->route('admin.document_requirements.index')->with('success', 'Document requirement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentRequirement $documentRequirement)
    {
        return redirect()->route('admin.document_requirements.edit', $documentRequirement);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentRequirement $documentRequirement)
    {
        return view('admin.document_requirements.edit', compact('documentRequirement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentRequirement $documentRequirement)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_required' => 'boolean',
        ]);

        $documentRequirement->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_required' => $request->has('is_required'),
        ]);

        return redirect()->route('admin.document_requirements.index')->with('success', 'Document requirement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentRequirement $documentRequirement)
    {
        $documentRequirement->delete();
        return redirect()->route('admin.document_requirements.index')->with('success', 'Document requirement deleted successfully.');
    }
}
