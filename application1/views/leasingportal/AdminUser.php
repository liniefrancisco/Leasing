        <div class="content-wrapper" ng-controller="adminuser" ng-cloak>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-book"></i> 
                                <strong>Users</strong>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Users</li>
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
                                     <div class="row mb-5">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="col-sm-9">
                                                <button type="submit" class="btn btn-primary rounded-0" data-toggle="modal" data-target="#newUser"><i class="fas fa-plus"></i> Add New User</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <table id="adminUsersTable" class="table table-sm table-bordered table-hover" ng-init="getUsers()">
                                                <thead class="bg-dark">
                                                    <tr>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Tenant ID</th>
                                                        <th class="text-center">Username</th>
                                                        <th class="text-center">Password</th>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr ng-repeat="u in users">
                                                        <td class="text-center">{{ u.name }}</td>
                                                        <td class="text-center">{{ u.tenant_id }}</td>
                                                        <td class="text-center">{{ u.username }}</td>
                                                        <td class="text-center">{{ u.password_in_roman }}</td>
                                                        <td class="text-center">{{ u.type }}</td>
                                                        <td class="text-center">
                                                            <span ng-if="u.status == 'Active'" class="active">{{ u.status }}</span>
                                                            <span ng-if="u.status == 'Deactivated'" class="deactivated">{{ u.status }}</span> 
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                              <button class="btn btn-info dropdown-toggle btn-sm btn-flat" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                Action
                                                              </button>
                                                              <div class="dropdown-menu dropdown-menu-right rounded-0" aria-labelledby="dropdownMenuButton">
                                                                <a 
                                                                    ng-if="u.type == 'Tenant'" 
                                                                    href="#" 
                                                                    class="dropdown-item" 
                                                                    ng-click="updateuser(u)"
                                                                    data-toggle="modal"
                                                                    data-target="#updateUser1"><i class="fas fa-edit"></i> Update</a>
                                                                <a 
                                                                    ng-if="u.type == 'Admin' || u.type == 'Accounting'" 
                                                                    href="#" 
                                                                    class="dropdown-item" 
                                                                    ng-click="updateuser(u)"
                                                                    data-toggle="modal"
                                                                    data-target="#updateUser2"><i class="fas fa-edit"></i> Update</a>
                                                                <a ng-if="u.status == 'Active'" href="#" class="dropdown-item danger" ng-click="deactivate(u)"><i class="fas fa-ban"></i> Deactivate</a>
                                                                <a ng-if="u.status == 'Deactivated'" href="#" class="dropdown-item" ng-click="reactivate(u)" style="color: green;"><i class="fas fa-user-plus"></i> Reactivate</a>
                                                                <a href="#" class="dropdown-item" ng-click="resetpassword(u)"><i class="fas fa-undo"></i> Reset Password</a>
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

        <!-- Add New User Modal -->
        <div class="modal fade" id="newUser" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="paymentdetailsmodal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-dark rounded-0">
                        <h5 class="modal-title" id="paymentdetailsmodal"><i class="fas fa-user"></i> New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8" ng-submit="saveNewUser($event)" name="newUserForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group row">
                                        <label for="userType" class="col-sm-3 col-form-label"><span class="important">*</span> User Type</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <select class="form-control rounded-0" name="userType" ng-model="userType" required>
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option>Tenant</option>
                                                    <option>Admin</option>
                                                    <option>Accounting</option>
                                                </select>
                                            </div>
                                    </div>

                                    <!-- ---------------------------------- ADD NEW TENANT USER ---------------------------------- -->
                                    <div class="form-group row" ng-show="userType == 'Tenant'">
                                        <label for="tenantType" class="col-sm-3 col-form-label"><span class="important">*</span> Tenant Type</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <select class="form-control rounded-0" name="tenantType" ng-model="tenantType" ng-change="getProspects()">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option value="Long Term">Long Term</option>
                                                    <option value="Short Term">Short Term</option>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="form-group row" ng-show="userType == 'Tenant'">
                                        <label for="store" class="col-sm-3 col-form-label"><span class="important">*</span> Store</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <select class="form-control rounded-0" name="store" ng-model="store" ng-change="getProspects()">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option value="4">Alta Citta</option>
                                                    <option value="1">Alturas Mall</option>
                                                    <option value="2">Island City Mall</option>
                                                    <option value="3">Plaza Marcela</option>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="form-group row" ng-show="userType == 'Tenant'">
                                        <label for="tradeName" class="col-sm-3 col-form-label"><span class="important">*</span>Trade Name</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <select class="form-control rounded-0" name="tradeName" ng-model="tradeName">
                                                    <option value="" disabled="" selected="" style="display:none">Please Select One</option>
                                                    <option ng-repeat="p in prospects" value="{{p.tenant_id}}">{{p.trade_name}} - {{p.tenant_id}}</option>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="form-group row" ng-show="userType == 'Tenant'">
                                        <label for="tenantUserName" class="col-sm-3 col-form-label"><span class="important">*</span> Username</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="tenantUserName" value="{{tradeName}}" autocomplete="off">
                                            </div>
                                    </div>
                                    <!-- ---------------------------------- ADD NEW TENANT USER ---------------------------------- -->

                                    <!-- ---------------------------------- ADD NEW ADMIN/ACCOUNTING USER ---------------------------------- -->
                                    <div class="form-group row" ng-if="userType == 'Admin' || userType == 'Accounting'">
                                        <label for="firstName" class="col-sm-3 col-form-label"><span class="important">*</span> First Name</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="firstName" ng-model="firstName" autocomplete="off" required>
                                                <div class="validation-Error">
                                                    <span ng-show="newUserForm.firstName.$dirty && newUserForm.firstName.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="form-group row" ng-if="userType == 'Admin' || userType == 'Accounting'">
                                        <label for="middleName" class="col-sm-3 col-form-label"><span class="important">*</span> Middle Name</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="middleName" ng-model="middleName" autocomplete="off">
                                            </div>
                                    </div>
                                    <div class="form-group row" ng-if="userType == 'Admin' || userType == 'Accounting'">
                                        <label for="lastName" class="col-sm-3 col-form-label"><span class="important">*</span> Last Name</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="lastName" ng-model="lastName" autocomplete="off" required>
                                                <div class="validation-Error">
                                                    <span ng-show="newUserForm.lastName.$dirty && newUserForm.lastName.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="form-group row" ng-if="userType == 'Admin' || userType == 'Accounting'">
                                        <label for="userName" class="col-sm-3 col-form-label"><span class="important">*</span> Username</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="userName" ng-model="userName" autocomplete="off" required>
                                                <div class="validation-Error">
                                                    <span ng-show="newUserForm.userName.$dirty && newUserForm.userName.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                    <!-- ---------------------------------- ADD NEW ADMIN/ACCOUNTING USER ---------------------------------- -->

                                    <div class="form-group row" ng-show="userType">
                                        <label for="password" class="col-sm-3 col-form-label"><span class="important">*</span> Password</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input 
                                                    type="password" 
                                                    class="form-control rounded-0" 
                                                    name="password" 
                                                    ng-model="password"
                                                    ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/" 
                                                    ng-minlength="5" 
                                                    autocomplete="off" 
                                                    required>

                                                <div class="validation-Error">
                                                    <span ng-show="newUserForm.password.$dirty && newUserForm.password.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="newUserForm.password.$dirty && newUserForm.password.$error.pattern">
                                                        <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                    </span>
                                                    <span ng-show="newUserForm.password.$dirty && newUserForm.password.$error.minlength">
                                                        <p class="error-display">Atleast 5 characters.</p>
                                                    </span>
                                                </div>
                                            </div>

                                    </div>

                                    <div class="form-group row" ng-show="userType">
                                        <label for="confirmPassword" class="col-sm-3 col-form-label"><span class="important">*</span> Confirm Password</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input 
                                                    type="password" 
                                                    class="form-control rounded-0" 
                                                    name="confirmPassword" 
                                                    ng-model="confirmPassword"
                                                    ng-pattern="password" 
                                                    autocomplete="off" 
                                                    required>

                                                <div class="validation-Error">
                                                    <span ng-show="newUserForm.confirmPassword.$dirty && newUserForm.confirmPassword.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="newUserForm.confirmPassword.$dirty && newUserForm.confirmPassword.$error.pattern">
                                                        <p class="error-display">Password not match.</p>
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-flat" ng-disabled="newUserForm.$invalid"><i class="fas fa-save"></i> Save</button>
                            <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateUser1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="paymentdetailsmodal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-dark rounded-0">
                        <h5 class="modal-title" id="paymentdetailsmodal"><i class="fas fa-edit"></i> Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8" ng-submit="updateUserSubmit($event)" name="updateUserForm1">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group row">
                                        <label for="userType" class="col-sm-3 col-form-label"><span class="important">*</span> User Type</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="userType" ng-model="userType" autocomplete="off" required readonly="">
                                            </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tradeName" class="col-sm-3 col-form-label"><span class="important">*</span>Trade Name</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="tradeName" ng-model="tradeName" autocomplete="off">
                                            </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tenantUserName" class="col-sm-3 col-form-label"><span class="important">*</span> Username</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="tenantUserName" ng-model="tenantUserName" autocomplete="off">
                                            </div>
                                    </div>
                                    <!-- ---------------------------------- ADD NEW TENANT USER ---------------------------------- -->

                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 col-form-label"><span class="important">*</span> Password</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input 
                                                    type="password" 
                                                    class="form-control rounded-0" 
                                                    name="password" 
                                                    ng-model="password"
                                                    ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/" 
                                                    ng-minlength="5" 
                                                    autocomplete="off" 
                                                    required>

                                                <div class="validation-Error">
                                                    <span ng-show="updateUserForm1.password.$dirty && updateUserForm1.password.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="updateUserForm1.password.$dirty && updateUserForm1.password.$error.pattern">
                                                        <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                    </span>
                                                    <span ng-show="updateUserForm1.password.$dirty && updateUserForm1.password.$error.minlength">
                                                        <p class="error-display">Atleast 5 characters.</p>
                                                    </span>
                                                </div>
                                            </div>

                                    </div>

                                    <div class="form-group row">
                                        <label for="confirmPassword" class="col-sm-3 col-form-label"><span class="important">*</span> Confirm Password</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input 
                                                    type="password" 
                                                    class="form-control rounded-0" 
                                                    name="confirmPassword" 
                                                    ng-model="confirmPassword"
                                                    ng-pattern="password" 
                                                    autocomplete="off" 
                                                    required>

                                                <div class="validation-Error">
                                                    <span ng-show="updateUserForm1.confirmPassword.$dirty && updateUserForm1.confirmPassword.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="updateUserForm1.confirmPassword.$dirty && updateUserForm1.confirmPassword.$error.pattern">
                                                        <p class="error-display">Password not match.</p>
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-flat" ng-disabled="updateUserForm1.$invalid"><i class="fas fa-save"></i> Update</button>
                            <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateUser2" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="paymentdetailsmodal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header bg-dark rounded-0">
                        <h5 class="modal-title" id="paymentdetailsmodal"><i class="fas fa-user"></i> New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8" ng-submit="updateUserSubmit($event)" name="updateUserForm2">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group row">
                                        <label for="userType" class="col-sm-3 col-form-label"><span class="important">*</span> User Type</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="userType" ng-model="userType" autocomplete="off" required readonly="">
                                            </div>
                                    </div>

                                    <!-- ---------------------------------- ADD NEW ADMIN/ACCOUNTING USER ---------------------------------- -->
                                    <div class="form-group row">
                                        <label for="fullName" class="col-sm-3 col-form-label"><span class="important">*</span> Full Name</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="fullName" ng-model="fullName" autocomplete="off" required>
                                                <div class="validation-Error">
                                                    <span ng-show="updateUserForm.fullName.$dirty && updateUserForm.fullName.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="userName" class="col-sm-3 col-form-label"><span class="important">*</span> Username</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input type="text" class="form-control rounded-0" name="userName" ng-model="userName" autocomplete="off" required>
                                                <div class="validation-Error">
                                                    <span ng-show="updateUserForm2.userName.$dirty && updateUserForm2.userName.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                    <!-- ---------------------------------- ADD NEW ADMIN/ACCOUNTING USER ---------------------------------- -->

                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 col-form-label"><span class="important">*</span> Password</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input 
                                                    type="password" 
                                                    class="form-control rounded-0" 
                                                    name="password" 
                                                    ng-model="password"
                                                    ng-pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{5,}$/" 
                                                    ng-minlength="5" 
                                                    autocomplete="off" 
                                                    required>

                                                <div class="validation-Error">
                                                    <span ng-show="updateUserForm2.password.$dirty && updateUserForm2.password.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="updateUserForm2.password.$dirty && updateUserForm2.password.$error.pattern">
                                                        <p class="error-display">A combination of alphanumeric characters and at least 1 upppercase.</p>
                                                    </span>
                                                    <span ng-show="updateUserForm2.password.$dirty && updateUserForm2.password.$error.minlength">
                                                        <p class="error-display">Atleast 5 characters.</p>
                                                    </span>
                                                </div>
                                            </div>

                                    </div>

                                    <div class="form-group row">
                                        <label for="confirmPassword" class="col-sm-3 col-form-label"><span class="important">*</span> Confirm Password</label>
                                            <div class="col-sm-12 col-md-9 col-lg-9">
                                                <input 
                                                    type="password" 
                                                    class="form-control rounded-0" 
                                                    name="confirmPassword" 
                                                    ng-model="confirmPassword"
                                                    ng-pattern="password" 
                                                    autocomplete="off" 
                                                    required>

                                                <div class="validation-Error">
                                                    <span ng-show="updateUserForm2.confirmPassword.$dirty && updateUserForm2.confirmPassword.$error.required">
                                                        <p class="error-display">This field is required.</p>
                                                    </span>
                                                    <span ng-show="updateUserForm2.confirmPassword.$dirty && updateUserForm2.confirmPassword.$error.pattern">
                                                        <p class="error-display">Password not match.</p>
                                                    </span>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-flat" ng-disabled="updateUserForm2.$invalid"><i class="fas fa-save"></i> Update</button>
                            <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- /.content-wrapper -->