<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with('user.profile');
        if ($request->verified !== null && $request->verified !== '') {
            $query->where('is_verified', $request->verified);
        }
        $documents = $query->latest()->paginate(15);
        return view('admin.documents.index', compact('documents'));
    }

    public function verify(Document $document)
    {
        $document->update(['is_verified' => true]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Document verified.']);
        }

        return back()->with('success', 'Document verified.');
    }

    public function unverify(Request $request, Document $document)
    {
        $request->validate(['rejection_reason' => 'required|string|max:1000']);
        $reason = $request->rejection_reason;

        $document->update([
            'is_verified' => false,
            'rejection_reason' => $reason
        ]);

        $document->user->notify(new \App\Notifications\DocumentRejectedNotification($document, $reason));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Document rejected and user notified.']);
        }

        return back()->with('success', 'Document rejected and user notified.');
    }
}
