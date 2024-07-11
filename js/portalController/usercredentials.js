window.myApp.controller('settingsctrl', function($scope, $http, $compile) {

    $scope.savenewpassword = function(e)
    {
        e.preventDefault();

        let newpassword = $scope.newpassword;

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}changepassword`,
            data: $.param({password:newpassword})
        }).then(function successCallback(response) {

            if(response.data.info == 'Success')
            {
                Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: response.data.message
                }).then((result) => {
                    window.location.href = `${$base_url}logout`;
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
                                    title: response.data.info,
                                    text: response.data.message
                                })
            }
        });
    }

    $scope.savenewusername = function(e)
    {
        e.preventDefault();

        let newusername = $scope.newusername;

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}changeusername`,
            data: $.param({username:newusername})
        }).then(function successCallback(response) {

            if(response.data.info == 'Success')
            {
                Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: response.data.message
                }).then((result) => {
                    window.location.href = `${$base_url}logout`;
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
                                    title: response.data.info,
                                    text: response.data.message
                                })
            }
        });
    }

})