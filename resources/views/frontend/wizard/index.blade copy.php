@extends('frontend.layouts2.master')
@section('title','My Applications')

{{-- @section('content')
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
      <h3 class="mb-0">My Applications</h3>
      <small class="text-muted">Create new, resume drafts, or view submitted forms.</small>
    </div>

    <form action="{{ route('applications.store') }}" method="POST" class="ms-auto">
      @csrf
      <button class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> New Application
      </button>
    </form>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
  @endif

  @if($apps->isEmpty())
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center py-5">
        <div class="display-6 mb-2">👋</div>
        <h5 class="mb-1">No applications yet</h5>
        <p class="text-muted mb-3">Click the button below to start your Tourist Villa registration.</p>
        <form action="{{ route('applications.store') }}" method="POST">
          @csrf
          <button class="btn btn-primary">
            <i class="bi bi-rocket-takeoff me-1"></i> Start New Application
          </button>
        </form>
      </div>
    </div>
  @else
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="row g-3 mb-3">
          <div class="col-md-4 ms-auto">
            <input id="search" type="text" class="form-control" placeholder="Search by Reg ID or Status…">
          </div>
        </div>

        <div class="table-responsive">
          <table id="appsTable" class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:70px">#</th>
                <th>Registration ID</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Current Step</th>
                <th>Submitted At</th>
                <th style="width:160px">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($apps as $app)
                @php
                  $progress = $app->progress; // ['done'=>n, 'total'=>6]
                  $pct = intval(($progress['done'] / $progress['total']) * 100);
                  $badge = [
                    'draft'     => 'secondary',
                    'submitted' => 'warning',
                    'approved'  => 'success',
                    'rejected'  => 'danger',
                  ][$app->status] ?? 'secondary';

                  $stepNames = [1=>'Applicant',2=>'Property',3=>'Accommodation',4=>'Facilities',5=>'Photo & Signature',6=>'Enclosures',7=>'Review'];
                  $stepLabel = $stepNames[$app->current_step] ?? $app->current_step;
                @endphp
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td class="fw-semibold">
                    {{ $app->registration_id ?? '—' }}
                    <div class="small text-muted">#{{ $app->slug_id }}</div>
                  </td>
                  <td>
                    <span class="badge text-bg-{{ $badge }}">{{ ucfirst($app->status) }}</span>
                    @if($app->is_apply)
                      <span class="badge text-bg-info">is_apply</span>
                    @endif
                  </td>
                  <td style="min-width:220px">
                    <div class="d-flex align-items-center gap-2">
                      <div class="progress flex-grow-1" style="height:8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%"></div>
                      </div>
                      <small class="text-muted">{{ $progress['done'] }}/{{ $progress['total'] }}</small>
                    </div>
                  </td>
                  <td>
                    <span class="text-muted">{{ $stepLabel }}</span>
                  </td>
                  <td>
                    {{ $app->submitted_at? $app->submitted_at->format('d M Y, h:i A') : '—' }}
                  </td>
                  <td>
                    @if($app->status === 'draft')
                      <a class="btn btn-sm btn-outline-primary"
                         href="{{ route('wizard.show', [$app, 'step' => $app->current_step]) }}">
                        <i class="bi bi-play-circle me-1"></i> Resume
                      </a>
                      @if($progress['done']>0)
                        <a class="btn btn-sm btn-outline-secondary"
                           href="{{ route('wizard.show', [$app, 'step' => 1]) }}">
                           <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                      @endif
                    @else
                      <a class="btn btn-sm btn-outline-secondary" href="{{ route('wizard.show', [$app, 'step' => 7]) }}">
                        <i class="bi bi-eye me-1"></i> View
                      </a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  @endif
@endsection --}}
@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>My Applications</h1>

          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="mb-0">My Applications</h3>
                  </div>
                  <div class="card-body">


                      @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                      @endif
                      @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                      @endif

                      @if($apps->isEmpty())
                        <div class="card border-0 shadow-sm">
                          <div class="card-body text-center py-5">
                            <div class="display-6 mb-2">👋</div>
                            <h5 class="mb-1">No applications yet</h5>
                            <p class="text-muted mb-3">Click the button below to start your Tourist Villa registration.</p>
                            <form action="{{ route('applications.store') }}" method="POST">
                              @csrf
                              <button class="btn btn-primary">
                                <i class="bi bi-rocket-takeoff me-1"></i> Start New Application
                              </button>
                            </form>
                          </div>
                        </div>
                      @else
                        <div class="card border-0 shadow-sm">
                          <div class="card-body">
                            <div class="row g-3 mb-3">
                              <div class="col-md-4 ms-auto">
                                <input id="search" type="text" class="form-control" placeholder="Search by Reg ID or Status…">
                              </div>
                            </div>

                            <div class="table-responsive">
                              <table id="appsTable" class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                  <tr>
                                    <th style="width:70px">#</th>
                                    <th>Registration ID</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Current Step</th>
                                    <th>Submitted At</th>
                                    <th style="width:160px">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($apps as $app)
                                    @php
                                      $progress = $app->progress;
                                      $pct = intval(($progress['done'] / $progress['total']) * 100);
                                      $badge = [
                                        'draft'     => 'secondary',
                                        'submitted' => 'warning',
                                        'approved'  => 'success',
                                        'rejected'  => 'danger',
                                      ][$app->status] ?? 'secondary';

                                      $stepNames = [1=>'Applicant',2=>'Property',3=>'Accommodation',4=>'Facilities',5=>'Photo & Signature',6=>'Enclosures',7=>'Review'];
                                      $stepLabel = $stepNames[$app->current_step] ?? $app->current_step;
                                    @endphp
                                    <tr>
                                      <td>{{ $loop->iteration }}</td>
                                      <td class="fw-semibold">
                                        {{ $app->registration_id ?? '—' }}
                                        <div class="small text-muted">#{{ $app->slug_id }}</div>
                                      </td>
                                      <td>
                                        <span class="badge text-bg-{{ $badge }}">{{ ucfirst($app->status) }}</span>
                                        @if($app->is_apply)
                                          <span class="badge text-bg-info">is_apply</span>
                                        @endif
                                      </td>
                                      <td style="min-width:220px">
                                        <div class="d-flex align-items-center gap-2">
                                          <div class="progress flex-grow-1" style="height:8px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%"></div>
                                          </div>
                                          <small class="text-muted">{{ $progress['done'] }}/{{ $progress['total'] }}</small>
                                        </div>
                                      </td>
                                      <td>
                                        <span class="text-muted">{{ $stepLabel }}</span>
                                      </td>
                                      <td>
                                        {{ $app->submitted_at? $app->submitted_at->format('d M Y, h:i A') : '—' }}
                                      </td>
                                      <td>
                                        @if($app->status === 'draft')
                                          <a class="btn btn-sm btn-outline-primary"
                                             href="{{ route('wizard.show', [$app, 'step' => $app->current_step]) }}">
                                            <i class="bi bi-play-circle me-1"></i> Resume
                                          </a>
                                          @if($progress['done']>0)
                                            <a class="btn btn-sm btn-outline-secondary"
                                               href="{{ route('wizard.show', [$app, 'step' => 1]) }}">
                                               <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>
                                          @endif
                                        @else
                                          <a class="btn btn-sm btn-outline-secondary" href="{{ route('wizard.show', [$app, 'step' => 7]) }}">
                                            <i class="bi bi-eye me-1"></i> View
                                          </a>
                                        @endif
                                      </td>
                                    </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </div>

                          </div>
                        </div>
                      @endif
                  </div>

                </div>
              </div>
            </div>

          </div>
        </section>
@endsection


@push('styles')
<style>
  .table td, .table th { vertical-align: middle; }
</style>
@endpush

@push('scripts')
<script>
  // light client-side search (no external libs)
  const input = document.getElementById('search');
  const rows  = [...document.querySelectorAll('#appsTable tbody tr')];
  input && input.addEventListener('input', function(){
    const q = this.value.toLowerCase();
    rows.forEach(tr=>{
      const text = tr.innerText.toLowerCase();
      tr.style.display = text.includes(q) ? '' : 'none';
    });
  });
</script>
@endpush




