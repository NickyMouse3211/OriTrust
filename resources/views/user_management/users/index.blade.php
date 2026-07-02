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
                            @if (getPermission('users-create'))
                                <a href="{{ route($route . 'create') }}" class="btn btn-success">
                                    <i class="ti ti-plus"></i> Create
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- searching -->
                        <div class="row mb-3">
                            <div class="col-md-3 mb-3">
                                <input type="text" id="searchName" class="form-control" placeholder="Search by Name">
                            </div>
                            <div class="col-md-3 mb-3">
                                <input type="email" id="searchEmail" class="form-control" placeholder="Search by Email">
                            </div>
                            <div class="col-md-3 mb-3">
                                <select id="searchStatus" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                    <option value="99">With Trashed</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex mt-2 mt-md-0">
                                <button id="searchBtn" class="btn btn-primary mx-1"><i class="ti ti-search"></i>
                                    Search</button>
                                <button id="deleteSelectedBtn" class="btn btn-danger mx-1">
                                    <i class="ti ti-trash"></i> Delete Selected
                                </button>
                            </div>
                        </div>
                        <!-- end searching  -->
                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered display text-nowrap">
                                <thead>
                                    <tr>
                                        {{-- <th><input type="checkbox" id="checkAll"></th> --}}
                                        <th width="5%" scope="col">No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
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
    <!-- Make sure jQuery is loaded before this script -->
    <script>
        $(document).ready(function() {
            var id_datatable = "#datatable";
            var url_ajax = '{!! route($route . 'get_data') !!}';
            var filters = {
                name: '#searchName',
                email: '#searchEmail',
                status: '#searchStatus',
            };
            var column = [
                // {
                //     data: 'selectedRow',
                //     name: 'selectedRow',
                //     searchable: false,
                //     orderable: false,
                //     className: 'text-center align-middle'
                // },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false,
                    className: 'align-middle text-center text-sm'
                }, {
                    data: 'name',
                    name: 'name',
                    className: 'align-middle text-sm'
                }, {
                    data: 'email',
                    name: 'email',
                    className: 'align-middle text-sm'
                }, {
                    data: 'status',
                    name: 'status',
                    className: 'align-middle text-sm'
                }, {
                    data: 'aksi',
                    name: 'aksi',
                    className: 'text-center align-middle text-sm',
                    orderable: false
                },
            ];
            global.init_datatable(id_datatable, url_ajax, column, filters, {
                searching: false,
                deleteRoute: "{{ route($route . 'all_destroy') }}"
            });

            $('#searchBtn').click(function() {
                $(id_datatable).DataTable().ajax.reload();
            });
        });
    </script>
@endpush
