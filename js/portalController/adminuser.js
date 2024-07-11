window.myApp.controller('adminuser', function($scope, $http, $compile) {

	var loader = null;
    var userID = '';

	$scope.getUsers = function(e)
	{
        $('#loading').show();

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getUsers`
        }).then(function successCallback(response) 
        {
            $('#loading').hide();

            $(document).ready(function()
            {
                $('#adminUsersTable').DataTable({});
            });

            $scope.users = response.data;
        });
	}

    $scope.getProspects = function()
    {
        let prospect = {store: $scope.store, type: $scope.tenantType};

        $http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getProspects`,
            data: $.param(prospect)
        }).then(function successCallback(response) 
        {
            $scope.prospects = response.data;
        });
    }

    $scope.saveNewUser = function(e)
    {
        e.preventDefault();

        var formData = new FormData(e.target);

        Swal.fire({
            title: 'Save New User?',
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
                        url: `${$base_url}saveNewUser`,
                        data: formData,
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

    $scope.updateuser = function(data)
    {   
        let type = data.type;
        userID   = data.id;

        if(type == 'Tenant')
        {
            $scope.userType  = data.type;
            $scope.tradeName = data.name;
            $scope.tenantUserName = data.username;
            $scope.password       = data.password_in_roman;
        }
        else
        {
            $scope.userType = data.type;
            $scope.fullName = data.name;
            $scope.userName = data.username;
            $scope.password = data.password_in_roman;
        }

    }

    $scope.updateUserSubmit = function(e)
    {
        e.preventDefault();

        var formData = new FormData(e.target);
        formData.append('userID', userID);

        Swal.fire({
            title: 'Update User?',
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
                        url: `${$base_url}updateUser`,
                        data: formData,
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

    $scope.deactivate = function(data)
    {
        Swal.fire({
            title: 'Deactivate User?',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            allowOutsideClick: false,
        }).then((result) =>
        {
            $('#loading').show();

            $http({
                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
                method: 'GET',
                url: `${$base_url}deactivate/${data.id}`
            }).then(function successCallback(response) 
            {
                $('#loading').hide();

                if (response.data.info == 'success') 
                {
                   Swal.fire({
                      icon: 'success',
                      title: 'Success',
                      text: response.data.message
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
                        title: response.data.info,
                        text: response.data.message
                    })
                }
            });
        })
    }

    $scope.resetpassword = function(data)
    {
        Swal.fire({
            title: 'Reset Password?',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            allowOutsideClick: false,
        }).then((result) =>
        {
            $('#loading').show();

            $http({
                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
                method: 'GET',
                url: `${$base_url}resetPassword/${data.id}`
            }).then(function successCallback(response) 
            {
                $('#loading').hide();

                if (response.data.info == 'success') 
                {
                   Swal.fire({
                      icon: 'success',
                      title: 'Success',
                      text: response.data.message
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
                        title: response.data.info,
                        text: response.data.message
                    })
                }
            });
        })
    } 

    $scope.reactivate = function(data)
    {
        Swal.fire({
            title: 'Reactivate User?',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            allowOutsideClick: false,
        }).then((result) =>
        {
            $('#loading').show();

            $http({
                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
                method: 'GET',
                url: `${$base_url}reactivate/${data.id}`
            }).then(function successCallback(response) 
            {
                $('#loading').hide();

                if (response.data.info == 'success') 
                {
                   Swal.fire({
                      icon: 'success',
                      title: 'Success',
                      text: response.data.message
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
                        title: response.data.info,
                        text: response.data.message
                    })
                }
            });
        })
    }

})