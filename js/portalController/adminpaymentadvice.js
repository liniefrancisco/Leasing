window.myApp.controller('paymentadvice', function($scope, $http, $compile) {

	var loader      = null;
    var paymenttype = '';
    var adviceID    = '';

	$scope.getNotices = function()
	{
        $('#loading').show();

		$http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getNotices`
        }).then(function successCallback(response) 
        {
            $('#loading').hide();

            $(document).ready(function()
            {
                $('#paymentadvicenoticetable').DataTable({});
            });

            $scope.notices = response.data;
        });
	}

    $scope.getNoticesHistory = function()
    {
        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getNoticesHistory`
        }).then(function successCallback(response) 
        {
            $(document).ready(function()
            {
                $('#paymentadviceHistory').DataTable({});
            });

            $scope.noticesHistory = response.data;
        });
    }

    $scope.viewproof = function(data)
    {
        let filename = data.proof_of_transfer;
        window.open(`${$base_url}assets/proof_of_payment/${filename}`);
    }

    $scope.viewadvice = function(data)
    {
        let paymentAdviceID = data.id;
        paymenttype         = data.payment_type;
        adviceID            = data.id;

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'GET',
            url: `${$base_url}getAdvices/${paymentAdviceID}/${paymenttype}`
        }).then(function successCallback(response) 
        {
            if(paymenttype == 'One Location')
            {
                $scope.adviceCode         = response.data.advice_code;
                $scope.tenantid           = response.data.tenant_id;
                $scope.paymentDate        = response.data.payment_date;
                $scope.store              = response.data.store;
                $scope.storeBankAccount   = response.data.bank_account;
                $scope.storeAccountNumber = response.data.store_account;
                $scope.bankAccount        = response.data.tenant_bank;
                $scope.accountNumber      = response.data.account_number;
                $scope.accountName        = response.data.account_name;
                $scope.soaNo              = response.data.soa_no;
                $scope.totalPayable       = response.data.total_payable;
                $scope.amountPaid         = response.data.amount_paid;
            }
            else if(paymenttype == 'Multi Location')
            {
                $scope.adviceCode         = response.data.paymentadvice.advice_code;
                $scope.tenantid           = response.data.paymentadvice.tenant_id;
                $scope.paymentDate        = response.data.paymentadvice.payment_date;
                $scope.store              = response.data.paymentadvice.store;
                $scope.storeBankAccount   = response.data.paymentadvice.bank_account;
                $scope.storeAccountNumber = response.data.paymentadvice.store_account;
                $scope.bankAccount        = response.data.paymentadvice.tenant_bank;
                $scope.accountNumber      = response.data.paymentadvice.account_number;
                $scope.accountName        = response.data.paymentadvice.account_name;
                $scope.amountPaid         = response.data.paymentadvice.amount_paid;

                $scope.soa = response.data.paymentadvicesoa;
            }
        });
    }

    $scope.viewadviceHistory = function(data)
    {
        let paymentAdviceID = data.id;
        paymenttype         = data.payment_type;
        adviceID            = data.id;

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'GET',
            url: `${$base_url}getAdvicesHistory/${paymentAdviceID}/${paymenttype}`
        }).then(function successCallback(response) 
        {
            if(paymenttype == 'One Location')
            {
                $scope.adviceCode1         = response.data.advice_code;
                $scope.tenantid1           = response.data.tenant_id;
                $scope.paymentDate1        = response.data.payment_date;
                $scope.store1              = response.data.store;
                $scope.storeBankAccount1   = response.data.bank_account;
                $scope.storeAccountNumber1 = response.data.store_account;
                $scope.bankAccount1        = response.data.tenant_bank;
                $scope.accountNumber1      = response.data.account_number;
                $scope.accountName1        = response.data.account_name;
                $scope.soaNo1              = response.data.soa_no;
                $scope.totalPayable1       = response.data.total_payable;
                $scope.amountPaid1         = response.data.amount_paid;
            }
            else if(paymenttype == 'Multi Location')
            {
                $scope.adviceCode2         = response.data.paymentadvice.advice_code;
                $scope.tenantid2           = response.data.paymentadvice.tenant_id;
                $scope.paymentDate2        = response.data.paymentadvice.payment_date;
                $scope.store2              = response.data.paymentadvice.store;
                $scope.storeBankAccount2   = response.data.paymentadvice.bank_account;
                $scope.storeAccountNumber2 = response.data.paymentadvice.store_account;
                $scope.bankAccount2        = response.data.paymentadvice.tenant_bank;
                $scope.accountNumber2      = response.data.paymentadvice.account_number;
                $scope.accountName2        = response.data.paymentadvice.account_name;
                $scope.amountPaid2         = response.data.paymentadvice.amount_paid;

                $scope.soahistory = response.data.paymentadvicesoa;
            }
        });
    }

    $scope.postAdviceOne = function(e)
    {
        e.preventDefault();

        let formdata = new FormData(e.target);
        formdata.append('type', paymenttype);
        formdata.append('adviceID', adviceID);

        $.ajax({
            type: 'POST',
            url: `${$base_url}postAdviceOne`,
            data: formdata,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() 
            {
                 $('#loading').show();
            },
            success: function(response) {

             $('#loading').hide();

                if(response.info == 'success')
                {
                 Swal.fire({
                         icon: 'success',
                         title: 'Success',
                         text: response.message
                    }).then((result) => {                        
                        location.reload();
                    });
                }
                else
                {
                 const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'error',
                        title: response.info,
                        text: response.message
                    })
                }

            }
        });
    }

})