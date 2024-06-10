<header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                   
                    
                    <center><a href="dashboard.php"><img src="<?php echo $domain_url;?>assets/images/opengov-logo.jpg" style="max-width: 100%; height: auto;margin-left: -10px;" alt="homepage" class="dark-logo" /></a></center>
                                
                                
                    
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
              
                <div class="navbar-collapse collapse float-left mr-auto" id="navbarSupportedContent">
                    <ul class="navbar-nav float-left mr-auto">

                      

                      
                    </ul>
                    <ul class="navbar-nav float-right">
                        

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="<?php echo $domain_url;?>assets/images/admin.jpg" alt="user" class="rounded-circle"
                                    width="40">
                                <span class="ml-2 d-none d-lg-inline-block"><span>Hello,</span> <span
                                        class="text-dark"><?php echo $login_access['fullname'];?></span> <i data-feather="chevron-down"
                                        class="svg-icon"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <div class="pl-4 p-3"><a class="btn btn-sm btn-info" data-toggle="modal"
                                        data-target="#logout-modal" style="color:#fff;"><i data-feather="power"
                                        class="svg-icon mr-2 ml-1"></i>
                                    Logout</a></div>


                                
                            </div>
                        </li>
                      
                    </ul>
                </div>

            </nav>
        </header>