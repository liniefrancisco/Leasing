        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" ng-controller="settingsctrl" ng-cloak>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-4">
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="card rounded-0">
                                <div class="card-header bg-dark rounded-0">
                                    <h3 class="card-title"><i class="fas fa-key"></i> Change Password</h3>
                                </div>
                                <form action="" ng-submit="savenewpassword($event)" method="post" name="changepasswordform" id="changepasswordform" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="newpassword">New Password</label>
                                            <input type="password" class="form-control rounded-0" name="newpassword" ng-model="newpassword" autocomplete="off" required ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/" ng-minlength="5">

                                            <div class="validation-Error">
                                                <span ng-show="changepasswordform.newpassword.$dirty && changepasswordform.newpassword.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="changepasswordform.newpassword.$dirty && changepasswordform.newpassword.$error.pattern">
                                                    <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                </span>
                                                <span ng-show="changepasswordform.newpassword.$dirty && changepasswordform.newpassword.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmpassword">Confirm Password</label>
                                            <input type="password" class="form-control rounded-0" name="confirmpassword" ng-pattern="newpassword" ng-model="confirmpassword" autocomplete="off" required>
                                            <div class="validation-Error">
                                                <span ng-show="changepasswordform.confirmpassword.$dirty && changepasswordform.confirmpassword.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="changepasswordform.confirmpassword.$dirty && changepasswordform.confirmpassword.$error.pattern">
                                                    <p class="error-display">Password not match.</p>
                                                </span>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-info float-right btn-flat" ng-disabled="changepasswordform.$invalid">
                                                <i class="fas fa-paper-plane"></i> Save Changes
                                            </button>
                                        </div>
                                        <!-- /.card-footer -->
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
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