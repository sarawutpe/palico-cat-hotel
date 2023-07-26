@extends('layouts.home')

@section('content')

<div class="container-lg">
	<h2>สมัครสมาชิก</h2>

	<a href="{{ route('members.create') }}" class="btn btn-primary">เพิ่มสมาชิก</a>

	<form action="{{ route('members.store') }}" method="POST">
		@csrf
		<div class="form-group">
			<label for="member_name">Member Name</label>
			<input type="text" name="member_name" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="member_user">Member User</label>
			<input type="text" name="member_user" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="member_pass">Member Password</label>
			<input type="password" name="member_pass" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="member_address">Member Address</label>
			<input type="text" name="member_address" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="member_phone">Member Phone</label>
			<input type="text" name="member_phone" class="form-control" required>
		</div>
		<div class="form-group">
			<label for="member_facebook">Member Facebook</label>
			<input type="text" name="member_facebook" class="form-control">
		</div>
		<div class="form-group">
			<label for="member_lineid">Member Line ID</label>
			<input type="text" name="member_lineid" class="form-control">
		</div>
		<!-- Add more fields as needed -->
		<button type="submit" class="btn btn-primary">Save</button>
	</form>

	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#exampleModal">
		Launch demo modal
	</button>

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form onsubmit="handleSubmit()" action="{{ route('members.store') }}" method="POST">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title" id="exampleModalLabel">Modal title</h3>
						<button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="member_name" style="color: rgb(118, 121, 122)">Member Name</label>
							<input type="text" name="member_name" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="member_user">Member User</label>
							<input type="text" name="member_user" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="member_pass">Member Password</label>
							<input type="password" name="member_pass" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="member_address">Member Address</label>
							<input type="text" name="member_address" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="member_phone">Member Phone</label>
							<input type="text" name="member_phone" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="member_facebook">Member Facebook</label>
							<input type="text" name="member_facebook" class="form-control">
						</div>
						<div class="form-group">
							<label for="member_lineid">Member Line ID</label>
							<input type="text" name="member_lineid" class="form-control">
						</div>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
					<div class="modal-footer">
						<button type="button" id="close">close</button>
						<!-- <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button> -->
						<button type="submit" id="sub" class="btn btn-primary">Save changes</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {

		$('#close').click(function() {
			$('#exampleModal').modal('hide');
		});

		// $('#close').modal('hide');

		// function handleSubmit() {
		// }

		// Your code goes here
		var sub = $('#sub')
		console.log(sub)
	});
</script>

@endsection