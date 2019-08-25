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
                        <input type="text" class="form-control has-feedback-left" id="single_cal3" placeholder="First Name" aria-describedby="inputSuccess2Status3">
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
                  <option value="N" selected>New</option>
                  <option value="R">Received</option>
                  <option value="C">Change Order</option>
                  <option value="X">Cancelled</option>
                  <option value="D">Done</option>
                </select>
              </div>
            </div>

            <div class="ln_solid"></div>

            <div class="row">
              <div class="col-md-12">
                <table class="table table-striped" id="orders">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Raw Material</th>
                      <th>Quantity</th>
                      <th>Price</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <div class="ln_solid"></div>

            <div class="form-group">
              <div class="col-md-12 col-sm-6 col-xs-12 col-md-offset-5">
                <input type="button" class="btn btn-primary" value="Cancel" onclick="window.location.href='/products'" />
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
      });

      $('#supplier').on('select2:select', function(e){
        console.log(e.params.data.id);

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': _token
          },
          url: "/api/populateProducts/" + e.params.data.id,
          dataType: "JSON",
          success: function(data){
            console.log(data);
            $('#orders tr').not(':first').not(':last').remove();
            var html = '';
            for (var i = 0; i < data.length; i++) {
              html += '<tr>' +
                '<td>' + data[i].id + '</td>' +
                '<td>' + data[i].name + '</td>' +
                '<td>' + '1.00' + '</td>' +
                '<td>' + data[i].unit_price + '</td>' +
                '</tr>';
            }
            $('#orders tr').first().after(html);
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


      $('#btnSubmit').on('click', function(){
          // var _token = CSRF_TOKEN;
          var name = $('#product').val();
          var category_id = $('#category').val();
          var description  = $('#description').val();
          var content = $('#content').val();
          var net_weight = $('#net_weight').val();
          var stock_on_hand = $('#stock_on_hand').val();
          var purchase_price = $('#purchase_price').val();
          var unit_price = $('#unit_price').val();
          var supplier_id = $('#supplier').val();
          var warehouse_id = $('#warehouse').val();
          var section_id = $('#section').val();
          var brand_id = $('#brand').val();
          var type = $('#type').val();

          var data= {};

          // data.id = 0;
          data.name = name;
          data.category_id = category_id;
          data.description = description;
          data.content = content;
          data.net_weight = net_weight;
          data.stock_on_hand = stock_on_hand;
          data.purchase_price = purchase_price;
          data.unit_price = unit_price;
          data.supplier_id = supplier_id;
          data.warehouse_id = warehouse_id;
          data.section_id = section_id;
          data.brand_id = brand_id;
          data.type = type;

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': _token
          },
          url: '/products',
          type: "POST",
          data: data,
          dataType: 'JSON',
          success: function(data){
            if (data.errors != undefined && data.errors.length > 0) {
              showErrorMessage(data.errors);

            } else {
              toastr.success('New product was created', 'Success', {timeout: 1000});
              window.setTimeout(function(){
                window.location.href = '/products';
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
