var prefixApi = window.location.origin;
var linearIndeterminate = null;
var filePreviewElement = null;
var fileUploadElement = null;
var deleteFileElement = null;
var fileMessageElement = null;
var isRemovedFile = false;
var callSearchFunc = null;

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
    fileMessageElement = $("#file-message");

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

function formatDate(dateTime = "") {
    if (!dayjs(dateTime).isValid()) return "invalid date";

    // return dayjs(dateTime).format('YYYY-MM-DDTHH:mm');

    dayjs.extend(dayjs_plugin_buddhistEra);
    return dayjs(dateTime).format("DD-MM-BBBB HH:MM");
}

function formatRentStatus(type = "") {
    switch (type) {
        case "PENDING":
            return "กำลังรอ";
        case "RESERVED":
            return "จองแล้ว";
        case "CHECKED_IN":
            return "เช็คอิน";
        case "CHECKED_OUT":
            return "เช็คเอาท์";
        case "COMPLETED":
            return "เสร็จสิ้น";
        case "CANCELED":
            return "ยกเลิก";
        default:
            return "ประเภทไม่ถูกต้อง";
    }
}

function formatPayStatus(type = "") {
    switch (type) {
        case "PENDING":
            return "กำลังรอ";
        case "PAYING":
            return "จ่ายแล้ว";
        case "COMPLETED":
            return "เสร็จสิ้น";
        case "CANCELED":
            return "ยกเลิก";
        default:
            return "ประเภทไม่ถูกต้อง";
    }
}

function formatRoomType(type = "") {
    switch (type) {
        case "S":
            return "ขนาดเล็ก";
        case "M":
            return "ขนาดกลาง";
        case "L":
            return "ขนาดใหญ่";
        default:
            return "ประเภทไม่ถูกต้อง";
    }
}

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
    setMessage(type, message) {
        fileMessageElement.addClass("text-danger").text(message);
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
    showDialog(message) {
        return new Promise((resolve, reject) => {
            Swal.fire({
                title: `<strong>${message}</strong>`,
                icon: "info",
                showCloseButton: true,
                showCancelButton: false,
                showConfirmButton: false,
                focusConfirm: false,
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
            }).then((result) => {
                resolve();
            });
        });
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
    setLinearLoading(action = "") {
        if (action === "open") {
            linearIndeterminate.addClass("active");
        } else if (action === "close") {
            linearIndeterminate.removeClass("active");
        }
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
    clearAlert(elementId) {
        const target = $(elementId);
        target.empty();
    },
};

function pdf() {
    // Define a custom Thai font
    pdfMake.fonts = {
        ThaiFont: {
            normal: "{{ asset('fonts/DBHeavent.ttf') }}", // Replace with the path to your Thai font file
        },
    };

    // Your PDF document definition
    var docDefinition = {
        content: [
            {
                text: "สวัสดี, pdfMake!",
                font: "ThaiFont",
            }, // Use the Thai font here
        ],
    };

    // Create a PDF document
    pdfMake.createPdf(docDefinition).download("output.pdf");
}
