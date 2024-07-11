<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = "portal/index";
$route['404_override']       = '';

$route['check_login']     = "portal/check_login";
$route['logout']          = "portal/logout";
$route['tenant_soa']      = "portal/tenant_soa";
$route['rr_ledger']       = "portal/rr_ledger";
$route['utilityReadings'] = "portal/utilityReadings";

$route['user_credentials']                      = "portal/user_credentials";
$route['getTenantSoa']                          = "portal/get_tenantSoa";
$route['updateUserSettings/(:any)']             = "portal/update_usettings_tenant/$1";
$route['getForwardedBalance/(:any)/(:any)']     = "portal/get_forwarded_balance/$1/$2";
$route['get_ledgerTenant/(:any)/(:any)/(:any)'] = "portal/get_ledgerTenant/$1/$2/$3";
$route['get_payment_details/(:any)/(:any)']     = "portal/get_payment_details/$1/$2";
$route['getReading/(:any)/(:any)/(:any)']       = "portal/getReading/$1/$2/$3";


# PAYMENT ADVICE ROUTES
$route['paymentAdvice'] = "portal/paymentAdvice";
$route['soa_balances']  = "portal/soa_balances";
$route['getSoa/(:any)'] = "portal/getSoa/$1";

$route['savePaymentAdvice'] = "portal/savePaymentAdvice";
$route['notices']           = "portal/notices";
$route['getTenantsMulti']   = "portal/getTenantsMulti";

$route['getAdvices/(:any)/(:any)'] = "portal/getAdvices/$1/$2";
$route['postAdvice']               = "portal/postAdvice";
$route['notices']                  = "portal/notices";
$route['adminnoticeshistory']      = "portal/noticeshistory";
$route['getTenantsMulti']          = "portal/getTenantsMulti";
$route['exportPaymentAdvice']      = "portal/exportPaymentAdvice";

#API ROUTE KUNOHAY

$route['uploadSoaDataAPI']     = "maincontroller/uploadSoaDataAPI";
$route['uploadSoaFile']        = "maincontroller/uploadSoaFile";
$route['sendMail']             = "maincontroller/sendMail";
$route['notifications']        = "portmaincontrolleral/notifications";
$route['uploadAllInvoiceData'] = "maincontroller/uploadAllInvoiceData";
$route['uploadPaymentData']    = "maincontroller/uploadPaymentData";

# PAYMENT
$route['paymentNotification'] = "maincontroller/paymentNotification";

$route['translate_uri_dashes'] = FALSE;

# INVOICING ROUTES

$route['perTenantUpload'] = "maincontroller/perTenantUpload";


# TESTING

$route['testEmail'] = "portal/testEmail";
$route['blastSend'] = "maincontroller/blastSend";
