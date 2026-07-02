/**
 * ==========================================================
 * Upload Validation
 * ==========================================================
 */

/**
 * Validate mime type
 *
 * @param {File} file
 * @param {String} accept
 * @returns {{valid:boolean,mimeList:Array}}
 */
function validateMime(file, accept) {

    if (!accept) {
        return {
            valid: true,
            mimeList: []
        };
    }

    let valid = false;
    const mimeList = [];

    accept.split(",").forEach(function (item, index) {

        item = item.trim();

        mimeList.push(`${index + 1}. ${item}`);

        const regex = new RegExp(
            item
                .replace(/\*/g, ".*")
                .replace(/\+/g, "\\+")
        );

        if (regex.test(file.type)) {
            valid = true;
        }

        // Fallback for font extensions
        if (!valid && item.startsWith("font/")) {

            const ext = getExtension(file.name);

            if (item.toLowerCase().includes(ext)) {
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
 * Validate maximum file size
 *
 * @param {File} file
 * @param {Number|String} maxSizeMB
 * @returns {boolean}
 */
function validateSize(file, maxSizeMB) {

    if (
        maxSizeMB === undefined ||
        maxSizeMB === null ||
        maxSizeMB === ""
    ) {
        return true;
    }

    const maxBytes =
        parseFloat(
            String(maxSizeMB).replace(",", ".")
        ) * 1024 * 1024;

    return file.size <= maxBytes;

}

/**
 * Build validation error message
 *
 * @param {{valid:boolean,mimeList:Array}} mimeResult
 * @param {boolean} sizeValid
 * @param {String} maxSizeMB
 * @returns {String}
 */
function getValidationMessage(
    mimeResult,
    sizeValid,
    maxSizeMB
) {

    const messages = [];

    if (!mimeResult.valid) {

        let message = "Mimetype does not match.";

        if (mimeResult.mimeList.length) {

            message +=
                "<br><br>Supported types:<br>&nbsp;" +
                mimeResult.mimeList.join("<br>&nbsp;");

        }

        messages.push(message);

    }

    if (!sizeValid) {

        messages.push(
            `File size is too big. Maximum size is ${maxSizeMB} MB`
        );

    }

    return messages.join("<br><br>");

}