<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            {{-- <a href="">Maharashtra Tourism</a> --}}
            <img src="{{ asset('backend/mah-logo-300x277.png') }}"
            {{-- src="https://maharashtratourism.gov.in/wp-content/uploads/2025/01/mah-logo-300x277.png" --}}
            alt="Maharashtra Tourism Logo"
            style="width:180px;height:55px;object-fit:contain;display:block;margin:0 auto;">

            <span style="font-weight:700;font-size:18px;color:#2c3e50;">Maharashtra Tourism</span>
            {{-- <a href="">{{ $settings->site_name }}</a> --}}
        </div>

        <ul class="sidebar-menu mt-1">
            <li class="menu-header">Dashboard</li>

            <li class="dropdown active">

                {{-- <a href="{{ route('admin.dashbaord') }}" class="nav-link"><i --}}
                <a href="{{ route('frontend.dashboard') }}" class="nav-link"><i
                        class="fas fa-fire"></i><span>Dashboard</span></a>

            </li>
            {{-- <li><a class="nav-link" href="#"><i class="fas fa-home"></i><span>Home</span></a></li> --}}

            <li>
                <a class="nav-link" href="{{ route('frontend.application-forms.index') }}">

                    <i class="fas fa-layer-group" style="color: #198754;"></i>
                   <span>Application Form</span>
                </a>
            </li>
            <li>
                <a class="nav-link" href="{{ route('applications.index') }}">
                    {{-- <i class="fas fa-tasks text-success"></i> --}}
                    <i class="fas fa-user-plus text-primary"></i>
                   <span>My Applications</span>
                </a>
            </li>

              {{-- <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-clipboard-list "style="color:#ff6600;"></i>
                    <span>Application Form</span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ route('frontend.application-forms.index') }}">
                            <i class="fas fa-user-plus text-primary"></i>
                            Registration
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('applications.index') }}">

                            <i class="fas fa-layer-group" style="color: #198754;"></i>
                            My Applications
                        </a>
                    </li>
                </ul>
            </li> --}}

            {{-- <li><a class="nav-link" href="{{ route('provisional.registration.create') }}">  <i class="fas fa-user-clock text-info"></i><span>Provisional Registration</span></a></li> --}}

            {{-- <li class="menu-header">Ecommerce</li>

            <li
                class="dropdown {{ setActive(['admin.category.*', 'admin.sub-category.*', 'admin.child-category.*']) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i>
                    <span>Manage Categories</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.category.index') }}">Category</a></li>
                    <li class="{{ setActive(['admin.sub-category.*']) }}"><a class="nav-link"
                            href="{{ route('admin.sub-category.index') }}">Sub Category</a></li>
                    <li class="{{ setActive(['admin.child-category.*']) }}"> <a class="nav-link"
                            href="{{ route('admin.child-category.index') }}">Child Category</a></li>

                </ul>
            </li>


            <li><a class="nav-link {{ setActive(['admin.messages.index']) }}"
                href="{{ route('admin.messages.index') }}"><i class="fas fa-user"></i>
                <span>Messages</span></a></li>

            <li class="menu-header">Settings & More</li>



                     --}}

        </ul>

    </aside>
</div>
