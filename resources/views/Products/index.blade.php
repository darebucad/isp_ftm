@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
  <input type="hidden" id="delete_id">
    <div class="x_panel">

        <div class="x_title">
          <h2>Products - <span id="product_type">All</span> </h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
                <input type="button" class="btn btn-primary" value="New" onclick="window.location.href='/products/create'" />
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="form-group nav navbar-right panel_toolbox col-md-4">
            <label class="control-label col-md-3 col-sm-3 col-xs-6">Product Type:</label>
            <div class="col-md-9 col-sm-9 col-xs-6">
                <select class="form-control" id="productType">
                    <option value="-1">All</option>
                    <option value="0">Raw Material</option>
                    <option value="1">Finished Product</option>
                  </select>
            </div>
          </div>
        <div class="x_content">
          <table class="table" id="products">
            <thead>
              <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Content</th>
                <th>Net Weight</th>
                <th>Stock on Hand</th>
                <th>Actual on Hand</th>
                <th>Purchase Price</th>
                <th>Unit Price</th>
                <th>Supplier</th>
                <th>Warehouse</th>
                <th>Section</th>
                <th>Type</th>
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
      var _token = $('meta[name="csrf-token"]').attr('content');
      var table;

      table = initTable();


      $("#btnDelete").on('click', function(){
        var id = $("#delete_id").val();

        console.log(id);

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': _token
          },
          type:'DELETE',
          url:'/products/' + id,
          success:function(data){
              if(data.errors != undefined && data.errors.length > 0){
                showErrorMessage(data.errors);
              }else{
                toastr.success('Product was deleted','Success', {timeOut: 1000});
                $("#btnCancel").click();
                $('#product').DataTable().destroy();
                table = initTable();
              }
          },
          error:function(error){
              console.log(error);
          }
              });
      });


      $("#productType").change(function(){
        $('#product').DataTable().destroy();
        table = initTable();
      });


    });

    function editProduct(id){
      window.location.href = '/products/' + id + '/edit';
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

    function initTable(){
      $("#products").DataTable({
        "pageLength": 30,
        "processing": true,
        // "serverSide": true,
        "bDestroy": true,
        "ajax": "api/getProducts/"+ $("#productType").val(),
        "columns":[
          {
            "width": "15%",
            "data":"name",
          },
          {
            "width": "5%",
            "data": "category"
          },
          {
            "width": "5%",
            "data": "brand"
          },
          {
            "width": "5%",
            "data": "content"
          },
          {
            "width": "5%",
            "data": "net_weight"
          },
          {
            "width": "5%",
            "data": "stock_on_hand"
          },
          {
            "width": "5%",
            "data": "actual_on_hand"
          },
          {
            "width": "5%",
            "data": "purchase_price"
          },
          {
            "width": "5%",
            "data": "unit_price"
          },
          {
            "width": "10%",
            "data": "supplier"
          },
          {
            "width": "5%",
            "data": "warehouse"
          },
          {
            "width": "5%",
            "data": "section"
          },
          {
            "width": "10%",
            "data":null,
            "orderable": false,
            "searchable":false,
            render: function ( data, type, row ) {
              var pType = data.type == 1 ? 'Finished Product' : 'Raw Material';
              return pType;
            }
          },
          {
            "width": "20%",
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

      return $("#products").dataTable();
    }
  </script>
@endsection
