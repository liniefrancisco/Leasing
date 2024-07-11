        <div class="content-wrapper" ng-controller="uploadhistory" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>Uploading History</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Uploading History</li>
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
                                     <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="store" class="col-sm-3 col-form-label"><span class="important">*</span> Store</label>
                                                <div class="col-sm-12 col-md-9 col-lg-9">
                                                    <select class="form-control rounded-0" name="store" ng-model="store" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="ACT">Alta Citta</option>
                                                        <option value="AM">Alturas Mall</option>
                                                        <option value="ICM">Island City Mall</option>
                                                        <option value="PM">Plaza Marcela</option>  
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="input-group mb-3">
                                                <label for="datefrom" class="col-sm-3 col-form-label"><span class="important">*</span>Date From</label>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                </div>
                                                <input type="date" id="datefrom" name="datefrom" class="form-control rounded-0" ng-model="datefrom" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
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
                                                    <button type="button" class="btn btn-block btn-primary rounded-0" ng-click="gethistory()"><i class="fas fa-cogs"></i> Generate History</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- style="overflow-x:auto;" -->
                                    <div class="row" ng-if="uploadhistory">
                                        <div class="col-12">
                                            <table id="uploadhistorytable" class="table table-sm table-bordered table-hover">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center">Tenant ID</th>
                                                        <th class="text-center">Type Uploaded</th>
                                                        <th class="text-center">Status Message</th>
                                                        <th class="text-center">Date Uploaded</th>
                                                        <th class="text-center">User</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 12px;">
                                                    <tr ng-repeat="h in history">
                                                        <td class="text-center">{{ h.tenant_id }}</td>
                                                        <td class="text-center">{{ h.type_uploaded }}</td>
                                                        <td class="text-center">{{ h.status_message }}</td>
                                                        <td class="text-center">{{ h.date_uploaded }}</td>
                                                        <td class="text-center">{{ h.username }}</td>
                                                        <td class="text-center">
                                                            <button 
                                                            class="btn btn-info btn-xs" 
                                                            title="View History"
                                                            data-toggle="modal"
                                                            data-target="#historymodal" 
                                                            ng-click="viewhistory(h)"> View History</button>
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

        <!-- Modal -->
        <div class="modal fade" id="historymodal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="paymentdetailsmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-dark rounded-0">
                        <h5 class="modal-title" id="paymentdetailsmodal"><i class="fa fa-info-circle"></i> Upload History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6">
                            <div class="form-group row">
                                <label for="tenantid" class="col-sm-3 col-form-label">Tenant ID</label>
                                    <div class="col-sm-6">
                                        <input 
                                            type="text" 
                                            class="form-control rounded-0" 
                                            id="tenantid" 
                                            name="tenantid" 
                                            ng-model="tenantid" 
                                            readonly>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="typeUploaded" class="col-sm-3 col-form-label">Type Uploaded</label>
                                    <div class="col-sm-6">
                                        <input 
                                            type="text" 
                                            class="form-control rounded-0" 
                                            id="typeUploaded" 
                                            name="typeUploaded" 
                                            ng-model="typeUploaded" 
                                            readonly>
                                    </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-6">
                            <div class="form-group row">
                                <label for="dateUploaded" class="col-sm-3 col-form-label">Date Uploaded</label>
                                    <div class="col-sm-6">
                                        <input 
                                            type="text" 
                                            class="form-control rounded-0" 
                                            id="dateUploaded" 
                                            name="dateUploaded" 
                                            ng-model="dateUploaded" 
                                            readonly>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label for="statusMessage" class="col-sm-3 col-form-label">Status Message</label>
                                    <div class="col-sm-6">
                                        <input 
                                            type="text" 
                                            class="form-control rounded-0" 
                                            id="statusMessage" 
                                            name="statusMessage" 
                                            ng-model="statusMessage" 
                                            readonly>
                                    </div>
                            </div>
                        </div>
                        </div>

                        <div class="col-sm-12 col-lg-12" style="overflow-x:auto;" ng-cloak>
                            <table class="table table-bordered table-sm">
                                <thead class="bg-dark">
                                    <tr>
                                        <th class="text-center">Document No.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="ng-cloak" ng-repeat="hd in historyDocs" ng-if="hasdata" ng-cloak>
                                        <td class="text-center">{{ hd.documentno }}</td>
                                    </tr>
                                    <tr ng-if="nodata" ng-cloak>
                                        <td colspan="1" class="text-center">NO DATA FOUND</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->