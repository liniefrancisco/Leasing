        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-tachometer-alt"></i>
                                <strong>Dashboard</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-body">
                                    <h3><b>CONTENTS</b></h3>
                                    <ul>
                                        <?php if($this->session->userdata('user_type') == 'Admin'):?>
                                            <li>Users</li>
                                        <?php endif;?>
                                        <li>Reports</li>
                                        <ul>
                                            <li>Upload History</li>
                                            <li>Payment Advice Notices</li>
                                            <li>Payment Advice Notices History</li>
                                        </ul>
                                    </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->

</div>
        <!-- /.content-wrapper -->