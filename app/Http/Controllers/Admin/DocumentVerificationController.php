<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Auth;

class DocumentVerificationController extends Controller
{
    public function approve(Request $request, $id)
    {
        $document = ApplicationDocument::findOrFail($id);
        $role = Auth::user()->getRoleNames()->first(); // Assuming Spatie roles

        if (!$role) {
            return response()->json(['error' => 'User has no role assigned'], 403);
        }



        $approvals = $document->role_approvals ?? [];
        $approvals[$role] = [
            'status' => 'Approved',
            'remark' => $request->input('remark'),
            'date' => now()->toDateTimeString()
        ];

        if (isset($approvals['_meta'])) {
            unset($approvals['_meta']);
        }

        $document->role_approvals = $approvals;


        $document->save();

        return response()->json(['success' => true, 'message' => 'Document approved successfully.']);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'remark' => 'required|string'
        ]);

        $document = ApplicationDocument::findOrFail($id);
        $role = Auth::user()->getRoleNames()->first();

        if (!$role) {
            return response()->json(['error' => 'User has no role assigned'], 403);
        }

        $approvals = $document->role_approvals ?? [];
        $approvals[$role] = [
            'status' => 'Rejected',
            'remark' => $request->input('remark'),
            'date' => now()->toDateTimeString()
        ];

        // Clear the re-uploaded flag so frontend shows the new rejection
        if (isset($approvals['_meta']['is_reuploaded'])) {
            unset($approvals['_meta']['is_reuploaded']);
        }

        $document->role_approvals = $approvals;

        $document->role_approvals = $approvals;
        $document->overall_status = 'Clarification'; // Flag for user
        $document->save();

        
        return response()->json(['success' => true, 'message' => 'Document rejected.']);
    }
}
