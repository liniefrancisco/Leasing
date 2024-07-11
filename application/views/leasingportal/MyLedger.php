        <div class="content-wrapper" ng-controller="ledgercontroller" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>My Ledger</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">My Ledger</li>
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
                                    <form ng-submit="getledger($event)" name="getledgerform" method="post" enctype="multipart/form-data" accept-charset="utf-8">
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
                                                    <button type="submit" class="btn btn-block btn-primary rounded-0" ng-disabled="getledgerform.$invalid"><i class="fas fa-cogs"></i> Generate Ledger</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div ng-if="ledgerTable" ng-cloak>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="forwardedbalance" class="col-sm-3 col-form-label">Forwarded Balance</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control text-right rounded-0 currency" name="forwardedbalance" ng-value="forwardedbalance | currency : ''" value="0.00" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3" style="overflow-x:auto;">
                                        <div class="col-sm-12">
                                            <table id="myLedgerTable" class="table table-striped table-bordered table-hover nowrap table-sm display">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Doc No.</th>
                                                        <th class="text-center">Posting Date</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">Total Payable</th>
                                                        <th class="text-center">Payment</th>
                                                        <th class="text-center">Balance</th>
                                                        <th class="text-center">Running Balance</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="l in ledger">
                                                        <td class="text-center">{{ l.flag }}</td>
                                                        <td class="text-center">{{ l.doc_no }}</td>
                                                        <td class="text-center">{{ l.posting_date }}</td>
                                                        <td class="text-center">{{ l.due_date }}</td>
                                                        <td class="text-right">{{ l.debit | currency : '' }}</td>
                                                        <td class="text-right">{{ l.credit | currency : '' }}</td>
                                                        <td class="text-right">{{ l.balance | currency : '' }}</td>
                                                        <td class="text-right">{{ l.runningBalance | currency : '' }}</td>
                                                        <td class="text-center">
                                                            <button 
                                                            class="btn btn-info btn-xs" 
                                                            title="Payment Details"
                                                            data-toggle="modal"
                                                            data-target="#paymentdetailsmodal" 
                                                            ng-click="paymentdetails(l)"> Payment Details</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
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