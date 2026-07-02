@extends('layouts.app')

@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            @include('partials.breadcrumb', ['title' => 'Activation User', 'subtitle' => $sub_header])

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user_management.users.store') }}" method="POST" enctype="multipart/form-data"
                        novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 form-group">
                                    <label class="form-label d-flex justify-content-between">
                                        <span>Name <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="controls">
                                        <input type="text" name="name" class="form-control" required
                                            placeholder="John Doe" value="{{ old('name') }}"
                                            data-validation-required-message="This field is required" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="controls">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="johndoe@email.com" value="{{ old('email') }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">
                                        <span>Phone <span class="text-danger"></span></span>
                                    </label>
                                    <div class="controls">
                                        <input type="text" name="phone" class="form-control numberonly"
                                            placeholder="08124967654" value="{{ old('phone') }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">
                                        <span>Gender <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="controls">
                                        <div class="btn-group" data-bs-toggle="buttons">
                                            <label class="btn bg-primary-subtle text-primary  active">
                                                <div class="form-check">
                                                    <input type="radio" id="customRadio1" name="gender" value="male"
                                                        class="form-check-input" checked />
                                                    <label class="form-check-label" for="customRadio1"><span
                                                            class="d-block d-md-none">1</span><span
                                                            class="d-none d-md-block">Male</span></label>
                                                </div>
                                            </label>
                                            <label class="btn bg-primary-subtle text-primary ">
                                                <div class="form-check">
                                                    <input type="radio" id="customRadio2" name="gender" value="female"
                                                        class="form-check-input" />
                                                    <label class="form-check-label" for="customRadio2"><span
                                                            class="d-block d-md-none">2</span><span
                                                            class="d-none d-md-block">Female</span></label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label d-flex justify-content-between">
                                        <span>Birth Date <span class="text-danger"></span></span>
                                    </label>
                                    <div class="controls">
                                        <input type="text" id="birth_date" name="birth_date"
                                            class="form-control custom-flat-picker" cfp-type="date" required
                                            placeholder="06/07/1997" value="{{ old('birth_date') }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 form-group">
                                    <label class="form-label d-flex justify-content-between">
                                        <span>Address <span class="text-danger">*</span></span>
                                    </label>
                                    <div class="controls">
                                        <textarea name="address" class="form-control" required placeholder="Enter your address">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="controls">
                                        <input type="password" name="password" class="form-control" required required
                                            placeholder="Password"
                                            data-validation-required-message="This field is required" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="controls">
                                        <input type="password" name="password_confirm"
                                            data-validation-match-match="password" class="form-control" required
                                            placeholder="Confirm Password" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 form-group">
                                    <label class="form-label d-flex justify-content-between">
                                        <span>Photo <span class="text-danger"></span></span>
                                    </label>
                                    <div class="controls">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 position-relative">

                                                <input type="file" id="photo" name="photo"
                                                    class="uploadFileNM d-none" accept="image/png,image/jpeg,image/jpg">

                                                <div class="input-group">

                                                    <button type="button" class="btn btn-outline-secondary choose-file">
                                                        Browse
                                                    </button>

                                                    <div class="form-control custom-file-label d-flex align-items-center">
                                                        No file selected
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="remove-button ms-2"></div>
                                        </div>
                                        <span class="small">Max size :
                                            {{ Helper::staticValue('maximum', 'upload_size') }}
                                            MB</span>
                                    </div>
                                </div>
                            </div>
                            @if (getPermission('limited_roles-update'))
                                <div class="col-md-12">
                                    <div class="mb-3 form-group">
                                        <label class="form-label d-block">Role <span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <div class="role-repeater mb-3">
                                                <div data-repeater-list="roles">
                                                    <div data-repeater-item class="row mb-3">
                                                        <div class="col-md-8">
                                                            <select class="form-select basic-select2" name="roles"
                                                                unique-name="roles" required
                                                                data-placeholder="Select Roles">
                                                                @foreach ($listRole as $value)
                                                                    <option value="{{ $value }}">{{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 mt-3 mt-md-0 d-flex align-items-center">
                                                            <button data-repeater-delete class="btn btn-danger"
                                                                type="button">
                                                                <i class="ti ti-circle-x fs-5 d-flex"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" data-repeater-create
                                                    class="btn btn-success hstack gap-6">
                                                    Add Roles
                                                    <i class="ti ti-circle-plus ms-1 fs-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-12">
                                <div class="d-md-flex align-items-center">
                                    <div class="ms-auto mt-3 mt-md-0">
                                        <button type="submit" class="btn btn-primary hstack gap-6">
                                            <i class="ti ti-send fs-4"></i>
                                            Submit
                                        </button>
                                    </div>
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
    <!-- Make sure jQuery is loaded before this script -->
    <script>
        $(".basic-select2").each(function() {

            if ($(this).data("select2")) {
                $(this).select2("destroy");
            }

        });
        $(".role-repeater").repeater({
            show: function() {
                $(this).slideDown();
                const $newSelect = $(this).find(".basic-select2");
                if ($newSelect.length) {
                    safeReinitSelect2($newSelect);
                }

            },
            hide: function(remove) {
                $(this).slideUp(remove);
            },
        });
    </script>
@endpush
