var prefixUrl = window.location.origin;
var linearIndeterminate = null;

var filePreviewElement = null;
var fileUploadElement = null;
var deleteFileElement = null;

$(document).ready(function () {
    linearIndeterminate = $("#linear-indeterminate");
});

// File Upload Change
$(document).ready(function () {
    filePreviewElement = $("#file-preview");
    fileUploadElement = $("#file-upload");
    deleteFileElement = $("#file-delete");

    filePreviewElement.on("load", function (event) {
        $(this).css("opacity", 1);
        deleteFileElement.parent().show();
    });

    filePreviewElement.on("error", function (event) {
        $(this).css("opacity", 0);
        $(this).attr("src", "");
        deleteFileElement.parent().hide();
    });

    fileUploadElement.change(function (event) {
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
        $(this).parent().hide();
        filePreviewElement.attr("src", "");
        fileUploadElement.val("");
    });
});

const files = {
    setFilePreview(src) {
        filePreviewElement.attr("src", src || "");
    },
    getFileUpload() {
        return fileUploadElement.prop("files")[0];
    },
};

function delay(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
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
};
