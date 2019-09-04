@extends('layouts.master')

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
          <h2>New Purchase</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="products" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            @csrf

            <div class="form-group">
              <label class="col-md-4 col-sm-3 col-xs-12" for="order_date">Order Date<span class="required">*</span></label>
              <label class="col-md-5 col-sm-3 col-xs-12" for="supplier">Supplier<span class="required">*</span></label>
              <label class="col-md-2 col-sm-3 col-xs-12" for="status">Status</span></label>
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

              <div class="col-md-5 col-sm-10 col-xs-12">
                <select name="supplier" id="supplier" class="col-md-12 col-xs-12">
                  <option value=""></option>
                </select>
              </div>

              <div class="col-md-2 col-sm-10 col-xs-12">
                <select name="status" id="status" class="col-md-12 col-xs-12">
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
                <button type="button" name="btn_update" id="btn_update" class="btn btn-danger" onclick="addRow()">Update</button>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-striped" id="orders">
                  <thead>
                    <tr>
                      <th>Raw Material</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
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
@endsection()

@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Select2 4.0.8 -->
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <!-- jquery.inputmask -->
  <script src="{{ asset('js/jquery.inputmask.bundle.min.js') }}"></script>

  <script>

    $(document).ready(function(){
      var _token = $('meta[name="csrf-token"]').attr('content');

      $('#category').select2({
        placeholder: "Select a category",
        allowClear: true,
        ajax: {
          url: '/api/searchCategories', //'https://api.github.com/search/repositories',
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

      $('#supplier').select2({
        placeholder: "Select a supplier",
        allowClear: true,
        ajax: {
          url: '/api/searchSuppliers', //'https://api.github.com/search/repositories',
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

      $('#status').select2({
        placeholder: "Select a status",
        allowClear: true,
        ajax: {
          url: '/api/searchPurchaseStatus', //'https://api.github.com/search/repositories',
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

      }).val('1').trigger('change');


      $('#supplier').on('select2:select', function(e){
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': _token
          },
          url: "/api/populateProducts/" + e.params.data.id,
          dataType: "JSON",
          success: function(data){
            // $('#orders tr').not(':first').not(':last').remove();
            var html = '';
            for (var i = 0; i < data.length; i++) {
              html += '<tr id='+ data[i].id +' class="items">' +
                '<td class="name">' + data[i].name + '</td>' +
                '<td class="qty">' + '1.00' + '</td>' +
                '<td class="unit_price">' + data[i].unit_price + '</td>' +
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

            // $('#orders tr').first().after(html);
            $('#orders').prepend(html)
          },
          error: function(data){

          }
        });
      });

      // Restricts input for each element in the set of matched elements to the given inputFilter.
      (function($) {
        $.fn.inputFilter = function(inputFilter) {
          return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
            if (inputFilter(this.value)) {
              this.oldValue = this.value;
              this.oldSelectionStart = this.selectionStart;
              this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
              this.value = this.oldValue;
              this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            }
          });
        };
      }(jQuery));

      $('#content').inputFilter(function(value) {
        return /^-?\d*[.,]?\d*$/.test(value);
      });

      $('#net_weight').inputFilter(function(value) {
        return /^-?\d*[.,]?\d*$/.test(value);
      });

      $('#stock_on_hand').inputFilter(function(value) {
        return /^-?\d*[.,]?\d*$/.test(value);
      });

      $('#unit_price').inputFilter(function(value) {
        return /^-?\d*[.,]?\d*$/.test(value);
      });

      $('#purchase_price').inputFilter(function(value) {
        return /^-?\d*[.,]?\d*$/.test(value);
      });

      $('#orders').on('click', '.add', function(e){
        e.preventDefault();
          html = '<tr class="items">' +
            '<td class="name">' + '1' + '</td>' +
            '<td class="qty">' + '' + '</td>' +
            '<td class="unit_price">' + '' + '</td>' +
            '<td><button class="delete">Delete</button></td>' +
            '</tr>';
          $('#orders').prepend(html);
      });

      $('#orders').on('click', '.delete', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
      });

      $('#btnSubmit').on('click', function(e){
        e.preventDefault();
          var order_date = $('#single_cal3').val();
          var supplier_id = $('#supplier').val();
          var status_id = $('#status').val();
          var description = $('#description').val();
          var data= {};
          var items = [];

          // Items
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

          // Data
          data.order_date = order_date;
          data.supplier_id = supplier_id;
          data.status_id = status_id;
          data.description = description;
          data.items = items;

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': _token
          },
          url: '/purchases',
          type: "POST",
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
          error: function(error){
            console.log(error);
          }
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
@endsection()
