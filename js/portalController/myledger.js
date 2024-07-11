window.myApp.controller('ledgercontroller', function($scope, $http, $compile) {

    var loader = null;

    $scope.getledger = (e) =>
    {
        e.preventDefault();

        var data =  { tradename : $("[name = 'tradename']").val(), 
                      tenantid  : $("[name = 'tenantid']").val(),
                      datefrom  : $("[name = 'datefrom']").val(),
                      dateto    : $("[name = 'dateto']").val()
                    }

        $scope.ledger      = '';
        $scope.ledgerTable = false;

        $('#loading').show();

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}gettenantledger`,
            data: $.param(data)
        }).then(function successCallback(response) {

            $('#loading').hide();

            $(document).ready(function(){
                $('#myLedgerTable').DataTable({ order: [[2, 'asc']]});
            });
            
            $scope.forwardedbalance = response.data.fwdbalance.forwardedbalance;
            $scope.ledger           = response.data.withbalance;
            $scope.ledgerTable      = true;
        });
    }

    $scope.paymentdetails = function(data) 
    {
        $scope.invoiceno   = data.doc_no;
        $scope.postingdate = data.posting_date;

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getpaymentdetails`,
            data: $.param({referenceno : data.ref_no})
        }).then(function successCallback(response) {

            if(response.data.length > 0)
            {
                $scope.hasdata = true;
                $scope.nodata  = false;
                $scope.payment = response.data
            }
            else
            {
                $scope.hasdata = false;
                $scope.nodata  = true;
            }

            if (data.credit === '0.00') {
                $scope.grand_total = '0.00';
            } else {
                $scope.grand_total = data.credit;
            }
            
        });
    }

});