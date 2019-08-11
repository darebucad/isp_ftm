@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
          <h2>Edit - {{ $supplier->name }}</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="suppliers" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            @csrf
            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}" id="supplier_id">

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="supplier" id="supplier" required="required" class="form-control col-md-7 col-xs-12" value="{{ $supplier->name }}" autofocus>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea class="form-control" rows="3" placeholder="Address" id="address">{{ $supplier->address }}</textarea>
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
          var id = $('#supplier_id').val();
          var name = $('#supplier').val();
          var address = $('#address').val();
          var data= {};

          // data.id = 0;
          data.name = name;
          data.address = address;

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': _token
          },
          type: "PUT",
          url: "/suppliers/update/" + id,
          data: data,
          dataType: "JSON",
          success: function(data){
            if (data.errors != undefined && data.errors.length > 0) {
              showErrorMessage(data.errors);

            } else {
              toastr.success('Supplier was updated', 'Success', {timeout: 1000});
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
