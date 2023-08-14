@extends('layouts.dashboard')
@section('title', 'การจองของฉัน')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col">
                <div class="col h-100">
                    <fieldset class="scroll">
                        <legend>จัดการการจอง</legend>
                        <div id="alert-message"></div>
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" tabindex="0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">เลขที่ใบจอง</th>
                                        <th scope="col">วันที่</th>
                                        <th scope="col">ห้อง</th>
                                        <th scope="col">วันที่เช็คอิน</th>
                                        <th scope="col">วันที่เช็คเอาท์</th>
                                        <th scope="col">ระยะเวลา</th>
                                        <th scope="col">สถานะการจ่ายเงิน</th>
                                        <th scope="col">สถานะการจอง</th>
                                        <th scope="col">การชำระเงิน</th>
                                    </tr>
                                </thead>
                                <tbody id="rent-list"></tbody>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="pay-receipt-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pay-receipt-modal">การชำระเงิน</h5>
                        <div class="icon-button">
                            <i class="fa-solid fa-close fa-xs align-middle"></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p id="pay-receipt-datetime" class="mb-2">วันที่ชำระเงิน</p>
                        <img id="pay-receipt-qr" src="" alt="" width="100%" height="350px"
                            style="display: none; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage') }}"
        var id = "{{ session('id') }}"

        $(document).ready(async function() {
            await handleGetAllRent()
        })

        function handleGetAllRent() {
            return new Promise((resolve, reject) => {
                utils.setLinearLoading('open')
                $.ajax({
                    url: `${prefixApi}/api/rent/member/${id}`,
                    type: "GET",
                    headers: headers,
                    success: function(response, textStatus, jqXHR) {
                        if (!Array.isArray(response.data)) return
                        rents = response.data
                        let html = ''
                        response.data.forEach(function(rent, index) {
                            const diff = dayjs(rent.outDatetime).diff(rent.inDatetime, 'day')
                            const displayDiff = diff === 0 ? 1 : diff
                            html += `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${rent.rent_id}</td>
                                <td>${formatDate(rent.rent_datetime)}</td>
                                <td>${rent.room.room_name}</td>
                                <td>${formatDate(rent.in_datetime)}</td>
                                <td>${formatDate(rent.out_datetime)}</td>
                                <td>${displayDiff} วัน</td>
                                <td>${formatPayStatus(rent.pay_status)}</td>
                                <td>${formatRentStatus(rent.rent_status)}</td>
                                <td>
                                    <div class="icon-button" onclick="handleOpenPayReceipt('${rent.rent_id}')">
                                        <i class="fa-solid fa-magnifying-glass fa-xs align-middle"></i>
                                    </div>
                                </td>
                            </tr>`;
                        });
                        $('#rent-list').empty().append(html);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error();
                    }
                }).always(async function() {
                    resolve()
                    await delay(1000)
                    utils.setLinearLoading('close')
                });
            });
        }

        async function handleOpenPayReceipt(id) {
            try {
                utils.setLinearLoading('open');

                const response = await $.ajax({
                    url: `${prefixApi}/api/pay-receipt/${id}`,
                    type: "GET",
                    headers: headers
                });

                const data = response.data;
                if (data && data.pay_receipt_datetime && data.pay_receipt_qr) {
                    $('#pay-receipt-datetime').text(`วันที่ชำระเงิน ${formatDate(data.pay_receipt_datetime)}`);
                    $('#pay-receipt-qr').show().attr('src', `${storagePath}/${data.pay_receipt_qr}`);
                } else {
                    $('#pay-receipt-datetime').text(`ไม่พบข้อมูล`);
                    $('#pay-receipt-qr').hide()
                }

                $('#pay-receipt-modal').modal('show')
            } catch (error) {
                toastr.error();
            } finally {
                await delay(1000);
                utils.setLinearLoading('close');
            }
        }
    </script>
@endsection
