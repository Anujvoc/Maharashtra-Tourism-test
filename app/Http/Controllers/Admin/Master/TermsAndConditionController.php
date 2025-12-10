<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Master\Enterprise;
use App\Models\Admin\master\TermsAndCondition;
use App\Models\Admin\ApplicationForm;
use Illuminate\Support\Str;

class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('admin.master.TermsAndCondition.index');
    }

    public function data()
    {
        $query = TermsAndCondition::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()

            ->editColumn('forms', function ($row) {
                $form = ApplicationForm::where('id', $row->form_id)->first();
                $forms = $form->name ?? '';
                return $forms;
            })

            ->editColumn('description', function ($row) {
                $full = $row->description ?? '';
                $truncated = Str::words(strip_tags($full), 10, '...');
                $viewLink = '<a href="javascript:void(0);" class="view-description ms-2" data-id="' . $row->id . '">View</a>';
                return $truncated . $viewLink;
            })

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })

            ->addColumn('actions', function ($row) {
                $edit = route('admin.TermsAndCondition.edit', $row);

                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-primary me-1">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                ';
            })
            // allow HTML for these columns
            ->rawColumns(['description', 'is_active', 'actions'])
            ->make(true);
    }

    protected  function cleanHtml($html)
    {
        if (empty($html)) {
            return '';
        }
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);

        $html = preg_replace('/(<[^>]+)\s+on\w+\s*=\s*(["\']).*?\2/iu', '$1', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/iu', '$1=$2#$2', $html);

        $allowed = '<p><a><br><strong><b><em><i><ul><ol><li><img><h1><h2><h3><h4><h5><small><span>';
        $html = strip_tags($html, $allowed);
        $html = preg_replace('/(<[a-z][^>]*?)\sstyle=("[^"]*"|\'[^\']*\')/iu', '$1', $html);
        $html = preg_replace('/(<[a-z][^>]*?)\s(on\w+|data-[\w-]+)=("[^"]*"|\'[^\']*\')/iu', '$1', $html);

        return $html;
    }


    public function create()
    {
        $data['forms'] = ApplicationForm::orderBy('name', 'asc')->get();
        return view('admin.master.TermsAndCondition.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'form_id' => 'nullable|integer|unique:terms_and_conditions,form_id',
            'description' => 'required|string',
            'is_active' => 'nullable|boolean',
        ], [
            'form_id.unique' => 'Terms & Conditions for this form already exist.'
        ]);

        TermsAndCondition::create([
            'form_id' => $validated['form_id'] ?? null,
            'description' => $validated['description'],
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.TermsAndCondition.index')
            ->with('success', 'Terms & Condition created successfully.');
    }

    public function fullDescription($id)
    {
        $term = TermsAndCondition::findOrFail($id);
        $raw = $term->description ?? '';

        // sanitize using the helper method above
        $cleanHtml = $this->cleanHtml($raw);

        // Return sanitized HTML
        return response()->json(['html' => $cleanHtml]);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $TermsAndCondition = TermsAndCondition::findOrFail($id);
        $forms = ApplicationForm::orderBy('name', 'asc')->get();

        return view('admin.master.TermsAndCondition.edit', compact('TermsAndCondition', 'forms'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'form_id' => "nullable|integer|unique:terms_and_conditions,form_id,{$id}",
            'description' => 'required|string',
            'is_active' => 'nullable|boolean',
        ], [
            'form_id.unique' => 'Terms & Conditions for this form already exist.'
        ]);

        $term = TermsAndCondition::findOrFail($id);
        $term->update([
            'form_id' => $validated['form_id'] ?? null,
            'description' => $validated['description'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.TermsAndCondition.index')
            ->with('success', 'Terms & Condition updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
