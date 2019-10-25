@extends('layouts.app')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <input type="hidden" id="delId"/>
    <div class="x_panel">
        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
        <div class="x_title">
          <h2>Stores</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
                <input type="button" class="btn btn-primary" value="New Store" onclick="window.location.href='/stores/create'" />
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <table class="table" id="tblStore">
            <thead>
              <tr>
                <th>Name</th>
                <th>Address</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>
      </div>
      <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;" id="modalDialog">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Delete</h4>
              </div>
              <div class="modal-body">
                <p>Are you sure you want to delete this store?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnDelete">Yes</button>
              </div>

            </div>
          </div>
        </div>
  </div>
@endsection()

@section('js')
<script src="{{asset('js/dataTables.min.js')}}"></script>
<script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  var table;

  $(document).ready(function(){
    table = $("#tblStore").DataTable({
      "pageLength": 30,
      "processing": true,
      "serverSide": true,
      "ajax": "{{route('api.getStores')}}",
      "columns":[
        {
          "width": "20%",
          "data":"Name",
        },
        {
          "width": "60%",
          "data":"Address"
        },
        {
          "width": "20%",
          "data":null,
          "orderable": false,
          "searchable":false,
          render: function ( data, type, row ) {
            return '<button type="button" class="btn btn-default" onclick="editStore(\''+data.Id+'\')">Edit</button>'
                   +'<button type="button" class="btn btn-danger" data-toggle="modal" data-target=".bs-example-modal-sm" onclick="showDeleteConfirmation(\''+data.Id+'\')">Delete</button>';
          }
        },
      ]
    });

    $("#btnDelete").click(function(){
      var catId = $("#delId").val();
      $.ajax({
               headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                async:false,
                type:'DELETE',
                url:'/stores/' + catId,
                success:function(data){
                    if(data.errors != undefined && data.errors.length > 0){
                      showErrorMessage(data.errors);
                    }else{
                      toastr.success('Store deleted','success', {timeOut: 3000});
                      $("#btnCancel").click();
                      table.ajax.reload();
                    }
                },
                error:function(error){
                    console.log(error);
                }
              });
    });
  });

  function editStore(id){
    window.location.href = '/stores/' + id  + '/edit'
  }

  function showDeleteConfirmation(id){
    $("#delId").val(id);
  }

  function showErrorMessage(errMessage){
          var errMessageContent = '';
          errMessage.forEach(element => {
            errMessageContent = errMessageContent + element + '<br/>';
          });
          toastr.error(errMessageContent, 'Error', {timeOut: 3000});
  }

</script>
@endsection()
