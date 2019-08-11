@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
  <input type="hidden" id="delete_id">
    <div class="x_panel">

        <div class="x_title">
          <h2>Products</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
                <input type="button" class="btn btn-primary" value="New" onclick="window.location.href='/products/create'" />
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <table class="table" id="products">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Brand</th>
                <th>Content</th>
                <th>Net Weight</th>
                <th>Stock on Hand</th>
                <th>Purchase Price</th>
                <th>Unit Price</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Warehouse</th>
                <th>Section</th>
                <th>Created By</th>
                <th>Date Created</th>
                <th>Date Updated</th>
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
                <p>Are you sure you want to delete this product?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnDelete">Yes</button>
              </div>

            </div>
          </div>
        </div>
  </div>
@endsection



@section('js')
  <script src="{{asset('js/dataTables.min.js')}}"></script>
  <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <script>

    $(document).ready(function(){
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var table;

      table = $("#products").DataTable({
        "pageLength": 30,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('api.getProducts')}}",
        "columns":[
          {
            // "width": "20%",
            "data":"name",
          },
          {
            // "width": "60%",
            "data": "description"
          },
          { "data": "brand" },
          { "data": "content" },
          { "data": "net_weight" },
          { "data": "stock_on_hand" },
          { "data": "purchase_price" },
          { "data": "unit_price" },
          { "data": "category" },
          { "data": "supplier" },
          { "data": "warehouse" },
          { "data": "section" },
          { "data": "user" },
          { "data": "created_at" },
          { "data": "updated_at" },
          {
            // "width": "20%",
            "data":null,
            "orderable": false,
            "searchable":false,
            render: function ( data, type, row ) {
              return '<button type="button" class="btn btn-default" onclick="editProduct(\''+ data.id +'\')">Edit</button>'
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
