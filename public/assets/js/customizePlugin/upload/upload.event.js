$("html").on("change", ".uploadFileNM", function () {

    const $input = $(this);

    if (!this.files.length) {
        return;
    }

    const file = this.files[0];

    const id = $input.attr("id");

    const accept = $input.attr("accept") || "";
    const maxSize = $input.attr("max-size") || "";

    const prevWidth = $input.attr("prevWidth") || 300;
    const prevHeight = $input.attr("prevHeight") || "auto";

    const $wrapper = $input.closest(".position-relative");
    const $container = $input.closest(".d-flex");
    const $label = $wrapper.find(".custom-file-label");
    const $removeButton = $container.find(".remove-button");

    //--------------------------------------------------
    // Validation
    //--------------------------------------------------

    const mime = validateMime(file, accept);
    const size = validateSize(file, maxSize);

    if (!mime.valid || !size) {

        toastr.error(
            getValidationMessage(
                mime,
                size,
                maxSize
            )
        );

        $input.val("");

        return;

    }

    //--------------------------------------------------
    // UI
    //--------------------------------------------------

    const filename = getFilename(file.name);

    setFileLabel(
        $label,
        filename
    );

    showRemoveButton(
        $removeButton
    );

    //--------------------------------------------------
    // Preview
    //--------------------------------------------------

    previewFile(
        file,
        $wrapper,
        prevWidth,
        prevHeight
    );

    //--------------------------------------------------
    // Required border
    //--------------------------------------------------

    if ($input.prop("required")) {

        $wrapper
            .find(".custom-border-form")
            .css("border", "1px solid green");

    }

    //--------------------------------------------------
    // Hidden input
    //--------------------------------------------------

    $(".uploadFileNM-" + id).val(filename);

});