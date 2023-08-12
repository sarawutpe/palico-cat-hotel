var linearIndeterminate = null;

var filePreviewElement = null;
var fileUploadElement = null;
var deleteFileElement = null;

var isRemovedFile = false;

var callSearchFunc = null;
var prefixApi = window.location.origin;

$(document).ready(function () {
    linearIndeterminate = $("#linear-indeterminate");

    // Debounced Search
    $('input[name="search_input"]').on("input", function (e) {
        debouncedSearch(e.target.value);
    });

    const debouncedSearch = debounce(function (searchTerm) {
        setQueryParameter("q", searchTerm);
        callSearchFunc();
    }, 500);
});

// File Upload Change
function fileToolkit() {
    filePreviewElement = $("#file-preview");
    fileUploadElement = $("#file-upload");
    deleteFileElement = $("#file-delete");

    filePreviewElement.on("load", function (event) {
        isRemovedFile = false;

        $(this).css("opacity", 1);
        deleteFileElement.parent().show();
    });

    filePreviewElement.on("error", function (event) {
        $(this).css("opacity", 0);
        $(this).attr("src", "");
        deleteFileElement.parent().hide();
    });

    fileUploadElement.change(function (event) {
        isRemovedFile = false;

        const fileInput = event.target;

        if (fileInput && fileInput.files) {
            const file = fileInput.files[0];
            const imageURL = URL.createObjectURL(file);

            filePreviewElement.attr("src", imageURL);
        } else {
            filePreviewElement.attr("src", "");
        }
    });

    deleteFileElement.click(function (event) {
        isRemovedFile = true;
        $(this).parent().hide();
        filePreviewElement.attr("src", "");
        fileUploadElement.val("");
    });
}

$(document).ready(function () {
    fileToolkit();
});

function setQueryParameter(key, value) {
    const urlSearchParams = new URLSearchParams(window.location.search);
    urlSearchParams.set(key, value);
    const newUrl =
        window.location.origin +
        window.location.pathname +
        "?" +
        urlSearchParams.toString();
    window.history.pushState({ path: newUrl }, "", newUrl);
}

const files = {
    setFilePreview(src) {
        filePreviewElement.attr("src", src || "");
    },
    getFileUpload() {
        if (!fileUploadElement.prop("files")) return;

        return fileUploadElement.prop("files")[0];
    },
};

function delay(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

// Debug: form data
function logFormData(data) {
    if (data instanceof FormData) {
        const formDataArray = [];

        for (const pair of data.entries()) {
            formDataArray.push({ Field: pair[0], Value: pair[1] });
        }

        console.table(formDataArray);
    }
}
const utils = {
    jsonString(jsonData) {
        return typeof jsonData === "object"
            ? `'${JSON.stringify(jsonData).replace(/"/g, "&quot;")}'`
            : "invalid json";
    },
    confirmAlert() {
        return new Promise((resolve, reject) => {
            Swal.fire({
                title: "<strong>คุณต้องการลบข้อมูลหรือไม่?</strong>",
                icon: "info",
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
            }).then((result) => {
                if (result.isConfirmed) {
                    resolve(true);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    reject(false);
                }
            });
        });
    },
    loading(action) {
        if (action === "open") {
            let timerInterval;
            Swal.fire({
                timerProgressBar: true,
                showCloseButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    const b = Swal.getHtmlContainer().querySelector("b");
                    timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft();
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                },
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log("I was closed by the timer");
                }
            });
        }

        if (action === "close") {
            Swal.close();
        }
    },
    setLinearLoading() {
        linearIndeterminate.toggleClass("active");
    },
    showAlert(elementId, color, message) {
        const target = $(elementId);
        const colorClass = color === "success" ? "text-success" : "text-danger";
        let html = "";

        if (Array.isArray(message)) {
            message.forEach((item) => (html += `<li>${item}</li>`));
        } else {
            html = message || "";
        }
        target
            .empty()
            .append(
                `<div class="${colorClass} font-medium mb-2"><ul>${html}</ul></div>`
            );
    },
    reRenderForm(object) {
        const targetForm = $(formId);

        $("#file-preview")
            .css("opacity", 1)
            .attr("src", `${storagePath}/${employee.employee_img || ""}`);

        // if (targetForm.length > 0) {
        //     console.log(targetForm)
        // }
        // console.log(f)

        // $(selector).val(value);

        for (const key in object) {
            let input = $(`input[name="${key}"]`);
            let value = object[key];

            if (input && value) {
                input.val(value);
            }
        }
    },
};
