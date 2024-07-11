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
                                    <h3 class="card-title"><i class="fas fa-key"></i> Change Username</h3>
                                </div>
                                <form action="" ng-submit="savenewusername($event)" method="post" name="changeuserform" id="changeuserform" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="newusername">New Username</label>
                                            <input type="text" class="form-control rounded-0" name="newusername" ng-model="newusername" autocomplete="off" required ng-minlength="5">

                                            <div class="validation-Error">
                                                <span ng-show="changeuserform.newusername.$dirty && changeuserform.newusername.$error.required">
                                                    <p class="error-display">This field is required.</p>
                                                </span>
                                                <span ng-show="changeuserform.newusername.$dirty && changeuserform.newusername.$error.minlength">
                                                    <p class="error-display">Atleast 5 characters.</p>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-info float-right btn-flat" ng-disabled="changeuserform.$invalid">
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