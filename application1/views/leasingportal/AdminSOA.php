        <div class="content-wrapper" ng-controller="adminsoa" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>Upload SOA Data</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Upload SOA Data</li>
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
                                                    <select class="form-control rounded-0" name="store" ng-model="store" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="ACT">Alta Citta</option>
                                                        <option value="AM">Alturas Mall</option>
                                                        <option value="ICM">Island City Mall</option>
                                                        <option value="PM">Plaza Marcela</option>  
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="from" class="col-sm-3 col-form-label"><span class="important">*</span> From</label>
                                                <div class="col-sm-12 col-md-9 col-lg-9">
                                                    <select class="form-control rounded-0" name="from" ng-model="from" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="OLD">OLD - PMS</option>
                                                        <option value="CAS">CAS - PMS</option>
                                                    </select>
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
                                                    <button type="submit" class="btn btn-block btn-primary rounded-0" ng-click="getsoa()"><i class="fas fa-cogs"></i> Generate SOA Data</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <form method="post" id="soauploadtableform" ng-show="soaupload" ng-cloak ng-submit="uploadsoaselected($event)">
                                    <hr>
                                    <!-- <div class="row">
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
                                    </div> -->
                                    <!-- style="overflow-x:auto;" -->
                                    <div class="row" ng-if="soauploadtable">
                                        <div class="col-12">
                                            <table id="soauploadtable" class="table table-sm table-bordered table-hover">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center" style="width: 50px;">
                                                            <input class="checkbox" ng-checked="uploadall()" type="checkbox" class="ml-5 mr-5" ng-model="headerCheck" id="headerCheck" name="headerCheck">
                                                        </th>
                                                        <th class="text-center" style="position: '' !important;">Trade Name</th>
                                                        <th class="text-center">Tenant ID</th>
                                                        <th class="text-center">File Name</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">SOA No</th>
                                                        <th class="text-center">Billing Period</th>
                                                        <th class="text-center">Amount Payable</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 12px;">
                                                    <tr ng-repeat="s in soa">
                                                        <td class="text-center">
                                                            <input class="checkbox" type="checkbox" id="soacheck" name="soacheck[]" value="{{s.id}}" ng-model="s.soacheck">
                                                        </td>
                                                        <td class="text-center">{{ s.trade_name }}</td>
                                                        <td class="text-center">{{ s.tenant_id }}</td>
                                                        <td class="text-center">{{ s.file_name }}</td>
                                                        <td class="text-center">{{ s.collection_date }}</td>
                                                        <td class="text-right">{{ s.soa_no }}</td>
                                                        <td class="text-right">{{ s.billing_period }}</td>
                                                        <td class="text-right">{{ s.amount_payable | currency: ''}}</td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                              <button class="btn btn-info dropdown-toggle btn-sm btn-flat" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                              </button>
                                                              <div class="dropdown-menu dropdown-menu-right rounded-0" aria-labelledby="dropdownMenuButton">
                                                                <a href="#" class="dropdown-item" ng-click="viewsoa(s)"><i class="fas fa-search"></i> View</a>
                                                                <a href="#" class="dropdown-item" ng-click="uploadsoa(s)"><i class="fas fa-upload"></i> Upload</a>
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

        <!-- Modal -->
        <div class="modal fade" id="paymentdetailsmodal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="paymentdetailsmodal" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
              <div class="modal-header bg-dark rounded-0">
                <h5 class="modal-title" id="paymentdetailsmodal"><i class="fa fa-info-circle"></i> Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <div class="col-sm-12 col-lg-12">
                <div class="form-group row">
                    <label for="tenantid" class="col-sm-3 col-form-label">Invoice No.</label>
                    <div class="col-sm-9">
                        <input 
                        type="text" 
                        class="form-control rounded-0" 
                        id="tenantid" 
                        name="tenantid" 
                        ng-model="invoiceno" 
                        readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tenantid" class="col-sm-3 col-form-label">Posting Date</label>
                    <div class="col-sm-9">
                        <input 
                        type="text" 
                        class="form-control rounded-0" 
                        id="tenantid" 
                        name="tenantid" 
                        ng-model="postingdate" 
                        readonly>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12" style="overflow-x:auto;" ng-cloak>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">OR No.</th>
                            <th class="text-center">Posting Date</th>
                            <th class="text-center">Payment Type</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="ng-cloak" ng-repeat="p in payment" ng-if="hasdata" ng-cloak>
                            <td>{{ p.doc_no }}</td>
                            <td>{{ p.posting_date }}</td>
                            <td>{{ p.payment_type}}</td>
                            <td>{{ p.amount | currency : '' }}</td>
                        </tr>
                        <tr ng-if="nodata" ng-cloak>
                            <td colspan="4" class="text-center">NO PAYMENT FOUND</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6"></div>
                <div class="col-sm-12 col-md-6 col-lg-6">                                               
                    <div class="form-group">
                        <label for="tenantid" class="col-sm-6 col-form-label">Grand Total</label>
                        <div class="col-sm-3 col-md-3 col-lg-12">
                            <input type="text" class="form-control rounded-0 text-right currency" placeholder="&#8369 0.00" id="tenantid" name="tenantid" value="&#8369 {{ grand_total | currency : '' }}" readonly>
                        </div>
                    </div>
                </div>
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