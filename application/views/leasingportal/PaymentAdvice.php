        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" ng-controller="paymentadvicectrl" ng-cloak>
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><i class="fas fa-money-check"></i> <strong>Payment Advice</strong></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Payment Advice</li>
                            </ol>
                        </div><!-- /.col -->
                        <div class="col-sm-12">
                            <h4><a class="float-right" href="" data-target="#instructions" data-toggle="modal" style="color: red;"><i class="fas fa-book-reader"></i> Instructions</a><h4>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="card rounded-0">
                                <div class="card-header bg-dark rounded-0">
                                    <h3 class="card-title">Payment Advice Form</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form action="" ng-submit="savepaymentadvice($event)" method="post" name="paymentadviceform" id="paymentadviceform" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="storelocation" class="col-sm-3 col-form-label">Store Location</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" id="storelocation" name="storelocation" value="<?php echo $location;?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="storebankaccount" class="col-sm-3 col-form-label"><span class="important">*</span> Store Bank Account</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <?php if($location === 'Island City Mall'):?>
                                                <select class="form-control rounded-0" name="storebankaccount" ng-model="storebankaccount" ng-change="getaccount()" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>Bank of the Philippine Islands</option>
                                                    <option>Land Bank of the Philippines</option>
                                                    <option>PNB</option>
                                                </select>
                                                <?php elseif($location === 'Alta Citta'):?>
                                                <select class="form-control rounded-0" name="storebankaccount" ng-model="storebankaccount" ng-change="getaccount()" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>Bank of the Philippine Islands</option>
                                                    <option>Land Bank of the Philippines</option>
                                                    <option>PNB</option>
                                                </select>
                                                <?php elseif($location === 'Alturas Mall'):?>
                                                <select class="form-control rounded-0" name="storebankaccount" ng-model="storebankaccount" ng-change="getaccount()" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>Bank of the Philippine Islands</option>
                                                    <option>PNB</option>
                                                </select>
                                                <?php elseif($location === 'Plaza Marcela'):?>
                                                <select class="form-control rounded-0" name="storebankaccount" ng-model="storebankaccount" ng-change="getaccount()" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>Land Bank of the Philippines</option>
                                                    <option>PNB</option>
                                                </select>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="storeaccountnumber" class="col-sm-3 col-form-label"><span class="important">*</span> Store Account Number</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <select class="form-control rounded-0" name="storeaccountnumber" ng-model="storeaccountnumber" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option ng-repeat="a in account">{{ a.account }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tenantaccount" class="col-sm-3 col-form-label"><span class="important">*</span> Tenant Bank Account</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <select class="form-control rounded-0" name="tenantaccount" ng-model="tenantaccount" required ng-change="getaccountformat()">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>BANKS OF THE PHILIPPINE ISLANDS</option>
                                                    <option>BDO</option>
                                                    <option>CHINA BANK</option>
                                                    <option>CITIBANK</option>
                                                    <option>EASTWEST</option>
                                                    <option>FIRST CONSOLIDATED BANK</option>
                                                    <option>LAND BANK OF THE PHILIPPINES</option>
                                                    <option>METRO BANK</option>
                                                    <option>PHILIPPINE BANK OF COMMUNICATIONS</option>
                                                    <option>PNB</option>
                                                    <option>ROBINSONS BANK</option>
                                                    <option>SECURITY BANK</option>
                                                    <option>UCPB</option>
                                                    <option>UNION BANK</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tenantaccountnumber" class="col-sm-3 col-form-label"><span class="important">*</span> Tenant Account Number</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input ng-disabled="numberformat" type="text" class="form-control rounded-0" name="tenantaccountnumber" ng-model="tenantaccountnumber" ui-mask="{{format}}" ui-mask-placeholder ui-mask-placeholder-char="" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tenantaccountname" class="col-sm-3 col-form-label"><span class="important">*</span> Tenant Account Name</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="tenantaccountname" ng-model="tenantaccountname" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="paymentdate" class="col-sm-3 col-form-label"><span class="important">*</span> Payment Date</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="date" class="form-control rounded-0" name="paymentdate" ng-model="paymentdate" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="paymenttype" class="col-sm-3 col-form-label"><span class="important">*</span> Payment Type</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <select class="form-control rounded-0" name="paymenttype" ng-model="paymenttype" ng-change="type()" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>One Location</option>
                                                    <option>Multi Location</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row" ng-show="soanumber">
                                            <label for="soano" class="col-sm-3 col-form-label"><span class="important">*</span> SOA No.</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <select class="form-control rounded-0" name="soano" ng-model="soano" ng-change="getsoaamount()">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option ng-repeat="s in soa">{{s.soa_no}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="input-group form-group" ng-show="soanumber">
                                            <label for="totalpayable" class="col-sm-3 col-form-label">Total Payable Amount</label>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><strong>&#8369;</strong></span>
                                            </div>
                                            <input 
                                                type="text" 
                                                class="form-control text-right col-sm-12 col-md-9 col-lg-9 rounded-0 currency" 
                                                name="totalpayable" 
                                                ng-model="totalpayable" 
                                                placeholder="0.00"
                                                ui-number-mask="2"
                                                readonly>
                                        </div>

                                        <div class="input-group form-group" ng-show="soanumber">
                                            <label for="amounttopay" class="col-sm-3 col-form-label"><span class="important">*</span> Amount to Pay</label>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><strong>&#8369;</strong></span>
                                            </div>
                                            <input 
                                                type="text" 
                                                class="form-control text-right col-sm-12 col-md-9 col-lg-9 rounded-0 currency" 
                                                name="amounttopay" 
                                                ng-model="amounttopay" 
                                                placeholder="0.00"
                                                ui-number-mask="2"
                                                autocomplete="off">
                                        </div>

                                        <div class="form-group row" ng-show="multilocation">
                                            <label for="paymentdate" class="col-sm-3 col-form-label"><span class="important">*</span> Other Location/Tenant ID</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9" ng-init="multi = [{}];">
                                                <div ng-repeat="data in multi">
                                                    <div class="row">
                                                        <div class="form-group col-sm-12 col-md-5 col-lg-5">
                                                            <input type="text" class="form-control rounded-0" name="locations" ng-model="data.locations" autocomplete="off" placeholder="ICM-LTXXXXXXX" autocomplete="off">
                                                        </div>

                                                        <div class="form-group col-sm-12 col-md-5 col-lg-5">
                                                            <input type="text" class="form-control rounded-0 text-right currency" name="locationamount" ng-model="data.locationamount" autocomplete="off" ui-number-mask="2" placeholder="0.00" autocomplete="off">
                                                        </div>
                                                   
                                                        <div class="form-group col-sm-12 col-md-1 col-md-1">
                                                            <button class="btn btn-default btn-flat btn-block" type="button" ng-if="$index == 0" ng-click="multi.push({})">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-flat btn-block" ng-if="$index > 0" ng-click="multi.splice($index, 1)">
                                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group form-group" ng-show="multilocation">
                                            <label for="totalamountpaid" class="col-sm-3 col-form-label"><span class="important">*</span> Total Amount Paid</label>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><strong>&#8369;</strong></span>
                                            </div>
                                            <input 
                                                type="text" 
                                                class="form-control text-right col-sm-12 col-md-9 col-lg-9 rounded-0 currency" 
                                                name="totalamountpaid" 
                                                ng-model="totalamountpaid"
                                                ng-value="totalPaidAmount() | currency : ''" 
                                                placeholder="0.00"
                                                ui-number-mask="2"
                                                readonly>
                                        </div>
                                        <div class="form-group row" ng-show="proofoftransfer">
                                            <label for="proofoftransfer" class="col-sm-3 col-form-label"><span class="important">*</span> Proof of Transfer</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="file" class="form-control rounded-0" name="proofoftransfer" multiple accept="image/*" style="height: 45px;" required>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-info float-right btn-flat" ng-disabled="paymentadviceform.$invalid">
                                                <i class="fas fa-paper-plane"></i> Submit
                                            </button>
                                        </div>
                                        <!-- /.card-footer -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->

            <div class="modal fade" id="instructions" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content rounded-0">
                        <div class="modal-header rounded-0 bg-dark">
                            <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-book-reader"></i> Instructions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <ul class="list-unstyled">
                                    <li><strong>Store Location</strong>       - Where the shop/store is located. By default your store is <b><?php echo $location;?></b></li>
                                    <li><strong>Store Bank Account</strong>   - Select from the drop down the Bank Account that you deposited.</li>
                                    <li><strong>Store Account Number</strong> - Select from the dropdown the Bank Account number that you deposited.</li>
                                    <li><strong>Tenant Bank Account</strong>  - Select from the dropdown your bank account.</li>
                                    <li><strong>Tenant Account Number</strong>- Input your bank account number.</li>
                                    <li><strong>Tenant Account Name</strong>  - Input your bank account name.</li>
                                    <li><strong>Payment Date</strong>         - Select the date you made the deposit.</li>
                                    <li><strong>Payment Type</strong>
                                        <ul>
                                            <li><i>One Location</i>   - Only Select this option if you are only paying the SOA of this account that you are using.</li>
                                            <li><i>Multi Location</i> - Only Select this option when you have multiple stall/shop in one location.</li>
                                        </ul>
                                    </li>
                                    <li><strong>One Location</strong>
                                        <ul>
                                            <li><i>SOA No.</i> - Select from the drop down the SOA number that you intend to pay</li>
                                            <li><i>Total Payable Amount</i> - Total Amount of your SOA</li>
                                            <li><i>Amount to pay</i> - Input The amount in from your deposit slip</li>
                                        </ul>
                                    </li>
                                    <li><strong>Multi Location</strong>
                                        <ul>
                                            <li><strong>Other Location/Tenant ID</strong>
                                                <ul>
                                                    <li>Example: Tenant ID: [ICM-LTXXXXX] Amount: [5,000]</li>
                                                    <li>Input the tenant ID of your other shop, and the amount that you need to pay.</li>
                                                    <li>Then the Leasing Accounting will allocate the amount to a specified SOA that is pending</li>
                                                    <li>To add more location please click the + button near the amount field</li>
                                                </ul>
                                            </li>
                                            <li><strong>Total Amount Paid</strong> - Total Amount you inputted</li>
                                        </ul>
                                    </li>
                                    <li><strong>Proof of transfer</strong> - Proof of payment, scanned image of your deposit slip. Only accept the following formats: <i>.jpg, .png. jpeg .gif</i></li>
                                </ul>

                                <p><strong>After you fill up all the required fields you can now post/sent your payment advice.</strong></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->