<aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->

            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap"><span class="hide-menu">Navigation Menu</span></li>

                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link active" href="dashboard.php"
                                aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                                    class="hide-menu">Dashboard</span></a></li>
                        <li class="list-divider"></li>

                        <li class="sidebar-item"> <a class="sidebar-link" href="for-approval.php"
                                aria-expanded="false"><i class=" fas fa-mouse-pointer"></i> <span
                                    class="hide-menu">DEL Approval

                                <?php
                                    $sqlstate = "SELECT COUNT(*) AS total FROM qr_delegates WHERE state = 'Pending'";
                                    $resultstate = $conn->query($sqlstate);

                                    if ($resultstate->num_rows > 0) {
                                        $rowstate = $resultstate->fetch_assoc();
                                        $badgeColor = ($rowstate['total'] > 0) ? 'success' : 'secondary';
                                        echo '<span id="counter" class="badge badge-pill badge-' . $badgeColor . '">' . $rowstate['total'] . '</span>';
                                    } else {
                                        echo '<span id="counter" class="badge badge-pill badge-secondary">0</span>';
                                    }
                                ?>

                                <script type="text/javascript">
                                  var refreshInterval;
                                    refreshInterval = setInterval(function() {
                                      $('#counter').load(document.URL + ' #counter');
                            
                                    }, 2000);
                                
                                </script>

                                     
                                </span></a>
                        </li>

                         
                     

                       

                        <li class="list-divider"></li>


                         <li class="sidebar-item"> <a class="sidebar-link" href="event_manage.php"
                                aria-expanded="false"><i class="fas fa-qrcode"></i> <span
                                    class="hide-menu">&nbsp;Manage Event
                                </span></a>
                        </li>
                        <?php if($login_access['role']=='1' || $login_access['role']=='4'){?>
						 <li class="sidebar-item"> <a class="sidebar-link" href="data_import.php"
                                aria-expanded="false"><i class="fas fa-qrcode"></i> <span
                                    class="hide-menu">&nbsp;Import Excel File
                                </span></a>
                        </li>
                        <?php } ?>

						<?php if($login_access['role']=='1' || $login_access['role']=='2' || $login_access['role']=='4'){?>
						<li class="sidebar-item"> <a class="sidebar-link" href="aproval_deligate_list.php"
                                aria-expanded="false"><i class="fas fa-users"></i> <span
                                    class="hide-menu">Approval Import File
                                </span></a>
                        </li>
						<?php } ?>
						<?php if($login_access['role']=='1'){?>
						<li class="sidebar-item"> <a class="sidebar-link" href="users.php"
                                aria-expanded="false"><i class="fas fa-users"></i> <span
                                    class="hide-menu">Registered user
                                </span></a>
                        </li>
						<li class="sidebar-item"> <a class="sidebar-link" href="user_role.php"
                                aria-expanded="false"><i class="fas fa-users"></i> <span
                                    class="hide-menu">Add User Role
                                </span></a>
                        </li>
						<li class="sidebar-item"> <a class="sidebar-link" href="user_privileges.php"
                                aria-expanded="false"><i class="fas fa-users"></i> <span
                                    class="hide-menu">Add Privileges
                                </span></a>
                        </li>
						
						<?php } ?>
						
                        <li class="list-divider"></li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>