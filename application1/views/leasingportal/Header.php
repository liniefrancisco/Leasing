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
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/">
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/sweetalert2/sweetalert2.all.min.css">

    <style type="text/css">
        .currency{
            font-weight: bold;
        }
        .error-display
        {
            font-weight: bold;
            color: red;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav" ng-clock>
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark navbar-dark">
            <div class="container-fluid">
                <a href="#" class="navbar-brand">
                    <img class="brand-image" src="<?php echo base_url(); ?>img/LeasingPortalLogo.png" style="opacity: .8">
                    <span class="brand-text font-weight-heavy">LEASING PORTAL</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>mysoa" class="nav-link"><i class="fas fa-receipt"></i> My SOA</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>myledger" class="nav-link"><i class="fas fa-book"></i> My Ledger</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>utilityreadings" class="nav-link"><i class="fas fa-tachometer-alt"></i> Utility Readings</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url(); ?>paymentadvice" class="nav-link"><i class="fas fa-money-check"></i> Payment Advice</a>
                        </li>
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <!-- Messages Dropdown Menu -->
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

        <div class="alert alert-dismissible fade show container mt-1" role="alert">
            <strong>Tip:</strong> If some fields doesn't work, please update to the latest browser. You can close this tip.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- /.navbar -->