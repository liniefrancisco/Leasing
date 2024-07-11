window.myApp.controller('soacontroller', function($scope, $http, $compile) {

    var loader = null;

	$scope.gettenantsoa = function()
	{
        $scope.soa = '';
        $scope.soaTable = false;

        $('#loading').show();

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: $base_url + 'portalGetSoa',
            data: $.param({tradename: $('#tradename').val()})
        }).then(function successCallback(response) {

            $('#loading').hide();

            if(response.data != '')
            {
                $(document).ready(function(){
                    $('#mySoaTable').DataTable({ order: [[1, 'desc']]});
                });

                $scope.soa = response.data;
                $scope.soaTable = true;
            }
            else
            {
                pop.alert('<strong>No SOA with balances found!</strong>');
                $scope.soaTable = false;
            }
        });
	}

}).directive('print',function(){
        return{
            restrict:'E',
            scope:{'filename':'@'},
            template:'<a type="button" title="Print" class="btn btn-info btn-sm rounded-0"><i class="fas fa-print"></i></a>',
            link:function(scope,element,attrs){
                element.on('click',function(){window.open('http://172.16.161.37/agc-pms/assets/pdf/' + attrs.filename)})
            }
        }
    })