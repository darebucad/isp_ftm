@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
          <h2>Suppliers / Create(breadcrumbs)</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
              <input type="button" name="btn_save" value="Save" id="btn_save" class="btn btn-danger">
                <!-- <input type="button" class="btn btn-primary" value="New Product Category" onclick="window.location.href='/suppliers/create'" /> -->
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form id="suppliers">
            @csrf

            <div class="form-group row">
              <div class="col-md-8">
                <input type="text" name="supplier_name" value="" id="supplier_name" class="form-control" placeholder="Supplier Name">
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-8">
                <input type="text" name="supplier_address" value="" id="supplier_address" class="form-control" placeholder="Supplier Address">
              </div>
            </div>

          </form>

          <!-- <table class="table" id="tblCategories">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table> -->
        </div>
      </div>
  </div>
@endsection()

@section('js')
  <script src="{{asset('js/dataTables.min.js')}}"></script>
  <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
  <script>
    $(document).ready(function(){

      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

      $('#btn_save').on('click', function(){
        // console.log('clicked');
        var _token = CSRF_TOKEN;
        var supplier_name = $('#supplier_name').val();
        var supplier_address = $('#supplier_address').val();
        var arrData = [];
        var obj = {};

        // VALIDATION

        // obj._token = _token;
        obj.supplier_name = supplier_name;
        obj.supplier_address = supplier_address;

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
      $("#tblCategories").DataTable({
        "pageLength": 30,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('api.getCategories')}}",
        "columns":[
          {
            "width": "20%",
            "data":"Name",
          },
          {
            "width": "60%",
            "data":"Description"
          },
          {
            "width": "20%",
            "data":null,
            "orderable": false,
            "searchable":false,
            render: function ( data, type, row ) {
              return '<button type="button" class="btn btn-default">Edit</button>'
                     +'<button type="button" class="btn btn-danger">Delete</button>';
            }
          },
        ]
      });
    });
  </script>
@endsection()
