const utils = {
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

};


