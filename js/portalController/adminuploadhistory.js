window.myApp.controller('uploadhistory', function($scope, $http, $compile) {

	var loader = null;

	$scope.gethistory = function(e)
	{
        let store      = $scope.store;
        let date1      = $("[name = 'datefrom']").val();
        let date2      = $("[name = 'dateto']").val();
        $scope.uploadhistory = false;
        $('#loading').show();
		$http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getuploadhistory`,
            data: $.param({store: store, date1: date1, date2: date2})
        }).then(function successCallback(response) 
        {
            $('#loading').hide();
            if(response.data.length > 0)
            {
                $(document).ready(function()
                {
                    $('#uploadhistorytable').DataTable({ order: [[3, 'desc']]});
                });

                $scope.uploadhistory = true;
                $scope.history       = response.data;
            }
            else
            {
                Swal.fire({
                    title: `<i class='fas fa-info-circle fa-lg' style='color:#005ce6'></i>`,
                    html: `<strong>NO HISTORY FOUND</strong>`,
                    allowOutsideClick: false,
                    confirmButtonText: 'OK'
                })

                $scope.uploadhistory = false;
            }
        });
	}

    $scope.viewhistory = function(data)
    {
        let historyID = data.id;

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}gethistorydocs`,
            data: $.param({historyID: historyID})
        }).then(function successCallback(response) 
        {
            $scope.tenantid      = data.tenant_id;
            $scope.typeUploaded  = data.type_uploaded;
            $scope.dateUploaded  = data.date_uploaded;
            $scope.statusMessage = data.status_message;

            if(response.data.length > 0)
            {
                $scope.hasdata     = true;
                $scope.nodata      = false;
                $scope.historyDocs = response.data;
            }
            else
            {
                $scope.hasdata     = false;
                $scope.nodata      = true;
            }
        });
    }

})