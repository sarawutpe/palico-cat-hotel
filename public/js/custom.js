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

function formatDate(date = "") {
    const newDate = dayjs(date);
    if (!newDate.isValid()) return "invalid date";

    dayjs.extend(dayjs_plugin_buddhistEra);
    return newDate.format("DD-MM-BBBB");
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
        case "CANCELED":
            return "ยกเลิก";
        default:
            return "ประเภทไม่ถูกต้อง";
    }
}

function formatRoomType(type = "") {
    switch (type) {
        case "S":
            return "ห้องเล็ก";
        case "M":
            return "ห้องกลาง";
        case "L":
            return "ห้องใหญ่";
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
    showDialog(message, icon = "") {
        return new Promise((resolve, reject) => {
            Swal.fire({
                title: `<strong>${message}</strong>`,
                icon: icon ? icon : "info",
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
                title: "กำลังตรวจสอบข้อมูล",
                timerProgressBar: true,
                showCloseButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
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

function printPdf(table) {
    try {
        if (typeof table !== "object") return;

        utils.setLinearLoading("open");
        pdfMake.fonts = {
            DBHeavent: {
                normal: "DBHeavent.ttf",
                medium: "DBHeavent-Med.ttf",
                bold: "DBHeavent-Bold.ttf",
            },
            Roboto: {
                normal: "Roboto-Regular.ttf",
                bold: "Roboto-Bold.ttf",
                italics: "Roboto-Italic.ttf",
                bolditalics: "Roboto-BoldItalic.ttf",
            },
        };

        // Your PDF document definition
        var docDefinition = {
            pageOrientation: "portrait",
            pageSize: "A4",
            defaultStyle: {
                font: "DBHeavent",
                fontSize: 16,
            },
            header: function (currentPage, pageCount) {
                return {
                    text: `หน้า ${currentPage}`,
                    fontSize: 14,
                    alignment: "right",
                    margin: [35, 10],
                };
            },
            content: [
                {
                    stack: [
                        {
                            text: "รายงานข้อมูลพนักงาน",
                            fontSize: 18,
                            bold: true,
                            alignment: "center",
                        },
                        {
                            text: `วันที่พิมพ์ ${formatDate(dayjs().format())}`,
                            fontSize: 14,
                            alignment: "right",
                            margin: [0, 0, 0, 10],
                        },
                    ],
                },
                {
                    table: table,
                    layout: {
                        hLineWidth: function (i, node) {
                            return 1;
                        },
                        vLineWidth: function (i) {
                            return 1;
                        },
                        hLineColor: function () {
                            return "#ddd";
                        },
                        vLineColor: function () {
                            return "#ddd";
                        },
                    },
                },
            ],
        };

        function progressCallback(progress) {
            if (progress === 1) {
                setTimeout(() => {
                    utils.setLinearLoading("close");
                }, 500);
            }
        }

        pdfMake.createPdf(docDefinition).download("output.pdf", null, {
            progressCallback: progressCallback,
        });
    } catch (error) {
        console.log(error);
    }
}
