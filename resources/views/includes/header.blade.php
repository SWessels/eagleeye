<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{ url('/') }}">
               {!!  HTML::image('assets/img/logo.png', 'logo', array('class'=> 'logo-default'))  !!}  </a>
            <div class="menu-toggler sidebar-toggler"> </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">


                <li class="dropdown  dropdown-domains">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-globe"></i>
                        <span class="username username-hide-on-mobile">
                        <?php $active_site = ''; ?>
                        @if(Session::has('connection'))
                                @foreach($domains as $domain => $domain_name)
                                    @if(Session::get('connection') == $domain)
                                        <?php $active_site = $domain_name; ?>
                                    @endif
                                @endforeach
                                {{ $active_site }}
                            @endif
                        </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <?php $di = 1; ?>
                        @foreach($domains as $domain => $domain_name)
                                @if(Session::get('connection') == $domain)
                                    <li>
                                        <a href="#">
                                            {{ $di++ }}.{{ $domain_name }}
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('changedomain', ['domain_id' => $domain]) }}">
                                            {{ $di++ }}.{{ $domain_name }}
                                        </a>
                                    </li>
                                @endif
                        @endforeach
                    </ul>
                </li>


                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        {!!  HTML::image('assets/img/avatar2.jpg', 'avatar', array('class'=> 'img-circle'))  !!}
                        <span class="username username-hide-on-mobile"> {!!   Auth::user()->name !!} </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="page_user_profile_1.html">
                                <i class="icon-user"></i> My Profile </a>
                        </li>
                        <li>
                            <a href="app_calendar.html">
                                <i class="icon-calendar"></i> My Calendar </a>
                        </li>
                        <li>
                            <a href="app_inbox.html">
                                <i class="icon-envelope-open"></i> My Inbox
                                <span class="badge badge-danger"> 3 </span>
                            </a>
                        </li>
                        <li>
                            <a href="app_todo.html">
                                <i class="icon-rocket"></i> My Tasks
                                <span class="badge badge-success"> 7 </span>
                            </a>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <a href="page_user_lock_1.html">
                                <i class="icon-lock"></i> Lock Screen </a>
                        </li>
                        <li>
                            <a href="/logout">
                                <i class="icon-key"></i> Log Out </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->

            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->