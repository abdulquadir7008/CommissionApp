<!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
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
            <!-- ============================================================== -->