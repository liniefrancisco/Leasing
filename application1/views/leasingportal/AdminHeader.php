<!DOCTYPE html>
<html lang="en" ng-app="app">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leasing Portal | <?php echo $title; ?></title>
    <link rel="icon" href="<?php echo base_url(); ?>img/LeasingPortalLogo.png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/style.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/sweetalert2/sweetalert2.all.min.css">
</head>

<body class="hold-transition layout-top-nav" ng-clock>
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark navbar-dark">
            <div class="container-fluid">
                <a href="<?php echo base_url();?>admindashboard" class="navbar-brand">
                    <img class="brand-image" src="<?php echo base_url(); ?>img/LeasingPortalLogo.png" style="opacity: .8">
                    <span class="brand-text font-weight-heavy">LEASING PORTAL ADMIN</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <?php if($this->session->userdata('user_type') == 'Admin'):?>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>adminusers" class="nav-link"><i class="fas fa-user"></i> Users</a>
                            </li>
                        <?php endif;?>
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-upload"></i> Uploading</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li class="dropdown-submenu dropdown-hover">
                                
                                    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Invoice Data</a>
                                    <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                                        <li><a href="<?php echo base_url();?>admininvoicepertenant" class="dropdown-item">Upload Invoice Per Tenant</a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url();?>adminsoa" class="dropdown-item">SOA Data </a></li>
                                <li class="dropdown-submenu dropdown-hover">
                                
                                    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Payment Data</a>
                                    <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                                        <li><a href="<?php echo base_url();?>paymentpertenant" class="dropdown-item">Upload Payment Per Tenant</a></li>
                                        <li><a href="<?php echo base_url();?>paymentperstore" class="dropdown-item">Upload Payment Per Store</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
    
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-print"></i> Reports</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li><a href="<?php echo base_url();?>uploadinghistory" class="dropdown-item">Upload History </a></li>
                                <!-- <li><a href="<?php echo base_url();?>paymentnotices" class="dropdown-item">Payment Advice Notices </a></li>
                                <li><a href="<?php echo base_url();?>paymentnoticeshistory" class="dropdown-item">Payment Advice History </a></li> -->
                            </ul>
                        </li>

                        <?php if($this->session->userdata('user_type') == 'Admin'):?>
                            <li class="nav-item">
                                <a href="<?php echo base_url(); ?>blastUser" class="nav-link"><i class="fas fa-paper-plane"></i> Blast SMS/Email Users</a>
                            </li>
                        <?php endif;?>
                    </ul>
                </div>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <!-- <a class="nav-link" href="<?php echo base_url();?>paymentnotices">
                Payment Advice
                <i class="far fa-bell"></i>
                <span class="badge badge-danger navbar-badge"><b><?php echo $count;?></b></span>
            </a> -->
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-arrow-alt-circle-left"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>
    <!-- LOADING MODAL -->
    <div class="modal_loading" id="loading">
        <div class="mr-3">
            <center><img id="loading-image" width="50px;" src="<?php echo base_url();?>img/spinner2.svg"></center>
        </div>
        <div class="loader-text" style="margin-top: 5px;">
            <center><b id="app-loader-msg">Loading, Please wait ...</b></center>
        </div>
    </div>
    <!-- LOADING MODAL -->