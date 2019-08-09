@extends('layouts.master')

@section('css')
  <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
@endsection()

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
          <h2>Suppliers</h2>
          <ul class="nav navbar-right panel_toolbox">
            <li>
                <input type="button" class="btn btn-primary" value="New" onclick="window.location.href='/suppliers/create'" />
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">

          <table class="table" id="suppliers">
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
  </div>
@endsection()

@section('js')
  <script src="{{asset('js/dataTables.min.js')}}"></script>
  <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
  <script>
    $(document).ready(function(){
      $("#suppliers").DataTable({
        "pageLength": 30,
        "processing": true,
        "serverSide": true,
        "ajax": "{{route('api.getSuppliers')}}",
        "columns":[
          {
            "width": "20%",
            "data":"name",
          },
          {
            "width": "60%",
            "data":"address"
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
