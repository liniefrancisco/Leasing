        
<div id="app-loader" class="modal fade in" tabindex="-1" role="dialog" aria-hidden="true" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog" style="margin-top: 200px; background: rgb(255,255,255); border-radius: 10px;">
        <div class="modal-body">
            <div class="" style="text-align: center;">
                <div>
                    <center>
                        <img width="50px;" src="<?php echo base_url();?>img/spinner2.svg">
                    </center>
                </div>
                <div class="loader-text" style="margin-top: 5px;">
                    <center>
                        <b id="app-loader-msg">Collecting data. Please wait ...</b>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5><i class="fas fa-cogs"></i> Account Settings</h5>
            </div>
            <div class="p-3">
                <h5><?php echo $this->session->userdata('trade_name');?></h5>
            </div>
            <div class="p-3 ml-2">
                <div class="row mb-2">
                    <a href="<?php echo base_url();?>useruserform"><i class="fas fa-user"></i> Change Username</a>
                </div>
                <div class="row mb-2">
                   <a href="<?php echo base_url();?>userpasswordform"><i class="fas fa-key"></i> Change Password</a>
                </div>
                <div class="row">
                    <a href="<?php echo base_url();?>logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                <!-- Anything you want -->
            </div>
            <!-- Default to the left -->
            <strong>Leasing Portal 1.2 - 2022</strong>
        </footer>
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->
        <script>
            //eeshiro_revisions
            window.$base_url = `<?= base_url() ?>`
        </script>
        <!-- jQuery -->
        <script src="<?php echo base_url(); ?>plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="<?php echo base_url(); ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo base_url(); ?>dist/js/adminlte.min.js"></script>
        <!-- DataTables  & Plugins -->

        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/angular-1.8.2/angular.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>plugins/angular-1.8.2/angular-touch.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/jszip/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/pdfmake/pdfmake.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/pdfmake/vfs_fonts.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="<?php echo base_url(); ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/angular.mask.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/mask.min.js"></script>
        <script src='<?php echo base_url(); ?>js/main.js' type="text/javascript"></script>
        <script src="<?= base_url('js/bootbox.min.js') ?>" type="text/javascript"></script>
        <script src="<?= base_url('js/myplugin.js') ?>" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>plugins/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- ANGULAR JS-->
        <script type="text/javascript" src="<?php echo base_url(); ?>dist/angularjs/root.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/angular-input-masks-standalone.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>dist/angularjs/soa.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/portalController/mysoa.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/portalController/myledger.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/portalController/utilityreadings.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/portalController/paymentadvice.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/portalController/usercredentials.js"></script>

        <script>
            $(window).on('load', function() 
            {
                $('#loading').hide();
            })
        </script>

        </body>

        </html>