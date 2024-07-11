        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" ng-controller="readingscontroller" ng-cloak>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><i class="fas fa-tachometer-alt"></i> <strong>Utility Readings</strong></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Utility Readings</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card rounded-0">
                                <div class="card-body">
                                    <form ng-submit="getutilityreadings($event)" name="getutilityreadingsform" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                                       <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="tradename" class="col-sm-3 col-form-label">Trade Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control rounded-0" id="tradename" name="tradename" value="<?php echo $this->session->userdata('trade_name');?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tenantid" class="col-sm-3 col-form-label">Tenant ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control rounded-0" id="tenantid" name="tenantid" value="<?php echo $this->session->userdata('tenant_id');?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="input-group mb-3">
                                                <label for="datefrom" class="col-sm-3 col-form-label"><span class="important">*</span>Date From</label>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                </div>
                                                <input type="date" id="datefrom" name="datefrom" class="form-control rounded-0" ng-model="datefrom" required>
                                            </div>
                                            <div class="input-group mb-3">
                                                <label for="dateto" class="col-sm-3 col-form-label"><span class="important">*</span>Date To</label>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                </div>
                                                <input type="date" id="dateto" name="dateto" class="form-control rounded-0" ng-model="dateto" required>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                                                <div class="col-sm-9">
                                                    <button type="submit" class="btn btn-block btn-primary rounded-0" ng-disabled="getutilityreadingsform.$invalid"><i class="fas fa-cogs"></i> Generate Readings</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                    <div class="row mt-3" ng-if="readingstable" ng-cloak>
                                        <div class="col-12">
                                            <table id="utilityReadings" class="table table-striped table-bordered table-hover responsive nowrap table-sm display">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center">Invoice. No</th>
                                                        <th class="text-center">Posting Date</th>
                                                        <th class="text-center">Description</th>
                                                        <th class="text-center">UOM</th>
                                                        <th class="text-center">Previous Reading</th>
                                                        <th class="text-center">Currrent Reading</th>
                                                        <th class="text-center">Unit Price</th>
                                                        <th class="text-center">Total Unit</th>
                                                        <th class="text-center">Actual Amount</th>
                                                        <th class="text-center">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="r in readings">
                                                        <td>{{r.doc_no}}</td>
                                                        <td>{{r.posting_date}}</td>
                                                        <td>{{r.description}}</td>
                                                        <td>{{r.uom}}</td>
                                                        <td class="text-right">{{r.prev_reading}}</td>
                                                        <td class="text-right">{{r.curr_reading}}</td>
                                                        <td class="text-right">{{r.unit_price}}</td>
                                                        <td class="text-right">{{r.total_unit}}</td>
                                                        <td class="text-right">{{r.actual_amt}}</td>
                                                        <td>{{r.balance}}</td>
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