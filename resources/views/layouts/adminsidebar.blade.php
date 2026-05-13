<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
    <div class="sidebar-brand"> <!--begin::Brand Link--> <a href="../index.html" class="brand-link">
            <!--begin::Brand Image-->
            {{-- <img src="../../../dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow">  --}}
                <!--end::Brand Image--> <!--begin::Brand Text-->
                <span
                class="brand-text fw-light">RZS</span> <!--end::Brand Text--> </a> <!--end::Brand Link--> </div>
    <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2"> <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon bi bi-border"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('admin/role/permission/*') ? 'menu-open' : '' }}"> <a href="#" class="nav-link {{ request()->is('admin/role/permission/*') ? 'active' : '' }}">  <span class="nav-icon mdi mdi-home"></span>
                        <p>
                           Role & Permission
                            <i class="nav-arrow bi bi-chevron-right"></i>

                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a href="{{ route('admin.roleIndex') }}" class="nav-link {{ request()->is('admin/role/permission/role/*') ? 'active' : '' }}"> <i
                                    class="mdi mdi-account-group"></i>
                                <p>Role List</p>
                            </a> </li>
                        <li class="nav-item"> <a href="{{ route('admin.permissionIndex') }}" class="nav-link {{ request()->is('admin/role/permission/permission/*') ? 'active' : '' }}"> <i
                                    class="mdi mdi-axis-lock"></i>
                                <p>Permission List</p>
                            </a> </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('admin/events/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/events/*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-event"></i>
                        <p>
                            Events
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.events.index') }}" class="nav-link {{ request()->is('admin/events') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>Event List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.event-details.index') }}" class="nav-link {{ request()->is('admin/event-details') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Registrations</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul> <!--end::Sidebar Menu-->
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside>
