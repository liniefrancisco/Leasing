        <div class="content-wrapper" ng-controller="paymentadvice" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>Payment Advice Notice History</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Payment Advice Notice History</li>
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
                                    <!-- style="overflow-x:auto;" -->
                                    <div class="row">
                                        <div class="col-12">
                                            <table id="paymentadviceHistory" class="table table-sm table-bordered table-hover" ng-init="getNoticesHistory()">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center">Advice Code</th>
                                                        <th class="text-center">Payment Date</th>
                                                        <th class="text-center">Tenant Bank Account</th>
                                                        <th class="text-center">Tenant Account Number</th>
                                                        <th class="text-center">Tenant Account Name</th>
                                                        <th class="text-center">Payment Type</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 12px;">
                                                    <tr ng-repeat="n in noticesHistory">
                                                        <td class="text-center">{{ n.advice_code }}</td>
                                                        <td class="text-center">{{ n.payment_date }}</td>                                                        
                                                        <td class="text-center">{{ n.tenant_bank }}</td>
                                                        <td class="text-center">{{ n.account_number }}</td>
                                                        <td class="text-center">{{ n.account_name }}</td>
                                                        <td class="text-center">{{ n.payment_type }}</td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                              <button class="btn btn-info dropdown-toggle btn-sm btn-flat" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                              </button>
                                                              <div class="dropdown-menu dropdown-menu-right rounded-0" aria-labelledby="dropdownMenuButton">
                                                                <a href="#" class="dropdown-item" ng-click="viewproof(n)"><i class="fas fa-search"></i> View Proof</a>
                                                                <a ng-if="n.payment_type == 'One Location'" href="#" class="dropdown-item" data-toggle="modal" data-target="#advice1" ng-click="viewadviceHistory(n)"><i class="fas fa-search"></i> View Advice</a>
                                                                <a ng-if="n.payment_type == 'Multi Location'" href="#" class="dropdown-item" data-toggle="modal" data-target="#advice2" ng-click="viewadviceHistory(n)"><i class="fas fa-search"></i> View Advice</a>
                                                              </div>
                                                            </div>
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
        <div class="modal fade" id="advice1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="paymentdetailsmodal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-dark rounded-0">
                        <h5 class="modal-title" id="paymentdetailsmodal"><i class="fa fa-info-circle"></i> Payment Advice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="adviceCode1" class="col-sm-12 col-form-label">Advice Code</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0"
                                                    name="adviceCode1" 
                                                    ng-model="adviceCode1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tenantid1" class="col-sm-12 col-form-label">Tenant ID</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0"
                                                    name="tenantid1" 
                                                    ng-model="tenantid1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paymentDate1" class="col-sm-12 col-form-label">Payment Date</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="paymentDate1" 
                                                    name="paymentDate1" 
                                                    ng-model="paymentDate1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="store1" class="col-sm-12 col-form-label">Store</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="store1" 
                                                    name="store1" 
                                                    ng-model="store1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeBankAccount1" class="col-sm-12 col-form-label">Store Bank Account</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="storeBankAccount1" 
                                                    name="storeBankAccount1" 
                                                    ng-model="storeBankAccount1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeAccountNumber1" class="col-sm-12 col-form-label">Store Account Number</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="storeAccountNumber1" 
                                                    name="storeAccountNumber1" 
                                                    ng-model="storeAccountNumber1" 
                                                    readonly>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="bankAccount1" class="col-sm-12 col-form-label">Bank Account</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="bankAccount1" 
                                                    name="bankAccount1" 
                                                    ng-model="bankAccount1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountNumber1" class="col-sm-12 col-form-label">Account Number</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="accountNumber1" 
                                                    name="accountNumber1" 
                                                    ng-model="accountNumber1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountName1" class="col-sm-12 col-form-label">Account Name</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="accountName1" 
                                                    name="accountName1" 
                                                    ng-model="accountName1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="soaNo1" class="col-sm-12 col-form-label">SOA No.</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="soaNo1" 
                                                    name="soaNo1" 
                                                    ng-model="soaNo1" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="totalPayable1" class="col-sm-12 col-form-label">Total Payable</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0 text-right currency" 
                                                    id="totalPayable1" 
                                                    name="totalPayable1" 
                                                    ng-model="totalPayable1"
                                                    ui-number-mask="2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="amountPaid1" class="col-sm-12 col-form-label">Amount Paid</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0 text-right currency" 
                                                    id="amountPaid1" 
                                                    name="amountPaid1" 
                                                    ng-model="amountPaid1" 
                                                    ui-number-mask="2"
                                                    readonly>
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

        <div class="modal fade" id="advice2" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="paymentdetailsmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-dark rounded-0">
                        <h5 class="modal-title" id="paymentdetailsmodal"><i class="fa fa-info-circle"></i> Payment Advice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8" ng-submit="postAdviceOne($event)">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group">
                                        <label for="adviceCode2" class="col-sm-12 col-form-label">Advice Code</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0"
                                                    name="adviceCode2" 
                                                    ng-model="adviceCode2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tenantid2" class="col-sm-12 col-form-label">Tenant ID</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0"
                                                    name="tenantid2" 
                                                    ng-model="tenantid2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paymentDate2" class="col-sm-12 col-form-label">Payment Date</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="paymentDate2" 
                                                    name="paymentDate2" 
                                                    ng-model="paymentDate2" 
                                                    readonly>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group">
                                        <label for="store2" class="col-sm-12 col-form-label">Store</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="store2" 
                                                    name="store2" 
                                                    ng-model="store2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeBankAccount2" class="col-sm-12 col-form-label">Store Bank Account</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="storeBankAccount2" 
                                                    name="storeBankAccount2" 
                                                    ng-model="storeBankAccount2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeAccountNumber2" class="col-sm-12 col-form-label">Store Account Number</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="storeAccountNumber2" 
                                                    name="storeAccountNumber2" 
                                                    ng-model="storeAccountNumber2" 
                                                    readonly>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group">
                                        <label for="bankAccount2" class="col-sm-12 col-form-label">Bank Account</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="bankAccount2" 
                                                    name="bankAccount2" 
                                                    ng-model="bankAccount2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountNumber2" class="col-sm-12 col-form-label">Account Number</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="accountNumber2" 
                                                    name="accountNumber2" 
                                                    ng-model="accountNumber2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountName2" class="col-sm-12 col-form-label">Account Name</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="accountName2" 
                                                    name="accountName2" 
                                                    ng-model="accountName2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="amountPaid2" class="col-sm-12 col-form-label">Total Amount Paid</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0 text-right currency" 
                                                    id="amountPaid2" 
                                                    name="amountPaid2" 
                                                    ng-model="amountPaid2" 
                                                    ui-number-mask="2"
                                                    readonly>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-12">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Tenant ID to Pay</th>
                                                <th class="text-center">Amount Paid</th>
                                                <th class="text-center">SOA Applied</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-repeat="s in soahistory">
                                                <td class="text-center">{{s.tenant_id}}</td>
                                                <td class="text-right currency">&#8369 {{s.amount_paid | currency: ''}}</td>
                                                <td class="text-center">{{s.soa_no}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->