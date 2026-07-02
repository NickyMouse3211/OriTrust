@extends('layouts.app')

@section('content')
<div class="body-wrapper">
    <div class="container-fluid">
        @include('partials.breadcrumb', ['title' => $header, 'subtitle' => $sub_header])

        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <a href="{{ route($route.'index') }}" class="btn btn-warning">
                    <i class="ti ti-arrow-left"></i> Cancel
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route($route.'store') }}" class="form-global-handle" method="POST">
                    @csrf

                    {{-- Apps Name --}}
                    <div class="col-md-6 mb-4">
                        <label for="AppName" class="form-label">
                            Apps Name
                            <span class="text-danger" data-bs-toggle="tooltip" title="Form ini harus diisi!">*</span>
                        </label>
                        <select class="form-select basic-select2"
                            name="application" unique-name="application" required data-placeholder="Select Apps"
                            data-table="{{ Helper::encryptForUrl('applications') }}"
                            data-value-field="{{ Helper::encryptForUrl('apps_code') }}"
                            data-text-field="{{ Helper::encryptForUrl('apps_name') }}">
                            <option></option>
                        </select>
                    </div>

                    {{-- Role Name --}}
                    <div class="col-md-6 mb-4">
                        <label for="RoleName" class="form-label">
                            Role Name
                            <span class="text-danger" data-bs-toggle="tooltip" title="Form ini harus diisi!">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" id="RoleName" placeholder="Example: Admin" autocomplete="off">
                    </div>

                    {{-- Permission List --}}
                    <div class="mb-4">
                        <label class="form-label">
                            List Permission
                            <span class="text-danger" data-bs-toggle="tooltip" title="Form ini harus diisi!">*</span>
                        </label>

                        <div class="row">
                            @forelse ($data as $key => $value)
                            <div class="col-lg-4 mb-4">
                                <div class="accordion accordion-flush border rounded" id="accordion{{ $key }}">
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header" id="heading{{ $key }}">
                                            <button class="accordion-button collapsed fw-bold text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                                {{ str_replace('-list', '', $value[0]->name) }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $key }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $key }}" data-bs-parent="#accordion{{ $key }}">
                                            <div class="accordion-body p-0">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input check-all" id="checkAll{{ $key }}" data-key="{{ $key }}">
                                                            <label class="form-check-label fw-semibold" for="checkAll{{ $key }}">Check All</label>
                                                        </div>
                                                    </li>
                                                    @foreach ($value as $key_value => $value_data)
                                                    <li class="list-group-item">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input sub-checkbox{{ $key }} custom-check" data-key="{{ $key }}" name="permission_id[]" value="{{ $value_data->id }}" id="permCheck{{ $key }}_{{ $key_value }}">
                                                            <label class="form-check-label" for="permCheck{{ $key }}_{{ $key_value }}">
                                                                {{ $value_data->name }}
                                                            </label>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="alert alert-warning">No permission data available.</div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-md-flex align-items-center justify-content-center">
                            <div class="ms-auto mt-3 mt-md-0">
                                <button type="submit" class="btn btn-primary hstack gap-6">
                                <i class="ti ti-send fs-4"></i>
                                Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('js')
<script>
    $(document).ready(function () {
        $('.check-all').on('change', function () {
            var key = $(this).data('key');
            $('.sub-checkbox' + key).prop('checked', $(this).is(':checked'));
        });

        $('.custom-check').on('change', function () {
            var key = $(this).data('key');
            var allChecked = $('.sub-checkbox' + key).length === $('.sub-checkbox' + key + ':checked').length;
            $('#checkAll' + key).prop('checked', allChecked);
        });
    });
</script>
@endpush