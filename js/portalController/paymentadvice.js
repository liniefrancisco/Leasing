window.myApp.controller('paymentadvicectrl', function($scope, $http, $compile) {

    $scope.numberformat = true;

    $scope.getaccount = function()
    {
        let store   = $("[name = 'storelocation']").val();
        let account = $scope.storebankaccount;

        if(store === 'Island City Mall')
        {
            if(account == 'Bank of the Philippine Islands')
            {
                $scope.account = [{account:'9471-0016-59'}, {account:'9471-0001-56'}, {account:'1201-0089-95'}];
            }
            else if(account == 'Land Bank of the Philippines')
            {
                $scope.account = [{account:'3522-1000-63'}];
            }
        }
        else if(store === 'Alta Citta')
        {
            if(account == 'Bank of the Philippine Islands')
            {
                $scope.account = [{account:'9471-0016-75'}];
            }
            else if(account == 'PNB')
            {
                $scope.account = [{account:'3079-7000-6462'}];
            }
        }
        else if(store === 'Alturas Mall')
        {
            if(account == 'Bank of the Philippine Islands')
            {
                $scope.account = [{account:'1201-0133-79'}];
            }
            else if(account == 'PNB')
            {
                $scope.account = [{account:'3059-7000-5922'}, {account:'3058-7000-4241'}];
            }
        }
        else if(store === 'Plaza Marcela')
        {
            if(account == 'Land Bank of the Philippines')
            {
                $scope.account = [{account:'0612-0011-11'}];
            }
        }
    }

    $scope.getaccountformat = function()
    {
        let bank = $scope.tenantaccount;

        if(bank === 'BDO')
        {
            $scope.format = '9999 99999 9';
        }
        else if(bank === 'BANKS OF THE PHILIPPINE ISLANDS')
        {
            $scope.format = '9999 9999 99';
        }
        else if(bank === 'CHINA BANK')
        {
            $scope.format = '9999 9999 9999';
        }
        else if(bank === 'CITIBANK')
        {
            $scope.format = '9 999999 999';
        }
        else if(bank === 'EASTWEST')
        {
            $scope.format = '9999 9999 9999';
        }
        else if(bank === 'FIRST CONSOLIDATED BANK')
        {
            $scope.format = '9999 9999 9999';
        }
        else if(bank === 'LAND BANK OF THE PHILIPPINES')
        {
            $scope.format = '9999 9999 99';
        }
        else if(bank === 'METRO BANK')
        {
            $scope.format = '9999 9999 9999 9';
        }
        else if(bank === 'PHILIPPINE BANK OF COMMUNICATIONS')
        {
            $scope.format = '9999 9999 9999';
        }
        else if(bank === 'SECURITY BANK')
        {
            $scope.format = '9999 9999 9999 9';
        }
        else if(bank === 'UCPB')
        {
            $scope.format = '9999 9999 999';
        }
        else if(bank === 'UNION BANK')
        {
            $scope.format = '9999 9999 9999';
        }
        else if(bank === 'PNB')
        {
            $scope.format = '9999 9999 9999';
        }

        $scope.numberformat = false;
    }

    var loader = null;

    $scope.multi = [{}];

    $scope.type = function()
    {
        let type = $scope.paymenttype;

        if(type === 'One Location')
        {
            if (!loader) {
                loader = pop.loading('Collecting data. Please wait ...');
            } else {
                loader.modal('show');
            }

            $http({
                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
                method: 'POST',
                url: $base_url + 'soabalances'
            }).then(function successCallback(response) {

                if(response.data.length > 0)
                {
                    $scope.multi           = [{}];
                    $scope.soa             = response.data;
                    $scope.soanumber       = true;
                    $scope.multilocation   = false;
                    $scope.proofoftransfer = true;
                }
                else
                {
                    pop.alert('<strong>No SOA with balances found!</strong>');
                }

                loader.modal('hide');
            });

        }
        else
        {
            $('#loading').show();
            $scope.amounttopay     = '';
            $scope.soa             = '';
            $scope.soanumber       = false;
            $scope.multilocation   = true;
            $scope.proofoftransfer = true;
            $('#loading').hide();
        }
    }

    $scope.getsoaamount = function() 
    {
        let soano = $scope.soano;

        $http({
                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
                method: 'POST',
                url: `${$base_url}soaamount`,
                data: $.param({soa: soano})
            }).then(function successCallback(response) {


                $scope.totalpayable = response.data.amount;
            });
    }

    $scope.newAmount = function()
    {
        let total = 0;

            $scope.multi.forEach(function(data)
            {
                total += parseNumber(data.locationamount);
            });

        return total;
    }

    $scope.totalPaidAmount = function()
    {
        let totalamount = $scope.newAmount();
        return parseNumber(totalamount);
    }

    $scope.savepaymentadvice = function(e)
    {
        e.preventDefault();

        var formData = new FormData(e.target);
        var multi    = JSON.parse(angular.toJson($scope.multi));
        formData     = convertModelToFormData(multi, formData, 'multi');

        Swal.fire({
              title: 'Submit Payment Advice?',
              showCancelButton: true,
              confirmButtonText: 'Submit',
            }).then((result) => {
                if (result.isConfirmed) 
                {
                    let loader = pop.loading('Posting Payment. Please wait ...');

                    $.ajax({
                        type: 'POST',
                        url: `${$base_url}savepaymentadvice`,
                        data: formData,
                        enctype: 'multipart/form-data',
                        async: true,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(result) {
                            loader.modal('hide');
                            if (result.info == 'Success') 
                            {
                               Swal.fire({
                                  icon: 'success',
                                  title: 'Success',
                                  text: 'Payment Advice Posted'
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
                                    title: result.info,
                                    text: result.message
                                })
                            }
                        }
                    });
                }
            })
    }

})