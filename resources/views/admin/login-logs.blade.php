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
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Outfit', sans-serif;
            color: #334155;
        }
        /* Custom Premium Styles */
        .portal-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border-radius: 1.25rem;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.025);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .icon-box {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .bg-primary-soft { background-color: #eff6ff; color: #2563eb; }
        .bg-success-soft { background-color: #f0fdf4; color: #16a34a; }
        .bg-warning-soft { background-color: #fffbeb; color: #d97706; }
        .bg-danger-soft { background-color: #fdf2f8; color: #db2777; }
        
        .main-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 3rem;
        }
        .table-responsive {
            border-radius: 1.25rem;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.2rem 1.5rem;
        }
        .table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .avatar-img {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 50%;
            object-fit: cover;
        }
        .avatar-wrapper {
            position: relative;
            display: inline-block;
            flex-shrink: 0;
        }
        .status-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }
        .badge-female { background-color: #db2777; }
        .badge-male { background-color: #4f46e5; }
        
        /* Timeline styling for side drawer */
        .timeline {
            position: relative;
            padding-left: 2.5rem;
            margin-bottom: 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            top: 8px;
            bottom: 8px;
            left: 1rem;
            width: 2px;
            background-color: #e2e8f0;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        .timeline-marker {
            position: absolute;
            left: -2.25rem;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 50%;
            background-color: #f1f5f9;
            border: 2px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            box-shadow: 0 0 0 4px #ffffff;
            font-size: 0.8rem;
        }
        .timeline-content {
            padding-top: 0.15rem;
        }
        
        /* Custom search styling */
        .search-container {
            position: relative;
        }
        .search-input {
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            padding-left: 2.5rem;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
        }
        .search-icon-wrapper {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        /* Sortable columns */
        .sortable-link {
            text-decoration: none;
            color: inherit;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .sortable-link:hover {
            color: #0f172a;
        }
        
        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .contact-info-list span {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="container py-4">
        
        <!-- Header -->
        <header class="portal-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="fa-solid fa-shield-halved me-2"></i>Usa Marry</h1>
                <p class="text-white-50 mb-0">Security & Activity Portal</p>
            </div>
            <div>
                <a href="https://api.usamarry.com" class="btn btn-outline-light rounded-pill px-4 btn-sm">
                    Back to Site <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </header>

        <!-- Stats Section -->
        <div class="row g-4 mb-4">
            <!-- Stat Card 1 -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="icon-box bg-primary-soft">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-uppercase small fw-bold text-muted mb-1">Total Logins</p>
                        <h3 class="fw-bold mb-0 text-slate-800">{{ $totalLoginsCount }}</h3>
                    </div>
                </div>
            </div>
            <!-- Stat Card 2 -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="icon-box bg-success-soft">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <p class="text-uppercase small fw-bold text-muted mb-1">Unique Users</p>
                        <h3 class="fw-bold mb-0 text-slate-800">{{ $totalUniqueUsersCount }}</h3>
                    </div>
                </div>
            </div>
            <!-- Stat Card 3 -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="icon-box bg-warning-soft">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-uppercase small fw-bold text-muted mb-1">Most Active</p>
                        @if($mostActiveUser)
                            <h6 class="fw-bold text-truncate mb-0" title="{{ $mostActiveUser->name }} (ID: {{ $mostActiveUser->profile_id }})">
                                {{ $mostActiveUser->name }}
                            </h6>
                            <small class="text-muted">{{ $mostActiveUserCount }} Logins</small>
                        @else
                            <h3 class="fw-bold mb-0">-</h3>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Stat Card 4 -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="icon-box bg-danger-soft">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-uppercase small fw-bold text-muted mb-1">Last Activity</p>
                        @if($lastLoginLog && $lastLoginLog->user)
                            <h6 class="fw-bold text-truncate mb-0" title="By {{ $lastLoginLog->user->name }}">
                                {{ $lastLoginLog->user->name }}
                            </h6>
                            <small class="text-muted">
                                {{ $lastLoginLog->logged_in_at ? $lastLoginLog->logged_in_at->diffForHumans() : 'N/A' }}
                            </small>
                        @else
                            <h3 class="fw-bold mb-0">-</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Data Table Container -->
        <div class="main-card">
            
            <!-- Filter Bar -->
            <div class="p-4 border-bottom bg-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1 text-slate-800">User Login Logs</h5>
                    <p class="text-muted small mb-0">Track and monitor user login frequency, patterns, and locations.</p>
                </div>
                
                <form method="GET" action="{{ url('admin/login-logs') }}" class="d-flex gap-2">
                    <!-- Preserve sorting parameters -->
                    <input type="hidden" name="sort" value="{{ request('sort', 'login_count') }}">
                    <input type="hidden" name="order" value="{{ request('order', 'desc') }}">
                    
                    <div class="search-container">
                        <span class="search-icon-wrapper">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control search-input" placeholder="Search users..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-filter me-1"></i>Search</button>
                    @if(request()->filled('search'))
                        <a href="{{ url('admin/login-logs') }}" class="btn btn-outline-secondary px-3"><i class="fa-solid fa-rotate-left"></i></a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 40%;">User Details</th>
                            <th style="width: 25%; text-align: center;">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'login_count', 'order' => request('sort') == 'login_count' && request('order') == 'desc' ? 'asc' : 'desc']) }}" class="sortable-link justify-content-center">
                                    Login Count
                                    @if(request('sort', 'login_count') === 'login_count')
                                        <i class="fa-solid fa-sort-{{ request('order', 'desc') === 'desc' ? 'down' : 'up' }} text-primary"></i>
                                    @else
                                        <i class="fa-solid fa-sort text-muted opacity-50"></i>
                                    @endif
                                </a>
                            </th>
                            <th style="width: 25%;">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_login_at', 'order' => request('sort') == 'last_login_at' && request('order') == 'desc' ? 'asc' : 'desc']) }}" class="sortable-link">
                                    Last Login At
                                    @if(request('sort') === 'last_login_at')
                                        <i class="fa-solid fa-sort-{{ request('order', 'desc') === 'desc' ? 'down' : 'up' }} text-primary"></i>
                                    @else
                                        <i class="fa-solid fa-sort text-muted opacity-50"></i>
                                    @endif
                                </a>
                            </th>
                            <th style="width: 10%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <!-- Avatar with dynamic initials/colors -->
                                        <div class="avatar-wrapper">
                                            @if($user->gender === 'Female')
                                                <img class="avatar-img border border-2 border-pink-subtle" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=fdf2f8&color=db2777&bold=true" alt="Female User">
                                                <span class="status-badge badge-female"></span>
                                            @else
                                                <img class="avatar-img border border-2 border-indigo-subtle" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=e0e7ff&color=4f46e5&bold=true" alt="Male User">
                                                <span class="status-badge badge-male"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-800">{{ $user->name }}</div>
                                            <div class="contact-info-list d-flex flex-wrap align-items-center gap-x-2 gap-y-1 mt-1 text-muted">
                                                <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace px-2">ID: {{ $user->profile_id }}</span>
                                                <span class="text-black-25">|</span>
                                                <span class="d-flex align-items-center gap-1">
                                                    <i class="fa-regular fa-envelope text-slate-400"></i>
                                                    <!--email_off-->{{ $user->email }}<!--/email_off-->
                                                </span>
                                                @if($user->phone)
                                                    <span class="text-black-25">|</span>
                                                    <span class="d-flex align-items-center gap-1">
                                                        <i class="fa-solid fa-phone text-slate-400"></i>
                                                        {{ $user->phone }}
                                                    </span>
                                                @endif
                                                @if($user->whatsapps)
                                                    <span class="text-black-25">|</span>
                                                    <span class="d-flex align-items-center gap-1 text-success fw-medium">
                                                        <i class="fa-brands fa-whatsapp text-success"></i>
                                                        {{ $user->whatsapps }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fw-semibold fs-6">
                                        {{ $user->login_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-slate-700">
                                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y h:i A') : 'N/A' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : '' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="fetchLogs({{ $user->id }})">
                                        <i class="fa-solid fa-eye me-1"></i>View Logs
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fa-regular fa-folder-open display-6 mb-3 d-block text-slate-300"></i>
                                    No records found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($users->hasPages())
                <div class="px-4 py-3 border-top bg-light">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>

    </div>

    <!-- BS5 Offcanvas Details Drawer -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="activityDrawer" aria-labelledby="activityDrawerLabel" style="width: 500px;">
        <div class="offcanvas-header border-bottom bg-light">
            <h5 class="offcanvas-title fw-bold" id="activityDrawerLabel">Activity Timeline</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            
            <!-- Loading Spinner -->
            <div id="loading-spinner" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- Content Area (Initially Hidden) -->
            <div id="logs-timeline" class="d-none">
                <!-- User Info Summary inside Drawer -->
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-4">
                    <div id="drawer-avatar">
                        <!-- Loaded Dynamically -->
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold mb-1 text-slate-800" id="drawer-user-name">User Name</h6>
                        <p class="text-muted small mb-0 text-truncate" id="drawer-user-email">User Email / Profile ID</p>
                    </div>
                </div>

                <h6 class="fw-bold text-uppercase small text-muted tracking-wider mb-3">Session Log History</h6>
                
                <!-- Timeline List -->
                <ul class="timeline" id="logs-list-items">
                    <!-- Dynamically populated via JS -->
                </ul>
            </div>
            
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AJAX & Drawer Script -->
    <script>
        // Store reference to the Offcanvas drawer instance
        let activityDrawer = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the Bootstrap 5 Offcanvas drawer
            activityDrawer = new bootstrap.Offcanvas(document.getElementById('activityDrawer'));
        });

        function fetchLogs(userId) {
            // Show offcanvas drawer immediately
            if (activityDrawer) {
                activityDrawer.show();
            }

            // Reset drawer state to loading
            document.getElementById('loading-spinner').classList.remove('d-none');
            document.getElementById('logs-timeline').classList.add('d-none');
            document.getElementById('drawer-user-name').innerText = 'Loading...';
            document.getElementById('drawer-user-email').innerText = '...';
            document.getElementById('drawer-avatar').innerHTML = '';

            // Fetch logs detail via AJAX
            fetch(`/admin/login-logs/${userId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Populate User Details
                        document.getElementById('drawer-user-name').innerText = data.user.name;
                        document.getElementById('drawer-user-email').innerText = `Profile ID: ${data.user.profile_id} | ${data.user.email}`;
                        
                        // Set Avatar based on gender styling
                        const isFemale = data.user.gender === 'Female';
                        const encodedName = encodeURIComponent(data.user.name);
                        const avatarUrl = isFemale 
                            ? `https://ui-avatars.com/api/?name=${encodedName}&background=fdf2f8&color=db2777&bold=true` 
                            : `https://ui-avatars.com/api/?name=${encodedName}&background=e0e7ff&color=4f46e5&bold=true`;
                        const borderClass = isFemale ? 'border-pink-subtle' : 'border-indigo-subtle';
                        
                        document.getElementById('drawer-avatar').innerHTML = `
                            <img class="avatar-img border border-2 ${borderClass}" src="${avatarUrl}" alt="Profile avatar">
                        `;

                        // Render timeline items
                        const listContainer = document.getElementById('logs-list-items');
                        listContainer.innerHTML = '';

                        if (data.logs.length === 0) {
                            listContainer.innerHTML = `
                                <li class="text-center text-muted py-4 small">
                                    <i class="fa-solid fa-clock-rotate-left d-block fs-3 mb-2 text-black-50"></i>
                                    No recorded logs available.
                                </li>
                            `;
                        } else {
                            data.logs.forEach(log => {
                                // Find appropriate device icon
                                let deviceIcon = 'fa-solid fa-desktop';
                                const deviceLower = (log.device || '').toLowerCase();
                                if (deviceLower.includes('phone') || deviceLower.includes('mobile') || deviceLower.includes('android') || deviceLower.includes('iphone')) {
                                    deviceIcon = 'fa-solid fa-mobile-screen-button';
                                } else if (deviceLower.includes('tablet') || deviceLower.includes('ipad')) {
                                    deviceIcon = 'fa-solid fa-tablet-screen-button';
                                }

                                const itemHtml = `
                                    <li class="timeline-item">
                                        <span class="timeline-marker">
                                            <i class="${deviceIcon}"></i>
                                        </span>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-slate-800 small">IP: ${log.ip_address}</span>
                                                <span class="text-muted small" style="font-size: 0.75rem;">${log.relative_time}</span>
                                            </div>
                                            <p class="small text-muted mb-1">
                                                <i class="fa-solid fa-location-dot me-1 text-slate-400"></i>Location: <span class="text-slate-700">${log.location}</span>
                                            </p>
                                            <p class="font-monospace text-slate-400 mb-1 text-truncate" style="font-size: 0.65rem;" title="${log.device}">
                                                ${log.device}
                                            </p>
                                            <span class="text-primary small fw-semibold" style="font-size: 0.7rem;">
                                                <i class="fa-regular fa-calendar-check me-1"></i>${log.logged_in_at}
                                            </span>
                                        </div>
                                    </li>
                                `;
                                listContainer.innerHTML += itemHtml;
                            });
                        }

                        // Display timeline and hide loader
                        document.getElementById('loading-spinner').classList.add('d-none');
                        document.getElementById('logs-timeline').classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error fetching logs:', error);
                    document.getElementById('loading-spinner').innerHTML = `
                        <div class="text-danger py-4">
                            <i class="fa-solid fa-circle-exclamation display-6 mb-2"></i>
                            <p class="fw-bold small mb-0">Failed to load activity logs.</p>
                        </div>
                    `;
                });
        }
    </script>
</body>
</html>
