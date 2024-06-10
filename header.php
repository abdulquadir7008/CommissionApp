<header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                   
                    
                    <center><img src="assets/images/opengov-logo.jpg" style="max-width: 100%; height: auto;margin-left: -10px;" alt="homepage" class="dark-logo" /></center>
                                
                                
                    
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
              
                <div class="navbar-collapse collapse float-left mr-auto ml-6 pl-0" id="navbarSupportedContent">
                    <ul class="navbar-nav float-left mr-auto ml-6 pl-0">

                         <?php
                            $sql0 = "SELECT * FROM qr_event WHERE STATUS='Active'";
                                                $result0 = $conn->query($sql0);
                                                if($result0->num_rows > 0){
                                                while($row0 = $result0->fetch_array()){
                                                    ?>
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-6 align-self-center">
                        <h3 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo $row0['EVENT_TITLE'] ;?></h3>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item"><small>EVENT NAME</small></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-6 align-self-center">
                        <div class="customize-input float-right">

                            
                            <h6 class="page-title text-truncate text-dark font-weight-medium mb-1"><?php echo $row0['EVENT_VENUE'] ;?>, <?php echo $row0['EVENT_DATE'] ;?></h6>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0 p-0">
                                    <li class="breadcrumb-item float-right"><small>EVENT DETAILS</small></li>
                                </ol>
                            </nav>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                                    }
                                    }else{
                                    echo "<center><p> No Records</p></center>";
                                    }
                                    ?>

                      
                    </ul>
                    <ul class="navbar-nav float-right">
                        

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <img src="assets/images/admin.jpg" alt="user" class="rounded-circle"
                                    width="40">
                                <span class="ml-2 d-none d-lg-inline-block"><span>Hello,</span> <span
                                        class="text-dark">Admin</span> <i data-feather="chevron-down"
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