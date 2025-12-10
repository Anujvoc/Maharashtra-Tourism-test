<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Master\Enterprise;
use App\Models\Admin\master\Undertaking;
use App\Models\Admin\master\TermsAndCondition;
use App\Models\Admin\ApplicationForm;
use Illuminate\Support\Str;

class UndertakingController extends Controller
{

    public function index()
    {

        return view('admin.master.undertaking.index');
    }

    public function data()
    {
        $query = Undertaking::query()->latest();

        return \Yajra\DataTables\Facades\DataTables::of($query)
            ->addIndexColumn()


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
                $edit = route('admin.undertaking.edit', $row);

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

        // $TermsAndCondition = Undertaking::where('id',1)->first();
    
        return view('admin.master.undertaking.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'description' => 'required|string',
            'is_active' => 'nullable|boolean',
        ], [
            'form_id.unique' => 'Terms & Conditions for this form already exist.'
        ]);

        Undertaking::create([
            'description' => $validated['description'],
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.TermsAndCondition.index')
            ->with('success', 'Terms & Condition created successfully.');
    }

    public function fullDescription($id)
    {
        $term = Undertaking::findOrFail($id);
        $raw = $term->description ?? '';

        $cleanHtml = $this->cleanHtml($raw);

        return response()->json(['html' => $cleanHtml]);
    }



    public function update(Request $request, string $id)
    {
        //  dd($request->all());die;
        $request->validate( [
            'is_active' => 'nullable|boolean',
            'description' => 'required|string',

        ]);

        Undertaking::updateOrCreate(
            ['id' => $id],
            [
                'is_active' => $request->is_active ?? 1,
                'description' => $request->description,
            ]
        );
        return redirect()->back()
            ->with('success', 'Undertaking  Updated Successfully!.');
    }


    public function show(string $id)
    {
        //
    }


    public function edit($id)
    {
        $TermsAndCondition = undertaking::findOrFail($id);
        return view('admin.master.TermsAndCondition.edit', compact('TermsAndCondition'));
    }







    public function destroy(string $id)
    {
        //
    }
}
