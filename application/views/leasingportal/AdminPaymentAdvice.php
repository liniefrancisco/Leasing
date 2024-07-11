        <div class="content-wrapper" ng-controller="paymentadvice" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>Payment Advice Notices</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Payment Advice Notices</li>
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
                                            <table id="paymentadvicenoticetable" class="table table-sm table-bordered table-hover" ng-init="getNotices()">
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
                                                    <tr ng-repeat="n in notices">
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
                                                                <a ng-if="n.payment_type == 'One Location'" href="#" class="dropdown-item" data-toggle="modal" data-target="#advice1" ng-click="viewadvice(n)"><i class="fas fa-upload"></i> View Advice</a>
                                                                <a ng-if="n.payment_type == 'Multi Location'" href="#" class="dropdown-item" data-toggle="modal" data-target="#advice2" ng-click="viewadvice(n)"><i class="fas fa-upload"></i> View Advice</a>
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
                    <form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8" ng-submit="postAdviceOne($event)">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="adviceCode" class="col-sm-12 col-form-label">Advice Code</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0"
                                                    name="adviceCode" 
                                                    ng-model="adviceCode" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tenantid" class="col-sm-12 col-form-label">Tenant ID</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0"
                                                    name="tenantid" 
                                                    ng-model="tenantid" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paymentDate" class="col-sm-12 col-form-label">Payment Date</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="paymentDate" 
                                                    name="paymentDate" 
                                                    ng-model="paymentDate" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="store" class="col-sm-12 col-form-label">Store</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="store" 
                                                    name="store" 
                                                    ng-model="store" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeBankAccount" class="col-sm-12 col-form-label">Store Bank Account</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="storeBankAccount" 
                                                    name="storeBankAccount" 
                                                    ng-model="storeBankAccount" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeAccountNumber" class="col-sm-12 col-form-label">Store Account Number</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="storeAccountNumber" 
                                                    name="storeAccountNumber" 
                                                    ng-model="storeAccountNumber" 
                                                    readonly>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="bankAccount" class="col-sm-12 col-form-label">Bank Account</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="bankAccount" 
                                                    name="bankAccount" 
                                                    ng-model="bankAccount" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountNumber" class="col-sm-12 col-form-label">Account Number</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="accountNumber" 
                                                    name="accountNumber" 
                                                    ng-model="accountNumber" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountName" class="col-sm-12 col-form-label">Account Name</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="accountName" 
                                                    name="accountName" 
                                                    ng-model="accountName" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="soaNo" class="col-sm-12 col-form-label">SOA No.</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="soaNo" 
                                                    name="soaNo" 
                                                    ng-model="soaNo" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="totalPayable" class="col-sm-12 col-form-label">Total Payable</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0 text-right currency" 
                                                    id="totalPayable" 
                                                    name="totalPayable" 
                                                    ng-model="totalPayable"
                                                    ui-number-mask="2" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="amountPaid" class="col-sm-12 col-form-label">Amount Paid</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0 text-right currency" 
                                                    id="amountPaid" 
                                                    name="amountPaid" 
                                                    ng-model="amountPaid" 
                                                    ui-number-mask="2"
                                                    readonly>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-flat">Post</button>
                            <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
                        </div>
                    </form>
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
                                        <label for="adviceCode" class="col-sm-12 col-form-label">Advice Code</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0"
                                                    name="adviceCode" 
                                                    ng-model="adviceCode" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tenantid" class="col-sm-12 col-form-label">Tenant ID</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0"
                                                    name="tenantid" 
                                                    ng-model="tenantid" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="paymentDate" class="col-sm-12 col-form-label">Payment Date</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="paymentDate" 
                                                    name="paymentDate" 
                                                    ng-model="paymentDate" 
                                                    readonly>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group">
                                        <label for="store" class="col-sm-12 col-form-label">Store</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="store" 
                                                    name="store" 
                                                    ng-model="store" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeBankAccount" class="col-sm-12 col-form-label">Store Bank Account</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="storeBankAccount" 
                                                    name="storeBankAccount" 
                                                    ng-model="storeBankAccount" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="storeAccountNumber" class="col-sm-12 col-form-label">Store Account Number</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="storeAccountNumber" 
                                                    name="storeAccountNumber" 
                                                    ng-model="storeAccountNumber" 
                                                    readonly>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-4">
                                    <div class="form-group">
                                        <label for="bankAccount" class="col-sm-12 col-form-label">Bank Account</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="bankAccount" 
                                                    name="bankAccount" 
                                                    ng-model="bankAccount" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountNumber" class="col-sm-12 col-form-label">Account Number</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="accountNumber" 
                                                    name="accountNumber" 
                                                    ng-model="accountNumber" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="accountName" class="col-sm-12 col-form-label">Account Name</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0" 
                                                    id="accountName" 
                                                    name="accountName" 
                                                    ng-model="accountName" 
                                                    readonly>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="amountPaid" class="col-sm-12 col-form-label">Total Amount Paid</label>
                                            <div class="col-sm-12">
                                                <input 
                                                    type="text" 
                                                    class="form-control rounded-0 text-right currency" 
                                                    id="amountPaid" 
                                                    name="amountPaid" 
                                                    ng-model="amountPaid" 
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
                                            <tr ng-repeat="s in soa">
                                                <td class="text-center">
                                                    <input 
                                                        type="text" 
                                                        class="form-control rounded-0 text-center"
                                                        name="tenantidtoapply[]"
                                                        autocomplete="off"
                                                        value="{{s.tenant_id}}"
                                                        readonly>
                                                </td>
                                                <td class="text-right currency">&#8369 {{s.amount_paid | currency: ''}}</td>
                                                <td class="text-center">
                                                    <input 
                                                        type="text" 
                                                        class="form-control rounded-0" 
                                                        id="soaapplied" 
                                                        name="soaapplied[]" 
                                                        ng-model="soaapplied"
                                                        autocomplete="off"
                                                        required
                                                        placeholder="Please Input SOA Applied">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-flat">Post</button>
                            <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->