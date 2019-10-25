@extends('layouts.app')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <!-- Select2 4.0.8 -->
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>New Store</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <br>
        <form id="frmStore" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
          <input type="hidden" name="_token" value="{{csrf_token()}}"/>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="store">Store Name<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" name="store" id="store" required="required" class="form-control col-md-7 col-xs-12">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Address <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control" rows="3" placeholder="Address" id="address"></textarea>
            </div>
          </div>
          <div class="ln_solid"></div>

          <div class="form-group row">
            <div class="col-md-8 col-md-offset-2">
              <button type="button" name="button" class="btn btn-danger">Update</button>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <table id="storeproducts" class="table table-striped" width="100%">
                <thead>
                  <tr>
                    <th>Product(s)</th>
                    <th>Qty</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="3"><a href="#" id="add">Add new item</a></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
              <input type="button" class="btn btn-primary" value="Cancel" onclick="window.location.href='/stores'" />
              <button type="button" class="btn btn-success" id="btnSubmit">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection()

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Select2 4.0.8 -->
<script src="{{ asset('js/select2.min.js') }}"></script>
<!-- SimpleTableCellEditor -->
<script src="{{ asset('plugins/edit-table/SimpleTableCellEditor.js') }}"></script>

  <script>
      $(document).ready(function(){
          var editor = new SimpleTableCellEditor("storeproducts");

          editor.SetEditableClass("editMe");
          editor.SetEditableClass("feedMeNumbers", { validation: $.isNumeric }); //If validation return false, value is not updated

          $('#orders').on("cell:edited", function (event) {
            // console.log(`Cell edited : ${event.oldValue} => ${event.newValue}`);
          });

          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });

          $('#storeproducts').on('click', '#add', function(e){
            e.preventDefault();
              html = '<tr id="" class="items">' +
                '<td width="55%">' + '<select class="product col-md-12"><option value=""></option></select>' + '</td>' +
                '<td class="qty feedMeNumbers" width="25%">' + '1.00' + '</td>' +
                '<td width="20%"><button class="delete">Delete</button></td>' +
                '</tr>';
              $('#storeproducts').prepend(html);

              $('.product').select2({
                placeholder: "Please select a finished product",
                allowClear: true,
                ajax: {
                  url: '/api/searchFinishedProducts', //'https://api.github.com/search/repositories',
                  dataType: 'JSON',
                  delay: 200,
                  data: function (params){
                    return {
                      q: params.term,
                      page: params.page
                    };
                  },
                  processResults: function(data, params){
                    params.page = params.page || 1;

                    return {
                      results: data.items,
                      pagination: {
                        more: (params.page = 10) < data.total
                      }
                    };
                  },
                  cache: true
                },
              });
          });





          $('#storeproducts').on('select2:select', '.product', function(e){
            $(this).closest('tr').attr('id', e.params.data.id);

          });

          $('#storeproducts').on('click', '.delete', function(e){
            e.preventDefault();

            $(this).closest('tr').remove();
          })

          $("#btnSubmit").click(function(){
              var data = {};
              var items = [];


              $('#storeproducts .items').each(function(){
                var product_id = $(this).attr('id');
                var qty = $(this).find('.qty').text();
                var obj = {};

                obj.product_id = product_id;
                obj.qty = qty;
                items.push(obj);
              });

              data.id=0;
              data.name = $("#store").val();
              data.address = $("#address").val();
              data.items = items;

              $.ajax({
                  type:'POST',
                  url:'/stores',
                  data:data,
                  success:function(data){
                      if(data.errors != undefined && data.errors.length > 0){
                        showErrorMessage(data.errors);
                      }else{
                        toastr.success('New Store created','success', {timeOut: 3000});
                        window.setTimeout( function(){
                          window.location.href="/stores";
                        }, 3000 );

                      }
                  },
                  error:function(error){
                      console.log(error);
                  }
                });
          });

          $("#btnCloseError").click(function(){
            $("#divErrorMessage").hide();
          });

          function showErrorMessage(errMessage){
            var errMessageContent = '';
            errMessage.forEach(element => {
              errMessageContent = errMessageContent + element + '<br/>';
            });
            toastr.error(errMessageContent, 'Error', {timeOut: 3000});
          }
      });
  </script>
@endsection()
