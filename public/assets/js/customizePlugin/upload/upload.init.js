/**
 * ==========================================================
 * Upload Initialization
 * ==========================================================
 */

function uploadNM() {

    $(".uploadFileNM").each(function (key) {

        const $input = $(this);
        const $wrapper = $input.closest(".position-relative");
        const $container = $input.closest(".d-flex");
        const $label = $wrapper.find(".custom-file-label");
        const $removeButton = $container.find(".remove-button");

        const id = $input.attr("id") || "";
        const name = ($input.attr("name") || "").replace("[]", "");

        let oldval = $input.attr("oldval") || "";
        const oldpath = ($input.attr("oldpath") || "").replace("NMbaseurl", base_url);

        const arrayfield = $input.is("[arrayfield]");
        const required = $input.hasClass("NM-required");

        const className = replaceAll(
            replaceAll(name, "\\[", ""),
            "\\]",
            ""
        );

        //--------------------------------------------------
        // Hidden field wrapper
        //--------------------------------------------------

        let $append = $wrapper.find(".append-" + className);

        if (!$append.length) {

            $wrapper.append(
                `<div class="append-${className}"></div>`
            );

            $append = $wrapper.find(".append-" + className);

        }

        //--------------------------------------------------
        // Existing hidden fields
        //--------------------------------------------------

        const isEdit = $append.find(".old-" + id).val();
        const alreadyDelete = $append.find(".delete-" + id).val();

        if (!isEdit) {

            const hiddenFields = arrayfield && $input.attr("name").includes("[]")
                ? `
                    <input
                        type="hidden"
                        id="old-${id}"
                        name="old-${name}[${key}]"
                        class="old-${id} NM-oldfile"
                        value="${oldval}">

                    <input
                        type="hidden"
                        id="delete-${id}"
                        name="delete-${name}"
                        class="delete-${id} NM-delete"
                        value="false">
                `
                : `
                    <input
                        type="hidden"
                        id="old-${id}"
                        name="old-${$input.attr("name")}"
                        class="old-${id} NM-oldfile"
                        value="${oldval}">

                    <input
                        type="hidden"
                        id="delete-${id}"
                        name="delete-${name}"
                        class="delete-${id} NM-delete"
                        value="false">
                `;

            $append.html(hiddenFields);

        } else {

            oldval = oldval || isEdit;

        }

        //--------------------------------------------------
        // Existing file
        //--------------------------------------------------

        if (oldval && alreadyDelete === "false") {

            setFileLabel(
                $label,
                oldval
            );

            if (isImage(oldval)) {

                showImagePreview(
                    $wrapper,
                    `<img src="${oldpath}${oldval}" style="width:200px !important;">`,
                    oldval
                );

            } else {

                showDocumentPreview(
                    $wrapper,
                    oldval
                );

            }

            if (!required) {

                showRemoveButton(
                    $removeButton
                );

            }

        }

        //--------------------------------------------------
        // Array upload
        //--------------------------------------------------

        if (
            arrayfield &&
            $input.attr("name").includes("[]")
        ) {

            $input.attr(
                "name",
                $input.attr("name")
                    .replace(
                        "[]",
                        "[" + key + "]"
                    )
            );

        }

    });

}

$('html').on('click', '.choose-file, .custom-file-label', function () {

    const input = $(this)
        .closest('.position-relative')
        .find('.uploadFileNM')[0];

    if (input) {
        input.click();
    }

});


function removeitem(e) {

    const $container = $(e).closest(".d-flex");
    const $wrapper = $container.find(".position-relative");
    const $input = $wrapper.find(".uploadFileNM");
    const $label = $wrapper.find(".custom-file-label");
    const $removeButton = $container.find(".remove-button");

    const required = $input.hasClass("NM-required");

    let oldfile =
        $input.attr("oldval") ||
        $wrapper.find(".NM-oldfile").val() ||
        "";

    const oldpath = ($input.attr("oldpath") || "")
        .replace("NMbaseurl", base_url);

    const idInput = $input.attr("id");

    // Clear validation message
    $wrapper.find(".background_image-error").html("");

    // Remove existing preview
    clearPreview($wrapper);

    if (required) {

        if (oldfile !== "") {

            setFileLabel($label, oldfile);

            if (isImage(oldfile)) {

                showImagePreview(
                    $wrapper,
                    `<img src="${oldpath}${oldfile}" style="max-width:200px;">`,
                    oldfile
                );

            } else {

                showDocumentPreview(
                    $wrapper,
                    oldfile
                );

            }

        } else {

            setFileLabel($label, "No file selected");

        }

    } else {

        setFileLabel($label, "No file selected");

    }

    // Clear selected file
    $input.val("");

    // Remove remove button
    hideRemoveButton($removeButton);

    // Mark delete=true for edit mode
    const deleteInput = document.getElementById(
        "delete-" + idInput
    );

    if (deleteInput) {
        deleteInput.value = "true";
    }

    // Restore placeholder if exists
    if ($label.attr("labelplaceholder")) {
        setFileLabel(
            $label,
            $label.attr("labelplaceholder")
        );
    }

    // Reset hidden filename input
    $(".uploadFileNM-" + idInput).val("");

}