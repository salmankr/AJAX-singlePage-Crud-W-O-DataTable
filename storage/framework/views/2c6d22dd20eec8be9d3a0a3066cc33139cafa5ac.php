<!DOCTYPE html>
<html>
<head>
    <title>AJAX</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">CNIC</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody id="MyDataShow">
                  </tbody>
                </table>
                <button type="button" onclick="EmptyFormFields()" class="btn btn-primary" data-toggle="modal" data-target="#NewCreateModal">
                  New Entry
                </button>
            </div>
        </div>
    </div>


    <!--create-->
    <div class="modal fade" id="NewCreateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create new Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Name">Name</label>
                        <input type="text" class="form-control" id="CreateName" aria-describedby="emailHelp" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="CNIC">CNIC</label>
                        <input type="text" class="form-control" id="CreateCNIC" aria-describedby="emailHelp" placeholder="Enter CNIC">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="Submit" id="CreateSubmit" class="btn btn-primary" data-dismiss="modal">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>

    <!--update-->
    <div class="modal fade" id="NewUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update this profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Name">Name</label>
                        <input type="text" class="form-control" id="UpdateName" aria-describedby="emailHelp" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="CNIC">CNIC</label>
                        <input type="text" class="form-control" id="UpdateCNIC" aria-describedby="emailHelp" placeholder="Enter CNIC">
                    </div>
                    <input type="hidden" id="citizenID">
                </div>
                <div class="modal-footer">
                    <button type="Submit" id="UpdateSubmit" class="btn btn-primary" data-dismiss="modal">Submit</button>
                </div>
            </form>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script><!--jquerycdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script><!--poopercdn -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script><!--bscdnjs -->

    <script type="text/javascript">
        function displayRes(){
            $.ajax({
                url: '/show',
                type: 'GET',
                success: function(response){
                    $('#MyDataShow').empty();

                    $.each(response, function(index, val) {
                        //console.log(val);
                        var index = index+1;
                        var data =
                        "<tr>"+
                        "<td>"+ index +"</td>"+
                        "<td>"+ val.name +"</td>"+
                        "<td>"+ val.CNIC +"</td>"+
                        "<td>"+
                        "<button class='btn btn-info' data-toggle='modal' data-target='#NewUpdateModal' "+
                        " onclick = 'UpdateData("+val.id+")'>Update</button>"+
                        "<button class='btn btn-danger ml-2 DeleteData' onclick = 'DeleteData("+val.id+")'>Delete</button>"+
                        "</td>"+
                        "</tr>";
                        $('#MyDataShow').append(data);
                    });
                }
            })
            }

            $('#CreateSubmit').click(function(event) {
                var name = $('#CreateName').val();
                var CNIC = $('#CreateCNIC').val();
                $.ajax({
                    url: '/create',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        name: name,
                        cnic: CNIC
                    },
                    success: function(response){
                        alert('Citizen having name '+response.name+' & CNIC '+response.cnic+' is registered.');
                        displayRes();
                    },
                    error: function(response){
                        if (response.status == 422) {
                            $.each(response.responseJSON.errors, function(index, val) {
                                alert(val);
                            });
                        }
                    }
                })
                event.preventDefault();
            });

            function DeleteData(id){
               $.ajax({
                   url: '/delete/' + id,
                   type: 'post',
                   data: {
                    _method: 'delete'
                },
                success: function(response){
                   alert('user having name '+response.name+' & CNIC '+response.CNIC+' is deleted');
                   displayRes();
                },
                error: function(response){
                    console.log(response);
                }
               })
            }

            function EmptyFormFields(){
                $('#CreateName').val('');
                $('#CreateCNIC').val('');
            }

            function UpdateData(id){
                console.log(id);
                $.ajax({
                    url: '/citizen-data/' + id,
                    type: 'POST',
                    success: function(response){
                        console.log(response);
                        $('#UpdateName').val(response.name);
                        $('#UpdateCNIC').val(response.CNIC);
                        $('#citizenID').val(response.id);
                    }
                })
            }

            $('#UpdateSubmit').click(function(event) {
                var name = $('#UpdateName').val();
                var CNIC = $('#UpdateCNIC').val();
                var id =  $('#citizenID').val();
                $.ajax({
                    url: '/update/' + id,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        name: name,
                        cnic: CNIC,
                        _method: 'PUT'
                    },
                    success: function(response){
                        alert("Citizen's data has been updated as requested");
                        displayRes();
                    },
                    error: function(response){
                        if (response.status == 422) {
                            $.each(response.responseJSON.errors, function(index, val) {
                                alert(val);
                            });
                        }
                    }
                })
                
                event.preventDefault();
            });
        $(document).ready(function() {
            displayRes();
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            })
        });
    </script>
</body>
</html>