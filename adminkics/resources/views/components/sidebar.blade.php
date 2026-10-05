<div data-simplebar>
    <ul class="app-menu">

        <li class="menu-title">Menu</li>

        <li class="menu-item">
            <a class='menu-link waves-effect' href="{{ route('admin.dashboard') }}">
                <span class="menu-icon"><i data-lucide="home"></i></span>
                <span class="menu-text"> Dashboards </span>
                <span class="badge bg-info rounded-pill ms-auto"></span>
            </a>
        </li>

        <li class="menu-title">Custom</li>

        <li class="menu-item">
            <a class="menu-link waves-effect" href="{{ route('admin.legacy.index') }}">
                <span class="menu-icon"><i data-lucide="database"></i></span>
                <span class="menu-text"> Admin Modules </span>
            </a>
        </li>

        <li class="menu-item">
            <a class="menu-link waves-effect" href="{{ route('admin.groups.index') }}">
                <span class="menu-icon"><i data-lucide="laptop"></i></span>
                <span class="menu-text"> Labs </span>
            </a>
        </li>

        <li class="menu-item">
            <a class='menu-link waves-effect' href="{{ route('project.list') }}">
                <span class="menu-icon"><i data-lucide="briefcase"></i></span>
                <span class="menu-text"> Projects </span>
            </a>
        </li>

        <!-- Events Menu -->
<li class="menu-item">
    <a href="#menuEvents" data-bs-toggle="collapse" class="menu-link waves-effect">
        <span class="menu-icon"><i data-lucide="calendar"></i></span>
        <span class="menu-text">Events</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="menuEvents">
        <ul class="sub-menu">
            <li class="menu-item">
                <a class='menu-link' href="{{ route('admin.events.index') }}">
                    <span class="menu-icon"><i data-lucide="list"></i></span>
                    <span class="menu-text">All Events</span>
                </a>
            </li>
            <li class="menu-item">
                <a class='menu-link' href="{{ route('admin.events.create') }}">
                    <span class="menu-icon"><i data-lucide="plus-circle"></i></span>
                    <span class="menu-text">Add Event</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<li class="menu-item">
    <a class="menu-link waves-effect" href="{{ route('admin.announcement-popup.edit') }}">
        <span class="menu-icon"><i data-lucide="megaphone"></i></span>
        <span class="menu-text">Homepage Popup</span>
    </a>
</li>

        <!-- ====== ERP MANAGEMENT ====== -->
        <li class="menu-item">
            <a href="#menuErp" data-bs-toggle="collapse" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="building"></i></span>
                <span class="menu-text"> ERP Management </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="menuErp">
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a class='menu-link' href="{{ route('admin.erp.departments.index') }}">
                            <span class="menu-icon"><i data-lucide="layers"></i></span>
                            <span class="menu-text"> ERP Departments </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class='menu-link' href="{{ route('admin.erp.employees.index') }}">
                            <span class="menu-icon"><i data-lucide="users"></i></span>
                            <span class="menu-text"> ERP Employees </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class='menu-link' href="{{ route('admin.erp.sync') }}">
                            <span class="menu-icon"><i data-lucide="refresh-cw"></i></span>
                            <span class="menu-text"> Sync ERP Data </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <!-- ====== END ERP MANAGEMENT ====== -->

        <li class="menu-item">
            <a href="#menuExtendedui" data-bs-toggle="collapse" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="users"></i></span>
                <span class="menu-text"> Staff </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="menuExtendedui">
                <ul class="sub-menu">
                    <li class="menu-item">
                        <a class='menu-link' href="{{ url('admin/staff') }}">
                            <span class="menu-icon"><i data-lucide="user"></i></span>
                            <span class="menu-text"> Staff </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class='menu-link' href="{{ route('designations.index') }}">
                            <span class="menu-icon"><i data-lucide="award"></i></span>
                            <span class="menu-text"> Staff Designation </span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class='menu-link' href="{{ url('admin/posts') }}">
                            <span class="menu-icon"><i data-lucide="clipboard"></i></span>
                            <span class="menu-text"> Staff Post </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="menu-item">
            <a class='menu-link waves-effect' href="{{ url('admin/publications') }}">
                <span class="menu-icon"><i data-lucide="book-open"></i></span>
                <span class="menu-text"> Publications </span>
            </a>
        </li>

        <li class="menu-item">
            <a class='menu-link waves-effect' href="{{ route('admin.career.index') }}">
                <span class="menu-icon"><i data-lucide="briefcase"></i></span>
                <span class="menu-text"> Career </span>
            </a>
        </li>

        <li class="menu-item">
            <a class='menu-link waves-effect' href="{{ route('admin.tags.index') }}">
                <span class="menu-icon"><i data-lucide="briefcase"></i></span>
                <span class="menu-text">Tags </span>
            </a>
        </li>

        <li class="menu-item">
            <a href="#menuNews" data-bs-toggle="collapse" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="rss"></i></span>
                <span class="menu-text">News</span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse menu-dropdown" id="menuNews">
                <ul class="nav nav-sm flex-column">
                    <li class="menu-item">
                        <a href="{{ route('news.index') }}" class="menu-link">
                            <i class="bi bi-pencil-square me-1"></i>
                            <span>Post a News</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('admin.news_categories.index') }}" class="menu-link">
                            <i class="bi bi-card-list me-1"></i>
                            <span>News Categories</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="menu-item">
            <a class='menu-link waves-effect' href="{{ route('users.index') }}">
                <span class="menu-icon"><i data-lucide="user-check"></i></span>
                <span class="menu-text"> User Management </span>
            </a>
        </li>

        <li class="menu-title">Website</li>

        <li class="menu-item">
            <a href="#menuWebsite" data-bs-toggle="collapse" class="menu-link waves-effect">
                <span class="menu-icon"><i data-lucide="globe"></i></span>
                <span class="menu-text">Website</span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse menu-dropdown" id="menuWebsite">
                <ul class="nav nav-sm flex-column">
                    <li class="menu-item">
                        <a href="{{ route('about.index') }}" class="menu-link">
                            <i class="bi bi-person-lines-fill me-2"></i>
                            <span>About</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('vision.index') }}" class="menu-link">
                            <i class="bi bi-bullseye me-2"></i>
                            <span>Vision</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('director_message.index') }}" class="menu-link">
                            <i class="bi bi-megaphone me-2"></i>
                            <span>Director Message</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('menu-management') }}" class="menu-link">
                            <i class="bi bi-megaphone me-2"></i>
                            <span>Menu</span>
                        </a>
                    </li>
                    
                    <li class="menu-item">
                        <a href="{{ route('partners.index') }}" class="menu-link">
                            <i class="bi bi-handshake me-2"></i>
                            <span>Partners</span>
                        </a>
                    </li>
                    
                </ul>
            </div>
        </li>

    </ul>
</div>
