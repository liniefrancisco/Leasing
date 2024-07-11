window.myApp.controller('invoicepertenant', function ($scope, $http, $compile) {

    var loader = null;

    $scope.getenants = function () {
        let store = $scope.store;
        let from = $scope.tenant_from;
        let type = $scope.tenanttype;

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}admingettenant`,
            data: $.param({ store: store, from: from, type: type })
        }).then(function successCallback(response) {
            trade_name = response.data;
        });
    }

    $scope.searchTenant = function () {
        let url = `${$base_url}admingettenant`;
        let store = $scope.store;
        let from = $scope.tenant_from;
        let type = $scope.tenanttype;
        let string = $scope.trade_name;
        let data = { store: store, from: from, type: type, trade_name: string };

        if (string == "" || string == undefined) {
            $(".search-results").hide();
        } else {
            $.get(url, data, function (res) {
                $scope.tenants = res;
                if ($scope.tenants) {
                    $scope.hasResults = 1;
                    $scope.tenants = res;
                } else {
                    $scope.hasResults = 0;
                    $scope.tenants.push({ trade_name: "No Results Found" });
                }

                $scope.$apply();
            }, 'json');
        }
    }

    $scope.getTenantID = function (data) {
        $scope.trade_name = data.trade_name;
        $scope.tenant_id = data.tenant_id;
        $("#supplier_id").val(data.id);
        $(".search-results").hide();
    }

    $scope.uploadpertenant = function (e) {
        e.preventDefault();

        var formdata = new FormData(e.target);

        $.ajax({
            type: 'POST',
            url: `${$base_url}uploadinvoicepertenant`,
            data: formdata,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (response) {

                $('#loading').hide();

                if (response.info == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Invoice Data Uploaded'
                    }).then((result) => {
                        location.reload();
                    });
                }
                else {
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