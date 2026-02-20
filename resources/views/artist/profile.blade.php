@extends('artist.layouts.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-2xl space-y-6">
    <!-- Profile Info -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Profile Information</h2>

        @if ($errors->any() && !$errors->has('current_password'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        @if(!str_contains($error, 'password'))
                            <li>{{ $error }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('artist.profile.update') }}" method="POST" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" id="firstName" name="firstName" value="{{ old('firstName', $artist->firstName) }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
                </div>
                <div>
                    <label for="midName" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                    <input type="text" id="midName" name="midName" value="{{ old('midName', $artist->midName) }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none">
                </div>
                <div>
                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text" id="lastName" name="lastName" value="{{ old('lastName', $artist->lastName) }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $artist->email) }}"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
            </div>

            <div>
                <label for="artBio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea id="artBio" name="artBio" rows="4"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none">{{ old('artBio', $artist->artBio) }}</textarea>
            </div>

            <button type="submit" class="bg-ochre hover:bg-ochre/90 text-white px-6 py-2 rounded-lg transition-colors">
                Update Profile
            </button>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Change Password</h2>

        @if ($errors->has('current_password') || $errors->has('password'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside text-sm">
                    @if($errors->has('current_password'))
                        <li>{{ $errors->first('current_password') }}</li>
                    @endif
                    @if($errors->has('password'))
                        <li>{{ $errors->first('password') }}</li>
                    @endif
                </ul>
            </div>
        @endif

        <form action="{{ route('artist.password.update') }}" method="POST" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" id="current_password" name="current_password"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-ochre focus:border-transparent outline-none" required>
                </div>
            </div>

            <button type="submit" class="bg-ochre hover:bg-ochre/90 text-white px-6 py-2 rounded-lg transition-colors">
                Change Password
            </button>
        </form>
    </div>
</div>
@endsection
