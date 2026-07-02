let baseUrl = "/global/select2/basic/__TABLE__";

// Initialize Select2 on page load
$(document).ready(function () {
    initSelect2($(".basic-select2"));
});


function safeReinitSelect2($el) {
    // Destroy only this element safely
    if ($el.data("select2") || $el.hasClass("select2-hidden-accessible")) {
        try {
            $el.select2("destroy");
        } catch (e) {
            console.warn("Select2 destroy skipped:", e.message);
        }

        // Remove any ghost containers
        $el.nextAll(".select2-container").remove();
    }

    // Wait a bit for DOM cleanup, then reinitialize
    setTimeout(() => theSelect2($el), 100);
}

function initSelect2($elements) {
    $elements.each(function () {
        const $el = $(this);
        safeReinitSelect2($el);
    });
}

function theSelect2($el) {
    const table = $el.data("table");
    const placeholder = $el.data("placeholder") || "Select an option";
    const valueField = $el.data("value-field") || "id";
    const textField = $el.data("text-field") || "name";
    const route = baseUrl.replace("__TABLE__", table);
    const isrequired = $el.prop("required");

    const selectedId = $el.data("selected-id");
    const selectedText = $el.data("selected-text");
    if (selectedId && selectedText) {
        const newOption = new Option(selectedText, selectedId, true, true);
        $el.append(newOption).trigger("change");
    }

    const getName = $el.attr("unique-name") || "";

    var allParam = $el[0].attributes;
    var parameters = {};

    // Initialize Select2
    // $el.select2({
    //     placeholder,
    //     allowClear: !isrequired,
    //     width: "100%",
    //     ajax: {
    //         url: route,
    //         dataType: "json",
    //         delay: 250,
    //         data: params => ({
    //             data: $.trim(params.term),
    //             valueField,
    //             textField,
    //         }),
    //         processResults: data => ({ results: data }),
    //         cache: true,
    //     },
    //     escapeMarkup: markup => markup,
    //     templateResult: data => data.text,
    //     templateSelection: function (data) {
    //         if (!data.id) return data.text;

    //         const html = $("<div>").html(data.text);
    //         const span = html.find("span.select2-option");
    //         const codeEl = span.find(".select2-option-code");
    //         if (!span.length || !codeEl.length) return data.text;

    //         const code = codeEl.text().trim();
    //         const description = span
    //             .contents()
    //             .filter(function () {
    //                 return this.nodeType === 3;
    //             })
    //             .text()
    //             .trim();

    //         const fullText = `${code} | ${description}`;
    //         const selectBoxWidth = $el
    //             .next(".select2-container")
    //             .find(".select2-selection")
    //             .width();

    //         return fullText.length * 7 > selectBoxWidth - 20
    //             ? description
    //             : fullText;
    //     },
    // });
    if (typeof table !== "undefined" && table !== null) {
        $el.select2({
            placeholder,
            allowClear: !isrequired,
            width: "100%",
            ajax: {
                url: route,
                dataType: "json",
                delay: 250,
                data: params => {
                    let exceptionValues = $('select[unique-name="' + getName + '"]')
                        .map(function () {
                            return $(this).val();
                        })
                        .get();

                    $.each(allParam, function () {
                        if (this.specified) {
                            if (this.name.indexOf("data-class-") >= 0) {
                                parameters[
                                    this.name.replace("data-class-", "")
                                ] = $("." + this.value).val();
                            } else if (this.name.indexOf("data-id-") >= 0) {
                                parameters[
                                    this.name.replace("data-id-", "")
                                ] = $("#" + this.value).val();
                            } else if (this.name.indexOf("param-") >= 0) {
                                parameters[this.name.replace("param-", "")] =
                                    this.value;
                            }
                        }
                    });

                    return {
                        data: $.trim(params.term),
                        valueField,
                        textField,
                        exceptionValues: exceptionValues,
                        parameter: parameters,
                    };
                },
                processResults: data => ({ results: data }),
                cache: true,
            },
            escapeMarkup: markup => markup,
            templateResult: data => data.text,
            templateSelection: function (data) {
                if (!data.id) return data.text;

                const html = $("<div>").html(data.text);
                const span = html.find("span.select2-option");
                const codeEl = span.find(".select2-option-code");
                if (!span.length || !codeEl.length) return data.text;

                const code = codeEl.text().trim();
                const description = span
                    .contents()
                    .filter(function () {
                        return this.nodeType === 3;
                    })
                    .text()
                    .trim();

                const fullText = `${code} | ${description}`;
                const selectBoxWidth = $el
                    .next(".select2-container")
                    .find(".select2-selection")
                    .width();

                return fullText.length * 7 > selectBoxWidth - 20
                    ? description
                    : fullText;
            },
        });
    } else {
        $el.select2({
            placeholder,
            allowClear: !isrequired,
            width: "100%",
        })

    }
}