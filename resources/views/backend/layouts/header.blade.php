<header id="page-header">


@php
    $user = Auth::user();
    $roles = $user->roles;
    // foreach ($roles as $role) {
    //     dd([
    //         'id' => $role->id,
    //         'name' => $role->name
    //     ]);
    // }

    $role_id = $roles[0]->id;
    $role_name = $roles[0]->name;

@endphp

    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="space-x-1">
            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- END Toggle Sidebar -->

<!-- Display Current Role -->
<span class="badge bg-info text-white align-self-center" style="font-size: 0.75rem;">
    <i class="fa fa-user-shield me-1"></i> Role: {{ $role_name }}
</span>

            <!-- Open Search Section -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="header_search_on">
                <i class="fa fa-fw opacity-50 fa-search"></i> <span class="ms-1 d-none d-sm-inline-block">Search</span>
            </button>
            <!-- END Open Search Section -->
        </div>
        <!-- END Left Section -->
        <div class="mb-0 d-flex align-items-center d-none d-md-flex" style="gap: 0.5rem;">
            <img src="{{ asset('backend/mah-logo-300x277.png') }}" alt="Logo" style="height:46px; width:auto;" class="me-2">
            <!-- <img src="{{ asset('assets/media/logo/logo.png') }}" alt="Logo" style="height:36px; width:auto;" class="me-2"> -->
            <div class="text-start" style="line-height:1;">
            <div class="fw-bold fs-xs text-dark" style="font-size: 0.85rem;">
                Maharashtra Tourism
            </div> 
            <div class="fs-xs text-primary" style="font-size: 0.75rem;">
                Tourism Department, Government of Maharashtra
            </div>
            <div class="fs-xs text-muted" style="font-size: 0.7rem;">
                Maharashtra Tourism Development Corporation (MTDC)
            </div>
        </div>

        </div>
        <!-- Right Section -->
        <div class="space-x-1">
            <!-- User Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-alt-secondary" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-user d-sm-none"></i>
                    <span class="d-none d-sm-inline-block text-primary">{{ Auth::user()->name ?? '' }}</span>
                    <i class="fa fa-fw fa-angle-down opacity-50 ms-1 d-none d-sm-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="page-header-user-dropdown">
                    <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
                        User Options
                    </div>
                    <div class="p-2">
                        {{--
                        
                        <a class="dropdown-item" href="be_pages_generic_profile.html">
                            <i class="far fa-fw fa-user me-1"></i> Profile
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_inbox.html">
                            <span><i class="far fa-fw fa-envelope me-1"></i> Inbox</span>
                            <span class="badge bg-primary rounded-pill">3</span>
                        </a>
                        <a class="dropdown-item" href="be_pages_generic_invoice.html">
                            <i class="far fa-fw fa-file-alt me-1"></i> Invoices
                        </a>
                        <div role="separator" class="dropdown-divider"></div>

                        <!-- Toggle Side Overlay -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <a class="dropdown-item" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">
                            <i class="far fa-fw fa-building me-1"></i> Settings
                        </a>
                        --}}
                        
                       

                        <div role="separator" class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="far fa-fw fa-arrow-alt-circle-left me-1"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- END User Dropdown -->
            
            {{--

            <!-- Notifications Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-alt-secondary" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-bell"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                    <div class="bg-primary-dark rounded-top fw-semibold text-white text-center p-3">
                        Notifications
                    </div>
                   
                    <div class="p-2 border-top">
                        <a class="btn btn-alt-primary w-100 text-center" href="javascript:void(0)">
                            <i class="fa fa-fw fa-eye opacity-50 me-1"></i> View All
                        </a>
                    </div>
                </div>
            </div>
            
            --}}
            <!-- END Notifications Dropdown -->

            <!-- Toggle Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <!--<button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="side_overlay_toggle">-->
            <!--    <i class="far fa-fw fa-list-alt"></i>-->
            <!--</button>-->
            <!-- END Toggle Side Overlay -->
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    <div id="page-header-search" class="overlay-header bg-header-dark">
        <div class="bg-white-10">
            <div class="content-header">
                <form class="w-100" action="be_pages_generic_search.html" method="POST">
                    <div class="input-group">
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button type="button" class="btn btn-alt-primary" data-toggle="layout" data-action="header_search_off">
                            <i class="fa fa-fw fa-times-circle"></i>
                        </button>
                        <input type="text" class="form-control border-0" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Header Search -->

    <!-- Header Loader -->
    <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
    <div id="page-header-loader" class="overlay-header bg-header-dark">
        <div class="bg-white-10">
            <div class="content-header">
                <div class="w-100 text-center">
                    <i class="fa fa-fw fa-sun fa-spin text-white"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>
