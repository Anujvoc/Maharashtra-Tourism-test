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

        // Clear re-uploaded flag on approval
        if (isset($approvals['_meta'])) {
            unset($approvals['_meta']);
        }

        $document->role_approvals = $approvals;

        // Update overall status logic if needed, but per requirements, 
        // "Approved" status is per role. 
        // We might want to set overall_status ONLY if ALL required roles approved? 
        // Or just keep it loosely coupled. 
        // For now, let's update overall_status to 'Pending' if currently 'Clarification', 
        // or keep it simple.

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

        // Also update the main application status if needed? 
        // Requirement: "If any role rejects a document: The application is sent back to the user for clarification."
        // We might want to trigger a workflow event here, OR simply block the forward.
        // The requirement says "The application is sent back". 
        // We should probably rely on the "Forward" button being clicked, 
        // OR we can auto-update the application status.
        // Let's allow the Admin to reject multiple documents first, 
        // then they might click "Return to Clarification" or similar?
        // Actually, the prompt says "If any role rejects... The application is sent back".
        // This implies an immediate effect or a block.
        // Let's update the specific document status. The "Forward" action restriction will handle the rest.

        return response()->json(['success' => true, 'message' => 'Document rejected.']);
    }
}
