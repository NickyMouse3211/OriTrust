"use strict";
// Class definition

var global = function () {
    // Private functions

    // basic demo
    var datatablenya = function (id_datatable, url_ajax, column, filters = {}, options = {}) {
        var searching =
            options.searching !== undefined ? options.searching : true;
        var deleteRoute = options.deleteRoute || null;

        var ajaxConfig = {
            url: url_ajax,
            type: "GET",
            data: function (d) {
                // Add filter values to ajax data if filters are defined
                if (filters && typeof filters === "object") {
                    Object.keys(filters).forEach(function (key) {
                        var selector = filters[key];
                        if (selector) {
                            var val = $(selector).val();
                            if (
                                val !== undefined &&
                                val !== null &&
                                val !== ""
                            ) {
                                d[key] = val;
                            }
                        }
                    });
                }
            },
        };

        var datatable = $(id_datatable).DataTable({
            language: {
                // processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                paginate: {
                    next: '<span><i class="fa fa-angle-right" aria-hidden="true"></i></span>',
                    previous:
                        '<span><i class="fa fa-angle-left" aria-hidden="true"></i></span>',
                },
            },
            processing: true,
            serverSide: true,
            searching: searching,
            ajax: ajaxConfig,
            columns: column,
            pagingType: "full_numbers",
        });

        // Optional: reload table when filter changes
        // if (filters && typeof filters === 'object') {
        //     Object.values(filters).forEach(function (selector) {
        //         if (selector) {
        //             $(document).on('change', selector, function () {
        //                 $(id_datatable).DataTable().ajax.reload();
        //             });
        //         }
        //     });
        // }

        // -- Checkbox + Delete Selected Logic --
        const $checkAll = $("#checkAll");
        const $deleteBtn = $("#deleteSelectedBtn");

        $deleteBtn.hide();

        function toggleDeleteBtn() {
            let anyChecked = $(".select-item:checked").length > 0;
            if (anyChecked) {
                $deleteBtn.show();
            } else {
                $deleteBtn.hide();
            }
        }

        // Select all checkbox
        $(id_datatable).on("change", "#checkAll", function () {
            let isChecked = $(this).is(":checked");
            $(".select-item").prop("checked", isChecked);
            toggleDeleteBtn();
        });

        // Individual checkbox
        $(id_datatable).on("change", ".select-item", function () {
            let allChecked =
                $(".select-item").length === $(".select-item:checked").length;
            $checkAll.prop("checked", allChecked);
            toggleDeleteBtn();
        });

        // Reset on reload
        $(id_datatable).on("draw.dt", function () {
            $checkAll.prop("checked", false);
            $deleteBtn.hide();
        });

        $(id_datatable).on("click", ".btn-delete", function (e) {
            e.preventDefault();
            var el = this;
            var route = $(this).attr("data-route");
            console.log(route);
            Swal.fire({
                title: "Apakah yakin hapus data ini ?",
                text: "Lanjutkan untuk menghapus",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: route,
                        type: "get",
                        dataType: "JSON",
                        beforeSend: function () {
                            $.blockUI({
                                message:
                                    '<i class="ti ti-refresh text-white fs-5"></i>',
                                timeout: 5000, //unblock after 2 seconds
                                overlayCSS: {
                                    backgroundColor: "#000",
                                    opacity: 0.5,
                                    cursor: "wait",
                                },
                                css: {
                                    border: 0,
                                    padding: 0,
                                    backgroundColor: "transparent",
                                },
                            });
                        },
                        success: function (response) {
                            if (response.status == true) {
                                $(id_datatable).DataTable().ajax.reload();
                                swal.fire(
                                    "Deleted!",
                                    response.message,
                                    "success"
                                );
                            } else {
                                swal.fire("Failed!", response.message, "error");
                            }
                            $.unblockUI();
                        },
                        error: function (xhr) {
                            // tampilkan error sesuai dengan response dari server
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                swal.fire(
                                    "Error!",
                                    xhr.responseJSON.message,
                                    "error"
                                );
                            } else {
                                swal.fire(
                                    "Error!",
                                    "Something went wrong, please try again.",
                                    "error"
                                );
                            }
                            // tampilkan pesan error
                            // console.log(xhr.responseText);
                            $.unblockUI();
                        },
                    });
                }
            });
        });

        $(id_datatable).on("click", ".btn-restore", function (e) {
            e.preventDefault();
            var el = this;
            var route = $(this).attr("data-route");
            Swal.fire({
                title: "Apakah yakin mengembalikan data ini ?",
                text: "Lanjutkan untuk mengembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: route,
                        type: "get",
                        dataType: "JSON",
                        beforeSend: function () {
                            $.blockUI({
                                message:
                                    '<i class="ti ti-refresh text-white fs-5"></i>',
                                timeout: 5000, //unblock after 2 seconds
                                overlayCSS: {
                                    backgroundColor: "#000",
                                    opacity: 0.5,
                                    cursor: "wait",
                                },
                                css: {
                                    border: 0,
                                    padding: 0,
                                    backgroundColor: "transparent",
                                },
                            });
                        },
                        success: function (response) {
                            if (response.status == true) {
                                $(id_datatable).DataTable().ajax.reload();
                                swal.fire(
                                    "Restored!",
                                    response.message,
                                    "success"
                                );
                            } else {
                                swal.fire("Failed!", response.message, "error");
                            }
                            $.unblockUI();
                        },
                        error: function (xhr) {
                            // tampilkan error sesuai dengan response dari server
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                swal.fire(
                                    "Error!",
                                    xhr.responseJSON.message,
                                    "error"
                                );
                            } else {
                                swal.fire(
                                    "Error!",
                                    "Something went wrong, please try again.",
                                    "error"
                                );
                            }
                            // tampilkan pesan error
                            // console.log(xhr.responseText);
                            $.unblockUI();
                        },
                    });
                }
            });
        });

        $(id_datatable).on('click', '.btn-force-delete', function (e) {
            e.preventDefault();
            var el = this;
            var route = $(this).attr("data-route");
            Swal.fire({
                title: "Apakah yakin menghapus data ini secara permanen ?",
                text: "Lanjutkan untuk menghapus",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: route,
                        type: "get",
                        dataType: "JSON",
                        beforeSend: function () {
                            $.blockUI({
                                message:
                                    '<i class="ti ti-refresh text-white fs-5"></i>',
                                timeout: 5000, //unblock after 2 seconds
                                overlayCSS: {
                                    backgroundColor: "#000",
                                    opacity: 0.5,
                                    cursor: "wait",
                                },
                                css: {
                                    border: 0,
                                    padding: 0,
                                    backgroundColor: "transparent",
                                },
                            });
                        },
                        success: function (response) {
                            if (response.status == true) {
                                $(id_datatable).DataTable().ajax.reload();
                                swal.fire(
                                    "Deleted!",
                                    response.message,
                                    "success"
                                );
                            } else {
                                swal.fire("Failed!", response.message, "error");
                            }
                            $.unblockUI();
                        },
                        error: function (xhr) {
                            // tampilkan error sesuai dengan response dari server
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                swal.fire(
                                    "Error!",
                                    xhr.responseJSON.message,
                                    "error"
                                );
                            } else {
                                swal.fire(
                                    "Error!",
                                    "Something went wrong, please try again.",
                                    "error"
                                );
                            }
                            // tampilkan pesan error
                            // console.log(xhr.responseText);
                            $.unblockUI();
                        },
                    });
                }
            });
        });

        // Delete Selected Button Click
        $deleteBtn.off("click").on("click", function () {
            var selectedIds = [];
            $(".select-item:checked").each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                Swal.fire("Warning", "No items selected.", "warning");
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "This will delete the selected items.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete them!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteRoute,
                        method: "GET",
                        data: {
                            ids: selectedIds,
                        },
                        beforeSend: function () {
                            $.blockUI({
                                message:
                                    '<i class="ti ti-refresh text-white fs-5"></i>',
                                timeout: 5000, //unblock after 2 seconds
                                overlayCSS: {
                                    backgroundColor: "#000",
                                    opacity: 0.5,
                                    cursor: "wait",
                                },
                                css: {
                                    border: 0,
                                    padding: 0,
                                    backgroundColor: "transparent",
                                },
                            });
                        },
                        success: function (response) {
                            Swal.fire(
                                "Deleted!",
                                response.message ||
                                    "Items deleted successfully.",
                                "success"
                            );
                            $(id_datatable).DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            Swal.fire(
                                "Error!",
                                xhr.responseJSON?.message ||
                                    "Something went wrong.",
                                "error"
                            );
                        },
                    });
                }
            });
        });
    };

    return {
        // public functions
        init_datatable: function (id_datatable, url_ajax, column, filters = {}, options = {}) {
            datatablenya(id_datatable, url_ajax, column, filters, options);
        },
    };
}();
