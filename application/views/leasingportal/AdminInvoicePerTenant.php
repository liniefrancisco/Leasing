        <div class="content-wrapper" ng-controller="invoicepertenant" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>Upload Invoice Per Tenant</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Upload Invoice Per Tenant</li>
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
                                    <form ng-submit="uploadpertenant($event)" name="uploadpertenantform" method="post" enctype="multipart/form-data" accept-charset="utf-8">
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
                                            <div class="form-group row">
                                                <label for="tradename" class="col-sm-3 col-form-label"><span class="important">*</span> Trade Name</label>
                                                <div class="col-sm-12 col-md-9 col-lg-9">
                                                    <select class="form-control rounded-0" name="tradename" ng-model="tradename" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option ng-repeat="t in tenants" value="{{t.tenant_id}}">{{ t.tenant_id }} - {{ t.trade_name }}</option>
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
                                                    <button type="submit" class="btn btn-block btn-primary rounded-0" ng-disabled="uploadpertenantform.$invalid"><i class="fas fa-upload"></i> Upload Invoice Data</button>
                                                </div>
                                            </div>
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