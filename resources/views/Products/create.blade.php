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
          <h2>New Product</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="products" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            @csrf


            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12 " for="product">Name<span class="required">*</span></label>
            </div>

            <div class="form-group">
              <div class="col-md-10 col-sm-10 col-xs-12">
                <input type="text" name="product" id="product" required="required" class="form-control" autofocus>
              </div>
            </div>



            <div class="form-group">
              <label class="col-md-5 col-sm-3 col-xs-12" for="brand">Brand</label>

              <label class="col-md-5 col-sm-3 col-xs-12" for="category">Category</label>

            </div>

            <div class="form-group">
              <div class="col-md-5 col-sm-3 col-xs-12">
                <input type="text" name="brand" id="brand" required="required" class="form-control col-md-7 col-xs-12">

              </div>

              <div class="col-md-5 col-sm-12 col-xs-12">
                <select name="category" id="category" class="col-md-12 col-xs-12">
                  <option value=""></option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" for="description">Description<span class="required">*</span></label>
            </div>

            <div class="form-group">
              <div class="col-md-10 col-sm-6 col-xs-12">
                <textarea class="form-control" rows="2" placeholder="" id="description"></textarea>
              </div>
            </div>





            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" for="content">Content</label>

              <label class="col-md-3 col-sm-3 col-xs-12" for="net_weight">Net Weight</label>

              <label class="col-md-3 col-sm-3 col-xs-12" for="stock_on_hand">Stock on Hand</label>
            </div>

            <div class="form-group">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" name="content" id="content" required="required" class="form-control col-md-7 col-xs-12">
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" name="net_weight" id="net_weight" required="required" class="form-control col-md-7 col-xs-12">
              </div>

              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" name="stock_on_hand" id="stock_on_hand" required="required" class="form-control col-md-7 col-xs-12">
              </div>

            </div>



            <div class="form-group">


            </div>


            <div class="form-group">
              <label class="col-md-2 col-sm-2 col-xs-12" for="Purchase Price">Purchase Price</label>
              <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                <input type="text" class="form-control" name="purchase_price" id="purchase_price" required="required">
                <span class="fa fa-dollar form-control-feedback right" aria-hidden="true"></span>
              </div>

              <label class="col-md-2 col-sm-2 col-xs-12" for="unit_price">Unit Price</label>
              <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                <input type="text" class="form-control" name="unit_price" id="unit_price" required="required">
                <span class="fa fa-dollar form-control-feedback right" aria-hidden="true"></span>
              </div>

              <!-- <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="text" name="purchase_price" id="purchase_price" required="required" class="form-control col-md-7 col-xs-12">
              </div> -->
            </div>

            <div class="form-group">

            </div>

            <div class="form-group">
              <label class="col-md-4 col-sm-3 col-xs-12" for="supplier">Supplier</label>

              <label class="col-md-4 col-sm-3 col-xs-12" for="warehouse">Warehouse</label>

              <label class="col-md-4 col-sm-3 col-xs-12" for="section">Section</label>


            </div>

            <div class="form-group">

              <div class="col-md-4 col-sm-8 col-xs-12">
                <select name="supplier" id="supplier" class="col-md-12 col-xs-12">
                  <option value=""></option>
                </select>
              </div>

              <div class="col-md-4 col-sm-8 col-xs-12">
                <select name="warehouse" id="warehouse" class="col-md-12 col-xs-12">
                  <option value=""></option>
                </select>
              </div>

              <div class="col-md-4 col-sm-8 col-xs-12">
                <select name="section" id="section" class="col-md-12 col-xs-12">
                  <option value=""></option>
                </select>
              </div>
            </div>

            <div class="form-group">


            </div>


            <div class="ln_solid"></div>

            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
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

      $('#warehouse').select2({
        placeholder: "Select a warehouse",
        allowClear: true,
        ajax: {
          url: '/api/searchWarehouse', //'https://api.github.com/search/repositories',
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


      $('#section').select2({
        placeholder: "Select a sections",
        allowClear: true,
        ajax: {
          url: '/api/searchSections', //'https://api.github.com/search/repositories',
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


      $('#btnSubmit').on('click', function(){
          var _token = CSRF_TOKEN;
          var name = $('#supplier').val();
          var address = $('#address').val();
          var data= {};

          data.id = 0;
          data.name = name;
          data.address = address;

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': _token
          },
          type: "POST",
          url: "/suppliers/store",
          data: data,
          dataType: "JSON",
          success: function(data){
            if (data.errors != undefined && data.errors.length > 0) {
              showErrorMessage(data.errors);

            } else {
              toastr.success('New supplier was created', 'Success', {timeout: 1000});
              window.setTimeout(function(){
                window.location.href = '/suppliers';
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
