        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" ng-controller="soacontroller" ng-cloak>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><i class="fas fa-receipt"></i> <strong>My SOA</strong></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">My SOA</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tradename">Trade Name:</label>
                                                <input type="text" class="form-control rounded-0 text-center currency" id="tradename" value="<?php echo $this->session->userdata('trade_name')?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-block float-right btn-flat col-sm-12 col-lg-2" ng-click="gettenantsoa()">GENERATE SOAs</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3" ng-if="soaTable" style="overflow-x:auto;" ng-cloak>
                                        <div class="col-sm-12">
                                            <table id="mySoaTable" class="table table-striped table-bordered table-hover nowrap table-sm display">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <!-- <th class="text-center">Tenant ID</th> -->
                                                        <th class="text-center">SOA No.</th>
                                                        <th class="text-center">Collection Date</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="s in soa">
                                                        <!-- <td class="text-center">{{s.tenant_id}}</td> -->
                                                        <td class="text-center">{{s.soa_no}}</td>
                                                        <td class="text-center">{{s.collection_date}}</td>
                                                        <td class="text-center" style="width: 10%;">
                                                            <print filename="{{s.file_name}}"></print>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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