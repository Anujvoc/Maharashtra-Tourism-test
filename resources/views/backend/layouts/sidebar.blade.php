<div class="js-sidebar-scroll">
    <div class="content-side">
        <ul class="nav-main">
            <li class="nav-main-item">
                <a class="nav-main-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="nav-main-link-icon fa fa-location-arrow"></i>
                    <span class="nav-main-link-name">Dashboard</span>
                </a>
            </li>

            @php
            $WorkflowActive =
            request()->routeIs('admin.ApplicationForms.index');
            @endphp

            @canany(['Clerk', 'Asst Director', 'Dy Director', 'Joint Director', 'Director'])

<li class="nav-main-heading">Workflow Management</li>

<li class="nav-main-item {{ $WorkflowActive ? 'open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu {{ $WorkflowActive ? 'active' : '' }}"
       data-toggle="submenu" href="#">
        <i class="nav-main-link-icon fa fa-tasks"></i>
        <span class="nav-main-link-name">Workflow</span>
    </a>

    <ul class="nav-main-submenu" style="{{ $WorkflowActive ? 'display:block;' : '' }}">

        {{-- Pending --}}
        <li class="nav-main-item">
            <a class="nav-main-link {{ request('view_status')=='pending' ? 'active' : '' }}"
               href="{{ route('admin.ApplicationForms.index', ['view_status' => 'pending']) }}">
                <i class="nav-main-link-icon fa fa-hourglass-half"></i>
                <span class="nav-main-link-name">Pending Applications</span>
            </a>
        </li>

        {{-- Clarification list (Clerk) --}}
        @if(auth()->user()->hasRole('Clerk'))
        <li class="nav-main-item">
            <a class="nav-main-link {{ request('view_status')=='clarification_list' ? 'active' : '' }}"
               href="{{ route('admin.ApplicationForms.index', ['view_status' => 'clarification_list']) }}">
                <i class="nav-main-link-icon fa fa-question-circle"></i>
                <span class="nav-main-link-name">Clarification List</span>
            </a>
        </li>
        @endif

        {{-- Returned --}}
        <li class="nav-main-item">
            <a class="nav-main-link {{ request('view_status')=='returned' ? 'active' : '' }}"
               href="{{ route('admin.ApplicationForms.index', ['view_status' => 'returned']) }}">
                <i class="nav-main-link-icon fa fa-undo"></i>
                <span class="nav-main-link-name">Clarification / Returned</span>
            </a>
        </li>

        {{-- Approved --}}
        <li class="nav-main-item">
            <a class="nav-main-link {{ request('view_status')=='approved' ? 'active' : '' }}"
               href="{{ route('admin.ApplicationForms.index', ['view_status' => 'approved']) }}">
                <i class="nav-main-link-icon fa fa-check-circle"></i>
                <span class="nav-main-link-name">Approved List</span>
            </a>
        </li>

        {{-- Rejected --}}
        <li class="nav-main-item">
            <a class="nav-main-link {{ request('view_status')=='rejected' ? 'active' : '' }}"
               href="{{ route('admin.ApplicationForms.index', ['view_status' => 'rejected']) }}">
                <i class="nav-main-link-icon fa fa-times-circle"></i>
                <span class="nav-main-link-name">Rejected List</span>
            </a>
        </li>

        {{-- Certificate upload --}}
        @if(auth()->user()->hasRole('Asst Director'))
        <li class="nav-main-item">
            <a class="nav-main-link {{ request('view_status')=='certificate_pending' ? 'active' : '' }}"
               href="{{ route('admin.ApplicationForms.index', ['view_status' => 'certificate_pending']) }}">
                <i class="nav-main-link-icon fa fa-file-upload"></i>
                <span class="nav-main-link-name">Upload Certificate</span>
            </a>
        </li>
        @endif

        {{-- Site visit upload --}}
        @if(auth()->user()->hasRole('Clerk'))
        <li class="nav-main-item">
            <a class="nav-main-link {{ request('view_status')=='site_visit_requested' ? 'active' : '' }}"
               href="{{ route('admin.ApplicationForms.index', ['view_status' => 'site_visit_requested']) }}">
                <i class="nav-main-link-icon fa fa-upload"></i>
                <span class="nav-main-link-name">Site Visit Uploads</span>
            </a>
        </li>
        @endif

        {{-- Site visit request --}}
        @if(auth()->user()->hasRole('Dy Director'))
        <li class="nav-main-item">
            <a class="nav-main-link {{ request('view_status')=='site_visit_requested' ? 'active' : '' }}"
               href="{{ route('admin.ApplicationForms.index', ['view_status' => 'site_visit_requested']) }}">
                <i class="nav-main-link-icon fa fa-clipboard-list"></i>
                <span class="nav-main-link-name">Site Visit Requests</span>
            </a>
        </li>
        @endif

    </ul>
</li>

@endcanany



            <li class="nav-main-heading">Master Management</li>
            @php
                $masterDataActive = request()->is('districts*') ||
                    request()->is('states*') ||
                    request()->is('months*') ||
                    request()->is('projects*');
            @endphp
            @canany(['view districts', 'view studentdocuments','view projects', 'view states', 'view months', 'view batch', 'view branch', 'view teacher', 'view class', 'view reports'])
            <li class="nav-main-item {{ $masterDataActive ? 'open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu {{ $masterDataActive ? 'active' : '' }}"
        data-toggle="submenu" aria-haspopup="true"
        aria-expanded="{{ $masterDataActive ? 'true' : 'false' }}" href="#">
        <i class="nav-main-link-icon fa fa-database"></i>
        <span class="nav-main-link-name">Master Data</span>
    </a>
    @php
    $userdata = Auth::user();

    $roles = $userdata->roles;

    $role_id = $roles[0]->id;

    $role_name = $roles[0]->name;

    @endphp
    <ul class="nav-main-submenu" style="{{ $masterDataActive ? 'display: block;' : '' }}">
        @canany(['view projects', 'create projects'])
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('projects*') ? 'active' : '' }}"
                    href="{{ route('admin.master.countries.index') }}">
                    <i class="nav-main-link-icon fa fa-project-diagram"></i>
                   
                    <span class="nav-main-link-name">Country</span>
                   
                </a>
            </li>
        @endcanany



         @canany(['view courses', 'create courses'])
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('courses*') ? 'active' : '' }}"
                    href="{{ route('admin.master.states.index') }}">
                    <i class="nav-main-link-icon fa fa-flag"></i>
                    <span class="nav-main-link-name">State</span>
                </a>
            </li>
        @endcanany

         @canany(['view courses', 'create courses'])
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('courses*') ? 'active' : '' }}"
                    href="{{ route('admin.master.districts.index') }}">
                     <i class="nav-main-link-icon fa fa-map-marker-alt"></i>
                    <span class="nav-main-link-name">Districts</span>
                </a>
            </li>
        @endcanany
          @canany(['view courses', 'create courses'])
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('courses*') ? 'active' : '' }}"
                    href="{{ route('admin.application-forms.index') }}">
                    <i class="nav-main-link-icon fa fa-book-open"></i>
                    <span class="nav-main-link-name">Application Forms</span>
                </a>
            </li>
        @endcanany
         @canany(['view courses', 'create courses'])
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('courses*') ? 'active' : '' }}"
                    href="{{ route('admin.enterprises.index') }}">
                    <i class="nav-main-link-icon fa fa-book-open"></i>
                    <span class="nav-main-link-name">Enterprise</span>
                </a>
            </li>
        @endcanany

   
        @canany(['view branch', 'create branch'])
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('branch*') ? 'active' : '' }}"
                    href="{{ route('admin.categories.index') }}">
                    <i class="nav-main-link-icon fa fa-code-branch"></i>
                    <span class="nav-main-link-name">Category</span>
                </a>
            </li>
        @endcanany

        @canany(['view branch', 'create branch'])
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('branch*') ? 'active' : '' }}"
                    href="{{ route('admin.tourism-facilities.index') }}">
                    <i class="nav-main-link-icon fa fa-code-branch"></i>
                    <span class="nav-main-link-name">Tourism-Facility</span>
                </a>
            </li>
        @endcanany

        @canany(['view branch', 'create branch'])
            <li class="nav-main-item">
                <a class="nav-main-link {{ request()->is('branch*') ? 'active' : '' }}"
                    href="">
                    <i class="nav-main-link-icon fa fa-code-branch"></i>
                    <span class="nav-main-link-name">Divisions</span>
                </a>
            </li>
        @endcanany
            @php
                $usersActive = request()->is('users*');
            @endphp
         @canany(['view users'])
            <li class="nav-main-item {{ $usersActive ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu {{ $usersActive ? 'active' : '' }}" data-toggle="submenu"
                    aria-haspopup="true" aria-expanded="{{ $usersActive ? 'true' : 'false' }}" href="#">
                    <i class="nav-main-link-icon fa fa-users"></i>
                    <span class="nav-main-link-name">Caravan</span>
                </a>
                <ul class="nav-main-submenu" style="{{ $usersActive ? 'display: block;' : '' }}">
                    @can('view users')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ $usersActive ? 'active' : '' }}" href="">
                                <i class="nav-main-link-icon fa fa-user"></i>
                                <span class="nav-main-link-name">Caravan Type</span>
                            </a>
                        </li>
                    @endcan
                     @canany(['view states', 'create states'])
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('states*') ? 'active' : '' }}"
                            href="">
                            <i class="nav-main-link-icon fa fa-flag"></i>
                            <span class="nav-main-link-name">Caravan Optional Feature</span>
                        </a>
                    </li>
                @endcanany
                @canany(['view districts', 'create districts'])
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->is('districts*') ? 'active' : '' }}"
                            href="">
                            <i class="nav-main-link-icon fa fa-map-marker-alt"></i>
                            <span class="nav-main-link-name">Caravan Amenity</span>
                        </a>
                    </li>
                @endcanany
                </ul>
            </li>
            
@endcanany

@php
$ProvisionalActive =
request()->routeIs('admin.master.area.*') ||
request()->routeIs('admin.master.zone.*') ||
request()->routeIs('admin.master.projectType.*') ||
request()->routeIs('admin.master.projectCategory.*');
@endphp

              @canany([
    'view ProvisionalZone',
    'create ProvisionalZone',
    'view ProvisionalArea',
    'create ProvisionalArea',
    'view ProvisionalProjectType',
    'create ProvisionalProjectType',
    'create ProvisionalProjectCategory',
    'view ProvisionalProjectCategory',
])

<li class="nav-main-item {{ $ProvisionalActive ? 'open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu {{ $ProvisionalActive ? 'active' : '' }}"
       data-toggle="submenu" href="#">
        <i class="nav-main-link-icon fa fa-layer-group"></i>
        <span class="nav-main-link-name">Provisional</span>
    </a>

    <ul class="nav-main-submenu" style="{{ $ProvisionalActive ? 'display:block;' : '' }}">

        {{-- ================= Classification Zone ================= --}}
        @canany(['view ProvisionalArea','view ProvisionalZone'])
        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                <i class="nav-main-link-icon fa fa-sitemap"></i>
                <span class="nav-main-link-name">Classification Zone</span>
            </a>

            <ul class="nav-main-submenu">

                {{-- Area --}}
                @can('view ProvisionalArea')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.master.area.*') ? 'active' : '' }}"
                       href="{{ route('admin.master.area.index') }}">
                        <i class="nav-main-link-icon fa fa-map-marker-alt"></i>
                        <span class="nav-main-link-name">Area</span>
                    </a>
                </li>
                @endcan

                {{-- Zone --}}
                @can('view ProvisionalZone')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.master.zone.*') ? 'active' : '' }}"
                       href="{{ route('admin.master.zone.index') }}">
                        <i class="nav-main-link-icon fa fa-draw-polygon"></i>
                        <span class="nav-main-link-name">Zone</span>
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endcanany


        {{-- ================= Project Category ================= --}}
        @canany(['view ProvisionalProjectType','view ProvisionalProjectCategory'])
        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                <i class="nav-main-link-icon fa fa-tags"></i>
                <span class="nav-main-link-name">Project Category</span>
            </a>

            <ul class="nav-main-submenu">

                {{-- Project Type --}}
                @can('view ProvisionalProjectType')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.master.projectType.*') ? 'active' : '' }}"
                       href="{{ route('admin.master.projectType.index') }}">
                        <i class="nav-main-link-icon fa fa-list-ul"></i>
                        <span class="nav-main-link-name">Project Type</span>
                    </a>
                </li>
                @endcan

                {{-- Project Category --}}
                @can('view ProvisionalProjectCategory')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.master.projectCategory.*') ? 'active' : '' }}"
                       href="{{ route('admin.master.projectCategory.index') }}">
                        <i class="nav-main-link-icon fa fa-tag"></i>
                        <span class="nav-main-link-name">Project Category</span>
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endcanany

    </ul>
</li>

@endcanany
    </ul>
</li>


            @endcanany
            
            @canany(['view users'])
            <li class="nav-main-item {{ $usersActive ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu {{ $usersActive ? 'active' : '' }}" data-toggle="submenu"
                    aria-haspopup="true" aria-expanded="{{ $usersActive ? 'true' : 'false' }}" href="#">
                    <i class="nav-main-link-icon fa fa-users"></i>
                    <span class="nav-main-link-name">User Management</span>
                </a>
                <ul class="nav-main-submenu" style="{{ $usersActive ? 'display: block;' : '' }}">
                    @can('view users')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ $usersActive ? 'active' : '' }}" href="">
                                <i class="nav-main-link-icon fa fa-user"></i>
                                <span class="nav-main-link-name">All Users</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            @endcan

           
            @php
                $rolesPermissionsActive = request()->is('roles*') || request()->is('permissions*');
            @endphp
       
                <li class="nav-main-heading">Website Settings</li>
               
                    <li class="nav-main-submenu" style="{{ $rolesPermissionsActive ? 'display: block;' : '' }}">
                   
                            <li class="nav-main-item ">
                                <a class="nav-main-link {{ request()->is('setting*') ? 'active' : '' }}"
                                    href="">
                                    <i class="nav-main-link-icon fa fa-cog"></i>
                                    <span class="nav-main-link-name">Settings</span>
                                </a>
                            </li>
                    
                          
                    
                </li>

                @php
$RolesPermissionActive =
request()->routeIs('admin.permissions.*') ||
request()->routeIs('admin.roles.*') ||
request()->routeIs('admin.users.*') ||
request()->routeIs('admin.all.roles.permission') ||
request()->routeIs('admin.add.roles.permission');
@endphp


@canany(['view permission', 'create permission', 'view roles', 'create roles', 'view user', 'create user'])

<li class="nav-main-heading">Roles & Permissions</li>

<li class="nav-main-item {{ $RolesPermissionActive ? 'open' : '' }}">
    <a class="nav-main-link nav-main-link-submenu {{ $RolesPermissionActive ? 'active' : '' }}"
       data-toggle="submenu" href="#">
        <i class="nav-main-link-icon fa fa-user-shield"></i>
        <span class="nav-main-link-name">Roles & Permissions</span>
    </a>

    <ul class="nav-main-submenu" style="{{ $RolesPermissionActive ? 'display:block;' : '' }}">

        {{-- ================= Permissions ================= --}}
        @canany(['view permission', 'create permission'])
        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                <i class="nav-main-link-icon fa fa-key"></i>
                <span class="nav-main-link-name">Permissions</span>
            </a>

            <ul class="nav-main-submenu">

                @can('view permission')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.permissions.index') ? 'active' : '' }}"
                       href="{{ route('admin.permissions.index') }}">
                        <i class="nav-main-link-icon fa fa-list"></i>
                        <span class="nav-main-link-name">All Permission</span>
                    </a>
                </li>
                @endcan

                @can('create permission')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.permissions.create') ? 'active' : '' }}"
                       href="{{ route('admin.permissions.create') }}">
                        <i class="nav-main-link-icon fa fa-plus-circle"></i>
                        <span class="nav-main-link-name">Add Permission</span>
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endcanany


        {{-- ================= Roles ================= --}}
        @canany(['view roles', 'create roles'])
        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                <i class="nav-main-link-icon fa fa-user-tag"></i>
                <span class="nav-main-link-name">Roles</span>
            </a>

            <ul class="nav-main-submenu">

                @can('view roles')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}"
                       href="{{ route('admin.roles.index') }}">
                        <i class="nav-main-link-icon fa fa-users"></i>
                        <span class="nav-main-link-name">All Roles</span>
                    </a>
                </li>
                @endcan

                @can('create roles')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.roles.create') ? 'active' : '' }}"
                       href="{{ route('admin.roles.create') }}">
                        <i class="nav-main-link-icon fa fa-user-plus"></i>
                        <span class="nav-main-link-name">Add Roles</span>
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endcanany


        {{-- ================= Assign Permission ================= --}}
        @canany(['edit roles', 'view roles'])
        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                <i class="nav-main-link-icon fa fa-link"></i>
                <span class="nav-main-link-name">Assign Permission</span>
            </a>

            <ul class="nav-main-submenu">

                @can('view roles')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.all.roles.permission') ? 'active' : '' }}"
                       href="{{ route('admin.all.roles.permission') }}">
                        <i class="nav-main-link-icon fa fa-list"></i>
                        <span class="nav-main-link-name">All RolesPermission</span>
                    </a>
                </li>
                @endcan

                @can('edit roles')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.add.roles.permission') ? 'active' : '' }}"
                       href="{{ route('admin.add.roles.permission') }}">
                        <i class="nav-main-link-icon fa fa-plus"></i>
                        <span class="nav-main-link-name">Add RolesPermission</span>
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endcanany


        {{-- ================= Manage Admin ================= --}}
        @canany(['view user', 'create user'])
        <li class="nav-main-item">
            <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" href="#">
                <i class="nav-main-link-icon fa fa-user-cog"></i>
                <span class="nav-main-link-name">Manage Admin</span>
            </a>

            <ul class="nav-main-submenu">

                @can('view user')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="nav-main-link-icon fa fa-users-cog"></i>
                        <span class="nav-main-link-name">All Users</span>
                    </a>
                </li>
                @endcan

                @can('create user')
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}"
                       href="{{ route('admin.users.create') }}">
                        <i class="nav-main-link-icon fa fa-user-plus"></i>
                        <span class="nav-main-link-name">Add Users</span>
                    </a>
                </li>
                @endcan

            </ul>
        </li>
        @endcanany

    </ul>
</li>

@endcanany

             
           
    </div>
   

</div>
