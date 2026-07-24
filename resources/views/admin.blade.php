<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkOwenz - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Manrope', sans-serif;
        }
        .hidden {
            display: none;
        }
        .active {
            display: block;
        }
        .sidebar-link.active {
            background-color: rgba(99, 102, 241, 0.2);
            color: #6366f1;
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #1e293b;
            margin: 3% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            color: white;
            max-height: 90vh;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .modal-content::-webkit-scrollbar {
            width: 0;
            height: 0;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: white;
        }
        .progress-bar {
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-active {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        .status-completed {
            background-color: rgba(99, 102, 241, 0.2);
            color: #6366f1;
        }
        .status-cancelled {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        .hide-scrollbar {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .hide-scrollbar::-webkit-scrollbar {
            width: 0;
            height: 0;
        }
        .admin-conversation-item {
            min-height: 140px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .unread-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 9999px;
            background-color: #16a34a;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
        }
        .messages-grid {
            height: calc(100vh - 200px);
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
    <!-- Login Screen -->
    <div id="loginScreen" class="min-h-screen hidden flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            <div class="flex items-center gap-3 justify-center mb-8">
                <span class="material-symbols-outlined text-indigo-600 text-4xl">local_parking</span>
                <h2 class="text-2xl font-bold dark:text-white">ParkOwenz Admin</h2>
            </div>
            <h1 class="text-2xl font-bold text-center mb-2 dark:text-white">Admin Login</h1>
            <p class="text-gray-600 dark:text-gray-400 text-center mb-8">Enter your credentials to access the dashboard</p>
            
            <form id="loginForm" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium mb-2 dark:text-gray-300">Email</label>
                    <input type="email" id="email" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-12 px-4 text-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="admin@parkowenz.com" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-2 dark:text-gray-300">Password</label>
                    <input type="password" id="password" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-12 px-4 text-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter password" required>
                </div>
                <button type="submit" class="w-full flex cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-12 px-4 bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition-colors">
                    <span class="material-symbols-outlined text-base">login</span>
                    <span>Login</span>
                </button>
            </form>
            <p id="loginError" class="text-red-500 text-sm mt-4 text-center hidden">Invalid credentials. Please try again.</p>
        </div>
    </div>

    <!-- Dashboard -->
    <div id="dashboard">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div id="sidebarContainer" class="w-64 bg-white dark:bg-gray-800 shadow-lg flex flex-col">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-indigo-600 text-3xl">local_parking</span>
                        <h2 class="text-xl font-bold dark:text-white">ParkOwenz Admin</h2>
                    </div>
                </div>
                
                <div class="flex-1 p-4">
                    <nav class="space-y-2">
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300 active" data-section="overview">
                            <span class="material-symbols-outlined">dashboard</span>
                            <span>Overview</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="bookings">
                            <span class="material-symbols-outlined">book_online</span>
                            <span>Bookings</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="payments">
                            <span class="material-symbols-outlined">payments</span>
                            <span>Payments</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="messages">
                            <span class="material-symbols-outlined">chat</span>
                            <span>Messages</span>
                            <span id="sidebarMessagesBadge" class="unread-pill hidden ml-auto">0</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="pricing">
                            <span class="material-symbols-outlined">edit</span>
                            <span>Pricing</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="analytics">
                            <span class="material-symbols-outlined">analytics</span>
                            <span>Analytics</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="lots">
                            <span class="material-symbols-outlined">local_parking</span>
                            <span>Lots</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="managers">
                            <span class="material-symbols-outlined">group</span>
                            <span>Managers</span>
                        </a>
                    </nav>
                </div>
                
                <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="goToFindParking()" class="w-full text-left">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-bold">A</div>
                        <div>
                            <div class="text-sm font-medium dark:text-white leading-tight">Admin User</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 leading-tight">Parking Manager</div>
                        </div>
                    </div>
                    </button>
                    <div class="relative">
                        <button id="logoutBtn" type="button" onclick="toggleLogoutMenu(event)" class="w-full flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <span class="material-symbols-outlined">logout</span>
                            <span>Logout</span>
                        </button>
                        <div id="logoutMenu" class="hidden absolute bottom-12 left-0 w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg z-20">
                            <button type="button" onclick="handleAdminLogout(event)" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                            <button type="button" onclick="toggleLogoutMenu(event, false)" class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="flex-1 overflow-auto bg-gray-50 dark:bg-gray-900">
                <!-- Overview Section -->
                <div id="overviewSection" class="section p-6 active">
                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold dark:text-white">Dashboard Overview</h1>
                            <p class="text-gray-600 dark:text-gray-400">Welcome back! Here's an overview of your parking operations.</p>
                        </div>
                        <button type="button"
                            onclick="openAddLotModal()"
                            class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 bg-indigo-600 text-white text-sm font-semibold shadow hover:bg-indigo-700">
                            <span class="material-symbols-outlined text-base">add_location_alt</span>
                            <span>Add New Lot</span>
                        </button>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Active Bookings</p>
                                    <p class="text-2xl font-bold dark:text-white mt-1" id="activeBookingsCount">12</p>
                                </div>
                                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">book_online</span>
                                </div>
                            </div>
                            <p class="text-green-500 text-sm mt-2">+2 from yesterday</p>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Today's Revenue</p>
                                    <p class="text-2xl font-bold dark:text-white mt-1" id="todayRevenue">UGX 245</p>
                                </div>
                                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                                    <span class="material-symbols-outlined text-green-600 dark:text-green-400">payments</span>
                                </div>
                            </div>
                            <p class="text-green-500 text-sm mt-2">+15% from yesterday</p>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Occupancy Rate</p>
                                    <p class="text-2xl font-bold dark:text-white mt-1" id="occupancyRate">68%</p>
                                </div>
                                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">local_parking</span>
                                </div>
                            </div>
                            <p class="text-red-500 text-sm mt-2">-5% from yesterday</p>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Avg. Duration</p>
                                    <p class="text-2xl font-bold dark:text-white mt-1" id="avgDuration">2.4 hrs</p>
                                </div>
                                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full">
                                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400">schedule</span>
                                </div>
                            </div>
                            <p class="text-green-500 text-sm mt-2">+0.2 hrs from yesterday</p>
                        </div>
                    </div>
                    
                    <!-- Charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Booking Trends</h3>
                            <div class="h-64">
                                <canvas id="bookingsChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Revenue Sources</h3>
                            <div class="h-64">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Bookings -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold dark:text-white">Current Bookings</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spot</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehicle</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Start Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time Left</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="currentBookingsTable">
                                    <!-- Bookings will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Bookings Section -->
                <div id="bookingsSection" class="section p-6 hidden">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold dark:text-white">Booking Management</h1>
                        <p class="text-gray-600 dark:text-gray-400">View and manage all parking bookings.</p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-semibold dark:text-white">All Bookings</h3>
                            <div class="flex space-x-2">
                                <select id="bookingFilter" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm">
                                    <option value="all">All Bookings</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <div class="relative">
                                    <input type="text" id="bookingSearch" placeholder="Search bookings..." class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg pl-10 pr-4 py-2 text-sm w-64">
                                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">search</span>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spot</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehicle</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="allBookingsTable">
                                    <!-- All bookings will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Payments Section -->
                <div id="paymentsSection" class="section p-6 hidden">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold dark:text-white">Payment History</h1>
                        <p class="text-gray-600 dark:text-gray-400">View all payment transactions.</p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-semibold dark:text-white">Payment Records</h3>
                            <div class="flex space-x-2">
                                <select id="paymentFilter" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm">
                                    <option value="all">All Payments</option>
                                    <option value="completed">Completed</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                                <div class="relative">
                                    <input type="text" id="paymentSearch" placeholder="Search payments..." class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg pl-10 pr-4 py-2 text-sm w-64">
                                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">search</span>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="paymentsTable">
                                    <!-- Payments will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Messages Section -->
                <div id="messagesSection" class="section p-6 hidden">
                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold dark:text-white">Messages</h1>
                            <p class="text-gray-600 dark:text-gray-400">Latest chats from users.</p>
                        </div>
                        <button type="button" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm" id="refreshMessagesBtn">
                            <span class="material-symbols-outlined text-base">refresh</span>
                            <span>Refresh</span>
                        </button>
                    </div>
                    
                    <div class="messages-grid grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-6 min-h-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow flex flex-col h-full min-h-0">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-semibold dark:text-white">User Conversations</h3>
                                    <span id="adminUnreadBadge" class="unread-pill hidden">0</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tap a user to view the chat thread.</p>
                            </div>
                            <div id="adminConversationsList" class="flex-1 min-h-0 divide-y divide-gray-100 dark:divide-gray-700 overflow-y-auto hide-scrollbar">
                                <!-- Conversations render here -->
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow flex flex-col h-full min-h-0">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold dark:text-white" id="adminChatTitle">Select a conversation</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="adminChatSubtitle">Choose a user to start messaging.</p>
                            </div>
                            <div id="adminChatMessages" class="flex-1 min-h-0 p-6 space-y-4 overflow-y-auto hide-scrollbar">
                                <!-- Chat messages render here -->
                            </div>
                            <form id="adminChatForm" class="border-t border-gray-200 dark:border-gray-700 p-4 flex gap-3" onsubmit="sendAdminMessageFromUI(event)">
                                <input id="adminChatInput" type="text" class="flex-1 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 py-3 text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Type your message..." disabled>
                                <button type="button" id="adminChatSend" onclick="sendAdminMessageFromUI(event)" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-indigo-300">Send</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div id="pricingSection" class="section p-6 hidden">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold dark:text-white">Pricing Management</h1>
                        <p class="text-gray-600 dark:text-gray-400">Set and update parking rates.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Current Rates</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium dark:text-white">First Hour</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Standard rate for the first hour</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold dark:text-white">UGX 5.00</span>
                                        <button class="text-indigo-600 hover:text-indigo-800" onclick="editPrice('firstHour')">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium dark:text-white">Additional Hours</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Rate for each additional hour</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold dark:text-white">UGX 3.00</span>
                                        <button class="text-indigo-600 hover:text-indigo-800" onclick="editPrice('additionalHour')">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium dark:text-white">Daily Maximum</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Maximum charge for 24 hours</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold dark:text-white">UGX 25.00</span>
                                        <button class="text-indigo-600 hover:text-indigo-800" onclick="editPrice('dailyMax')">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium dark:text-white">Monthly Pass</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Unlimited parking for 30 days</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold dark:text-white">UGX 150.00</span>
                                        <button class="text-indigo-600 hover:text-indigo-800" onclick="editPrice('monthlyPass')">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Special Rates</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium dark:text-white">Evening Rate (6PM-6AM)</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Reduced rate during off-peak hours</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold dark:text-white">UGX 2.50</span>
                                        <button class="text-indigo-600 hover:text-indigo-800" onclick="editPrice('eveningRate')">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium dark:text-white">Weekend Rate</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Special rate for weekends</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold dark:text-white">UGX 4.00</span>
                                        <button class="text-indigo-600 hover:text-indigo-800" onclick="editPrice('weekendRate')">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium dark:text-white">EV Charging Fee</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Additional fee for electric vehicle charging</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold dark:text-white">UGX 2.00</span>
                                        <button class="text-indigo-600 hover:text-indigo-800" onclick="editPrice('evFee')">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">info</span>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Pricing Note</h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>Price changes will take effect immediately for new bookings. Existing active bookings will not be affected.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Analytics Section -->
                <div id="analyticsSection" class="section p-6 hidden">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold dark:text-white">Parking Analytics</h1>
                        <p class="text-gray-600 dark:text-gray-400">Detailed insights into your parking operations.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Revenue Trends</h3>
                            <div class="h-64">
                                <canvas id="revenueTrendsChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Occupancy Patterns</h3>
                            <div class="h-64">
                                <canvas id="occupancyChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Peak Hours</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium dark:text-white">8:00 AM - 10:00 AM</span>
                                        <span class="text-sm font-medium dark:text-white">85%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill bg-red-500" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium dark:text-white">12:00 PM - 2:00 PM</span>
                                        <span class="text-sm font-medium dark:text-white">72%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill bg-yellow-500" style="width: 72%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium dark:text-white">5:00 PM - 7:00 PM</span>
                                        <span class="text-sm font-medium dark:text-white">68%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill bg-green-500" style="width: 68%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Popular Spots</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="dark:text-white">A-01 to A-10</span>
                                    <span class="font-medium dark:text-white">92%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="dark:text-white">B-01 to B-10</span>
                                    <span class="font-medium dark:text-white">78%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="dark:text-white">C-01 to C-10</span>
                                    <span class="font-medium dark:text-white">65%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="dark:text-white">EV Charging Spots</span>
                                    <span class="font-medium dark:text-white">45%</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4 dark:text-white">Customer Stats</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="dark:text-white">Repeat Customers</span>
                                    <span class="font-medium dark:text-white">42%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="dark:text-white">New Customers</span>
                                    <span class="font-medium dark:text-white">58%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="dark:text-white">Avg. Visit Duration</span>
                                    <span class="font-medium dark:text-white">2.4 hrs</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="dark:text-white">Satisfaction Rate</span>
                                    <span class="font-medium dark:text-white">4.7/5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lots Section -->
                <div id="lotsSection" class="section p-6 hidden">
                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold dark:text-white">All Parking Lots</h1>
                            <p class="text-gray-600 dark:text-gray-400">Manage all parking locations shown on the website.</p>
                        </div>
                        <button type="button"
                            onclick="openAddLotModal()"
                            class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 bg-indigo-600 text-white text-sm font-semibold shadow hover:bg-indigo-700">
                            <span class="material-symbols-outlined text-base">add_location_alt</span>
                            <span>Add New Lot</span>
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <h3 class="text-lg font-semibold dark:text-white">Parking Lots</h3>
                            <div class="flex gap-2 items-center">
                                <select id="lotStatusFilter"
                                    class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm">
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="relative">
                                    <input type="text" id="lotSearch" placeholder="Search lots..."
                                        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg pl-10 pr-4 py-2 text-sm w-64">
                                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">search</span>
                                </div>
                                <button type="button" onclick="loadLotsAdmin()"
                                    class="px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-sm hover:bg-gray-300 dark:hover:bg-gray-600">
                                    Refresh
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Address</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rating</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slots</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="lotsTable">
                                    <!-- lots populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Managers Section -->
                <div id="managersSection" class="section p-6 hidden">
                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold dark:text-white">Lot Managers</h1>
                            <p class="text-gray-600 dark:text-gray-400">Create, edit, deactivate, or delete lot managers.</p>
                        </div>
                        <button type="button"
                            onclick="openCreateManagerModal()"
                            class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 bg-indigo-600 text-white text-sm font-semibold shadow hover:bg-indigo-700">
                            <span class="material-symbols-outlined text-base">person_add</span>
                            <span>Add Manager</span>
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <h3 class="text-lg font-semibold dark:text-white">Managers</h3>
                            <div class="flex gap-2 items-center">
                                <select id="managerStatusFilter"
                                    class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm">
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="relative">
                                    <input type="text" id="managerSearch" placeholder="Search managers..."
                                        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg pl-10 pr-4 py-2 text-sm w-64">
                                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-gray-400 text-lg">search</span>
                                </div>
                                <button type="button" onclick="loadManagers()"
                                    class="px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-sm hover:bg-gray-300 dark:hover:bg-gray-600">
                                    Refresh
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="managersTable">
                                    <!-- managers populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Price Modal -->
    <div id="editPriceModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editPriceModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4 dark:text-white" id="modalTitle">Edit Price</h2>
            <form id="priceForm">
                <div class="mb-4">
                    <label for="priceValue" class="block text-sm font-medium mb-2 dark:text-gray-300">Price (UGX)</label>
                    <input type="number" id="priceValue" step="0.01" min="0" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-12 px-4 text-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('editPriceModal')" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add New Lot + Assign Manager Modal -->
    <div id="addLotModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addLotModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4 dark:text-white">Add New Parking Lot</h2>

            <form id="addLotForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Lot Name</label>
                        <input type="text" id="lotName" required
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Price per Hour (UGX)</label>
                        <input type="number" id="lotPrice" step="0.01" min="0" required
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Rating (0–5)</label>
                        <input type="number" id="lotRating" step="0.1" min="0" max="5"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. 4.5" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Latitude</label>
                        <input type="number" id="lotLat" step="0.000001" required
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Longitude</label>
                        <input type="number" id="lotLng" step="0.000001" required
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Total Slots</label>
                        <input type="number" id="lotTotal" min="1" required
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Address</label>
                        <textarea id="lotAddress" rows="2" required
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Lot Image (optional)</label>
                        <input type="file" id="lotImage" accept="image/*"
                            class="w-full text-sm text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Max 5MB. JPG/PNG/WebP supported.</p>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300 mt-3">OR existing image path in project (e.g. <code>park/bd.jpg</code>)</label>
                        <input type="text" id="lotImagePath" placeholder="park/bd.jpg"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-10 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 dark:text-gray-300">Assign Manager</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                        Choose an existing Lot Manager or create a new one. The initial password will use the email prefix or phone number.
                    </p>
                    <div class="flex flex-col md:flex-row gap-4">
                        <label class="inline-flex items-center gap-2 text-sm dark:text-gray-200">
                            <input type="radio" name="managerMode" value="existing" checked class="text-indigo-600 focus:ring-indigo-500" />
                            <span>Existing Manager</span>
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm dark:text-gray-200">
                            <input type="radio" name="managerMode" value="new" class="text-indigo-600 focus:ring-indigo-500" />
                            <span>New Manager</span>
                        </label>
                    </div>

                    <div id="existingManagerFields" class="mt-3">
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Select Manager</label>
                        <select id="managerSelect"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Select Lot Manager --</option>
                        </select>
                    </div>

                    <div id="newManagerFields" class="mt-3 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-300">Manager Name</label>
                                <input type="text" id="managerName"
                                    class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-10 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-300">Manager Email</label>
                                <input type="email" id="managerEmail"
                                    class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-10 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-300">Manager Phone</label>
                                <input type="text" id="managerPhone"
                                    class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-10 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button"
                        onclick="closeModal('addLotModal')"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Create Lot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create/Edit Manager Modal -->
    <div id="managerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('managerModal')">&times;</span>
            <h2 class="text-xl font-bold mb-4 dark:text-white" id="managerModalTitle">Add Manager</h2>

            <form id="managerForm" class="space-y-4">
                <input type="hidden" id="managerId" />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Name</label>
                        <input type="text" id="managerFormName" required
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Email</label>
                        <input type="email" id="managerFormEmail" required
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 dark:text-gray-300">Phone</label>
                        <input type="text" id="managerFormPhone"
                            class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg h-11 px-3 text-sm text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 text-sm dark:text-gray-200">
                            <input type="checkbox" id="managerFormActive" checked class="text-indigo-600 focus:ring-indigo-500" />
                            <span>Active</span>
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm dark:text-gray-200">
                            <input type="checkbox" id="managerFormResetPassword" class="text-indigo-600 focus:ring-indigo-500" />
                            <span>Reset initial password</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button"
                        onclick="closeModal('managerModal')"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Save Manager
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="/api.js"></script>
    <script>
        // Function to sync token (will be available after api.js loads)
        function syncAuthToken() {
            if (typeof updateAuthToken === 'function') {
                updateAuthToken();
            } else {
                // Fallback if updateAuthToken not yet available
                if (typeof authToken !== 'undefined') {
                    authToken = localStorage.getItem('authToken') || null;
                }
            }
        }

        function initializeReverbEcho() {
            if (typeof Echo === 'undefined' || typeof Pusher === 'undefined') {
                console.warn('Echo or Pusher is not available for realtime chat.');
                return;
            }
            const baseUrl = typeof API_URL === 'string' ? API_URL.replace(/\/api\/?$/, '') : '';
            const reverbDefaults = {
                appId: @json(config('broadcasting.connections.reverb.app_id')),
                key: @json(config('broadcasting.connections.reverb.key')),
                host: @json(config('broadcasting.connections.reverb.options.host')),
                port: @json(config('broadcasting.connections.reverb.options.port')),
                scheme: @json(config('broadcasting.connections.reverb.options.scheme')),
            };
            const storedKey = localStorage.getItem('reverbKey');
            const key = storedKey && storedKey !== reverbDefaults.appId ? storedKey : reverbDefaults.key;
            const host = localStorage.getItem('reverbHost') || reverbDefaults.host || window.location.hostname || 'localhost';
            const port = parseInt(localStorage.getItem('reverbPort') || reverbDefaults.port || '8080', 10);
            const scheme = localStorage.getItem('reverbScheme') || reverbDefaults.scheme || 'http';

            window.Echo = new Echo({
                broadcaster: 'pusher',
                key,
                wsHost: host,
                wsPort: port,
                wssPort: port,
                forceTLS: scheme === 'https',
                enabledTransports: ['ws', 'wss'],
                authEndpoint: `${baseUrl}/broadcasting/auth`,
                auth: {
                    headers: {
                        Authorization: authToken ? `Bearer ${authToken}` : '',
                        Accept: 'application/json',
                    },
                },
            });
        }

        // ------- Add Lot Modal helpers -------
        let adminManagersCache = [];

        async function openAddLotModal() {
            // Load managers (once per session, or refresh on each open)
            try {
                adminManagersCache = await fetchAdminManagers();
            } catch (e) {
                console.error('Failed to load managers', e);
                adminManagersCache = [];
            }

            const select = document.getElementById('managerSelect');
            if (select) {
                select.innerHTML = '<option value="">-- Select Lot Manager --</option>';
                adminManagersCache.forEach(m => {
                    const label = m.name + (m.email ? ` (${m.email})` : '');
                    const opt = document.createElement('option');
                    opt.value = m.id;
                    opt.textContent = label;
                    select.appendChild(opt);
                });
            }

            // Reset form fields
            const form = document.getElementById('addLotForm');
            if (form) {
                form.reset();
            }

            // Ensure correct manager mode sections
            const existingFields = document.getElementById('existingManagerFields');
            const newFields = document.getElementById('newManagerFields');
            if (existingFields && newFields) {
                existingFields.classList.remove('hidden');
                newFields.classList.add('hidden');
            }

            document.getElementById('addLotModal').style.display = 'block';
        }

        // Toggle between existing/new manager fields when radio changes
        document.addEventListener('DOMContentLoaded', function () {
            const modeRadios = document.querySelectorAll('input[name="managerMode"]');
            const existingFields = document.getElementById('existingManagerFields');
            const newFields = document.getElementById('newManagerFields');

            modeRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'existing') {
                        existingFields.classList.remove('hidden');
                        newFields.classList.add('hidden');
                    } else {
                        existingFields.classList.add('hidden');
                        newFields.classList.remove('hidden');
                    }
                });
            });

            const addLotForm = document.getElementById('addLotForm');
            if (addLotForm) {
                addLotForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const name = document.getElementById('lotName').value.trim();
                    const price = parseFloat(document.getElementById('lotPrice').value || '0');
                    const rating = document.getElementById('lotRating').value;
                    const lat = parseFloat(document.getElementById('lotLat').value || '0');
                    const lng = parseFloat(document.getElementById('lotLng').value || '0');
                    const total = parseInt(document.getElementById('lotTotal').value || '0', 10);
                    const address = document.getElementById('lotAddress').value.trim();

                    const mode = document.querySelector('input[name="managerMode"]:checked')?.value || 'existing';

                    // Use FormData so we can upload an optional image file
                    const payload = new FormData();
                    payload.append('name', name);
                    payload.append('price', String(price));
                    if (rating) {
                        payload.append('rating', String(rating));
                    }
                    payload.append('lat', String(lat));
                    payload.append('lng', String(lng));
                    payload.append('total', String(total));
                    payload.append('address', address);
                    payload.append('features', JSON.stringify([]));

                    const imageInput = document.getElementById('lotImage');
                    if (imageInput && imageInput.files && imageInput.files[0]) {
                        payload.append('image', imageInput.files[0]);
                    }

                    const imagePathInput = document.getElementById('lotImagePath');
                    if (imagePathInput && imagePathInput.value.trim()) {
                        payload.append('image_path', imagePathInput.value.trim());
                    }

                    if (mode === 'existing') {
                        const managerId = document.getElementById('managerSelect').value;
                        if (managerId) {
                            payload.append('manager_id', String(parseInt(managerId, 10)));
                        }
                    } else {
                        const managerName = document.getElementById('managerName').value.trim();
                        const managerEmail = document.getElementById('managerEmail').value.trim();
                        const managerPhone = document.getElementById('managerPhone').value.trim();

                        if (managerName) payload.append('manager_name', managerName);
                        if (managerEmail) payload.append('manager_email', managerEmail);
                        if (managerPhone) payload.append('manager_phone', managerPhone);
                    }

                    try {
                        const editId = addLotForm.getAttribute('data-edit-lot-id');

                        if (editId) {
                            // Update existing lot (only basic fields)
                            const updatePayload = {
                                name,
                                price,
                                rating: rating || null,
                                lat,
                                lng,
                                total,
                                address,
                            };
                            const imagePathInput = document.getElementById('lotImagePath');
                            if (imagePathInput && imagePathInput.value.trim()) {
                                updatePayload.image = imagePathInput.value.trim();
                            }

                            await updateAdminLot(parseInt(editId, 10), updatePayload);
                            showDialog('Lot updated successfully.', 'success', 'Lot Updated');
                            addLotForm.removeAttribute('data-edit-lot-id');
                            closeModal('addLotModal');
                            await loadLotsAdmin();
                        } else {
                            const data = await createAdminLot(payload);
                            const initialPassword = data.manager_initial_password || null;

                            let message = 'Parking lot created successfully.';
                            if (data.manager && initialPassword) {
                                message += `\n\nManager: ${data.manager.name} (${data.manager.email || data.manager.phone || ''})\nInitial password: ${initialPassword}`;
                            }

                            showDialog(message, 'success', 'Lot Created');
                            closeModal('addLotModal');
                            // Optionally refresh stats so the new lot affects occupancy etc.
                            await loadStats?.();
                            await loadData();
                            await loadLotsAdmin();
                        }
                    } catch (error) {
                        console.error('Error creating/updating lot', error);
                        showDialog(error.message || 'Unable to save lot. Please try again.', 'error', 'Error');
                    }
                });
            }
        });

        // ------- Managers & Lots CRUD (UI) -------
        let managersData = [];
        let lotsData = [];

        async function loadManagers() {
            try {
                managersData = await fetchAdminManagers();
            } catch (e) {
                managersData = [];
                showDialog(e.message || 'Unable to load managers.', 'error', 'Error');
            }
            renderManagers();
        }

        function renderManagers() {
            const tbody = document.getElementById('managersTable');
            if (!tbody) return;

            const filter = document.getElementById('managerStatusFilter')?.value || 'all';
            const q = (document.getElementById('managerSearch')?.value || '').toLowerCase();

            const rows = (managersData || []).filter(m => {
                const statusOk =
                    filter === 'all' ||
                    (filter === 'active' && m.is_active) ||
                    (filter === 'inactive' && !m.is_active);

                const searchOk =
                    !q ||
                    (m.name || '').toLowerCase().includes(q) ||
                    (m.email || '').toLowerCase().includes(q) ||
                    (m.phone || '').toLowerCase().includes(q);

                return statusOk && searchOk;
            });

            if (rows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No managers found.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';
            rows.forEach(m => {
                const tr = document.createElement('tr');
                const statusBadge = m.is_active
                    ? '<span class="status-badge status-completed">active</span>'
                    : '<span class="status-badge status-cancelled">inactive</span>';

                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white">${m.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${m.name || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${m.email || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${m.phone || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <button class="text-indigo-600 hover:text-indigo-900 mr-3" onclick="openEditManagerModal(${m.id})">Edit</button>
                        <button class="text-yellow-600 hover:text-yellow-800 mr-3" onclick="toggleManagerActive(${m.id})">${m.is_active ? 'Deactivate' : 'Activate'}</button>
                        <button class="text-red-600 hover:text-red-900" onclick="deleteManagerUI(${m.id})">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function loadLotsAdmin() {
            try {
                lotsData = await fetchAdminLots();
            } catch (e) {
                lotsData = [];
                showDialog(e.message || 'Unable to load lots.', 'error', 'Error');
            }
            renderLotsAdmin();
        }

        function renderLotsAdmin() {
            const tbody = document.getElementById('lotsTable');
            if (!tbody) return;

            const filter = document.getElementById('lotStatusFilter')?.value || 'all';
            const q = (document.getElementById('lotSearch')?.value || '').toLowerCase();

            const rows = (lotsData || []).filter(lot => {
                const active = lot.is_active === true || lot.is_active === 1 || lot.is_active === null;
                const statusOk =
                    filter === 'all' ||
                    (filter === 'active' && active) ||
                    (filter === 'inactive' && !active);

                const searchOk =
                    !q ||
                    (lot.name || '').toLowerCase().includes(q) ||
                    (lot.address || '').toLowerCase().includes(q);

                return statusOk && searchOk;
            });

            if (rows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No lots found.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';
            rows.forEach(lot => {
                const tr = document.createElement('tr');
                const active = lot.is_active === true || lot.is_active === 1 || lot.is_active === null;
                const statusBadge = active
                    ? '<span class="status-badge status-completed">active</span>'
                    : '<span class="status-badge status-cancelled">inactive</span>';

                const thumb = lot.image
                    ? `<div class="w-12 h-8 rounded overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <img src="/${lot.image}" alt="" class="w-full h-full object-cover">
                       </div>`
                    : '<span class="text-xs text-gray-400">no image</span>';

                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white">${lot.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${lot.name || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate">${lot.address || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${thumb}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">UGX ${lot.price}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${lot.rating ?? 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${lot.available}/${lot.total}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <button class="text-indigo-600 hover:text-indigo-900 mr-3" onclick="openEditLotModal(${lot.id})">Edit</button>
                        <button class="text-yellow-600 hover:text-yellow-800 mr-3" onclick="toggleLotActive(${lot.id})">${active ? 'Deactivate' : 'Activate'}</button>
                        <button class="text-red-600 hover:text-red-900" onclick="deleteLotAdmin(${lot.id})">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function openCreateManagerModal() {
            document.getElementById('managerModalTitle').textContent = 'Add Manager';
            document.getElementById('managerId').value = '';
            document.getElementById('managerFormName').value = '';
            document.getElementById('managerFormEmail').value = '';
            document.getElementById('managerFormPhone').value = '';
            document.getElementById('managerFormActive').checked = true;
            document.getElementById('managerFormResetPassword').checked = false;
            document.getElementById('managerModal').style.display = 'block';
        }

        function openEditManagerModal(id) {
            const m = (managersData || []).find(x => x.id === id);
            if (!m) return;

            document.getElementById('managerModalTitle').textContent = 'Edit Manager';
            document.getElementById('managerId').value = String(m.id);
            document.getElementById('managerFormName').value = m.name || '';
            document.getElementById('managerFormEmail').value = m.email || '';
            document.getElementById('managerFormPhone').value = m.phone || '';
            document.getElementById('managerFormActive').checked = !!m.is_active;
            document.getElementById('managerFormResetPassword').checked = false;
            document.getElementById('managerModal').style.display = 'block';
        }

        async function toggleManagerActive(id) {
            const m = (managersData || []).find(x => x.id === id);
            if (!m) return;

            const next = !m.is_active;
            const ok = confirm(`Are you sure you want to ${next ? 'activate' : 'deactivate'} this manager?`);
            if (!ok) return;

            try {
                await updateAdminManager(id, { is_active: next });
                await loadManagers();
                // Refresh manager dropdown in Add Lot modal if needed
                adminManagersCache = await fetchAdminManagers();
                showDialog(`Manager ${next ? 'activated' : 'deactivated'} successfully.`, 'success', 'Updated');
            } catch (e) {
                showDialog(e.message || 'Unable to update manager.', 'error', 'Error');
            }
        }

        async function deleteManagerUI(id) {
            const ok = confirm('Delete this manager? This will unassign their lots.');
            if (!ok) return;

            try {
                await deleteAdminManager(id);
                await loadManagers();
                adminManagersCache = await fetchAdminManagers();
                showDialog('Manager deleted successfully.', 'success', 'Deleted');
            } catch (e) {
                showDialog(e.message || 'Unable to delete manager.', 'error', 'Error');
            }
        }

        function openEditLotModal(id) {
            const lot = (lotsData || []).find(x => x.id === id);
            if (!lot) return;

            openAddLotModal();
            document.getElementById('lotName').value = lot.name || '';
            document.getElementById('lotPrice').value = lot.price || '';
            document.getElementById('lotRating').value = lot.rating ?? '';
            document.getElementById('lotLat').value = lot.lat || '';
            document.getElementById('lotLng').value = lot.lng || '';
            document.getElementById('lotTotal').value = lot.total || '';
            document.getElementById('lotAddress').value = lot.address || '';
            document.getElementById('lotImagePath').value = lot.image || '';

            const form = document.getElementById('addLotForm');
            if (form) {
                form.setAttribute('data-edit-lot-id', String(lot.id));
            }
        }

        async function toggleLotActive(id) {
            const lot = (lotsData || []).find(x => x.id === id);
            if (!lot) return;

            const active = lot.is_active === true || lot.is_active === 1 || lot.is_active === null;
            const next = !active;
            const ok = confirm(`Are you sure you want to ${next ? 'activate' : 'deactivate'} this lot?`);
            if (!ok) return;

            try {
                await updateAdminLot(id, { is_active: next });
                await loadLotsAdmin();
                showDialog(`Lot ${next ? 'activated' : 'deactivated'} successfully.`, 'success', 'Updated');
            } catch (e) {
                showDialog(e.message || 'Unable to update lot.', 'error', 'Error');
            }
        }

        async function deleteLotAdmin(id) {
            const ok = confirm('Delete this lot? This will remove it from the website.');
            if (!ok) return;

            try {
                await deleteAdminLot(id);
                await loadLotsAdmin();
                showDialog('Lot deleted successfully.', 'success', 'Deleted');
            } catch (e) {
                showDialog(e.message || 'Unable to delete lot.', 'error', 'Error');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const statusFilter = document.getElementById('managerStatusFilter');
            const search = document.getElementById('managerSearch');
            if (statusFilter) statusFilter.addEventListener('change', renderManagers);
            if (search) search.addEventListener('input', renderManagers);

            const lotStatusFilter = document.getElementById('lotStatusFilter');
            const lotSearch = document.getElementById('lotSearch');
            if (lotStatusFilter) lotStatusFilter.addEventListener('change', renderLotsAdmin);
            if (lotSearch) lotSearch.addEventListener('input', renderLotsAdmin);

            const managerForm = document.getElementById('managerForm');
            if (managerForm) {
                managerForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const id = document.getElementById('managerId').value;
                    const name = document.getElementById('managerFormName').value.trim();
                    const email = document.getElementById('managerFormEmail').value.trim();
                    const phone = document.getElementById('managerFormPhone').value.trim();
                    const isActive = document.getElementById('managerFormActive').checked;
                    const resetPassword = document.getElementById('managerFormResetPassword').checked;

                    try {
                        if (!id) {
                            const data = await createAdminManager({ name, email, phone: phone || null });
                            const initialPassword = data.initial_password;
                            showDialog(`Manager created.\n\nInitial password: ${initialPassword}`, 'success', 'Manager Created');
                        } else {
                            await updateAdminManager(parseInt(id, 10), {
                                name,
                                email,
                                phone: phone || null,
                                is_active: isActive,
                                reset_password: resetPassword,
                            });
                            showDialog('Manager updated successfully.', 'success', 'Updated');
                        }

                        closeModal('managerModal');
                        await loadManagers();
                        adminManagersCache = await fetchAdminManagers();
                    } catch (e) {
                        showDialog(e.message || 'Unable to save manager.', 'error', 'Error');
                    }
                });
            }
        });
        
        // Sample data
        // const bookingsData = [
        //     { id: 1001, customer: "John Doe", spot: "A-05", vehicle: "ABC-123", startTime: "10:30 AM", duration: "2 hours", timeLeft: "1h 15m", amount: 10, status: "active" },
        //     { id: 1002, customer: "Jane Smith", spot: "B-12", vehicle: "XYZ-789", startTime: "11:15 AM", duration: "1.5 hours", timeLeft: "45m", amount: 7.5, status: "active" },
        //     { id: 1003, customer: "Robert Johnson", spot: "C-03", vehicle: "PARK-001", startTime: "9:45 AM", duration: "4 hours", timeLeft: "30m", amount: 16, status: "active" },
        //     { id: 1004, customer: "Sarah Williams", spot: "A-08", vehicle: "FAST-24", startTime: "12:00 PM", duration: "3 hours", timeLeft: "2h 10m", amount: 12, status: "active" },
        //     { id: 1005, customer: "Michael Brown", spot: "EV-02", vehicle: "EV-1234", startTime: "8:30 AM", duration: "6 hours", timeLeft: "1h 45m", amount: 24, status: "active" }
        // ];
        
        // const allBookingsData = [
        //     ...bookingsData,
        //     { id: 1006, customer: "Lisa Anderson", spot: "B-07", vehicle: "CAR-567", startTime: "2023-10-14 2:00 PM", duration: "2 hours", amount: 8, status: "completed" },
        //     { id: 1007, customer: "David Wilson", spot: "A-12", vehicle: "TRK-890", startTime: "2023-10-14 4:30 PM", duration: "3 hours", amount: 12, status: "completed" },
        //     { id: 1008, customer: "Emily Davis", spot: "C-05", vehicle: "SUV-246", startTime: "2023-10-13 1:15 PM", duration: "1 hour", amount: 5, status: "cancelled" }
        // ];
        
        // const paymentsData = [
        //     { id: 2001, customer: "John Doe", date: "2023-10-15 10:30 AM", amount: 10, method: "Credit Card", status: "completed" },
        //     { id: 2002, customer: "Jane Smith", date: "2023-10-15 11:15 AM", amount: 7.5, method: "Mobile Payment", status: "completed" },
        //     { id: 2003, customer: "Robert Johnson", date: "2023-10-15 9:45 AM", amount: 16, method: "Credit Card", status: "completed" },
        //     { id: 2004, customer: "Sarah Williams", date: "2023-10-15 12:00 PM", amount: 12, method: "Debit Card", status: "completed" },
        //     { id: 2005, customer: "Michael Brown", date: "2023-10-15 8:30 AM", amount: 24, method: "Credit Card", status: "completed" },
        //     { id: 2006, customer: "Lisa Anderson", date: "2023-10-14 2:00 PM", amount: 8, method: "Cash", status: "completed" },
        //     { id: 2007, customer: "David Wilson", date: "2023-10-14 4:30 PM", amount: 12, method: "Mobile Payment", status: "pending" },
        //     { id: 2008, customer: "Emily Davis", date: "2023-10-13 1:15 PM", amount: 5, method: "Credit Card", status: "failed" }
        // ];
        // const bookingsData = JSON.parse(localStorage.getItem("bookings")) || [];
        // const allBookingsData = JSON.parse(localStorage.getItem("allBookings")) || bookingsData;
        // const paymentsData = JSON.parse(localStorage.getItem("payments")) || [];

        // Load data from API
        async function loadData() {
            try {
                const [bookings, payments] = await Promise.all([
                    fetchAllBookings(),
                    fetchAllPayments()
                ]);
                
                // Transform all bookings first
                allBookingsData = bookings.map(b => transformBookingData(b));
                
                // Filter for active bookings (currently active based on time, not just status)
                const now = new Date();
                bookingsData = allBookingsData.filter(booking => {
                    // Include if status is active
                    if (booking.status === 'active') return true;
                    
                    // Include if status is upcoming but time has started
                    if (booking.status === 'upcoming' && booking.date && booking.startTime && booking.endTime) {
                        try {
                            const startDateTime = new Date(`${booking.date}T${booking.startTime}`);
                            const endDateTime = new Date(`${booking.date}T${booking.endTime}`);
                            // If current time is between start and end, it's active
                            if (now >= startDateTime && now < endDateTime) {
                                return true;
                            }
                        } catch (e) {
                            console.warn('Error parsing booking date/time:', e);
                        }
                    }
                    
                    return false;
                });
                
                paymentsData = payments.map(p => transformPaymentData(p));
                
                return { bookingsData, allBookingsData, paymentsData };
            } catch (error) {
                console.error('Error loading data:', error);
                return { bookingsData: [], allBookingsData: [], paymentsData: [] };
            }
        }
        
        // Transform booking data to match admin dashboard format
        function transformBookingData(booking) {
            const now = new Date();
            
            // Determine if booking is currently active based on time
            let isCurrentlyActive = false;
            if (booking.date && booking.start_time && booking.end_time) {
                try {
                    const startDateTime = new Date(`${booking.date}T${booking.start_time}`);
                    const endDateTime = new Date(`${booking.date}T${booking.end_time}`);
                    isCurrentlyActive = (now >= startDateTime && now < endDateTime);
                } catch (e) {
                    console.warn('Error determining active status:', e);
                }
            }
            
            // Calculate time left
            let timeLeft = "Expired";
            if (booking.date && booking.end_time) {
                try {
                    const endDateTime = new Date(`${booking.date}T${booking.end_time}`);
                    const diff = endDateTime - now;
                    
                    if (diff > 0) {
                        const hours = Math.floor(diff / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        timeLeft = hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;
                    } else {
                        timeLeft = "Expired";
                    }
                } catch (e) {
                    console.warn('Error calculating time left:', e);
                    timeLeft = "N/A";
                }
            }
            
            // Use customer name from API
            const customerName = booking.customer || "Customer";
            
            // Use duration from API or calculate
            let duration = booking.duration || "N/A";
            if (duration === "N/A" && booking.start_time && booking.end_time) {
                try {
                    const start = new Date(`2000-01-01T${booking.start_time}`);
                    const end = new Date(`2000-01-01T${booking.end_time}`);
                    const hours = (end - start) / (1000 * 60 * 60);
                    duration = `${hours.toFixed(1)} hours`;
                } catch (e) {
                    console.warn('Error calculating duration:', e);
                }
            }
            
            // Update status if currently active
            let finalStatus = booking.status;
            if (isCurrentlyActive && (booking.status === 'upcoming' || booking.status === 'active')) {
                finalStatus = 'active';
            }
            
            return {
                id: booking.id,
                customer: customerName,
                spot: booking.spot || "A-01",
                vehicle: booking.vehicle,
                startTime: booking.start_time,
                endTime: booking.end_time,
                duration: duration,
                timeLeft: timeLeft,
                amount: parseFloat(booking.amount) || 0,
                status: finalStatus,
                date: booking.date,
                created_at: booking.created_at,
                isCurrentlyActive: isCurrentlyActive
            };
        }
        
        // Transform payment data
        // function transformPaymentData(payment) {
        //     return {
        //         id: payment.id,
        //         customer: payment.parking || "Customer",
        //         date: payment.date,
        //         amount: payment.amount || 0,
        //         method: payment.method || "Credit Card",
        //         status: payment.status || "completed"
        //     };
        // }
        
        // let bookingsData = [];
        // let allBookingsData = [];
        // let paymentsData = [];
        
        // Initial load
        //loadData();
        
        // Auto-refresh data every 5 seconds
        setInterval(() => {
            loadData();
            
            // Refresh current view if logged in
            if (localStorage.getItem('isAdminLoggedIn') === 'true') {
                const activeSection = document.querySelector('.section:not(.hidden)');
                if (activeSection && activeSection.id === 'overviewSection') {
                    displayOverview();
                    // Refresh charts with new data
                    initializeCharts();
                } else if (activeSection && activeSection.id === 'bookingsSection') {
                    displayAllBookings();
                } else if (activeSection && activeSection.id === 'paymentsSection') {
                    displayPayments();
                } else if (activeSection && activeSection.id === 'analyticsSection') {
                    // Refresh analytics charts
                    initializeCharts();
                }
            }
        }, 5000);
        
       // Transform payment data
        function transformPaymentData(payment) {
            return {
                id: payment.id,
                customer: payment.customer || "Customer",
                parking: payment.parking || "Unknown Parking",
                date: payment.date || payment.created_at?.split(' ')[0] || new Date().toISOString().split('T')[0],
                time: payment.time || payment.created_at?.split(' ')[1]?.substring(0, 5) || '',
                amount: parseFloat(payment.amount) || 0,
                method: payment.method || "Credit Card",
                status: payment.status || "completed",
                phone: payment.phone,
                provider: payment.provider,
                card_last4: payment.card_last4
            };
        }
        
        let bookingsData = [];
        let allBookingsData = [];
        let paymentsData = [];
        let adminLots = [];
        let adminTotalSpots = 0;
        let adminStats = {};
        let adminMessages = [];
        let adminConversations = [];
        let adminActiveConversationKey = null;
        let adminProfile = null;
        
        // Auto-refresh data every 30 seconds
        let refreshInterval;
        let messagesRefreshInterval;
        let messagesSetup = false;

        async function loadAdminMessages() {
            try {
                syncAuthToken();
                if (!authToken) {
                    return;
                }
                adminMessages = await fetchAdminChatMessages();
                renderAdminMessages();
            } catch (error) {
                console.error('Failed to load admin messages:', error);
            }
        }

        function getSenderRole(message) {
            if (message.sender_role) {
                if (message.sender_role === 'user' && !message.user?.id) {
                    return 'guest';
                }
                return message.sender_role;
            }
            if (adminProfile && message.user?.id && message.user.id === adminProfile.id) {
                return 'admin';
            }
            return message.user?.id ? 'user' : 'guest';
        }

        function getConversationKey(message) {
            const senderRole = getSenderRole(message);
            if (senderRole === 'admin') {
                if (message.recipient?.id) {
                    return `user:${message.recipient.id}`;
                }
                if (message.recipient?.email) {
                    return `email:${message.recipient.email}`;
                }
                return null;
            }
            const user = message.user || {};
            if (user.id) {
                return `user:${user.id}`;
            }
            if (user.email) {
                return `email:${user.email}`;
            }
            if (user.name) {
                return `name:${user.name}`;
            }
            return 'guest';
        }

        function getConversationUser(message) {
            const senderRole = getSenderRole(message);
            if (senderRole === 'admin') {
                return {
                    id: message.recipient?.id || null,
                    name: message.recipient?.name || '',
                    email: message.recipient?.email || '',
                };
            }
            return {
                id: message.user?.id || null,
                name: message.user?.name || '',
                email: message.user?.email || '',
            };
        }

        function getConversationReadKey(conversationKey) {
            return `adminChatRead:${conversationKey}`;
        }

        function getConversationUnreadCount(conversation) {
            const readKey = getConversationReadKey(conversation.key);
            const lastRead = localStorage.getItem(readKey);
            const lastReadTime = lastRead ? new Date(lastRead).getTime() : 0;
            return conversation.messages.filter((message) => {
                if (getSenderRole(message) === 'admin') {
                    return false;
                }
                const messageTime = message.created_at ? new Date(message.created_at).getTime() : 0;
                return messageTime > lastReadTime;
            }).length;
        }

        function markConversationRead(conversation) {
            if (!conversation || !conversation.latest?.created_at) return;
            const readKey = getConversationReadKey(conversation.key);
            localStorage.setItem(readKey, conversation.latest.created_at);
        }

        function buildAdminConversations() {
            const map = new Map();

            adminMessages.forEach((message) => {
                const key = getConversationKey(message);
                if (!key) return;

                const user = getConversationUser(message);
                const existing = map.get(key) || {
                    key,
                    user: { id: null, name: '', email: '' },
                    messages: [],
                    latest: null,
                };

                if (user.id && !existing.user.id) {
                    existing.user.id = user.id;
                }
                if (user.name && !existing.user.name) {
                    existing.user.name = user.name;
                }
                if (user.email && !existing.user.email) {
                    existing.user.email = user.email;
                }

                existing.messages.push(message);
                const existingTime = existing.latest?.created_at ? new Date(existing.latest.created_at).getTime() : 0;
                const messageTime = message.created_at ? new Date(message.created_at).getTime() : 0;
                if (!existing.latest || messageTime >= existingTime) {
                    existing.latest = message;
                }
                map.set(key, existing);
            });

            const conversations = Array.from(map.values()).map((conversation) => {
                conversation.messages.sort((a, b) => {
                    const aTime = a.created_at ? new Date(a.created_at).getTime() : 0;
                    const bTime = b.created_at ? new Date(b.created_at).getTime() : 0;
                    return aTime - bTime;
                });
                return conversation;
            });

            conversations.sort((a, b) => {
                const aTime = a.latest?.created_at ? new Date(a.latest.created_at).getTime() : 0;
                const bTime = b.latest?.created_at ? new Date(b.latest.created_at).getTime() : 0;
                return bTime - aTime;
            });

            return conversations;
        }

        function renderAdminMessages() {
            const conversationsList = document.getElementById('adminConversationsList');
            const chatMessages = document.getElementById('adminChatMessages');
            const chatTitle = document.getElementById('adminChatTitle');
            const chatSubtitle = document.getElementById('adminChatSubtitle');
            const chatInput = document.getElementById('adminChatInput');
            const chatSend = document.getElementById('adminChatSend');
            const unreadBadge = document.getElementById('adminUnreadBadge');
            const sidebarBadge = document.getElementById('sidebarMessagesBadge');

            if (!conversationsList || !chatMessages || !chatTitle || !chatSubtitle || !chatInput || !chatSend) {
                return;
            }

            conversationsList.innerHTML = '';
            chatMessages.innerHTML = '';

            adminConversations = buildAdminConversations();
            const totalUnread = adminConversations.reduce((sum, conversation) => {
                return sum + getConversationUnreadCount(conversation);
            }, 0);

            if (unreadBadge) {
                if (totalUnread > 0) {
                    unreadBadge.textContent = totalUnread > 99 ? '99+' : String(totalUnread);
                    unreadBadge.classList.remove('hidden');
                } else {
                    unreadBadge.classList.add('hidden');
                }
            }
            if (sidebarBadge) {
                if (totalUnread > 0) {
                    sidebarBadge.textContent = totalUnread > 99 ? '99+' : String(totalUnread);
                    sidebarBadge.classList.remove('hidden');
                } else {
                    sidebarBadge.classList.add('hidden');
                }
            }

            if (adminConversations.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'p-6 text-center text-sm text-gray-500 dark:text-gray-400';
                empty.textContent = 'No messages yet.';
                conversationsList.appendChild(empty);
                chatTitle.textContent = 'Select a conversation';
                chatSubtitle.textContent = 'Choose a user to start messaging.';
                chatInput.disabled = true;
                chatSend.disabled = true;
                return;
            }

            const activeKeyExists = adminConversations.some(c => c.key === adminActiveConversationKey);
            if (!activeKeyExists) {
                adminActiveConversationKey = adminConversations[0].key;
            }

            adminConversations.forEach(conversation => {
                const item = document.createElement('button');
                item.type = 'button';
                  item.className = 'admin-conversation-item w-full text-left p-4 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors';

                const isActive = conversation.key === adminActiveConversationKey;
                if (isActive) {
                    item.className += ' bg-indigo-50 dark:bg-indigo-900/40';
                }

                const name = conversation.user.name || conversation.user.email || 'Guest';
                const email = conversation.user.email || '';
                const latestMessage = conversation.latest?.message || '';
                const latestTime = conversation.latest?.created_at ? new Date(conversation.latest.created_at) : null;
                const timeLabel = latestTime ? latestTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';
                const unreadCount = getConversationUnreadCount(conversation);
                const unreadLabel = unreadCount > 0 ? `<span class="unread-pill">${unreadCount > 99 ? '99+' : unreadCount}</span>` : '';

                item.innerHTML = `
                    <div class="flex items-center justify-between gap-3">
                        <div class="font-semibold text-gray-900 dark:text-white truncate">${name}</div>
                        <div class="flex items-center gap-2 text-xs text-gray-400">${unreadLabel}<span>${timeLabel}</span></div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">${email}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 truncate mt-1">${latestMessage}</div>
                `;

                item.addEventListener('click', () => {
                    adminActiveConversationKey = conversation.key;
                    markConversationRead(conversation);
                    renderAdminMessages();
                });

                conversationsList.appendChild(item);
            });

            const activeConversation = adminConversations.find(c => c.key === adminActiveConversationKey);
            if (!activeConversation) {
                return;
            }

            markConversationRead(activeConversation);

            const activeName = activeConversation.user.name || activeConversation.user.email || 'Guest';
            const activeEmail = activeConversation.user.email || 'No email on file';
            chatTitle.textContent = activeName;
            chatSubtitle.textContent = activeEmail;
            chatInput.disabled = false;
            chatSend.disabled = false;

            activeConversation.messages.forEach(message => {
                const isAdmin = getSenderRole(message) === 'admin';
                const row = document.createElement('div');
                row.className = `flex ${isAdmin ? 'justify-end' : 'justify-start'}`;

                const bubble = document.createElement('div');
                bubble.className = `max-w-[70%] rounded-2xl px-4 py-3 text-sm shadow ${isAdmin ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100'}`;
                bubble.textContent = message.message || '';

                const meta = document.createElement('div');
                meta.className = `mt-1 text-xs ${isAdmin ? 'text-indigo-200' : 'text-gray-400'}`;
                if (message.created_at) {
                    meta.textContent = new Date(message.created_at).toLocaleString();
                }

                bubble.appendChild(meta);
                row.appendChild(bubble);
                chatMessages.appendChild(row);
            });

            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        async function sendAdminMessageFromUI(event) {
            if (event) {
                event.preventDefault();
            }
            syncAuthToken();
            if (!authToken) {
                showDialog('Please log in again to send messages.', 'error', 'Session Expired');
                return;
            }
            const input = document.getElementById('adminChatInput');
            const text = input?.value.trim();
            if (!text) return;
            if (!adminActiveConversationKey) {
                showDialog('Select a conversation before sending.', 'info', 'No Conversation Selected');
                return;
            }

            const activeConversation = adminConversations.find(c => c.key === adminActiveConversationKey);
            if (!activeConversation) return;
            if (!activeConversation.user.id && !activeConversation.user.email) {
                showDialog('Cannot reply without a user email.', 'info', 'Reply Unavailable');
                return;
            }

            try {
                const sentMessage = await sendChatMessage(text, {
                    recipient_id: activeConversation.user.id,
                    recipient_email: activeConversation.user.email || undefined,
                });
                adminMessages.unshift(sentMessage);
                if (input) {
                    input.value = '';
                }
                renderAdminMessages();
            } catch (error) {
                console.error('Failed to send admin message:', error);
                showDialog(error.message || 'Failed to send message.', 'error', 'Message Error');
            }
        }

        function setupAdminMessages() {
            if (messagesSetup) return;
            messagesSetup = true;
            loadAdminMessages();

            const refreshBtn = document.getElementById('refreshMessagesBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => {
                    loadAdminMessages();
                });
            }


            if (window.Echo) {
                window.Echo.private('admin.chat')
                    .listen('.chat.message', (event) => {
                        if (event && event.message) {
                            adminMessages.unshift(event.message);
                            renderAdminMessages();
                        }
                    });
            }

            if (!messagesRefreshInterval) {
                messagesRefreshInterval = setInterval(() => {
                    loadAdminMessages();
                }, 2000);
            }
        }

        // Navigation setup function - call this when dashboard is shown
        let sidebarNavigationSetup = false;
        function setupSidebarNavigation() {
            // Re-query elements to ensure they exist (dashboard might have been hidden)
            const sidebarLinksElements = document.querySelectorAll('.sidebar-link');
            
            if (sidebarLinksElements.length === 0) {
                console.warn('No sidebar links found');
                return false;
            }
            
            // Only setup once, unless we're re-setting up
            if (sidebarNavigationSetup) {
                // Remove old listeners by using a single delegated listener
                return true;
            }
            
            // Use event delegation on the sidebar container
            const sidebarContainer = document.getElementById('sidebarContainer') || document.querySelector('.w-64.bg-white, .w-64.bg-gray-800');
            const handleNavClick = function(link) {
                const targetSection = link.getAttribute('data-section');

                if (!targetSection) {
                    console.warn('No data-section attribute found on link');
                    return;
                }

                // Re-query to get fresh list (in case DOM changed)
                const allSidebarLinks = document.querySelectorAll('.sidebar-link');
                const allSections = document.querySelectorAll('.section');

                // Update active nav link
                allSidebarLinks.forEach(nav => nav.classList.remove('active'));
                link.classList.add('active');

                // Show target section
                allSections.forEach(section => section.classList.add('hidden'));
                const targetSectionEl = document.getElementById(`${targetSection}Section`);
                if (targetSectionEl) {
                    targetSectionEl.classList.remove('hidden');
                } else {
                    console.warn(`Section ${targetSection}Section not found`);
                }

                // Load section-specific data
                if (targetSection === 'bookings') {
                    displayAllBookings();
                } else if (targetSection === 'payments') {
                    displayPayments();
                } else if (targetSection === 'overview') {
                    displayOverview();
                    initializeCharts();
                } else if (targetSection === 'analytics') {
                    initializeCharts();
                } else if (targetSection === 'managers') {
                    loadManagers();
                } else if (targetSection === 'lots') {
                    loadLotsAdmin();
                } else if (targetSection === 'pricing') {
                    // Pricing section doesn't need special data loading
                  } else if (targetSection === 'messages') {
                      loadAdminMessages();
                      if (!messagesRefreshInterval) {
                          messagesRefreshInterval = setInterval(() => {
                              loadAdminMessages();
                          }, 2000);
                      }
                  }
              };

            if (sidebarContainer) {
                sidebarContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('.sidebar-link');
                    if (!link) return;
                    e.preventDefault();
                    e.stopPropagation();
                    handleNavClick(link);
                });
            } else {
                console.warn('Sidebar container not found. Falling back to direct link listeners.');
                sidebarLinksElements.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        handleNavClick(link);
                    });
                });
            }

            sidebarNavigationSetup = true;
            return true;
        }

        // DOM Elements - will be queried after DOM loads
        let loginScreen, dashboard, loginForm, loginError, logoutBtn;
        
        let logoutInProgress = false;

        function goToFindParking() {
            localStorage.setItem('redirectToFindParking', '1');
            window.location.assign('/');
        }

        function toggleLogoutMenu(event, forceOpen = null) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const menu = document.getElementById('logoutMenu');
            if (!menu) return;
            const shouldOpen = forceOpen !== null ? forceOpen : menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !shouldOpen);
        }

        async function handleAdminLogout(event) {
            if (event) {
                event.preventDefault();
            }
            if (logoutInProgress) return;
            logoutInProgress = true;

            // Clear timers/connections immediately
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
            if (messagesRefreshInterval) {
                clearInterval(messagesRefreshInterval);
            }
            if (window.Echo) {
                window.Echo.disconnect();
            }

            // Clear local auth immediately so UI reacts even if network fails
            localStorage.removeItem('role');
            localStorage.removeItem('userProfile');
            localStorage.removeItem('isAdminLoggedIn');
            localStorage.removeItem('isAdmin');
            localStorage.removeItem('authToken');
            localStorage.setItem('redirectToFindParking', '1');
            syncAuthToken();
            adminProfile = null;

            // Fire backend logout but do not block redirect
            if (typeof logoutUser === 'function') {
                logoutUser().catch(err => console.error('Logout error:', err));
            }

            // Always navigate away
            setTimeout(() => {
                window.location.assign('/');
            }, 10);
        }

        window.handleAdminLogout = handleAdminLogout;

        async function loadStats() {
            try {
                adminStats = await fetchAdminStats();
                try {
                    adminLots = await fetchAdminLots();
                    adminTotalSpots = Array.isArray(adminLots)
                        ? adminLots.reduce((sum, lot) => sum + (parseInt(lot.total, 10) || 0), 0)
                        : 0;
                } catch (lotError) {
                    console.warn('Failed to load admin lots for occupancy:', lotError);
                    adminLots = [];
                    adminTotalSpots = 0;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', async function() {
            // Wait a bit for api.js to fully load
            await new Promise(resolve => setTimeout(resolve, 100));
            
            // Query DOM elements
            loginScreen = document.getElementById('loginScreen');
            dashboard = document.getElementById('dashboard');
            loginForm = document.getElementById('loginForm');
            loginError = document.getElementById('loginError');
            logoutBtn = document.getElementById('logoutBtn');
            
            if (!dashboard) {
                console.error('Admin dashboard container not found');
            }
            
            if (logoutBtn) {
                logoutBtn.addEventListener('click', toggleLogoutMenu);
            }
            document.addEventListener('click', function (e) {
                const menu = document.getElementById('logoutMenu');
                if (menu && !menu.classList.contains('hidden')) {
                    const container = e.target.closest('#logoutMenu');
                    const button = e.target.closest('#logoutBtn');
                    if (!container && !button) {
                        menu.classList.add('hidden');
                    }
                }
                const target = e.target.closest('#logoutBtn');
                if (!target) return;
                toggleLogoutMenu(e);
            });

            // Refresh token from localStorage
            syncAuthToken();
            setupSidebarNavigation();
            initializeReverbEcho();
            const currentToken = localStorage.getItem('authToken');
            const currentIsAdmin = localStorage.getItem('isAdmin') === 'true';
            
            // Check if user is already logged in as admin via API
            if (currentToken && currentIsAdmin) {
                try {
                    // Verify token is still valid by getting profile
                    const profile = await getProfile();
                    const user = profile.data?.user || profile.data || profile;
                    adminProfile = user;
                    const isAdminFromProfile = profile.data?.is_admin || user?.is_admin || false;
                    
                    if (user && (isAdminFromProfile === true || isAdminFromProfile === 1 || user.is_admin === true || user.is_admin === 1)) {
                        // Ensure admin flag is set
                        localStorage.setItem('isAdmin', 'true');
                        await showDashboard();
                        try {
                            await loadData();
                            await loadStats();
                            displayOverview();
                            initializeCharts();
                        } catch (error) {
                            console.error('Admin data load failed:', error);
                        }
                        // Start auto-refresh
                        refreshInterval = setInterval(async () => {
                            await loadData();
                            await loadStats();
                            const activeSection = document.querySelector('.section:not(.hidden)');
                            if (activeSection && activeSection.id === 'overviewSection') {
                                displayOverview();
                                initializeCharts(); // Re-initialize charts with new data
                            } else if (activeSection && activeSection.id === 'bookingsSection') {
                                displayAllBookings();
                            } else if (activeSection && activeSection.id === 'paymentsSection') {
                                displayPayments();
                            }
                        }, 30000);
                        return;
                    } else {
                        // User is not admin, clear admin flag and redirect
                        localStorage.removeItem('isAdmin');
                        localStorage.removeItem('authToken');
                        window.location.href = '/';
                        return;
                    }
                } catch (error) {
                    console.error('Profile check error:', error);
                    // Token invalid, show login
                    localStorage.removeItem('authToken');
                    localStorage.removeItem('isAdmin');
                }
            } else if (currentToken && !currentIsAdmin) {
                // User is logged in but not admin, redirect to main page
                window.location.href = '/';
                return;
            }
            
            // Login form submission
            if (loginForm) {
                loginForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    if (loginError) {
                        loginError.classList.add('hidden');
                    }
                    const email = document.getElementById('email')?.value || '';
                    const password = document.getElementById('password')?.value || '';
                    
                    try {
                        const user = await loginUser(email, password);
                        adminProfile = user;
                        
                        // Update token after login
                        syncAuthToken();
                        
                        if (user.is_admin === true || user.is_admin === 1) {
                            // Ensure admin flag is set
                            localStorage.setItem('isAdmin', 'true');
                            await showDashboard();
                            try {
                                await loadData();
                                await loadStats();
                                displayOverview();
                                initializeCharts();
                            } catch (error) {
                                console.error('Admin data load failed:', error);
                            }
                            // Start auto-refresh
                            refreshInterval = setInterval(async () => {
                                await loadData();
                                await loadStats();
                                const activeSection = document.querySelector('.section:not(.hidden)');
                                if (activeSection && activeSection.id === 'overviewSection') {
                                    displayOverview();
                                    initializeCharts(); // Re-initialize charts with new data
                                } else if (activeSection && activeSection.id === 'bookingsSection') {
                                    displayAllBookings();
                                } else if (activeSection && activeSection.id === 'paymentsSection') {
                                    displayPayments();
                                }
                            }, 30000);
                        } else if (loginError) {
                            loginError.textContent = 'Access denied. Admin credentials required.';
                            loginError.classList.remove('hidden');
                            // Logout the user since they're not admin
                            try {
                                await logoutUser();
                            } catch (e) {
                                console.error('Logout error:', e);
                            }
                            syncAuthToken();
                        }
                    } catch (error) {
                        if (loginError) {
                            loginError.textContent = error.message || 'Invalid credentials. Please try again.';
                            loginError.classList.remove('hidden');
                        }
                    }
                });
            }
            
            // Setup navigation initially (after DOM is ready)
            setupSidebarNavigation();
            
            // Booking filter
            const bookingFilterEl = document.getElementById('bookingFilter');
            if (bookingFilterEl) {
                bookingFilterEl.addEventListener('change', function() {
                    displayAllBookings();
                });
            }
            
            // Booking search
            const bookingSearchEl = document.getElementById('bookingSearch');
            if (bookingSearchEl) {
                bookingSearchEl.addEventListener('input', function() {
                    displayAllBookings();
                });
            }
            
            // Payment filter
            const paymentFilterEl = document.getElementById('paymentFilter');
            if (paymentFilterEl) {
                paymentFilterEl.addEventListener('change', function() {
                    displayPayments();
                });
            }
            
            // Payment search
            const paymentSearchEl = document.getElementById('paymentSearch');
            if (paymentSearchEl) {
                paymentSearchEl.addEventListener('input', function() {
                    displayPayments();
                });
            }
            
            // Price form submission
            const priceFormEl = document.getElementById('priceForm');
            if (priceFormEl) {
                priceFormEl.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const priceValue = document.getElementById('priceValue').value;
                    const priceType = priceFormEl.getAttribute('data-price-type');
                    
                    // In a real app, you would save this to a backend
                    showDialog(`Price for ${priceType} updated to UGX ${priceValue}`, 'success', 'Price Updated');
                    closeModal('editPriceModal');
                });
            }
            
        });
        
        async function showDashboard() {
            if (loginScreen) {
                loginScreen.classList.add('hidden');
            }
            if (dashboard) {
                dashboard.classList.remove('hidden');
            }
            await loadData();
            if (!adminStats || Object.keys(adminStats).length === 0) {
                await loadStats();
            }
            
            // Setup sidebar navigation after dashboard is visible
            setTimeout(() => {
                setupSidebarNavigation();
            }, 100);

            setupAdminMessages();
            
            displayOverview();
            // Initialize charts after stats are loaded
            initializeCharts();
        }
        
        function showLogin() {
            if (dashboard) {
                dashboard.classList.add('hidden');
            }
            if (loginScreen) {
                loginScreen.classList.remove('hidden');
            }
            if (loginForm) {
                loginForm.reset();
            }
            if (loginError) {
                loginError.classList.add('hidden');
            }
        }
        
        function initializeCharts() {
            function buildBookingTrendFromData(bookings) {
                const labels = [];
                const counts = [];
                const today = new Date();
                const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                const dateCounts = {};

                (bookings || []).forEach((booking) => {
                    const rawDate = booking.created_at || booking.date || '';
                    const datePart = String(rawDate).split('T')[0].split(' ')[0];
                    if (!/^\d{4}-\d{2}-\d{2}$/.test(datePart)) return;
                    dateCounts[datePart] = (dateCounts[datePart] || 0) + 1;
                });

                for (let i = 6; i >= 0; i--) {
                    const day = new Date(today);
                    day.setDate(today.getDate() - i);
                    const y = day.getFullYear();
                    const m = String(day.getMonth() + 1).padStart(2, '0');
                    const d = String(day.getDate()).padStart(2, '0');
                    const key = `${y}-${m}-${d}`;
                    labels.push(dayNames[day.getDay()]);
                    counts.push(dateCounts[key] || 0);
                }

                return { labels, counts };
            }

            function buildRevenueByMethodFromData(payments) {
                const totals = {};
                (payments || []).forEach((payment) => {
                    const status = String(payment.status || '').toLowerCase();
                    if (status && status !== 'completed') {
                        return;
                    }
                    const method = String(payment.method || 'Unknown');
                    const amount = parseFloat(payment.amount) || 0;
                    totals[method] = (totals[method] || 0) + amount;
                });
                return totals;
            }

            function buildRevenueTrendFromData(payments) {
                const now = new Date();
                const labels = [];
                const values = [];
                const monthlyTotals = {};

                (payments || []).forEach((payment) => {
                    const status = String(payment.status || '').toLowerCase();
                    if (status && status !== 'completed') {
                        return;
                    }
                    const rawDate = payment.date || payment.created_at || '';
                    const datePart = String(rawDate).split('T')[0].split(' ')[0];
                    if (!/^\d{4}-\d{2}-\d{2}$/.test(datePart)) return;
                    const [year, month] = datePart.split('-');
                    const key = `${year}-${month}`;
                    monthlyTotals[key] = (monthlyTotals[key] || 0) + (parseFloat(payment.amount) || 0);
                });

                for (let i = 5; i >= 0; i--) {
                    const date = new Date(now.getFullYear(), now.getMonth() - i, 1);
                    const y = date.getFullYear();
                    const m = String(date.getMonth() + 1).padStart(2, '0');
                    const key = `${y}-${m}`;
                    const label = date.toLocaleString('en-US', { month: 'short' });
                    labels.push(label);
                    values.push(monthlyTotals[key] || 0);
                }

                return { labels, values };
            }

            // Check if chart elements exist
            const bookingsChartEl = document.getElementById('bookingsChart');
            const revenueChartEl = document.getElementById('revenueChart');
            
            if (!bookingsChartEl && !revenueChartEl) {
                console.warn('Overview charts not found, continuing with analytics charts');
            }
            
            // Destroy existing charts if they exist
            if (window.bookingsChart) {
                try { window.bookingsChart.destroy(); } catch(e) { console.warn('Error destroying bookings chart:', e); }
            }
            if (window.revenueChart) {
                try { window.revenueChart.destroy(); } catch(e) { console.warn('Error destroying revenue chart:', e); }
            }
            if (window.revenueTrendsChart) {
                try { window.revenueTrendsChart.destroy(); } catch(e) {}
            }
            if (window.occupancyChart) {
                try { window.occupancyChart.destroy(); } catch(e) {}
            }
            
            // Bookings Chart
            if (bookingsChartEl) {
                const bookingsCtx = bookingsChartEl.getContext('2d');
                let bookingsLabels = adminStats.bookings_trend?.map(b => b.date) || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                let bookingsData = adminStats.bookings_trend?.map(b => b.count) || [0, 0, 0, 0, 0, 0, 0];

                if (!adminStats.bookings_trend?.length || bookingsData.every(d => d === 0)) {
                    const fallback = buildBookingTrendFromData(allBookingsData);
                    bookingsLabels = fallback.labels;
                    bookingsData = fallback.counts;
                }
                
                console.log('Bookings Chart Data:', { labels: bookingsLabels, data: bookingsData, adminStats });
                
                window.bookingsChart = new Chart(bookingsCtx, {
                    type: 'line',
                    data: {
                        labels: bookingsLabels,
                        datasets: [{
                            label: 'Daily Bookings',
                            data: bookingsData,
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true,
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        }
                    }
                });
            }
            
            // Revenue Chart (by payment method)
            if (revenueChartEl) {
                const revenueCtx = revenueChartEl.getContext('2d');
                let revenueByMethod = adminStats.revenue_by_method || {};
                const methodLabels = Object.keys(revenueByMethod);
                const methodData = Object.values(revenueByMethod).map(v => parseFloat(v) || 0);
                const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
                
                console.log('Revenue Chart Data:', { labels: methodLabels, data: methodData, revenueByMethod, adminStats });
                
                // Only create chart if we have data
                if (methodLabels.length === 0 || methodData.every(d => d === 0)) {
                    revenueByMethod = buildRevenueByMethodFromData(paymentsData);
                }

                const fallbackLabels = Object.keys(revenueByMethod);
                const fallbackData = Object.values(revenueByMethod).map(v => parseFloat(v) || 0);

                if (fallbackLabels.length > 0 && fallbackData.some(d => d > 0)) {
                    window.revenueChart = new Chart(revenueCtx, {
                        type: 'doughnut',
                        data: {
                            labels: fallbackLabels,
                            datasets: [{
                                data: fallbackData,
                                backgroundColor: colors.slice(0, fallbackLabels.length),
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                            return `${label}: UGX ${value.toFixed(2)} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    // Show placeholder if no data
                    console.log('No revenue data available for chart');
                    revenueCtx.clearRect(0, 0, revenueChartEl.width, revenueChartEl.height);
                    revenueCtx.fillStyle = '#9ca3af';
                    revenueCtx.font = '14px Arial';
                    revenueCtx.textAlign = 'center';
                    revenueCtx.textBaseline = 'middle';
                    revenueCtx.fillText('No payment data available', revenueChartEl.width / 2, revenueChartEl.height / 2);
                }
            }
            
            // Revenue Trends Chart
            const revenueTrendsEl = document.getElementById('revenueTrendsChart');
            if (revenueTrendsEl) {
                const revenueTrendsCtx = revenueTrendsEl.getContext('2d');
                let revenueLabels = adminStats.revenue_trend?.map(r => r.month) || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                let revenueData = adminStats.revenue_trend?.map(r => r.revenue) || [0, 0, 0, 0, 0, 0];

                if (!adminStats.revenue_trend?.length || revenueData.every(v => v === 0)) {
                    const fallback = buildRevenueTrendFromData(paymentsData);
                    revenueLabels = fallback.labels;
                    revenueData = fallback.values;
                }
                
                window.revenueTrendsChart = new Chart(revenueTrendsCtx, {
                    type: 'bar',
                    data: {
                        labels: revenueLabels,
                        datasets: [{
                            label: 'Monthly Revenue',
                            data: revenueData,
                            backgroundColor: '#6366f1'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        }
                    }
                });
            }
            
            // Occupancy Chart (simplified - using daily data)
            const occupancyEl = document.getElementById('occupancyChart');
            if (occupancyEl) {
                const occupancyCtx = occupancyEl.getContext('2d');
                const occupancyLabels = ['6AM', '9AM', '12PM', '3PM', '6PM', '9PM'];
                // For now, use a simplified occupancy based on current rate
                let currentOccupancy = adminStats.occupancy_rate || 0;

                if (!currentOccupancy && adminTotalSpots > 0) {
                    const activeCount = (allBookingsData || []).filter(b => b.status === 'active' || b.isCurrentlyActive).length;
                    currentOccupancy = Math.round((activeCount / adminTotalSpots) * 100);
                }
                const occupancyData = occupancyLabels.map((_, i) => {
                    // Simulate hourly variation around current rate
                    const variation = (i % 2 === 0 ? 1.2 : 0.8);
                    return Math.min(100, Math.max(0, currentOccupancy * variation));
                });
                
                window.occupancyChart = new Chart(occupancyCtx, {
                    type: 'line',
                    data: {
                        labels: occupancyLabels,
                        datasets: [{
                            label: 'Occupancy Rate',
                            data: occupancyData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        }
                    }
                });
            }
        }
        
        function displayOverview() {
            // Update stats from adminStats
            if (adminStats.active_bookings !== undefined) {
                document.getElementById('activeBookingsCount').textContent = adminStats.active_bookings;
            } else {
                document.getElementById('activeBookingsCount').textContent = bookingsData.filter(b => b.status === "active" || b.status === "upcoming").length;
            }
            
            if (adminStats.today_revenue !== undefined) {
                document.getElementById('todayRevenue').textContent = `UGX ${adminStats.today_revenue.toFixed(2)}`;
            } else {
                const today = new Date().toISOString().split("T")[0];
                const todayPayments = paymentsData.filter(p => p.date?.includes(today));
                const todayRevenue = todayPayments.reduce((sum, p) => sum + Number(p.amount), 0);
                document.getElementById('todayRevenue').textContent = `UGX ${todayRevenue.toFixed(2)}`;
            }
            
            if (adminStats.occupancy_rate !== undefined) {
                document.getElementById('occupancyRate').textContent = `${adminStats.occupancy_rate}%`;
            }
            
            if (adminStats.avg_duration !== undefined) {
                document.getElementById('avgDuration').textContent = `${adminStats.avg_duration} hrs`;
            }
            
            // Display current bookings (only active ones)
            const currentBookingsTable = document.getElementById('currentBookingsTable');
            currentBookingsTable.innerHTML = '';
            
            // Filter to show only currently active bookings
            const activeBookings = bookingsData.filter(booking => {
                // Show if status is active or if it's currently active based on time
                return booking.status === 'active' || booking.isCurrentlyActive === true;
            });
            
            if (activeBookings.length === 0) {
                currentBookingsTable.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No active bookings at the moment
                        </td>
                    </tr>
                `;
                return;
            }
            
            activeBookings.forEach(booking => {
                const row = document.createElement('tr');
                
                // Determine progress bar color based on time left
                let progressColor = 'bg-green-500';
                if (booking.timeLeft.includes('30m') || booking.timeLeft.includes('15m')) {
                    progressColor = 'bg-red-500';
                } else if (booking.timeLeft.includes('45m')) {
                    progressColor = 'bg-yellow-500';
                }
                
                // Calculate progress percentage (simplified)
                let progressPercent = 70;
                if (booking.timeLeft.includes('30m') || booking.timeLeft.includes('15m')) {
                    progressPercent = 20;
                } else if (booking.timeLeft.includes('45m')) {
                    progressPercent = 45;
                }
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white">${booking.spot}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${booking.vehicle}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${booking.startTime}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <div class="flex items-center">
                            <span class="mr-2">${booking.timeLeft}</span>
                            <div class="w-16 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 rounded-full ${progressColor}" style="width: ${progressPercent}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">UGX ${booking.amount}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <button class="text-indigo-600 hover:text-indigo-900 mr-3">Extend</button>
                        <button class="text-red-600 hover:text-red-900">Cancel</button>
                    </td>
                `;
                
                currentBookingsTable.appendChild(row);
            });
        }
        
        function displayAllBookings() {
            const filter = document.getElementById('bookingFilter').value;
            const searchTerm = document.getElementById('bookingSearch').value.toLowerCase();
            
            const allBookingsTable = document.getElementById('allBookingsTable');
            allBookingsTable.innerHTML = '';
            
            const filteredBookings = allBookingsData.filter(booking => {
                const matchesFilter = filter === 'all' || booking.status === filter;
                const matchesSearch = 
                    booking.customer.toLowerCase().includes(searchTerm) ||
                    booking.spot.toLowerCase().includes(searchTerm) ||
                    booking.vehicle.toLowerCase().includes(searchTerm);
                
                return matchesFilter && matchesSearch;
            });
            
            if (filteredBookings.length === 0) {
                allBookingsTable.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No bookings found matching your criteria
                        </td>
                    </tr>
                `;
                return;
            }
            
            filteredBookings.forEach(booking => {
                const row = document.createElement('tr');
                
                let statusClass = 'status-active';
                if (booking.status === 'completed') {
                    statusClass = 'status-completed';
                } else if (booking.status === 'cancelled') {
                    statusClass = 'status-cancelled';
                }
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white">${booking.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${booking.customer}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${booking.spot}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${booking.vehicle}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${booking.duration}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">UGX ${booking.amount}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge ${statusClass}">${booking.status}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <button class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                        <button class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                `;
                
                allBookingsTable.appendChild(row);
            });
        }
        
        function displayPayments() {
            const filter = document.getElementById('paymentFilter').value;
            const searchTerm = document.getElementById('paymentSearch').value.toLowerCase();
            
            const paymentsTable = document.getElementById('paymentsTable');
            paymentsTable.innerHTML = '';
            
            const filteredPayments = paymentsData.filter(payment => {
                const matchesFilter = filter === 'all' || payment.status === filter;
                const matchesSearch = 
                    payment.customer.toLowerCase().includes(searchTerm) ||
                    payment.method.toLowerCase().includes(searchTerm);
                
                return matchesFilter && matchesSearch;
            });
            
            if (filteredPayments.length === 0) {
                paymentsTable.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No payments found matching your criteria
                        </td>
                    </tr>
                `;
                return;
            }
            
            filteredPayments.forEach(payment => {
                const row = document.createElement('tr');
                
                let statusClass = 'status-active';
                if (payment.status === 'completed') {
                    statusClass = 'status-completed';
                } else if (payment.status === 'failed') {
                    statusClass = 'status-cancelled';
                }
                
                const methodDisplay = payment.method + (payment.phone ? ` (${payment.phone})` : '') + (payment.card_last4 ? ` (****${payment.card_last4})` : '');
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium dark:text-white">${payment.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${payment.customer}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${payment.date} ${payment.time || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">UGX ${payment.amount.toFixed(2)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">${methodDisplay}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge ${statusClass}">${payment.status}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <button class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                        <button class="text-red-600 hover:text-red-900">Refund</button>
                    </td>
                `;
                
                paymentsTable.appendChild(row);
            });
        }
        
        function editPrice(priceType) {
            const modal = document.getElementById('editPriceModal');
            const modalTitle = document.getElementById('modalTitle');
            const priceForm = document.getElementById('priceForm');
            const priceValue = document.getElementById('priceValue');
            
            // Set modal title based on price type
            const priceTitles = {
                firstHour: "First Hour Rate",
                additionalHour: "Additional Hour Rate",
                dailyMax: "Daily Maximum Rate",
                monthlyPass: "Monthly Pass Rate",
                eveningRate: "Evening Rate",
                weekendRate: "Weekend Rate",
                evFee: "EV Charging Fee"
            };
            
            modalTitle.textContent = `Edit ${priceTitles[priceType]}`;
            priceForm.setAttribute('data-price-type', priceType);
            
            // Set current value (in a real app, this would come from your backend)
            const currentValues = {
                firstHour: 5.00,
                additionalHour: 3.00,
                dailyMax: 25.00,
                monthlyPass: 150.00,
                eveningRate: 2.50,
                weekendRate: 4.00,
                evFee: 2.00
            };
            
            priceValue.value = currentValues[priceType];
            
            // Show modal
            modal.style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target === modals[i]) {
                    modals[i].style.display = 'none';
                }
            }
        }
    </script>

    <!-- Beautiful Dialog Component -->
    <div id="customDialog" class="custom-dialog">
        <div class="dialog-overlay"></div>
        <div class="dialog-container">
            <div class="dialog-icon" id="dialogIcon"></div>
            <h3 class="dialog-title" id="dialogTitle">Notification</h3>
            <p class="dialog-message" id="dialogMessage"></p>
            <button class="dialog-button" id="dialogButton" onclick="closeDialog()">OK</button>
        </div>
    </div>

    <style>
    .custom-dialog {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .custom-dialog.show {
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    .dialog-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .dialog-container {
        position: relative;
        background: white;
        border-radius: 20px;
        padding: 32px;
        max-width: 420px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        text-align: center;
        animation: slideUp 0.3s ease;
        z-index: 10001;
    }

    .dark .dialog-container {
        background: #1f2937;
        color: white;
    }

    .dialog-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        animation: scaleIn 0.3s ease;
    }

    .dialog-icon.success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .dialog-icon.error {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .dialog-icon.info {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .dialog-icon.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .dialog-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 12px 0;
        color: #111827;
    }

    .dark .dialog-title {
        color: white;
    }

    .dialog-message {
        font-size: 16px;
        line-height: 1.6;
        color: #6b7280;
        margin: 0 0 24px 0;
        white-space: pre-line;
    }

    .dark .dialog-message {
        color: #d1d5db;
    }

    .dialog-button {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        border: none;
        padding: 12px 32px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .dialog-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
    }

    .dialog-button:active {
        transform: translateY(0);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }
    </style>

    <script>
    function showDialog(message, type = 'info', title = null) {
        const dialog = document.getElementById('customDialog');
        const dialogIcon = document.getElementById('dialogIcon');
        const dialogTitle = document.getElementById('dialogTitle');
        const dialogMessage = document.getElementById('dialogMessage');
        
        dialogIcon.className = 'dialog-icon ' + type;
        
        const icons = {
            success: '✓',
            error: '✕',
            info: 'ℹ',
            warning: '⚠'
        };
        
        dialogIcon.textContent = icons[type] || icons.info;
        
        if (title) {
            dialogTitle.textContent = title;
        } else {
            const titles = {
                success: 'Success',
                error: 'Error',
                info: 'Notification',
                warning: 'Warning'
            };
            dialogTitle.textContent = titles[type] || titles.info;
        }
        
        dialogMessage.textContent = message;
        dialog.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDialog() {
        const dialog = document.getElementById('customDialog');
        dialog.classList.remove('show');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dialog = document.getElementById('customDialog');
        const overlay = dialog.querySelector('.dialog-overlay');
        
        overlay.addEventListener('click', closeDialog);
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dialog.classList.contains('show')) {
                closeDialog();
            }
        });
    });
    </script>
    <script src="api.js"></script>
</body>
</html>
