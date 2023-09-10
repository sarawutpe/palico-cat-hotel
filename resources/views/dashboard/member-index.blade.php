@extends('layouts.dashboard')
@section('title', 'หน้าแรก')
@section('content')

    <div class="container-lg">
        <div class="row">
            <p id="say"></p>
        </div>
        <!-- /.row-->
    </div>

    <script>
        var headers = {
            'X-CSRF-Token': "{{ csrf_token() }}"
        }
        var id = "{{ session('id') }}"
        var type = "{{ session('type') }}"

        $(document).ready(function() {
            utils.setLinearLoading('open')
            $.ajax({
                url: `${prefixApi}/api/user/profile/${type}/${id}`,
                type: "GET",
                headers: headers,
                success: function(response, textStatus, jqXHR) {
                    const value = response.data
                    $('#say').text(`สวัสดี, ${value.member_name}`);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Failed');
                }
            }).always(function() {
                utils.setLinearLoading('close')
            });
        });
    </script>
@endsection
