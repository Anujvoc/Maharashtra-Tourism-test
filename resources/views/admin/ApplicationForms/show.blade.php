@extends('admin.layouts.app')

@section('title', 'Application Details')

@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Application Details</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                        class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.ApplicationForms.index') }}">Applications</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Details</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                {{-- Left Column: Application Report (Iframe) --}}
                <div class="col-lg-8 col-xl-8">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="ratio ratio-1x1" style="min-height: 800px;">
                                <iframe
                                    src="{{ route('admin.ApplicationForms.report', ['type' => $type, 'id' => $application->id]) }}"
                                    title="Application Report" allowfullscreen stylesheet="border:none;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Workflow Actions --}}
                <div class="col-lg-4 col-xl-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0 text-white">Workflow Actions</h6>
                        </div>
                        <div class="card-body">
                            {{-- Integrate the Workflow Actions Component --}}
                            <x-admin.workflow-actions :application="$application" :type="$type" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
