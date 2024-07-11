        <div class="content-wrapper" ng-controller="adminpayment" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>Upload Payment Per Tenant</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Upload Payment Per Tenant</li>
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
                                                <label for="tenanttype" class="col-sm-3 col-form-label"><span class="important">*</span> Tenant Type</label>
                                                <div class="col-sm-12 col-md-9 col-lg-9">
                                                    <select class="form-control rounded-0" name="tenanttype" ng-model="tenanttype" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="LT">Long Term</option>
                                                        <option value="ST">Short Term</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="store" class="col-sm-3 col-form-label"><span class="important">*</span> Store</label>
                                                <div class="col-sm-12 col-md-9 col-lg-9">
                                                    <select class="form-control rounded-0" name="store" ng-model="store" ng-change="getenants()" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="ACT">Alta Citta</option>
                                                        <option value="AM">Alturas Mall</option>
                                                        <option value="ICM">Island City Mall</option>
                                                        <option value="PM">Plaza Marcela</option>  
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="tenantID" class="col-sm-3 col-form-label"><span class="important">*</span> Trade Name</label>
                                                <div class="col-sm-12 col-md-9 col-lg-9">
                                                    <select class="form-control rounded-0" name="tenantID" ng-model="tenantID" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat="t in tenants" value="{{t.tenant_id}}">{{ t.tenant_id }} - {{ t.trade_name }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                                                <div class="col-sm-9">
                                                    <button type="submit" class="btn btn-block btn-primary rounded-0" ng-click="getpayment()"><i class="fas fa-cogs"></i> Generate Payments Data</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <form ng-show="paymentupload" method="post" id="paymentuploadtableform" ng-cloak ng-submit="uploadpaymentselected($event)">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="forwardedbalance" class="col-form-label"></label>
                                                <div class="col-sm-5">
                                                     <button 
                                                            type="submit" 
                                                            class="btn btn-info btn-sm btn-flat" 
                                                            title="Upload Selected"
                                                            name="uploadselect"
                                                            ng-disabled="uploadselect"><i class="fas fa-upload"></i> Upload Selected</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- style="overflow-x:auto;" -->
                                    <div class="row" ng-if="paymentuploadtable">
                                        <div class="col-12">
                                            <table id="paymentuploadtable" class="table table-sm table-bordered table-hover">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center" style="position: '' !important; width: 50px;">
                                                            <input class="checkbox" ng-checked="uploadall()" type="checkbox" class="ml-5 mr-5" ng-model="headerCheck" id="headerCheck" name="headerCheck">
                                                        </th>
                                                        <th class="text-center">Receipt No</th>
                                                        <th class="text-center">Description</th>
                                                        <th class="text-center">Amount Paid</th>
                                                        <th class="text-center">Check No</th>
                                                        <th class="text-center">Check Date</th>
                                                        <th class="text-center">Payee</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 12px;">
                                                    <tr ng-repeat="p in payment">
                                                        <td class="text-center">
                                                            <input class="checkbox" type="checkbox" id="paymentcheck" name="paymentcheck[]" value="{{p.id}}" ng-model="p.paymentcheck">
                                                        </td>
                                                        <td class="text-center">{{ p.receipt_no }}</td>
                                                        <td class="text-center">{{ p.tender_typeDesc }}</td>
                                                        <td class="text-right">{{ p.amount_paid | currency: '' }}</td>
                                                        <td class="text-center">
                                                            <span ng-if="p.check_no != '' || p.check_no != null">{{ p.check_no }}</span>
                                                            <span ng-if="p.check_no == '' || p.check_no == null">N/A</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span ng-if="p.check_date != '' || p.check_date != null">{{ p.check_date }}</span>
                                                            <span ng-if="p.check_date == '' || p.check_date == null">N/A</span>
                                                        </td>
                                                        <td class="text-center">{{ p.payee }}</td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                              <button class="btn btn-info dropdown-toggle btn-sm btn-flat" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                              </button>
                                                              <div class="dropdown-menu dropdown-menu-right rounded-0" aria-labelledby="dropdownMenuButton">
                                                                <a href="#" class="dropdown-item" ng-click="viewpayment(p)"><i class="fas fa-search"></i> View</a>
                                                                <a href="#" class="dropdown-item" ng-click="uploadpayment(p)"><i class="fas fa-upload"></i> Upload</a>
                                                              </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
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