@extends('layouts.dashboard')
@section('title', 'จัดอุปกรณ์ในร้าน')

@section('content')
    <style>
        .box-card-list {
            border: 1px solid transparent;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem
        }

        .box-card-list:hover,
        .box-card-list.active {
            background: rgba(255, 255, 255, 0.50);
            border-color: #eee;
        }
    </style>

    <section class="content">

        <div class="d-flex justify-content-end">
            <div class="btn-toolbar d-none d-md-block" role="toolbar">
                <div class="btn-group btn-group-toggle mx-3" data-coreui-toggle="buttons">
                    <input class="btn-check" id="option-all" type="radio" name="option-stats" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary" onclick="handleFilter('all')">ทั้งหมด</label>
                    <input class="btn-check" id="option-is-nearly" type="radio" name="option-stats" autocomplete="off">
                    <label class="btn btn-outline-secondary" onclick="handleFilter('is-nearly')">สต๊อกใกล้หมด</label>
                    <input class="btn-check" id="option-is-out" type="radio" name="option-stats" autocomplete="off">
                    <label class="btn btn-outline-secondary" onclick="handleFilter('is-out')">หมดสต็อก</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <form id="form" class="h-100" enctype="multipart/form-data" onsubmit="handleSubmit(event)">
                    <div class="col h-100">
                        <fieldset class="scroll">
                            <legend>ข้อมูลอุปกรณ์</legend>

                            <div id="alert-message"></div>

                            <div class="">
                                <div class="row">
                                    <!-- Section 1 -->
                                    <div class="col-8">
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">ชื่ออุปกรณ์</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="product_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">สต๊อกสินค้า</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="product_stock" class="form-control"
                                                    value="0">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label required">สถานะ</label>
                                            <div class="col-sm-9">
                                                <div class="d-flex gap-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_active"
                                                            id="is_active" value="1" checked>
                                                        <label class="form-check-label mt-1" for="is_active">
                                                            จำหน่าย
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="is_active"
                                                            id="is_active_2" value="0">
                                                        <label class="form-check-label mt-1" for="is_active_2">
                                                            ยกเลิก
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-sm-3 col-form-label">ข้อมูลเพิ่มเติม</label>
                                            <textarea name="product_detail" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <!-- Section 2 Upload -->
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <div class="upload-block">
                                                <div class="preview-img-block">
                                                    <img id="file-preview" src="" style="opacity: 0;">
                                                </div>
                                                <div class="btn-img-block">
                                                    <div class="btn btn-secondary position-relative w-100">
                                                        <input type="file" id="file-upload" name="product_img"
                                                            accept="image/png, image/jpeg"
                                                            class="position-absolute opacity-0 w-100 h-100"
                                                            style="top: 0; left: 0; cursor: pointer;">
                                                        <span class="mx-1">อัพโหลด</span>
                                                        <i class="fa-solid fa-upload fa-xs align-middle"></i>
                                                    </div>
                                                    <div class="btn btn-secondary position-relative w-100"
                                                        style="display: none;">
                                                        <input type="button" id="file-delete"
                                                            class="position-absolute opacity-0 w-100 h-100"
                                                            style="top: 0; left: 0; cursor: pointer;">
                                                        <span class="mx-1">ลบ</span>
                                                        <i class="fa-solid fa-trash fa-xs align-middle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="d-flex gap-4" style="padding: 12px">
                            <button type="button" class="btn btn-info" onclick="handleUpdateProduct()">แก้ไข</button>
                            <button type="button" class="btn btn-danger" onclick="handleDeleteProduct()">ลบ</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-6">
                <div class="">
                    <fieldset class="scroll">
                        <legend>อุปกรณ์</legend>
                        <div id="product-list"></div>
                    </fieldset>
                </div>
            </div>
        </div>
    </section>

    <script>
        var selectedId = null
        var selectedIndex = null
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var storagePath = "{{ asset('storage') }}"
        var formData = null

        // Initialize
        $(document).ready(function() {
            handleGetAllProduct()
        })

        function resetForm() {
            $("#form")[0].reset();
            selectedId = null
            files.setFilePreview()
        }

        function handleSubmit(event) {
            event.preventDefault()
            handleAddProduct()
        }

        function handleFilter(q) {
            $(`input#option-${q}`).prop("checked", true);
            debouncedSearch(q)
        }

        const debouncedSearch = debounce(function(searchTerm) {
            handleGetAllProduct(searchTerm)
        }, 250);

        function handleGetAllProduct(q = '') {
            utils.setLinearLoading('open')

            $.ajax({
                url: `${prefixApi}/api/product/list`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    if (!Array.isArray(response.data)) return

                    // Filter all, is-nearly, is-out
                    if (q === 'is-nearly') {
                        response.data = response.data.filter((item) => Number(item.product_stock) <= 10)
                    } else if (q === 'is-out') {
                        response.data = response.data.filter((item) => Number(item.product_stock) === 0)
                    }

                    let html = ''
                    response.data.forEach(function(product, index) {

                        const isCancel = product.is_active === 0

                        html += `
                        <div class="box-card-list" onclick="handleShowProduct(${index} ,${utils.jsonString(product)})">
                            <div>
                                <p>รหัสอุปกรณ์ ${product.product_id}</p>
                                <p>ชื่ออุปกรณ์ ${product.product_name}</p>
                                <p>สต๊อกสินค้า ${product.product_stock}</p>
                                <p class="${isCancel ? 'text-danger' : ''}">สถานะ ${isCancel ? 'ยกเลิก' : 'จำหน่าย'}</p>
                            </div>
                            <div class="border rounded bg-white" style="overflow: hidden; width: 100px; height: 100px">
                                <img id="file-preview" onerror="this.style.opacity = 0"
                                src="{{ asset('storage/') }}/${product.product_img}"
                                style="object-fit: cover;" width="100%" height="100%">
                            </div>
                        </div>`;
                    });
                    $('#product-list').empty().append(html);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Failed');
                }
            }).always(async function() {
                utils.setLinearLoading('close')
            });
        }

        function handleAddProduct() {
            formData = new FormData();
            formData.append('product_name', $('input[name="product_name"]').val());
            formData.append('product_stock', $('input[name="product_stock"]').val());
            formData.append('product_detail', $('textarea[name="product_detail"]').val());
            formData.append('is_active', $("input[name='is_active']:checked").val());

            const file = files.getFileUpload()
            if (file) {
                formData.append("product_img", file);
            }

            $.ajax({
                url: `${prefixApi}/api/product`,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    resetForm()
                    toastr.success();
                    handleGetAllProduct()
                    utils.clearAlert('#alert-message')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    utils.showAlert('#alert-message', 'error', response.errors)
                },
            });
        }

        function handleShowProduct(index, data) {
            const product = JSON.parse(data)
            if (typeof product !== 'object') return

            selectedId = product.product_id
            selectedIndex = index
            $('.box-card-list').removeClass('active').eq(index).addClass('active');

            $('input[name="product_name"]').val(product.product_name || "");
            $('input[name="product_stock"]').val(product.product_stock || 0);
            $('textarea[name="product_detail"]').val(product.product_detail || "");

            if (product.is_active == 1) {
                $('input[name="is_active"][value="1"]').prop('checked', true);
            } else {
                $('input[name="is_active"][value="0"]').prop('checked', true);
            }

            if (product.product_img) {
                $('#file-preview').css('opacity', 1).attr('src', `${storagePath}/${(product.product_img || "")}`);
            } else {
                $('#file-preview').css('opacity', 0).attr('src', '');
            }
        }

        function handleUpdateProduct() {
            if (!selectedId) return

            formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('product_name', $('input[name="product_name"]').val());
            formData.append('product_stock', $('input[name="product_stock"]').val());
            formData.append('product_detail', $('textarea[name="product_detail"]').val());
            formData.append('is_active', $("input[name='is_active']:checked").val());

            const url = new URL(`${prefixApi}/api/product/${selectedId}`);
            const file = files.getFileUpload()
            if (file) {
                formData.append("product_img", file);
            } else if (!file && isRemovedFile) {
                url.searchParams.set('set', 'file_null')
            }

            $.ajax({
                url: url,
                type: "POST",
                headers: headers,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, jqXHR) {
                    toastr.success();
                    handleGetAllProduct()
                    resetForm()
                    utils.clearAlert('#alert-message')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    const response = jqXHR.responseJSON
                    utils.showAlert('#alert-message', 'error', response.errors)
                },
            });
        }

        async function handleDeleteProduct() {
            if (!selectedId) return

            const confirm = await utils.confirmAlert();
            if (confirm) {
                $.ajax({
                    url: `${prefixApi}/api/product/${selectedId}`,
                    type: "DELETE",
                    headers: headers,
                    success: function(data, textStatus, jqXHR) {
                        resetForm()
                        toastr.success('', '')
                        handleGetAllProduct()
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.success('', '')
                    },
                })
            }
        }
    </script>

@endsection
