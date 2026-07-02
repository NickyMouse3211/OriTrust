/**
 * ==========================================================
 * Upload Preview
 * ==========================================================
 */

/**
 * Preview file
 */
function previewFile(file, $wrapper, prevWidth = 300, prevHeight = "auto") {

    const filename = file.name;
    const ext = getExtension(filename);

    if (!isImage(filename)) {
        previewDocument($wrapper, filename);
        return;
    }

    if (ext === "svg") {
        previewSvg(file, $wrapper, filename, prevWidth, prevHeight);
        return;
    }

    previewImage(file, $wrapper, filename, prevWidth, prevHeight);

}

/**
 * Preview document
 */
function previewDocument($wrapper, filename) {

    showDocumentPreview(
        $wrapper,
        filename
    );

}

/**
 * Preview normal image
 */
function previewImage(file, $wrapper, filename, prevWidth = 300, prevHeight = "auto") {

    const reader = new FileReader();

    reader.onload = function (e) {

        const image = new Image();

        image.onload = function () {

            const dataUri = imageToDataUri(
                image,
                prevWidth,
                prevHeight,
                getExtension(filename)
            );

            showImagePreview(
                $wrapper,
                `<img src="${dataUri}" style="width:200px;">`,
                filename
            );

        };

        image.src = e.target.result;

    };

    reader.readAsDataURL(file);

}

/**
 * Preview SVG
 */
function previewSvg(file, $wrapper, filename, prevWidth = 300, prevHeight = "auto") {

    const reader = new FileReader();

    reader.onload = function (e) {

        const svg = `
            <svg
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                width="${prevWidth}"
                height="${prevHeight}"
                style="max-width:100%;">

                <image
                    x="0"
                    y="0"
                    width="100%"
                    height="100%"
                    xlink:href="${e.target.result}" />

            </svg>
        `;

        showSvgPreview(
            $wrapper,
            svg,
            filename
        );

    };

    reader.readAsDataURL(file);

}