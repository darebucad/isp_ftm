@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
  <input type="hidden" id="delete_id">
    <div class="x_panel">
        <div class="x_title">
          <h2>Purchase Orders</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
                <input type="button" class="btn btn-primary" value="New" onclick="window.location.href='/purchases/create'" />
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <table class="table table-striped" id="purchases" style="width:100%; cursor:pointer;">
            <thead>
              <tr>
                <th>PO No.</th>
                <th>Date Ordered</th>
                <th>Supplier</th>
                <th>Desription</th>
                <th>Date Received</th>
                <th>Status</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>

      <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;" id="modalDialog">
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
  <script>
    $(document).ready(function(){
      var table = $("#purchases").DataTable({
        "pageLength": 30,
        "processing": true,
        "ajax": "api/getPurchaseOrders",
        "columns":[
          {
            "width": "15%",
            "data":"po_no",
          },
          {
            "width": "10%",
            "data": "created_at"
          },
          {
            "width": "15%",
            "data": "name"
          },
          {
            "width": "20%",
            "data": "description"
          },
          {
            "width": "10%",
            "data": "receipt_date"
          },
          {
            "width": "10%",
            "data": "status"
          },
        ],
        order: [[0, "desc"]]
      });

      $('#purchases').on('click', 'tr', function(e){
        var id = table.row( $(this).closest('tr') ).data().id;

        window.location.href = '/purchases/' + id + '/edit';
      });

      $("#btnDelete").on('click', function(){
        var id = $("#delete_id").val();
        console.log(id);

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type:'DELETE',
          url:'/products/' + id,
          success:function(data){
              if(data.errors != undefined && data.errors.length > 0){
                showErrorMessage(data.errors);
              }else{
                toastr.success('Product was deleted','Success', {timeOut: 1000});
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
  </script>
@endsection
