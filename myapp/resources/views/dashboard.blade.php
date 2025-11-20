<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex">

    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg h-screen p-5">
        <h2 class="text-xl font-bold mb-6">My Dashboard</h2>

        <ul class="space-y-4">
            <li>
                <a href="/dashboard" class="block p-2 rounded hover:bg-gray-200">Dashboard</a>
            </li>
            <li>
                <a href="/profile" class="block p-2 rounded hover:bg-gray-200">Edit Profile</a>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block w-full text-left p-2 rounded hover:bg-red-200 text-red-600">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">

        <h1 class="text-3xl font-bold mb-6">Welcome, {{ auth()->user()->name }} 👋</h1>

        <!-- Profile Card -->
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-6">

            <!-- Profile Photo -->
            <div>
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                         class="w-24 h-24 rounded-full border">
                @else
                    <img src="https://via.placeholder.com/120"
                         class="w-24 h-24 rounded-full border">
                @endif
            </div>

            <!-- User Info -->
            <div>
                <h2 class="text-2xl font-bold">{{ auth()->user()->name }}</h2>
                <p class="text-gray-600">{{ auth()->user()->email }}</p>
                <p class="text-gray-500 text-sm mt-1">Member since:
                    {{ auth()->user()->created_at->format('d M, Y') }}
                </p>
            </div>

        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">

            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold">Posts</h3>
                <p class="text-4xl font-bold text-blue-600 mt-2">0</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold">Messages</h3>
                <p class="text-4xl font-bold text-green-600 mt-2">0</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold">Notifications</h3>
                <p class="text-4xl font-bold text-yellow-600 mt-2">0</p>
            </div>

        </div>

    </div>

</div>

</body>
</html>
