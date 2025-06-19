@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="max-w-lg mx-auto mt-12 bg-white p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 text-center">User Profile</h1>
    <div class="mb-6 flex justify-center">
        <div class="h-20 w-20 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-bold">
            {{ strtoupper(substr($user->first_name,0,1)) }}{{ strtoupper(substr($user->last_name,0,1)) }}
        </div>
    </div>
    <div class="space-y-4">
        <div>
            <span class="block text-gray-600 text-sm">Full Name</span>
            <span class="block text-lg font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</span>
        </div>
        <div>
            <span class="block text-gray-600 text-sm">Email</span>
            <span class="block text-lg font-semibold text-gray-900">{{ $user->email }}</span>
        </div>
        <div>
            <span class="block text-gray-600 text-sm">Mobile</span>
            <span class="block text-lg font-semibold text-gray-900">{{ $user->mobile ?? '-' }}</span>
        </div>
        <div>
            <span class="block text-gray-600 text-sm">Role</span>
            <span class="block text-lg font-semibold text-gray-900">{{ ucfirst($user->role ?? 'User') }}</span>
        </div>
    </div>
    <div class="mt-8 text-center">
        <a href="/dashboard" class="btn-primary">Back to Dashboard</a>
    </div>
</div>
@endsection 