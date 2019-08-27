@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>New Unit of Measure</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <br>
        <form id="frmUOM" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
          <input type="hidden" name="_token" value="{{csrf_token()}}"/>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="uom">Unit of Measure<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" name="uom" id="uom" required="required" class="form-control col-md-7 col-xs-12">
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <input type="button" class="btn btn-primary" value="Cancel" onclick="window.location.href='/uom'" />
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

          $("#btnSubmit").click(function(){
              var data = {};
              data.id=0;
              data.name = $("#uom").val();

              $.ajax({
                 headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  async:false,
                  type:'POST',
                  url:'/uom',
                  data:data,
                  success:function(data){
                      if(data.errors != undefined && data.errors.length > 0){
                        showErrorMessage(data.errors);
                      }else{
                        toastr.success('New Unit of Measure created','success', {timeOut: 3000});
                        window.setTimeout( function(){
                          window.location.href="/uom";
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