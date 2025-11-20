<h2>Update Profile</h2>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Name:</label>
    <input type="text" name="name" value="{{ auth()->user()->name }}" required>
    <br>

    <label>Email:</label>
    <input type="email" name="email" value="{{ auth()->user()->email }}" required>
    <br>

    <label>Password (change if needed):</label>
    <input type="password" name="password">
    <br>

    <label>Confirm Password:</label>
    <input type="password" name="password_confirmation">
    <br>

    <label>Profile Photo:</label>
    <input type="file" name="profile_photo">
    <br>

    @if(auth()->user()->profile_photo)
        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" width="120" style="margin-top:10px;">
    @endif

    <br><br>

    <button type="submit">Update Profile</button>
</form>
