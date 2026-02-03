<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserDocumentController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120'
        ]);

        $document = ApplicationDocument::findOrFail($id);

        if ($document->application && $document->application->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $path = $file->store('application_documents', 'public');

            $document->file_path = $path;
            $document->overall_status = 'Pending';
            // Preserve history but mark as re-uploaded
            $approvals = $document->role_approvals ?? [];
            if (!is_array($approvals)) {
                $approvals = [];
            }
            $approvals['_meta'] = ['is_reuploaded' => true];
            $document->role_approvals = $approvals;
            $document->save();

            // Sync Logic
            $parent = $document->application;
            $key = $document->document_key;

            if ($parent && $key) {
                if (str_contains($key, '.')) {
                    // Handle JSON path: e.g. "enclosures.travel_life"
                    $parts = explode('.', $key);
                    $jsonCol = $parts[0]; // "enclosures"
                    $jsonKey = $parts[1]; // "travel_life"

                    $data = $parent->$jsonCol ?? [];
                    if (isset($data[$jsonKey]) && is_array($data[$jsonKey])) {
                        $data[$jsonKey]['file_path'] = $path;
                        // Assuming other fields like doc_no, date exist, we keep them or update if passed?
                        // For re-upload, we usually just update the file.
                    } else {
                        // Fallback if structure mismatches or generic key
                        $data[$jsonKey] = ['file_path' => $path];
                    }
                    $parent->$jsonCol = $data;
                    $parent->save();

                } else {
                    // Direct column
                    $parent->$key = $path;
                    $parent->save();
                }
            }

            // Sync Logic: Update Parent Application Status if in Clarification
            if ($parent && $parent->workflow_status === 'Clarification') {
                $parent->workflow_status = 'Pending';
                $parent->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Document updated successfully.',
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
