@extends('layouts.home')

@section('content')
<div class="container-lg">
	<h1>Create Member</h1>
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
</div>
@endsection