        <div class="content-wrapper" ng-controller="adminblast" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>Blast User SMS/Email</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Blast User SMS/Email</li>
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
                                    <form ng-submit="sendUsers($event)" name="blastSMSEMailForm" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                                     <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="tenanttype" class="col-sm-3 col-form-label"><span class="important">*</span> Tenant Type</label>
                                                <div class="col-sm-12 col-md-9 col-lg-9">
                                                    <select class="form-control rounded-0" name="tenanttype" ng-model="tenanttype" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="Long Term">Long Term</option>
                                                        <option value="Short Term">Short Term</option> 
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group row">
                                                <label for="store" class="col-sm-3 col-form-label"><span class="important">*</span> Store</label>
                                                <div class="col-sm-12 col-md-9 col-lg-9">
                                                    <select class="form-control rounded-0" name="store" ng-model="store" ng-change="getenants()" required>
                                                        <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                        <option value="4">Alta Citta</option>
                                                        <option value="1">Alturas Mall</option>
                                                        <option value="2">Island City Mall</option>
                                                        <option value="3">Plaza Marcela</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label"></label>
                                                <div class="col-sm-9">
                                                    <button type="submit" class="btn btn-block btn-primary rounded-0" ng-disabled="blastSMSEMailForm.$invalid"><i class="fas fa-upload"></i> Blast</button>
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