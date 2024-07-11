<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = "maincontroller/login";
$route['404_override']       = '';

# LEASING PORTAL 1.2 ROUTES
$route['leasingportal2']  = "maincontroller/login";
$route['home']            = "maincontroller/home";
$route['mysoa']           = "maincontroller/mysoa";
$route['myledger']        = "maincontroller/myledger";
$route['utilityreadings'] = "maincontroller/utilityreadings";
$route['paymentadvice']   = "maincontroller/paymentadvice";
$route['checkuser']       = "maincontroller/checkuser";
$route['logout']          = "maincontroller/logout";
$route['maintenance']     = "maincontroller/maintenance";
$route['adviceHistory']   = "maincontroller/adviceHistory";

$route['changeusername']   = "maincontroller/changeusername";
$route['changepassword']   = "maincontroller/changepassword";
$route['userpasswordform'] = "maincontroller/userpasswordform";
$route['useruserform']     = "maincontroller/useruserform";
$route['testingSMS']     = "maincontroller/testingSMS";

# SOA CONTROLLER
$route['portalGetSoa'] = 'soacontroller/get_tenantSoa';

# LEDGER CONTROLLER
$route['getforwardedbalance'] = "ledgercontroller/getforwardedbalance";
$route['gettenantledger']     = "ledgercontroller/gettenantledger";
$route['getpaymentdetails']   = "ledgercontroller/getpaymentdetails";

# UTILITY READINGS CONTROLLER
$route['getreadings'] = "readingscontroller/getreadings";

# PAYMENT ADVICE CONTROLLER
$route['soabalances']         = "paymentadvicecontroller/soabalances";
$route['soaamount']           = "paymentadvicecontroller/soaamount";
$route['savepaymentadvice']   = "paymentadvicecontroller/savepaymentadvice";
$route['paymentadviceh']      = "paymentadvicecontroller/paymentadviceh";
$route['updatePaymentAdvice'] = "paymentadvicecontroller/updatePaymentAdvice";

# ADMIN LEASING PORTAL
$route['admindashboard']         = "admincontroller/admindashboard";
$route['admininvoicepertenant']  = "admincontroller/admininvoicepertenant";
$route['adminsoa']               = "admincontroller/adminsoa";
$route['paymentpertenant']       = "admincontroller/paymentpertenant";
$route['paymentperstore']        = "admincontroller/paymentperstore";
$route['uploadinghistory']       = "admincontroller/uploadinghistory";
$route['paymentnotices']         = "admincontroller/paymentnotices";
$route['paymentnoticeshistory']  = "admincontroller/paymentnoticeshistory";
$route['adminusers']             = "admincontroller/adminusers";
$route['blastUser']              = "admincontroller/blastUser";

$route['admingettenant']         = "admininvoicecontroller/admingettenant";
$route['uploadinvoicepertenant'] = "admininvoicecontroller/uploadinvoicepertenant";

$route['getsoa']                 = "adminsoacontroller/getsoa";
$route['uploadsoadata']          = "adminsoacontroller/uploadsoadata";
$route['uploadsoadatachecked']   = "adminsoacontroller/uploadsoadatachecked";

$route['getpayment']               = "adminpaymentcontroller/getpayment";
$route['uploadpaymentdata']        = "adminpaymentcontroller/uploadpaymentdata";
$route['uploadpaymentdatachecked'] = "adminpaymentcontroller/uploadpaymentdatachecked";
$route['getpaymentperstore']       = "adminpaymentcontroller/getpaymentperstore";

$route['getuploadhistory']  = "adminuploadhistorycontroller/getuploadhistory";
$route['gethistorydocs']    = "adminuploadhistorycontroller/gethistorydocs";

$route['getNotices']                      = "adminpaymentadvicecontroller/getNotices";
$route['getNoticesHistory']               = "adminpaymentadvicecontroller/getNoticesHistory";
$route['getAdvices/(:any)/(:any)']        = "adminpaymentadvicecontroller/getAdvices/$1/$2";
$route['postAdviceOne']                   = "adminpaymentadvicecontroller/postAdviceOne";
$route['getAdvicesHistory/(:any)/(:any)'] = "adminpaymentadvicecontroller/getAdvicesHistory/$1/$2";
$route['getAdvicesTenant/(:any)/(:any)']  = "adminpaymentadvicecontroller/getAdvicesTenant/$1/$2";

$route['getUsers']             = "adminuserscontroller/getUsers";
$route['getProspects']         = "adminuserscontroller/getProspects";
$route['saveNewUser']          = "adminuserscontroller/saveNewUser";
$route['updateUser']           = "adminuserscontroller/updateUser";
$route['deactivate/(:any)']    = "adminuserscontroller/deactivate/$1";
$route['resetPassword/(:any)'] = "adminuserscontroller/resetPassword/$1";
$route['reactivate/(:any)']    = "adminuserscontroller/reactivate/$1";

$route['senUserBlast'] = "adminblastcontroller/senUserBlast";

# API ROUTES
$route['uploadSoaDataAPI']     = "maincontroller/uploadSoaDataAPI";
$route['uploadSoaFile']        = "maincontroller/uploadSoaFile";
$route['sendMail']             = "maincontroller/sendMail";
$route['notifications']        = "portmaincontrolleral/notifications";
$route['uploadAllInvoiceData'] = "maincontroller/uploadAllInvoiceData";
$route['uploadPaymentData']    = "maincontroller/uploadPaymentData";
$route['paymentNotification']  = "maincontroller/paymentNotification";
$route['perTenantUpload']      = "maincontroller/perTenantUpload";
$route['blastSend']            = "maincontroller/blastSend";
$route['uploadNewDataOnline']  = "maincontroller/uploadNewDataOnline";

$route['test']                 = "maincontroller/test";


#-----------------------------------------------------------------------------------------------------
// # BASE ROUTES
// $route['check_login']     = "portal/check_login";
// // $route['logout']          = "portal/logout";

// # PORTAL ROUTES
// $route['tenant_soa']                            = "portal/tenant_soa";
// $route['rr_ledger']                             = "portal/rr_ledger";
// $route['utilityReadings']                       = "portal/utilityReadings";
// $route['user_credentials']                      = "portal/user_credentials";
// $route['getTenantSoa']                          = "portal/get_tenantSoa";
// $route['updateUserSettings/(:any)']             = "portal/update_usettings_tenant/$1";
// $route['getForwardedBalance/(:any)/(:any)']     = "portal/get_forwarded_balance/$1/$2";
// $route['get_ledgerTenant/(:any)/(:any)/(:any)'] = "portal/get_ledgerTenant/$1/$2/$3";
// $route['get_payment_details/(:any)/(:any)']     = "portal/get_payment_details/$1/$2";
// $route['getReading/(:any)/(:any)/(:any)']       = "portal/getReading/$1/$2/$3";

// # PAYMENT ADVICE PORTAL
// $route['paymentAdvice']     = "portal/paymentAdvice";
// $route['savePaymentAdvice'] = "portal/savePaymentAdvice";
// $route['soa_balances']      = "portal/soa_balances";


// # - PORTAL ADMIN ROUTES
// $route['admin_tenants'] = "portal/admin_tenants";
// # - - SOA
// $route['getSoa/(:any)'] = "portal/getSoa/$1";
// $route['admin_soa']     = "portal/admin_soa";
// # - - INVOICE
// $route['admin_invoices'] = "portal/admin_invoices";
// $route['perStore']       = "portal/perStore";
// $route['allData']        = "portal/allData";
// $route['perTenant']      = "portal/perTenant";
// $route['uploadAllInvoiceData'] = "portal/uploadAllInvoiceData";
// $route['getTenants']           = "portal/getTenants";

// $route['perTenantUpload'] = "portal/perTenantUpload";


// # - - PAYMENT
// $route['admin_payment'] = "portal/admin_payment";
// $route['admin_paymentPerStore'] = "portal/admin_paymentPerStore";
// $route['get_payment']   = "portal/get_payment";
// $route['get_paymentPerStore']   = "portal/get_paymentPerStore";
// $route['get_soa']                  = "portal/get_soa";
// $route['uploadSoaData/(:any)']     = 'portal/uploadSoaData/$1';
// $route['uploadPaymentData/(:any)'] = 'portal/uploadPaymentData/$1';
// $route['search_tradeName']         = "portal/search_tradeName";

// $route['translate_uri_dashes'] = FALSE;

// # MARCH 31, 2022
// # PAYMENT ADVICE ADMIN ROUTES
// $route['getAdvices/(:any)/(:any)'] = "portal/getAdvices/$1/$2";
// $route['postAdvice']               = "portal/postAdvice";
// $route['notices']                  = "portal/notices";
// $route['getTenantsMulti']          = "portal/getTenantsMulti";

// # OTHERS ROUTES
// $route['uploadLeasingData'] = "portal/uploadLeasingData";
// $route['uploadhistory'] = "portal/uploadhistory";
// $route['exportPaymentAdvice'] = "portal/exportPaymentAdvice";

// $route['uploadCheckedSoa']     = "portal/uploadCheckedSoa";
// $route['uploadCheckedPayment'] = "portal/uploadCheckedPayment";

$route['translate_uri_dashes'] = FALSE;
