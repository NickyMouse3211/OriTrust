
@extends('layouts.app')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
      @include('partials.breadcrumb', ['title' => $header, 'subtitle' => $sub_header])

      <div class="datatables">
        <!-- start Alternative Pagination -->
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-end">
              @can($permission.'create') 
                <a href="{{ route($route.'create') }}" class="btn btn-success">
                  <i class="ti ti-plus"></i> Create
                </a>
              @endcan
            </div>
          </div>
          <div class="card-body">
            <!-- searching -->
            <div class="row mb-3">
              <div class="col-md-3 mb-3">
                <input type="text" id="searchName" class="form-control" placeholder="Search by Name">
              </div>
              <div class="col-md-3 mb-3 d-flex">
                  <button id="searchBtn" class="btn btn-primary"><i class="ti ti-search"></i> Search</button>
              </div>
            </div>
            <!-- end searching  -->
            <div class="table-responsive">
              <table id="datatable" class="table table-striped table-bordered display text-nowrap">
                <thead>
                  <tr>
                    <th width="5%" scope="col">No</th>
                    <th>Apps</th>
                    <th>Name</th>
                    <th style="widht:23%">Aksi</th>
                </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <!-- end Alternative Pagination -->
      </div>
    </div>
</div>
@endsection

@push('js')
<script>
  $(document).ready(function () {
      var id_datatable = "#datatable";
      var url_ajax = '{!! route($route.'get_data') !!}';
      var filters = {
          name: '#searchName',
      };
      var column = [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
          { data: 'apps_name', name: 'apps_name', className: 'align-middle text-sm' },
          { data: 'name', name: 'name', className: 'align-middle text-sm' },
          { data: 'aksi', name: 'aksi', className: 'text-center align-middle text-sm', orderable: false },
      ];
      global.init_datatable(id_datatable, url_ajax, column, filters, { searching: false });

      $('#searchBtn').click(function () {
          $(id_datatable).DataTable().ajax.reload();
      });
  });
</script>
@endpush