@extends('layouts.app')

@section('css')
  <!-- <link href="{{ asset('css/dataTables.bootstrap.min.css') }}" rel="stylesheet"> -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <!-- Select2 4.0.8 -->
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
          <h2>New Sales Order</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
                <input type="button" class="btn btn-primary" value="Print" id="print" style="display:none;">
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="sales_order" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            @csrf
            <input type="hidden" name="so_id" value="" id="so_id">
            <input type="hidden" name="salesorder_id" value="" id="salesorder_id">

            <div class="form-group">
              <label class="col-md-4 col-sm-3 col-xs-12" for="order_date">Order Date<span class="required">*</span></label>
              <label class="col-md-4 col-sm-3 col-xs-12" for="so_no">SO#<span class="required">*</span></label>
              <label class="col-md-4 col-sm-3 col-xs-12" for="store">Store/Agent<span class="required">*</span></label>
            </div>

            <div class="form-group">
              <div class="col-md-4 col-sm-10 col-xs-12">
                <fieldset>
                  <div class="control-group">
                    <div class="controls">
                      <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="single_cal3" placeholder="Select a Date" aria-describedby="inputSuccess2Status3">
                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        <span id="inputSuccess2Status3" class="sr-only">(success)</span>
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>

              <div class="col-md-4 col-sm-10 col-xs-12">
                <input type="text" name="so_no" value="{{ str_pad($salesorder->so_no + 1, 8, '0', STR_PAD_LEFT) }}" id="so_no" class="form-control" readonly>
              </div>

              <div class="col-md-4 col-sm-10 col-xs-12">
                <select name="store" id="store" class="col-md-12 col-xs-12">
                  <option value=""></option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 col-sm-3 col-xs-12" for="description">Description</label>
            </div>

            <div class="form-group row">
              <div class="col-md-12 col-sm-10 col-xs-12">
                <textarea name="description" rows="3" cols="80" id="description" class="form-control"></textarea>
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group row">
              <div class="col-md-12">
                <button type="button" class="btn btn-danger" onclick="updateTable()">Update</button>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-striped" id="orders" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>Finished Product (s)</th>
                      <th>Qty</th>
                      <th>Price</th>
                      <th>Subtotal</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <div class="ln_solid"></div>

            <div class="form-group">
              <div class="col-md-12 col-sm-6 col-xs-12 col-md-offset-5">
                <input type="button" class="btn btn-primary" value="Cancel" onclick="window.location.href='/salesorders'" />
                <button type="submit" class="btn btn-success" id="btn_submit">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
  </div>
@endsection

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Select2 4.0.8 -->
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <!-- jquery.inputmask -->
  <script src="{{ asset('js/jquery.inputmask.bundle.min.js') }}"></script>
  <!-- SimpleTableCellEditor -->
  <script src="{{ asset('plugins/edit-table/SimpleTableCellEditor.js') }}"></script>
  <!-- Custom JS -->
  <script>
    function updateTable(){
      var table = $('#orders tbody tr');
      var rowCount = table.length;

      if (rowCount > 0) {
        table.each(function(){
          var row = $(this);
          var qty = Number(row.find('.qty').text());
          var unit_price = Number(row.find('.unit_price').text());
          row.find('.subtotal').text(qty * unit_price);
        })

      }
    }

    $(document).ready(function(){
      var _token = $('meta[name="csrf-token"]').attr('content');
      var editor = new SimpleTableCellEditor("orders");

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

      $('#store').select2({
        placeholder: "Select a store/agent",
        allowClear: true,
        ajax: {
          url: '/api/searchStores', //'https://api.github.com/search/repositories',
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

      $('#store').on('select2:select', function(e){
        $.ajax({
          url: "/api/populateFinishedProducts/" + e.params.data.id,
          dataType: "JSON",
          success: function(data){
            $('#orders tbody').empty();
            var html = '';
            for (var i = 0; i < data.length; i++) {
              html += '<tr id='+ data[i].id +' class="items">' +
                '<td>' + data[i].name + '</td>' +
                '<td class="qty feedMeNumbers">' + data[i].quantity + '</td>' +
                '<td class="unit_price feedMeNumbers">' + data[i].unit_price + '</td>' +
                '<td class="subtotal">' + Number(data[i].quantity) * Number(data[i].unit_price) + '</td>' +
                '<td><button class="delete">Delete</button></td>' +
                '</tr>';
            }

            html += '<tr>' +
              '<td><a href="#" class="add">Add new item</a></td>' +
              '<td>' + '' + '</td>' +
              '<td>' + '' + '</td>' +
              '<td>' + '' + '</td>' +
              '<td>' + '' + '</td>' +
              '</tr>';
            $('#orders').prepend(html)
          },
          error: function(data){

          }
        });
      });



      $('#orders').on('click', '.add', function(e){
        e.preventDefault();
          html = '<tr id="" class="items">' +
            '<td>' + '<select class="product col-md-12"><option value=""></option></select>' + '</td>' +
            '<td class="qty feedMeNumbers">' + '1.00' + '</td>' +
            '<td class="unit_price feedMeNumbers">' + '1.00' + '</td>' +
            '<td class="subtotal">' + '1.00' + '</td>' +
            '<td><button class="delete">Delete</button></td>' +
            '</tr>';
          $('#orders').prepend(html);

          $('.product').select2({
            placeholder: "Please select a product",
            allowClear: true,
            ajax: {
              url: '/api/searchProducts', //'https://api.github.com/search/repositories',
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

      $('#orders').on('select2:select', '.product', function(e){
        $(this).closest('tr').attr('id', e.params.data.id);

      });

      $('#orders').on('click', '.delete', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
      });

      $('#print').on('click', function(e){
        e.preventDefault();

        console.log($('#salesorder_id').val());
        // window.location.href = '/print/purchase_order/' + $('#po_id').val();
      });

      // Submit button click event
      $('#btn_submit').on('click', function(e){
        e.preventDefault();

        var salesorder_id = $('#salesorder_id').val();
        var order_date = $('#single_cal3').val();
        var store_id = $('#store').val();
        var description = $('#description').val();
        var so_no = $('#so_no').val();
        var data= {};
        var items = [];

        $('#orders .items').each(function(){
          var row = $(this);
          var product_id = row.attr('id');
          var name = row.find('.name').text();
          var qty = row.find('.qty').text();
          var unit_price = row.find('.unit_price').text();
          var obj = {};

          obj.product_id = product_id;
          obj.name = name;
          obj.qty = qty;
          obj.unit_price = unit_price;

          items.push(obj);
        });
        data.sales_order_id = salesorder_id;
        data.order_date = order_date;
        data.store_id = store_id;
        data.description = description;
        data.so_no = so_no;
        data.items = items;

        $.ajax({
          url: '/api/salesorder',
          type: 'POST',
          data: data,
          dataType: 'JSON',
          success: function(data){
            if (data.errors != undefined && data.errors.length > 0) {
              showErrorMessage(data.errors);

            } else {
              toastr.success('New sales order was successfully added', 'Success', {timeout: 1000});

              console.log(data);
              $('#print').show();
              $('#salesorder_id').val(data.data.id);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            toastr.error('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
          },
        });

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
@endsection
