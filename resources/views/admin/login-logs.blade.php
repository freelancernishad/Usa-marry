<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Login Reports - Admin Dashboard</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <!-- Alpine.js (Optional but great for dynamic components) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Outfit', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="antialiased text-slate-800">

    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-600 text-white p-2 rounded-xl shadow-md shadow-indigo-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight text-slate-900">Usa Marry</h1>
                            <p class="text-xs text-slate-500 font-medium">Security & Activity Portal</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ url('/') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                            Back to Site &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">User Login Logs</h2>
                    <p class="mt-1 text-sm text-slate-500">Track and monitor user login frequency, patterns, locations, and sessions.</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Stat Card 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Logins Recorded</p>
                        <h4 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalLoginsCount) }}</h4>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unique Logged-In Users</p>
                        <h4 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalUniqueUsersCount) }}</h4>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                    <div class="p-4 bg-amber-50 text-amber-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider truncate">Most Active User</p>
                        @if($mostActiveUser)
                            <h4 class="text-base font-bold text-slate-800 mt-1 truncate" title="{{ $mostActiveUser->name }} (ID: {{ $mostActiveUser->profile_id }})">
                                {{ $mostActiveUser->name }}
                            </h4>
                            <p class="text-xs text-slate-500 font-medium">{{ $mostActiveUserCount }} Logins</p>
                        @else
                            <h4 class="text-2xl font-bold text-slate-800 mt-1">-</h4>
                        @endif
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                    <div class="p-4 bg-rose-50 text-rose-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider truncate">Last Activity</p>
                        @if($lastLoginLog && $lastLoginLog->user)
                            <h4 class="text-base font-bold text-slate-800 mt-1 truncate" title="By {{ $lastLoginLog->user->name }}">
                                {{ $lastLoginLog->user->name }}
                            </h4>
                            <p class="text-xs text-slate-500 font-medium">
                                {{ $lastLoginLog->logged_in_at ? $lastLoginLog->logged_in_at->diffForHumans() : 'N/A' }}
                            </p>
                        @else
                            <h4 class="text-2xl font-bold text-slate-800 mt-1">-</h4>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ detailUserId: null, isDrawerOpen: false }">
                
                <!-- Filter Bar -->
                <div class="p-5 border-b border-slate-200 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <form method="GET" action="{{ route('admin.login-logs') }}" class="w-full md:w-96 flex gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, profile ID..." class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-xl bg-white text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-sm transition-colors">
                            Search
                        </button>
                        @if(request('search') || request('sort'))
                            <a href="{{ route('admin.login-logs') }}" class="border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-3 py-2 rounded-xl transition-colors flex items-center justify-center">
                                Reset
                            </a>
                        @endif
                    </form>

                    <div class="flex items-center gap-3">
                        <div class="text-sm font-medium text-slate-500">Sort by:</div>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_login_at', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                            Last Login
                            @if(request('sort', 'last_login_at') === 'last_login_at')
                                <span>{{ request('order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'login_count', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                            Login Count
                            @if(request('sort') === 'login_count')
                                <span>{{ request('order', 'desc') === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                                <th class="py-4 px-6">User details</th>
                                <th class="py-4 px-6 text-center">Login Frequency</th>
                                <th class="py-4 px-6">Last Login Status</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <!-- User Column -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex-shrink-0">
                                                @if($user->gender === 'Female')
                                                    <img class="w-11 h-11 rounded-full object-cover border-2 border-rose-100 bg-rose-50" src="{{ url('files/female.jpeg') }}" alt="Female User">
                                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-rose-400"></span>
                                                @else
                                                    <img class="w-11 h-11 rounded-full object-cover border-2 border-indigo-100 bg-indigo-50" src="{{ url('files/male.jpeg') }}" alt="Male User">
                                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-indigo-400"></span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                                <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-x-2 gap-y-1">
                                                    <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-mono font-medium">ID: {{ $user->profile_id }}</span>
                                                    <span class="text-slate-300">|</span>
                                                    <span class="flex items-center gap-1 text-slate-600">
                                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                        <!--email_off-->{{ $user->email }}<!--/email_off-->
                                                    </span>
                                                    @if($user->phone)
                                                        <span class="text-slate-300">|</span>
                                                        <span class="flex items-center gap-1 text-slate-600">
                                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                            {{ $user->phone }}
                                                        </span>
                                                    @endif
                                                    @if($user->whatsapps)
                                                        <span class="text-slate-300">|</span>
                                                        <span class="flex items-center gap-1 text-emerald-600 font-medium">
                                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.5-5.739-1.451L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.417 9.863-9.864.003-2.637-1.023-5.115-2.89-6.984-1.867-1.868-4.35-2.899-6.983-2.9-5.443 0-9.867 4.419-9.87 9.867-.002 1.761.464 3.483 1.353 5.015l-.995 3.637 3.73-.978zm11.238-6.84c-.301-.151-1.78-.878-2.057-.978-.277-.1-.479-.15-.68.151-.2.301-.777.978-.952 1.178-.176.2-.352.226-.653.075-.301-.151-1.272-.469-2.423-1.496-.895-.798-1.5-1.783-1.676-2.084-.176-.301-.019-.464.131-.614.135-.135.301-.352.453-.527.151-.176.201-.301.302-.502.1-.2.05-.377-.025-.527-.075-.151-.68-1.631-.931-2.232-.245-.589-.493-.509-.68-.519-.176-.009-.377-.01-.578-.01-.2 0-.527.075-.803.377-.276.301-1.055 1.028-1.055 2.509 0 1.48 1.08 2.91 1.23 3.11.15.2 2.124 3.243 5.147 4.547.719.31 1.28.496 1.719.636.721.23 1.377.197 1.896.12.578-.087 1.78-.728 2.031-1.43.251-.702.251-1.303.176-1.43-.075-.127-.276-.201-.577-.352z"/></svg>
                                                            {{ $user->whatsapps }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Login Count -->
                                    <td class="py-4 px-6 text-center">
                                        <div class="inline-flex flex-col items-center">
                                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full">
                                                {{ $user->login_count }} {{ Str::plural('Time', $user->login_count) }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Last Login Date & Time -->
                                    <td class="py-4 px-6">
                                        @if($user->last_login_at)
                                            <div>
                                                <div class="font-semibold text-slate-800">
                                                    {{ \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y') }}
                                                    <span class="text-xs text-slate-500 font-normal">at {{ \Carbon\Carbon::parse($user->last_login_at)->format('h:i A') }}</span>
                                                </div>
                                                <div class="text-xs text-indigo-600 font-semibold mt-0.5">
                                                    {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-xs italic">Never logged in</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-6 text-right">
                                        <button 
                                            @click="detailUserId = {{ $user->id }}; isDrawerOpen = true; fetchLogs({{ $user->id }})"
                                            class="inline-flex items-center gap-1 px-3.5 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:text-indigo-600 hover:border-indigo-300 bg-white hover:bg-indigo-50/30 transition-all shadow-sm focus:outline-none"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Activity Logs
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-400">
                                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <p class="font-medium text-slate-600">No login logs found</p>
                                        <p class="text-xs text-slate-400 mt-1">Try resetting search or adjusting search query.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                        {{ $users->links() }}
                    </div>
                @endif

                <!-- Dynamic Slide-over Drawer for Log History -->
                <div 
                    x-show="isDrawerOpen" 
                    class="fixed inset-0 overflow-hidden z-50" 
                    aria-labelledby="slide-over-title" 
                    role="dialog" 
                    aria-modal="true"
                    x-cloak
                >
                    <div class="absolute inset-0 overflow-hidden">
                        <!-- Background backdrop with fade transition -->
                        <div 
                            x-show="isDrawerOpen"
                            x-transition:enter="ease-in-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in-out duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            @click="isDrawerOpen = false" 
                            class="absolute inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" 
                            aria-hidden="true"
                        ></div>

                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                            <!-- Drawer panel with slide-in transition -->
                            <div 
                                x-show="isDrawerOpen"
                                x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
                                x-transition:enter-start="translate-x-full"
                                x-transition:enter-end="translate-x-0"
                                x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
                                x-transition:leave-start="translate-x-0"
                                x-transition:leave-end="translate-x-full"
                                class="pointer-events-auto w-screen max-w-md"
                            >
                                <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl border-l border-slate-200">
                                    <!-- Drawer Header -->
                                    <div class="px-6 py-6 bg-slate-50 border-b border-slate-150 flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-bold text-slate-900" id="slide-over-title">Activity Timeline</h2>
                                            <p class="text-xs text-slate-500 mt-0.5">Session history and details</p>
                                        </div>
                                        <button 
                                            @click="isDrawerOpen = false" 
                                            type="button" 
                                            class="rounded-lg p-1.5 text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition-colors"
                                        >
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- User Info Block inside Drawer -->
                                    <div class="p-6 border-b border-slate-100 bg-white" id="drawer-user-info">
                                        <div class="flex items-center gap-3">
                                            <div id="drawer-avatar"></div>
                                            <div>
                                                <h3 class="font-bold text-slate-900" id="drawer-user-name">Loading...</h3>
                                                <p class="text-xs text-slate-500" id="drawer-user-email">...</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Drawer Timeline Content -->
                                    <div class="relative flex-1 py-6 px-6" id="drawer-content">
                                        <div id="loading-spinner" class="flex flex-col items-center justify-center h-48 text-slate-400">
                                            <svg class="animate-spin h-8 w-8 text-indigo-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-xs font-semibold">Retrieving session records...</span>
                                        </div>

                                        <div id="logs-timeline" class="hidden flow-root">
                                            <ul role="list" class="-mb-8" id="logs-list-items">
                                                <!-- List populated dynamically via JS -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- AJAX Drawer Handling Scripts -->
    <script>
        function fetchLogs(userId) {
            // Reset modal layout
            document.getElementById('loading-spinner').classList.remove('hidden');
            document.getElementById('logs-timeline').classList.add('hidden');
            document.getElementById('drawer-user-name').innerText = 'Loading...';
            document.getElementById('drawer-user-email').innerText = '...';
            document.getElementById('drawer-avatar').innerHTML = '';

            // Fetch
            fetch(`/admin/login-logs/${userId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Set User Info
                        document.getElementById('drawer-user-name').innerText = data.user.name;
                        document.getElementById('drawer-user-email').innerText = `Profile ID: ${data.user.profile_id} | ${data.user.email}`;
                        
                        // Set Avatar
                        const isFemale = data.user.gender === 'Female';
                        const avatarUrl = isFemale ? "{{ url('files/female.jpeg') }}" : "{{ url('files/male.jpeg') }}";
                        const borderClass = isFemale ? 'border-rose-100 bg-rose-50' : 'border-indigo-100 bg-indigo-50';
                        document.getElementById('drawer-avatar').innerHTML = `
                            <img class="w-12 h-12 rounded-full object-cover border-2 ${borderClass}" src="${avatarUrl}" alt="Profile avatar">
                        `;

                        // Render Logs
                        const listContainer = document.getElementById('logs-list-items');
                        listContainer.innerHTML = '';

                        if (data.logs.length === 0) {
                            listContainer.innerHTML = `
                                <li class="py-6 text-center text-slate-400 text-xs">
                                    No recorded logs available.
                                </li>
                            `;
                        } else {
                            data.logs.forEach((log, index) => {
                                const isLast = index === data.logs.length - 1;
                                const lineHtml = isLast ? '' : `
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                `;

                                // Simple parsing for browser agent icon/text
                                let deviceIcon = `
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                `;
                                if (log.device && (log.device.toLowerCase().includes('phone') || log.device.toLowerCase().includes('mobile') || log.device.toLowerCase().includes('android') || log.device.toLowerCase().includes('iphone'))) {
                                    deviceIcon = `
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    `;
                                }

                                const itemHtml = `
                                    <li>
                                        <div class="relative pb-8">
                                            ${lineHtml}
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center ring-8 ring-white">
                                                        ${deviceIcon}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0 pt-1.5">
                                                    <div class="text-xs text-slate-500 font-semibold flex justify-between">
                                                        <span>Logged in from <span class="text-slate-800 font-bold">${log.ip_address}</span></span>
                                                        <span class="text-slate-400 font-normal">${log.relative_time}</span>
                                                    </div>
                                                    <p class="text-xs text-slate-500 mt-1 font-medium">
                                                        Location: <span class="text-slate-700">${log.location}</span>
                                                    </p>
                                                    <p class="text-[10px] font-mono text-slate-400 mt-0.5 truncate" title="${log.device}">
                                                        ${log.device}
                                                    </p>
                                                    <p class="text-[10px] text-indigo-500 font-semibold mt-1">
                                                        ${log.logged_in_at}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                `;
                                listContainer.innerHTML += itemHtml;
                            });
                        }

                        document.getElementById('loading-spinner').classList.add('hidden');
                        document.getElementById('logs-timeline').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching logs:', error);
                    document.getElementById('loading-spinner').innerHTML = `
                        <div class="text-rose-500 flex flex-col items-center">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="text-xs font-bold">Failed to load activity logs.</span>
                        </div>
                    `;
                });
        }
    </script>
</body>
</html>
