<!-- resources/views/includes/sidebar.blade.php -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <nav class="mt-2">
            <a src="#" class="brand-link">
                <img src="{{ asset('assets/icon.png') }}"alt="AdminLTE Logo" class="brand-image  elevation-7" style="opacity: .8">
                  <span class="brand-text font-weight-light">Payment Request</span>
                </a>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add your sidebar menu items here -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa fa-th-list"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa fa-th-list"></i>
                        <p style="display: inline; margin-left: 5px;">Requests</p>
                        <i class="right fas fa-angle-left" style="margin-left: auto;"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('request.create') }}" class="nav-link">
                                <i class="far fa fa-plus nav-icon"></i>
                                <p style="display: inline; margin-left: 5px;">Add Request</p>
                            </a>
                        </li>
                        @can('show admin requests')
                        <li class="nav-item">
                            <a href="{{ route('request.show') }}" class="nav-link">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p style="display: inline; margin-left: 5px;">New Requests</p>
                            </a>
                        </li>
                        @endcan

                        @can('show requests')
                        <li class="nav-item">
                            <a href="{{ route('request.userRequests') }}" class="nav-link">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p style="display: inline; margin-left: 5px;">Show All</p>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p style="display: inline; margin-left: 5px;">Categories</p>
                        <i class="right fas fa-angle-left" style="margin-left: auto;"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('category.create') }}" class="nav-link">
                                <i class="far fa fa-plus nav-icon"></i>
                                <p style="display: inline; margin-left: 5px;">Add Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.show') }}" class="nav-link">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p style="display: inline; margin-left: 5px;">Show All</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p style="display: inline; margin-left: 5px;">Suppliers</p>
                        <i class="right fas fa-angle-left" style="margin-left: auto;"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('supplier.create') }}" class="nav-link">
                                <i class="far fa fa-plus nav-icon"></i>
                                <p style="display: inline; margin-left: 5px;">Add Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier.account') }}" class="nav-link">
                                <i class="fa fa-credit-card nav-icon"></i>
                                <p style="display: inline; margin-left: 5px;">Add Accounts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier.show') }}" class="nav-link">
                                <i class="far fa-list-alt nav-icon"></i>
                                <p style="display: inline; margin-left: 5px;">Show All</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @can('add user')
                <li class="nav-item">
                    <a href="{{ route('auth.signup') }}" class="nav-link">
                        <i class="nav-icon fa fa-user-plus"></i>
                        <p>Register User</p>
                    </a>
                </li>
                @endcan

                <li class="nav-item has-treeview">
                    <a href="{{ route('requests.history') }}" class="nav-link">
                        <i class="fas fa-file-signature nav-icon"></i>
                        <p style="display: inline; margin-left: 5px;">Requests Status</p>
                    </a>
                </li>


                @can('show admin requests')
                <li class="nav-item">
                    <a href="{{ route('supplier.report') }}" class="nav-link">
                        <i class="fas fa-file-signature nav-icon"></i>
                        <p>Supplier Report</p>
                    </a>
                </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('cash-accounts') }}" class="nav-link">
                        <i class="nav-icon fa fa-credit-card"></i>
                        <p>Cash Accounts</p>
                    </a>
                </li>
                @can('show admin requests')
                <li class="nav-item">
                    <a href="{{ route('transaction.report') }}" class="nav-link">
                        <i class="fas fa-file-signature nav-icon"></i>
                        <p>Transaction Report</p>
                    </a>
                </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>
