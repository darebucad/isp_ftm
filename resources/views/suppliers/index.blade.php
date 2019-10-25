@extends('layouts.app')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
  <input type="hidden" id="delete_id">
    <div class="x_panel">

        <div class="x_title">
          <h2>Suppliers</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
                <input type="button" class="btn btn-primary" value="New" onclick="window.location.href='/suppliers/create'" />
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <table class="table" id="suppliers">
            <thead>
              <tr>
                <th>Name</th>
                <th>Address</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>
      </div>

      <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" id="modalDialog">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Delete</h4>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete this supplier?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnDelete">Yes</button>
              </div>

            </div>
          </div>
        </div>
  </div>
@endsection()

@section('js')
  <script src="{{asset('js/dataTables.min.js')}}"></script>
  <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>

    $(document).ready(function(){
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var table;

      table = $("#suppliers").DataTable({
        "pageLength": 30,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('api.getSuppliers')}}",
        "columns":[
          {
            "width": "20%",
            "data":"name",
          },
          {
            "width": "60%",
            "data":"address"
          },
          {
            "width": "20%",
            "data":null,
            "orderable": false,
            "searchable":false,
            render: function ( data, type, row ) {
              return '<button type="button" class="btn btn-default" onclick="editSupplier(\''+ data.id +'\')">Edit</button>'
                     +'<button type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm" onclick="showDeleteConfirmation(\''+ data.id +'\')">Delete</button>';
            }
          },
        ]
      });


      $("#btnDelete").on('click', function(){
        var id = $("#delete_id").val();

        console.log(id);

        $.ajax({
          type:"GET",
          url:"/suppliers/delete/" + id,
          success:function(data){
              if(data.errors != undefined && data.errors.length > 0){
                showErrorMessage(data.errors);
              }else{
                toastr.success('Supplier was deleted','Success', {timeOut: 1000});
                $("#btnCancel").click();
                table.ajax.reload();
              }
          },
          error:function(error){
              console.log(error);
          }
              });
      });


    });

    function editSupplier(id){
      window.location.href = '/suppliers/edit/' + id;
    }

    function showDeleteConfirmation(id){
      $("#delete_id").val(id);
    }

    function showErrorMessage(errMessage){
            var errMessageContent = '';
            errMessage.forEach(element => {
              errMessageContent = errMessageContent + element + '<br/>';
            });
            toastr.error(errMessageContent, 'Error', {timeOut: 3000});
    }
  </script>
@endsection()
