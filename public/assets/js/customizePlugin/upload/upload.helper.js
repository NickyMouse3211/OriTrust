/**
 * ==========================================================
 * Upload Helper
 * ==========================================================
 */

/**
 * Set label text
 */
function setFileLabel($label, text) {
    $label.html(text || "No file selected");
}

/**
 * Show remove button
 */
function showRemoveButton($target) {
    $target.html(`
        <button
            type="button"
            class="btn btn-outline-danger remove-file"
            onclick="removeitem(this)">
            <i class="ti ti-x fs-4"></i>
        </button>
    `);
}

/**
 * Hide remove button
 */
function hideRemoveButton($target) {
    $target.empty();
}

/**
 * Dispose tooltip & popover
 */
function disposePreview($target) {

    try {
        $target.tooltip("dispose");
    } catch (e) { }

    try {
        $target.popover("dispose");
    } catch (e) { }

}

/**
 * Clear preview attributes
 */
function clearPreview($target) {

    disposePreview($target);

    $target
        .removeAttr("title")
        .removeAttr("data-original-title")
        .removeAttr("data-bs-title")
        .removeAttr("data-toggle")
        .removeAttr("data-bs-toggle")
        .removeAttr("data-container")
        .removeAttr("data-html");

}

/**
 * Show document preview
 */
function showDocumentPreview($target, filename) {

    clearPreview($target);

    $target
        .attr("title", `<u><b>${filename}</b></u>`)
        .attr("data-html", "true")
        .tooltip({
            trigger: "hover",
            html: true,
            container: "body"
        });

}

/**
 * Show image preview
 */
function showImagePreview($target, imageHtml, filename) {

    clearPreview($target);

    $target
        .attr("title", imageHtml)
        .attr("data-original-title", imageHtml)
        .attr("data-html", "true")
        .tooltip({
            trigger: "hover",
            html: true,
            container: "body",
            customClass: "image-tooltip",
            sanitize: false,
        });

}

/**
 * Show SVG preview
 */
function showSvgPreview($target, svgHtml, filename) {

    clearPreview($target);

    $target
        .attr("title", filename)
        .popover({
            trigger: "hover",
            html: true,
            container: "body",
            placement: "auto",
            content: function () {

                const $el = $("<div></div>");
                $el.append(svgHtml);

                return $el;

            }
        });

}

/**
 * Validate mime
 */
function validateMime(file, accept) {

    let valid = true;
    let mimeList = [];

    if (!accept) {

        return {
            valid: true,
            mimeList: []
        };

    }

    valid = false;

    accept.split(",").forEach(function (item, index) {

        item = item.trim();

        mimeList.push(`${index + 1}. ${item}`);

        const regex = new RegExp(
            item
                .replace("*", ".*")
                .replace("+", ".+")
        );

        if (regex.test(file.type)) {
            valid = true;
        }

        if (!valid && item.includes("font/")) {

            const ext = getExtension(file.name);

            if (item.includes(ext)) {
                valid = true;
            }

        }

    });

    return {
        valid,
        mimeList
    };

}

/**
 * Validate file size
 */
function validateSize(file, maxSizeMB) {

    if (!maxSizeMB)
        return true;

    const max =
        parseFloat(
            String(maxSizeMB).replace(",", ".")
        ) * 1024 * 1024;

    return file.size <= max;

}

/**
 * Filename
 */
function getFilename(path) {

    return path.replace(/C:\\fakepath\\/i, "");

}

/**
 * Extension
 */
function getExtension(filename) {

    return filename
        .split(".")
        .pop()
        .toLowerCase();

}

/**
 * Is image
 */
function isImage(filename) {

    return [
        "jpg",
        "jpeg",
        "png",
        "gif",
        "svg"
    ].includes(
        getExtension(filename)
    );

}