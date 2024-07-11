window.myApp.controller('adminsoa', function($scope, $http, $compile) {

	var loader = null;

	$scope.getsoa = function(e)
	{
        let tenanttype = $scope.tenanttype;
        let store      = $scope.store;
        let from       = $scope.from;
        let date1      = $("[name = 'datefrom']").val();
        let date2      = $("[name = 'dateto']").val();

        $('#loading').show();

		$http({
            headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8' },
            method: 'POST',
            url: `${$base_url}getsoa`,
            data: $.param({type : tenanttype, store: store, from: from, date1: date1, date2: date2})
        }).then(function successCallback(response) 
        {
            $('#loading').hide();
            if (response.data.length > 0) {
                if (!$.fn.dataTable.isDataTable('#soauploadtable')) { // Check if DataTable is not already initialized
                    $(document).ready(function () {
                        $('#soauploadtable').DataTable({
                            "aoColumnDefs": [{ "bSortable": false, "aTargets": [0] }]
                        });
                    });
                }
            
                $scope.soauploadtable = true;
                $scope.soaupload = true;
                $scope.soa = response.data;
            } else {
                Swal.fire({
                    title: `<i class='fas fa-info-circle fa-lg' style='color:#005ce6'></i>`,
                    html: `<strong>NO SOA DATA FOUND</strong>`,
                    allowOutsideClick: false,
                    confirmButtonText: 'OK'
                });
            
                $scope.soauploadtable = true;
                $scope.soaupload = false;
            }
            
        });
	}

    $scope.viewsoa = function(data)
    {
        let filename = data.file_name
        let from     = $scope.from;
        let web_ip = (from === 'OLD') ? '172.16.161.37/agc-pms' : '172.16.170.10/PMS/';
        window.open(`http://${web_ip}/assets/pdf/${filename}`);
        // window.open(`${$base_url}assets/pdf/` + filename);
    }

    $scope.uploadall = function()
    {
        $('#headerCheck').click(function(e) {
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });

        if($("[name = 'headerCheck']").is(":checked"))
        {
            $scope.uploadselect = false;
        } 
        else
        {
            $scope.uploadselect = true;
        }
    }

    $scope.uploadsoa = function(data)
    {
        let soaID = data.id;
        let from     = $scope.from;

        $.ajax({
            type: 'POST',
            url: `${$base_url}uploadsoadata`,
            data: {soaID : soaID, from: from},
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
                        $scope.soaupload = false;
                        $scope.soa = [];
                        $scope.getsoa();
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

    $scope.uploadsoaselected = function(e)
    {
        e.preventDefault();

        let formdata = new FormData(e.target);
        let from     = $scope.from;
        formdata.append('from', from);

        $.ajax({
            type: 'POST',
            url: `${$base_url}uploadsoadatachecked`,
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
                        $scope.soaupload = false;
                        $scope.soa = [];
                        $scope.getsoa();
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