window.myApp.controller('adminblast', function($scope, $http, $compile) {

    var loader = null;

	$scope.sendUsers = function(e)
	{
        e.preventDefault();
        let store = $scope.store;
        let type  = $scope.tenanttype;

        let formdata = new FormData(e.target);
        
        Swal.fire({
            title: 'Blast SMS and Email to Users?',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            allowOutsideClick: false,
        }).then((result) =>
        {
                if (result.isConfirmed) 
                {
                    $('#loading').show();

                    $.ajax({
                        type: 'POST',
                        url: `${$base_url}senUserBlast`,
                        data: formdata,
                        enctype: 'multipart/form-data',
                        async: true,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(result) {

                            $('#loading').hide();

                            if (result.info == 'success') 
                            {
                               Swal.fire({
                                  icon: 'success',
                                  title: 'Success',
                                  text: result.message
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