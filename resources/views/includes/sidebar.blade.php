<div class="page-sidebar-wrapper">

    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">

        <!-- BEGIN SIDEBAR MENU -->

        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper hide">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler"> </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->

            <li class="nav-item start ">
                <a href="{{ url('/') }}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                  </a>
            </li>

            @if($user->userCan('themusthaves.nl') || $user->userCan('themusthaves.de')  || $user->userCan('musthavesforreal.com') )
            <li class="nav-item  ">
                <a href="javascript;" class="nav-link nav-toggle">
                    <i class="icon-globe"></i>
                    <span class="title">Websites</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if($user->userCan('themusthaves.nl'))
                    <li class="nav-item  ">
                        <a href="#" class="nav-link ">
                            <span class="title">Themusthaves.nl</span>
                        </a>
                    </li>
                    @endif

                    @if($user->userCan('themusthaves.de'))
                    <li class="nav-item  ">
                        <a href="" class="nav-link ">
                            <span class="title">Themusthaves.de</span>
                        </a>
                    </li>
                    @endif

                    @if($user->userCan('musthavesforreal.com'))
                    <li class="nav-item  ">
                        <a href="" class="nav-link ">
                            <span class="title">Musthavesforreal.com</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if($user->userCan('posts'))
            <li class="nav-item">
                <a href="{{ route('posts.index') }}" class="nav-link nav-toggle">
                    <i class="icon-paper-plane"></i>
                    <span class="title">Posts</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="{{ route('posts.index') }}" class="nav-link ">
                            <span class="title">All Posts</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{ route('posts.create') }}" class="nav-link ">
                            <span class="title">Create New</span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="{{ url('/PostCategories') }}" class="nav-link ">
                            <span class="title">Categories</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{ url('/posttags') }}" class="nav-link ">
                            <span class="title">Post Tags</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if($user->userCan('media'))
            <li class="nav-item  ">
                <a href="{{ url('/media') }}" class="nav-link nav-toggle">
                    <i class="icon-camera"></i>
                    <span class="title">Media</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="{{ url('/media') }}" class="nav-link ">
                            <span class="title">Library</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{route('media.create')}}" class="nav-link ">
                            <span class="title">Add New</span>
                        </a>
                    </li>

                </ul>
            </li>
            @endif

            @if($user->userCan('pages'))
            <li class="nav-item  ">
                <a href="{{route('pages.index')}}" class="nav-link nav-toggle">
                    <i class="icon-book-open"></i>
                    <span class="title">Pages</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="{{route('pages.index')}}" class="nav-link ">
                            <span class="title">All Pages</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{route('pages.create')}}" class="nav-link ">
                            <span class="title">Add New Page</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif



            @if($user->userCan('coupons') || $user->userCan('tabs') || $user->userCan('product_feeds') || $user->userCan('invoices') || $user->userCan('settings'))
            <li class="nav-item  ">
                <a href="javascript;" class="nav-link nav-toggle">
                    <i class="icon-basket"></i>
                    <span class="title">Ecommerce</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                @if($user->userCan('coupons'))
                   <li class="nav-item  ">
                        <a href="{{ route('coupons.index')}}" class="nav-link ">
                            <span class="title">Coupons</span>
                        </a>
                   </li>
                 @endif

                 @if($user->userCan('tabs'))
                    <li class="nav-item  ">
                        <a href="{{route('tabs.index')}}" class="nav-link ">
                            <span class="title">Tabs</span>
                        </a>
                    </li>
                  @endif

                    @if($user->userCan('product_feeds'))
                    <li class="nav-item  ">
                        <a href="{{ url('/productfeeds')}}" class="nav-link ">
                            <span class="title">Product Feeds</span>
                        </a>
                    </li>
                    @endif

                    @if($user->userCan('invoices'))
                    <li class="nav-item  ">
                        <a href="#" class="nav-link ">
                            <span class="title">Invoices</span>
                        </a>
                    </li>
                    @endif

                    @if($user->userCan('settings'))
                    <li class="nav-item  ">
                        <a href="#" class="nav-link ">
                            <span class="title">Settings</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if($user->userCan('products'))
             <li class="nav-em  ">
                <a href="{{ route('products.index') }}" class="nav-link nav-toggle">
                    <i class="icon-grid"></i>
                    <span class="title">Products</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="{{ route('products.index') }}" class="nav-link ">
                            <span class="title">All Products</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{ route('products.create') }}" class="nav-link ">
                            <span class="title">Add New Product</span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="{{ url('/categories') }}" class="nav-link ">
                            <span class="title">Product Categories</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{ url('/tags') }}" class="nav-link ">
                            <span class="title">Product Tags</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{ url('/attributes') }}" class="nav-link ">
                            <span class="title">Attributes</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if($user->userCan('orders'))
                <li class="nav-item  ">
                    <a href="{{ route('orders.index') }}" class="nav-link nav-toggle">
                        <i class="icon-handbag"></i>
                        <span class="title">Orders</span>
                        <span class="arrow"></span>
                    </a>
                </li>
            @endif

            @if($user->userCan('revenue_report') || $user->userCan('sales_report') || $user->userCan('customer_report') || $user->userCan('stock_report'))
            <li class="nav-item  ">
                <a href="javascript;" class="nav-link nav-toggle">
                    <i class="icon-graph"></i>
                    <span class="title">Reports</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if($user->userCan('revenue_report'))
                    <li class="nav-item  ">
                        <a href="{{route('revenue_report.index')}}" class="nav-link ">
                            <span class="title">Revenue</span>
                        </a>
                    </li>
                    @endif

                    @if($user->userCan('sales_report'))
                    <li class="nav-item  ">
                        <a href="#" class="nav-link ">
                            <span class="title">Sales</span>
                        </a>
                    </li>
                    @endif

                    @if($user->userCan('customer_report'))
                    <li class="nav-item  ">
                        <a href="#" class="nav-link ">
                            <span class="title">Customers</span>
                        </a>
                    </li>
                    @endif

                    @if($user->userCan('stock_report'))
                    <li class="nav-item  ">
                        <a href="{{ route('stocks.index') }}" class="nav-link ">
                            <span class="title">Stock</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif


            @if($user->userCan('plugins'))
            <li class="nav-item  ">
                <a href="javascript;" class="nav-link nav-toggle">
                    <i class="icon-screen-desktop"></i>
                    <span class="title">Display</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="#" class="nav-link ">
                            <span class="title">Widgets</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{route('menu.index')}}" class="nav-link ">
                            <span class="title">Menus</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{route('sliders.index')}}" class="nav-link ">
                            <span class="title">Sliders</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            @if($user->userCan('plugins'))
            <li class="nav-item  ">
                <a href="#" class="nav-link nav-toggle">
                    <i class="icon-puzzle"></i>
                    <span class="title">Plugins</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if($user->userCan('category_drag_drop'))
                    <li class="nav-item">
                        <a href="/category_drag_drop/" class="nav-link ">
                            <span class="title">Drag and Drop Editor</span>
                        </a>
                    </li> 
                    @endif
                    @if($user->userCan('pricing'))
                        <li class="nav-item  ">
                            <a href="{{url('actions')}}" class="nav-link ">
                                <span class="title">Actions</span>
                            </a>
                        </li>
                    @endif
                        @if($user->userCan('seo_general'))
                            <li class="nav-item">
                                <a href="/seo_general/" class="nav-link ">
                                    <span class="title">SEO</span>
                                </a>
                                <ul class="sub-menu">
                                    @if($user->userCan('sitemap'))
                                        <li class="nav-item">
                                            <a href="/sitemap_index/" class="nav-link ">
                                                <span class="title">Sitemap</span>
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                        @if($user->userCan('top_banners'))
                            <li class="nav-item">
                                <a href="#" class="nav-link ">
                                    <span class="title">Top Of Page Banners</span>
                                </a>
                                <ul class="sub-menu">
                                    @if($user->userCan('mobile_banners'))
                                        <li class="nav-item">
                                            <a href="/show_mobile_banners/" class="nav-link ">
                                                <span class="title">Mobile Banners</span>
                                            </a>
                                        </li>
                                        @if($user->userCan('desktop_banners'))
                                            <li class="nav-item">
                                                <a href="/show_desktop_banners/" class="nav-link ">
                                                    <span class="title">Desktop Banners</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                </ul>

                            </li>
                        @endif

                </ul>

            </li>
            @endif

            @if($user->userCan('employees'))
            <li class="nav-item  ">
                 <a href="{{ route('users.index') }}" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">Employees</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="{{ route('users.index') }}" class="nav-link ">
                            <span class="title">Employees</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{ route('users.create') }}" class="nav-link ">
                            <span class="title">Add New Employee</span>
                        </a>
                    </li>

                    <li class="nav-item  ">
                        <a href="#" class="nav-link ">
                            <span class="title">Employee Capabilities</span>
                        </a>
                    </li>

                </ul>
            </li>
            @endif

            @if($user->userCan('customers'))
            <li class="nav-item">
                <a href="{{ route('customers.index') }}" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">Customers</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="{{ route('customers.index') }}" class="nav-link ">
                            <span class="title">All Customers</span>
                        </a>
                    </li>
                   {{-- <li class="nav-item  ">
                        <a href="{{ route('customers.create') }}" class="nav-link ">
                            <span class="title">Create New</span>
                        </a>
                    </li>--}}
                    </ul>
            </li>
            @endif
        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>