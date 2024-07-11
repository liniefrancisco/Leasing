window.myApp.controller('readingscontroller', function($scope, $http, $compile) {

    var loader = null;

	$scope.getutilityreadings = (e) =>
    {
        e.preventDefault();

        var data =  { tradename : $("[name = 'tradename']").val(), 
                      tenantid  : $("[name = 'tenantid']").val(),
                      datefrom  : $("[name = 'datefrom']").val(),
                      dateto    : $("[name = 'dateto']").val()
                    }

        $scope.readings      = '';
        $scope.readingstable = false;

        $('#loading').show();

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getreadings`,
            data: $.param(data)
        }).then(function successCallback(response) {

            $('#loading').hide();

            $(document).ready(function(){
                $('#utilityReadings').DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    order: [[1, 'asc']]
                });
            });

            $scope.readings      = response.data;
            $scope.readingstable = true;
        });

    }

})