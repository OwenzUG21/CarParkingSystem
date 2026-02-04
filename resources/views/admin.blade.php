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
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            color: white;
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
    <div id="dashboard" class="hidden">
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
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="pricing">
                            <span class="material-symbols-outlined">edit</span>
                            <span>Pricing</span>
                        </a>
                        <a href="#" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300" data-section="analytics">
                            <span class="material-symbols-outlined">analytics</span>
                            <span>Analytics</span>
                        </a>
                    </nav>
                </div>
                
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">A</div>
                        <div>
                            <div class="font-medium dark:text-white">Admin User</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Parking Manager</div>
                        </div>
                    </div>
                    <button id="logoutBtn" class="w-full flex items-center gap-3 rounded-lg px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="flex-1 overflow-auto bg-gray-50 dark:bg-gray-900">
                <!-- Overview Section -->
                <div id="overviewSection" class="section p-6 active">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold dark:text-white">Dashboard Overview</h1>
                        <p class="text-gray-600 dark:text-gray-400">Welcome back! Here's an overview of your parking operations.</p>
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
        let adminStats = {};
        
        // Auto-refresh data every 30 seconds
        let refreshInterval;

        // Navigation setup function - call this when dashboard is shown
        let sidebarNavigationSetup = false;
        function setupSidebarNavigation() {
            // Re-query elements to ensure they exist (dashboard might have been hidden)
            const sidebarLinksElements = document.querySelectorAll('.sidebar-link');
            const sectionsElements = document.querySelectorAll('.section');
            
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
            if (!sidebarContainer) {
                console.warn('Sidebar container not found');
                return false;
            }
            
            // Add single delegated event listener
            sidebarContainer.addEventListener('click', function(e) {
                const link = e.target.closest('.sidebar-link');
                if (!link) return;
                
                e.preventDefault();
                e.stopPropagation();
                
                const targetSection = link.getAttribute('data-section');
                
                if (!targetSection) {
                    console.warn('No data-section attribute found on link');
                    return;
                }
                
                console.log('Navigating to:', targetSection);
                
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
                    console.log('Section shown:', targetSection);
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
                } else if (targetSection === 'pricing') {
                    // Pricing section doesn't need special data loading
                }
            });
            
            sidebarNavigationSetup = true;
            return true;
        }

        // DOM Elements - will be queried after DOM loads
        let loginScreen, dashboard, loginForm, loginError, logoutBtn;
        
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
            
            // Check if elements exist
            if (!loginScreen || !dashboard || !loginForm || !loginError || !logoutBtn) {
                console.error('Required DOM elements not found');
                return;
            }
            
            // Logout functionality
            logoutBtn.addEventListener('click', async function() {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                }
                try {
                    await logoutUser();
                } catch (error) {
                    console.error('Logout error:', error);
                }
                localStorage.removeItem('isAdmin');
                localStorage.removeItem('authToken');
                syncAuthToken();
                showLogin();
            });

            // Refresh token from localStorage
            syncAuthToken();
            const currentToken = localStorage.getItem('authToken');
            const currentIsAdmin = localStorage.getItem('isAdmin') === 'true';
            
            // Check if user is already logged in as admin via API
            if (currentToken && currentIsAdmin) {
                try {
                    // Verify token is still valid by getting profile
                    const profile = await getProfile();
                    const user = profile.data?.user || profile.data || profile;
                    const isAdminFromProfile = profile.data?.is_admin || user?.is_admin || false;
                    
                    if (user && (isAdminFromProfile === true || isAdminFromProfile === 1 || user.is_admin === true || user.is_admin === 1)) {
                        // Ensure admin flag is set
                        localStorage.setItem('isAdmin', 'true');
                        await loadData();
                        await loadStats();
                        await showDashboard();
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
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                loginError.classList.add('hidden');
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                try {
                    const user = await loginUser(email, password);
                    
                    // Update token after login
                    syncAuthToken();
                    
                    if (user.is_admin === true || user.is_admin === 1) {
                        // Ensure admin flag is set
                        localStorage.setItem('isAdmin', 'true');
                        await loadData();
                        await loadStats();
                        await showDashboard();
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
                    } else {
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
                    loginError.textContent = error.message || 'Invalid credentials. Please try again.';
                    loginError.classList.remove('hidden');
                }
            });
            
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
            
            // Load stats function
            async function loadStats() {
                try {
                    adminStats = await fetchAdminStats();
                } catch (error) {
                    console.error('Error loading stats:', error);
                }
            }
        });
        
        async function showDashboard() {
            loginScreen.classList.add('hidden');
            dashboard.classList.remove('hidden');
            // Make sure stats are loaded before displaying
            if (!adminStats || Object.keys(adminStats).length === 0) {
                await loadStats();
            }
            
            // Setup sidebar navigation after dashboard is visible
            setTimeout(() => {
                setupSidebarNavigation();
            }, 100);
            
            displayOverview();
            // Initialize charts after stats are loaded
            initializeCharts();
        }
        
        function showLogin() {
            dashboard.classList.add('hidden');
            loginScreen.classList.remove('hidden');
            loginForm.reset();
            loginError.classList.add('hidden');
        }
        
        function initializeCharts() {
            // Check if chart elements exist
            const bookingsChartEl = document.getElementById('bookingsChart');
            const revenueChartEl = document.getElementById('revenueChart');
            
            if (!bookingsChartEl || !revenueChartEl) {
                console.warn('Chart elements not found, charts may not be visible yet');
                return;
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
            const bookingsCtx = bookingsChartEl.getContext('2d');
            const bookingsLabels = adminStats.bookings_trend?.map(b => b.date) || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const bookingsData = adminStats.bookings_trend?.map(b => b.count) || [0, 0, 0, 0, 0, 0, 0];
            
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
            
            // Revenue Chart (by payment method)
            const revenueCtx = revenueChartEl.getContext('2d');
            const revenueByMethod = adminStats.revenue_by_method || {};
            const methodLabels = Object.keys(revenueByMethod);
            const methodData = Object.values(revenueByMethod).map(v => parseFloat(v) || 0);
            const colors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
            
            console.log('Revenue Chart Data:', { labels: methodLabels, data: methodData, revenueByMethod, adminStats });
            
            // Only create chart if we have data
            if (methodLabels.length > 0 && methodData.some(d => d > 0)) {
                window.revenueChart = new Chart(revenueCtx, {
                    type: 'doughnut',
                    data: {
                        labels: methodLabels,
                        datasets: [{
                            data: methodData,
                            backgroundColor: colors.slice(0, methodLabels.length),
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
            
            // Revenue Trends Chart
            const revenueTrendsCtx = document.getElementById('revenueTrendsChart').getContext('2d');
            const revenueLabels = adminStats.revenue_trend?.map(r => r.month) || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const revenueData = adminStats.revenue_trend?.map(r => r.revenue) || [0, 0, 0, 0, 0, 0];
            
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
            
            // Occupancy Chart (simplified - using daily data)
            const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
            const occupancyLabels = ['6AM', '9AM', '12PM', '3PM', '6PM', '9PM'];
            // For now, use a simplified occupancy based on current rate
            const currentOccupancy = adminStats.occupancy_rate || 0;
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