@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <!-- Select2 4.0.8 -->
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
          <h2>Purchase Order #{{ str_pad($purchase->po_no, 6, '0', STR_PAD_LEFT) }} <span class="badge badge-light"> <span id="status">{{ $getstatus }}</span></span></h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
              <button type="button" name="approve" id="approve" class="btn btn-primary" style="@if($getstatus == 'New') @else display:none; @endif">Approve</button>
              <button type="button" name="decline" id="decline" class="btn btn-danger" style="@if($getstatus == 'New') @else display:none; @endif">Decline</button>
              <button type="button" name="received" id="received" class="btn btn-primary" style="@if($getstatus == 'Approved') @else display:none; @endif">Received</button>
              <button type="button" name="print" id="print" class="btn btn-primary" onclick="window.location.href='/print/purchase_order/'+{{ $purchase->id }}">Print</button>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="products" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            @csrf
            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}" id="purchase_id">
            <input type="hidden" name="supplier_id" value="{{ $purchase->supplier_id }}" id="supplier_id">
            <input type="hidden" name="status_id" value="{{ $purchase->status_id }}" id="status_id">

            <div class="form-group">
              <label class="col-md-4 col-sm-3 col-xs-12" for="order_date">Order Date<span class="required">*</span></label>
              <label class="col-md-4 col-sm-3 col-xs-12" for="po_no">PO#<span class="required">*</span></label>
              <label class="col-md-4 col-sm-3 col-xs-12" for="supplier">Supplier<span class="required">*</span></label>
            </div>

            <div class="form-group">
              <div class="col-md-4 col-sm-10 col-xs-12">
                <fieldset>
                  <div class="control-group">
                    <div class="controls">
                      <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left" id="single_cal3" placeholder="Select a Date" aria-describedby="inputSuccess2Status3" value="{{ date('m/d/Y', strtotime($purchase->order_date)) }}" disabled>
                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                        <span id="inputSuccess2Status3" class="sr-only">(success)</span>
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>

              <div class="col-md-4 col-sm-10 col-xs-12">
                <input type="text" name="po_no" value="{{ str_pad($purchase->po_no, 6, '0', STR_PAD_LEFT) }}" id="po_no" class="form-control" readonly>
              </div>

              <div class="col-md-4 col-sm-10 col-xs-12">
                <select name="supplier" id="supplier" class="col-md-12 col-xs-12" disabled>
                  <option value=""></option>
                  @foreach($suppliers as $supplier)
                  <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 col-sm-3 col-xs-12" for="description">Description</label>
            </div>

            <div class="form-group row">
              <div class="col-md-12 col-sm-10 col-xs-12">
                <textarea name="description" rows="3" cols="80" id="description" class="form-control">{{ $purchase->description }}</textarea>
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group row">
              <div class="col-md-12">
                <button type="button" name="btn_update" id="btn_update" class="btn btn-danger" onclick="updateTable();">Update</button>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-striped" id="orders" style="width: 100%;">
                  <thead>
                    <tr>
                      <th>Raw Material</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Subtotal</th>
                      <th style="@if($purchase->status_id == '2') display:none; @endif">Quantity Received</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($details as $detail)
                    <tr id="{{ $detail->id }}" class="items">
                      <td id="{{ $detail->product_id }}" class="name">{{ $detail->name }}</td>
                      <td class="qty @if($getstatus == 'New') feedMeNumbers @endif">{{ $detail->quantity }}</td>
                      <td class="unit_price @if($getstatus == 'New') feedMeNumbers @endif">{{ $detail->price }}</td>
                      <td class="subtotal">{{ ($detail->quantity * $detail->price) }}</td>
                      <td class="qty_received @if($getstatus == 'Approved') feedMeNumbers @endif" style="@if($purchase->status_id == '2') display:none; @endif">@if($getstatus == 'Approved') {{ $detail->quantity }}@elseif($getstatus == 'Received'){{ $detail->quantity_received }}@endif</td>
                      <td><a href="#" class="delete"><i class="fa fa-trash-o fa-2x"></i></a></td>
                    </tr>
                    @endforeach
                    <tr>
                      <td colspan="6"></td>
                      <!-- <td colspan="6"><a href="#" class="add">Add new item</a></td> -->
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="ln_solid"></div>

            <div class="form-group">
              <div class="col-md-12 col-sm-6 col-xs-12 col-md-offset-5">
                <input type="button" class="btn btn-primary" value="Cancel" onclick="window.location.href='/purchases'" />
                <button type="button" class="btn btn-success" id="btnSubmit">Submit</button>
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
      var purchase_id =  $('#purchase_id').val();
      var supplier_id = $('#supplier_id').val();
      var order_date = $('#single_cal3').val();
      var po_no = $('#po_no').val();
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

      $('#supplier').select2({
        placeholder: "Please select a supplier",
        allowClear: true
      }).val(supplier_id);
      $('#supplier').trigger('change');

      $('#orders').on('click', '.add', function(e){
        e.preventDefault();
          html = '<tr id="" class="items">' +
            '<td>' + '<select class="product col-md-12"><option value=""></option></select>' + '</td>' +
            '<td class="qty feedMeNumbers">' + '1.00' + '</td>' +
            '<td class="unit_price feedMeNumbers">' + '1.00' + '</td>' +
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

      $('#btnSubmit').on('click', function(e){
        e.preventDefault();
        var description = $('#description').val();
        var status = $('#status').text();
        var data= {};
        var items = [];

        // Items
        $('#orders .items').each(function(){
          var row = $(this);
          var id = row.attr('id')
          var product_id = row.find('td:eq(0)').attr('id');
          var qty = row.find('.qty').text();
          var unit_price = row.find('.unit_price').text();
          var qty_received = row.find('.qty_received').text();
          var obj = {};
          obj.id = id
          obj.product_id = product_id;
          obj.qty = qty;
          obj.unit_price = unit_price;
          obj.qty_received = qty_received;
          items.push(obj);
        });

        // Data
        data.order_date = order_date;
        data.supplier_id = supplier_id;
        data.status = status;
        data.description = description;
        data.items = items;
        data.po_no = po_no;

        console.log(data);

        $.ajax({
          type: "PUT",
          url: '/purchases/' + purchase_id,
          data: data,
          dataType: 'JSON',
          success: function(data){
            if (data.errors != undefined && data.errors.length > 0) {
              showErrorMessage(data.errors);

            } else {
              toastr.success(data.success, 'Success', {timeout: 1000});
              window.setTimeout(function(){
                window.location.href = '/purchases';
              }, 1000);
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

      $('#approve').on('click', function(){
        $('#status').text('Approved');
      });

      $('#decline').on('click', function(){
        $('#status').text('Declined');
      });

      $('#received').on('click', function(){
        $('#status').text('Received');
      });

    });
  </script>
@endsection
