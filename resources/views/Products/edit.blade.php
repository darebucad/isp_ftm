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
          <h2>Product - {{ $product->name }}</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="products" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}" id="product_id">
            <input type="hidden" name="category_id" value="{{ $product->category_id }}" id="category_id">
            <input type="hidden" name="supplier_id" value="{{ $product->supplier_id }}" id="supplier_id">
            <input type="hidden" name="warehouse_id" value="{{ $product->warehouse_id }}" id="warehouse_id">
            <input type="hidden" name="section_id" value="{{ $product->section_id }}" id="section_id">
            <input type="hidden" name="brand_id" value="{{ $product->brand_id }}" id="brand_id">
            <input type="hidden" name="type_id" value="{{ $product->type }}" id="type_id">
            <input type="hidden" name="unitofmeasure_id" value="{{ $product->unitofmeasure_id }}" id="unitofmeasure_id">

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" for="product">Name<span class="required">*</span></label>
            </div>

            <div class="form-group">
              <div class="col-md-10 col-sm-10 col-xs-12">
                <input type="text" name="product" id="product" value="{{ $product->name }}" required="required" class="form-control" autofocus />
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-5 col-sm-3 col-xs-12" for="category">Category<span class="required">*</span></label>
              <label class="col-md-5 col-sm-3 col-xs-12" for="brand">Brand</label>
            </div>

            <div class="form-group">
              <div class="col-md-5 col-sm-12 col-xs-12">
                <select name="category" id="category" required="required" class="col-md-12 col-xs-12">
                  <option value=""></option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-5 col-sm-3 col-xs-12">
                <select class="col-md-12 col-xs-12" name="brand" id="brand" required="required">
                  <option value=""></option>
                  @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" for="description">Description</label>
            </div>

            <div class="form-group">
              <div class="col-md-10 col-sm-6 col-xs-12">
                <textarea class="form-control" rows="2" placeholder="" id="description">{{ $product->description }}</textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" for="content">Content<span class="required">*</span></label>
              <label class="col-md-3 col-sm-3 col-xs-12" for="net_weight">Net Weight</label>
              <label class="col-md-3 col-sm-3 col-xs-12" for="stock_on_hand">Stock on Hand</label>
              <label class="col-md-3 col-sm-3 col-xs-12" for="actual_on_hand">Actual on Hand</label>
            </div>

            <div class="form-group">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" name="content" id="content" value="{{ $product->content }}" required="required" class="form-control col-md-7 col-xs-12">
              </div>

              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" name="net_weight" id="net_weight" value="{{ $product->net_weight }}" class="form-control col-md-7 col-xs-12">
              </div>

              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" name="stock_on_hand" id="stock_on_hand" value="{{ $product->stock_on_hand }}" class="form-control col-md-7 col-xs-12">
              </div>

              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" name="actual_on_hand" id="actual_on_hand" value="{{ $product->actual_on_hand }}" class="form-control col-md-7 col-xs-12">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 col-sm-3 col-xs-12" for="unitofmeasure">Unit of measurement</label>
              <label class="col-md-3 col-sm-3 col-xs-12" for="type">Type<span class="required">*</span></label>
              <label class="col-md-3 col-sm-3 col-xs-12" for="unit_price">Unit Price<span class="required">*</span></label>
              <label class="col-md-3 col-sm-3 col-xs-12" for="Purchase Price">Purchase Price</label>

            </div>

            <div class="form-group">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <select name="unitofmeasure" id="unitofmeasure" class="col-md-12 col-xs-12">
                  <option value=""></option>
                  @foreach($unitofmeasures as $unitofmeasure)
                  <option value="{{ $unitofmeasure->id }}">{{ $unitofmeasure->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                <select name="type" id="type" class="col-md-12 col-xs-12">
                  <option value=""></option>
                  <option value="0">Raw Material</option>
                  <option value="1">Finished Product</option>
                </select>
              </div>

              <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                <input type="text" class="form-control" name="unit_price" id="unit_price" value="{{ $product->unit_price }}" required="required">
                <span class="fa fa-dollar form-control-feedback right" aria-hidden="true"></span>
              </div>

              <div class="col-md-3 col-sm-3 col-xs-12 form-group has-feedback">
                <input type="text" class="form-control" name="purchase_price" id="purchase_price" value="{{ $product->purchase_price }}">
                <span class="fa fa-dollar form-control-feedback right" aria-hidden="true"></span>
              </div>
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
                  @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-4 col-sm-8 col-xs-12">
                <select name="warehouse" id="warehouse" class="col-md-12 col-xs-12">
                  <option value=""></option>
                  @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-4 col-sm-8 col-xs-12">
                <select name="section" id="section" class="col-md-12 col-xs-12">
                  <option value=""></option>
                  @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                  @endforeach
                </select>
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

  <script>

    $(document).ready(function(){
      var _token = $('meta[name="csrf-token"]').attr('content');
      var category_id = $('#category_id').val();
      var supplier_id = $('#supplier_id').val();
      var warehouse_id = $('#warehouse_id').val();
      var section_id = $('#section_id').val();
      var brand_id = $('#brand_id').val();
      var type_id = $('#type_id').val();
      var unitofmeasure_id = $('#unitofmeasure_id').val();

      // Category
      $('#category').select2({
        placeholder: 'Select a category',
        allowClear: true
      }).val(category_id);

      $('#category').trigger('change');

      // Supplier
      $('#supplier').select2({
        placeholder: 'Select a supplier',
        allowClear: true
      }).val(supplier_id);

      $('#supplier').trigger('change');

      // Warehouse
      $('#warehouse').select2({
        placeholder: 'Select a warehouse',
        allowClear: true
      }).val(warehouse_id);

      $('#warehouse').trigger('change');

      // Section
      $('#section').select2({
        placeholder: 'Select a section',
        allowClear: true
      }).val(section_id);

      $('#section').trigger('change');

      // Brand
      $('#brand').select2({
        placeholder: 'Select a brand',
        allowClear: true
      }).val(brand_id);

      $('#brand').trigger('change');

      // Type
      $('#type').select2({
        placeholder: 'Select a type',
        allowClear: true
      }).val(type_id);
      $('#type').trigger('change');

      // Unitofmeasure
      $('#unitofmeasure').select2({
        placeholder: 'Select a unit',
        allowClear: true
      }).val(unitofmeasure_id);
      $('#unitofmeasure').trigger('change');


      $('#btnSubmit').on('click', function(){
          var product_id = $('#product_id').val();
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
          var actual_on_hand = $('#actual_on_hand').val();
          var unitofmeasure_id = $('#unitofmeasure').val();

          var data= {};

          data.id = product_id;
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
          data.actual_on_hand = actual_on_hand;
          data.unitofmeasure_id = unitofmeasure_id;

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': _token
          },
          url: '/products/' + product_id,
          type: 'PUT',
          data: data,
          dataType: 'JSON',
          success: function(data){
            if (data.errors != undefined && data.errors.length > 0) {
              showErrorMessage(data.errors);

            } else {
              toastr.success('Product was edited', 'Success', {timeout: 1000});
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
@endsection
