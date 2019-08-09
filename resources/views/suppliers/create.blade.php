@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
          <h2>New Supplier</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="suppliers" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            @csrf

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier_name">Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="supplier_name" id="supplier_name" required="required" class="form-control col-md-7 col-xs-12" autofocus>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier_address">Address<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control" rows="3" placeholder="Address" id="supplier_address"></textarea>
              </div>
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <input type="button" class="btn btn-primary" value="Cancel" onclick="window.location.href='/suppliers'" />
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

  <script>
    $(document).ready(function(){
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

      $('#btnSubmit').on('click', function(){
          var _token = CSRF_TOKEN;
          var supplier_name = $('#supplier_name').val();
          var supplier_address = $('#supplier_address').val();
          var arrData = [];
          var obj = {};

        // VALIDATION

        // obj._token = _token;
        obj.name = supplier_name;
        obj.address = supplier_address;

        arrData.push(obj);

        $.ajax({
          type: "POST",
          url: "/suppliers/store",
          data: {_token: _token, arrData: arrData},
          dataType: "JSON",
          success: function(data){
            console.log('saved');
            console.log(data);
          }
        });
      });
    });
  </script>
@endsection()
