<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkOwenz - Smart Parking System</title>
    <link rel="stylesheet" href="sy.css">
</head>
<body>
    <!-- Landing Page -->
    <div id="landingPage" class="page active">
        <nav class="landing-nav">
            <div class="logo">ParkOwenz</div>
            <button class="cta-btn" onclick="showDashboard()">Get Started</button>
        </nav>
        
        <section class="hero">
            <div class="hero-content">
                <h1 class="hero-title">Find Your Perfect Parking Spot</h1>
                <p class="hero-subtitle">Smart parking solutions for modern cities. Book, navigate, and pay - all in one place.</p>
                <button class="hero-btn" onclick="showDashboard()">Start Parking Smart</button>
            </div>
            
            <div class="parking-animation">
                <div class="parking-lot">
                    <div class="parking-space" style="--delay: 0s">
                        <div class="car car1"></div>
                    </div>
                    <div class="parking-space" style="--delay: 0.5s">
                        <div class="car car2"></div>
                    </div>
                    <div class="parking-space" style="--delay: 1s">
                        <div class="car car3"></div>
                    </div>
                    <div class="parking-space" style="--delay: 1.5s">
                        <div class="car car4"></div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- How It Works Section -->
        <section class="how-it-works">
            <div class="how-container">
                <div class="how-header">
                    <h2 class="how-title">HOW IT WORKS</h2>
                    <p class="how-subtitle">Find and reserve parking in 3 simple steps</p>
                </div>
                
                <div class="how-steps">
                    <div class="how-step">
                        <div class="step-icon">
                            <span class="step-emoji">🔍</span>
                        </div>
                        <h3>SEARCH</h3>
                        <p>Find parking near your destination</p>
                    </div>
                    
                    <div class="how-step">
                        <div class="step-icon">
                            <span class="step-emoji">📅</span>
                        </div>
                        <h3>BOOK</h3>
                        <p>Reserve your spot in advance</p>
                    </div>
                    
                    <div class="how-step">
                        <div class="step-icon">
                            <span class="step-emoji">🚗</span>
                        </div>
                        <h3>PARK</h3>
                        <p>Drive to your guaranteed spot</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Features Section -->
        <section class="main-features">
            <div class="features-container">
                <div class="features-header">
                    <h2 class="features-title">WHY CHOOSE PARKOWENZ</h2>
                    <p class="features-subtitle">The smartest way to park in the city</p>
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3>SAVE MONEY</h3>
                        <p>Compare prices and find the best deals</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">⏰</div>
                        <h3>SAVE TIME</h3>
                        <p>No more circling for parking spots</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <h3>SECURE</h3>
                        <p>Safe and secure parking locations</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3>EASY TO USE</h3>
                        <p>Simple booking from your phone</p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">📍</div>
                <h3>Real-Time Availability</h3>
                <p>See available parking spots in real-time around your location</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🗺️</div>
                <h3>Smart Navigation</h3>
                <p>Get directions to your parking spot with Google Maps integration</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h3>Easy Payment</h3>
                <p>Secure online payment system for hassle-free transactions</p>
            </div>
        </section>
        
        <!-- Footer -->
        <footer class="main-footer" style="background: linear-gradient(135deg, #1e3a8a 0%, #1b3764 100%); color: #fff; padding: 60px 0 30px; margin-top: 80px;">
            <div class="footer-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                <div class="footer-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
                    <!-- Company Section -->
                    <div class="footer-section">
                        <div style="margin-bottom: 20px;">
                            <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 8px; color: #ffffff;">ParkOwenz</h3>
                            <p style="color: #94a3b8; font-size: 14px; line-height: 1.6;">Your smart parking companion for hassle-free urban parking solutions.</p>
                        </div>
                        <div style="display: flex; gap: 12px; margin-top: 20px;">
                            <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">f</a>
                            <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">t</a>
                            <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">in</a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="footer-section">
                        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Quick Links</h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <a href="#" onclick="showAbout()" style="color: #cbd5e1; text-decoration: none; font-size: 15px; transition: all 0.3s ease; padding: 4px 0;" onmouseover="this.style.color='#ffffff'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#cbd5e1'; this.style.paddingLeft='0'">About ParkOwenz</a>
                            <a href="#" onclick="showHowItWorks()" style="color: #cbd5e1; text-decoration: none; font-size: 15px; transition: all 0.3s ease; padding: 4px 0;" onmouseover="this.style.color='#ffffff'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#cbd5e1'; this.style.paddingLeft='0'">How It Works</a>
                            <a href="#" onclick="showServices()" style="color: #cbd5e1; text-decoration: none; font-size: 15px; transition: all 0.3s ease; padding: 4px 0;" onmouseover="this.style.color='#ffffff'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#cbd5e1'; this.style.paddingLeft='0'">Our Services</a>
                            <a href="#" style="color: #cbd5e1; text-decoration: none; font-size: 15px; transition: all 0.3s ease; padding: 4px 0;" onmouseover="this.style.color='#ffffff'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#cbd5e1'; this.style.paddingLeft='0'">Parking Locations</a>
                        </div>
                    </div>
                    
                    <!-- Support -->
                    <div class="footer-section">
                        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Support</h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <a href="#" onclick="showHelpCenter()" style="color: #cbd5e1; text-decoration: none; font-size: 15px; transition: all 0.3s ease; padding: 4px 0;" onmouseover="this.style.color='#ffffff'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#cbd5e1'; this.style.paddingLeft='0'">Help Center</a>
                            <a href="#" onclick="showFAQs()" style="color: #cbd5e1; text-decoration: none; font-size: 15px; transition: all 0.3s ease; padding: 4px 0;" onmouseover="this.style.color='#ffffff'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#cbd5e1'; this.style.paddingLeft='0'">FAQs</a>
                            <a href="#" style="color: #cbd5e1; text-decoration: none; font-size: 15px; transition: all 0.3s ease; padding: 4px 0;" onmouseover="this.style.color='#ffffff'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#cbd5e1'; this.style.paddingLeft='0'">Contact Us</a>
                            <a href="#" onclick="showBlog()" style="color: #cbd5e1; text-decoration: none; font-size: 15px; transition: all 0.3s ease; padding: 4px 0;" onmouseover="this.style.color='#ffffff'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#cbd5e1'; this.style.paddingLeft='0'">Blog & Updates</a>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="footer-section">
                        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #ffffff; text-transform: uppercase; letter-spacing: 1px;">Get In Touch</h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px; color: #cbd5e1; font-size: 15px;">
                                <span style="font-size: 18px;">📧</span>
                                <span>support@parkowenz.com</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px; color: #cbd5e1; font-size: 15px;">
                                <span style="font-size: 18px;">📞</span>
                                <span>+256 700 000 000</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px; color: #cbd5e1; font-size: 15px;">
                                <span style="font-size: 18px;">📍</span>
                                <span>Kampala, Uganda</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Bar -->
                <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px; text-align: center;">
                    <p style="color: #94a3b8; font-size: 14px; margin-bottom: 10px;">&copy; 2024 ParkOwenz. All rights reserved.</p>
                    <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
                        <a href="#" style="color: #94a3b8; text-decoration: none; font-size: 13px; transition: color 0.3s ease;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#94a3b8'">Privacy Policy</a>
                        <a href="#" style="color: #94a3b8; text-decoration: none; font-size: 13px; transition: color 0.3s ease;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#94a3b8'">Terms of Service</a>
                        <a href="#" style="color: #94a3b8; text-decoration: none; font-size: 13px; transition: color 0.3s ease;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#94a3b8'">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Login Page -->
    <div id="loginPage" class="page">
        <div class="auth-container">
            <div class="auth-card login-card">

                <div class="auth-header">
                    <h2>Welcome Back</h2>
                    <p class="auth-subtitle">Sign in to access your parking dashboard</p>
                </div>

                <form id="loginForm" onsubmit="handleLogin(event)">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="you@parkowenz.com" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" placeholder="Enter your password" required>
                    </div>
                    <div class="auth-actions">
                        <label class="remember-me">
                            <input type="checkbox" checked>
                            <span>Remember me</span>
                        </label>
                        <button type="button" class="text-link" onclick="showDialog('Password reset is coming soon.', 'info', 'Forgot Password')">Forgot password?</button>
                    </div>
                    <button type="submit" class="auth-btn">Sign In</button>
                </form>

                <div class="auth-divider">
                    <span>or continue with</span>
                </div>

                <div class="social-login">
                    <button type="button" class="social-btn google" onclick="showDialog('Google login will be available soon.', 'info', 'Social Login')">
                        <span class="social-icon">
                            <img src="park/k/ks" alt="Google" loading="lazy">
                        </span>
                        <span>Google</span>
                    </button>
                    <button type="button" class="social-btn apple" onclick="showDialog('Apple login will be available soon.', 'info', 'Social Login')">
                        <span class="social-icon"></span>
                        <span>Apple</span>
                    </button>
                </div>

                <p class="auth-footer">Don't have an account? <a href="#" onclick="showSignup()">Sign up</a></p>
            </div>
        </div>
    </div>

    <!-- Signup Page -->
    <div id="signupPage" class="page">
        <div class="auth-container">
            <div class="auth-card signup-card">
                <div class="auth-header">
                    <h2>Create Account</h2>
                    <p class="auth-subtitle">Join ParkOwenz for smart parking solutions</p>
                </div>

                <form id="signupForm" onsubmit="handleSignup(event)">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" placeholder="John Doe" required>

                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" placeholder="your@email.com" required>

                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" placeholder="Create a password" required>
                    </div>
                    <button type="submit" class="auth-btn">Sign Up</button>
                </form>

                <div class="auth-divider">
                    <span>or sign up with</span>
                </div>

                <div class="social-login">
                    <button type="button" class="social-btn google" onclick="showDialog('Google signup will be available soon.', 'info', 'Social Signup')">
                        <span class="social-icon">
                            <img src="park/k/ks" alt="Google" loading="lazy">
                        </span>
                        <span>Google</span>
                    </button>
                    <button type="button" class="social-btn apple" onclick="showDialog('Apple signup will be available soon.', 'info', 'Social Signup')">
                        <span class="social-icon"></span>
                        <span>Apple</span>
                    </button>
                </div>

                <p class="auth-footer">Already have an account? <a href="#" onclick="showLogin()">Sign in</a></p>
            </div>
        </div>
    </div>

    <!-- Main Dashboard -->
    <div id="dashboardPage" class="page">
        <nav class="dashboard-nav">
            <div class="logo">ParkOwenz</div>
            <div class="nav-links">
                <a href="#" onclick="showDashboard()" class="nav-link active">Find Parking</a>
                <a href="#" onclick="showReservations()" class="nav-link">My Reservations</a>
                <a href="#" onclick="showPayments()" class="nav-link">Payments</a>
                <a href="#" onclick="showSettings()" class="nav-link">Settings</a>
            </div>
            <div class="nav-actions">
                <div class="notification-wrapper">
                    <button class="icon-btn" id="notificationToggle" aria-label="Notifications">
                        <span class="icon">🔔</span>
                        <span class="badge" id="notificationCount">0</span>
                    </button>
                    <div class="dropdown-menu notification-menu" id="notificationMenu">
                        <div class="dropdown-header">Notifications</div>
                        <div class="notification-list" id="notificationList"></div>
                        <div class="dropdown-footer">
                            <button class="dropdown-link" onclick="markAllNotificationsRead()">Mark all as read</button>
                        </div>
                    </div>
                </div>
                <div class="profile-wrapper">
                    <button class="profile-btn" id="profileToggle">
                        <div class="user-avatar" id="userAvatar"></div>
                        <div class="user-info">
                            <span class="user-name" id="userName">John Doe</span>
                            <span class="user-email" id="userEmail">john@email.com</span>
                        </div>
                        <span class="chevron">▾</span>
                    </button>
                    <div class="dropdown-menu profile-menu" id="profileMenu">
                        <button class="dropdown-item" onclick="showSettings()">Profile &amp; Settings</button>
                        <button class="dropdown-item" id="profileLoginItem" onclick="showLogin()">Login</button>
                        <button class="dropdown-item logout-item" id="profileLogoutItem" onclick="logout()">Logout</button>
                    </div>
                </div>
            </div>
        </nav>

        <div id="dashboardContent" class="dashboard-content active">
            <div class="search-header">
                <h1>Find Parking Near You</h1>
                <div class="location-info">
                    <span class="location-icon">📍</span>
                    <span id="userLocation">Detecting location...</span>
                </div>
            </div>

            <!-- Beautiful Search Area -->
            <div class="parking-search-container">
                <div class="search-card">
                    <div class="search-input-group">
                        <div class="search-input-wrapper">
                            <span class="search-icon">🔍</span>
                            <input type="text" 
                                   id="parkingSearch" 
                                   class="search-input" 
                                   placeholder="Search for parking locations, landmarks, or addresses..."
                                   autocomplete="off">
                            <button class="search-clear-btn" id="clearSearch" style="display: none;">✕</button>
                        </div>
                        
                        <div class="search-filters">
                            <div class="filter-group">
                                <label class="filter-label">Distance</label>
                                <select class="filter-select" id="distanceFilter">
                                    <option value="all">Any Distance</option>
                                    <option value="1">Within 1 km</option>
                                    <option value="3">Within 3 km</option>
                                    <option value="5">Within 5 km</option>
                                    <option value="10">Within 10 km</option>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label class="filter-label">Price Range</label>
                                <select class="filter-select" id="priceFilter">
                                    <option value="all">Any Price</option>
                                    <option value="0-2000">Under 2,000 UGX</option>
                                    <option value="2000-5000">2,000 - 5,000 UGX</option>
                                    <option value="5000-10000">5,000 - 10,000 UGX</option>
                                    <option value="10000+">Above 10,000 UGX</option>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label class="filter-label">Features</label>
                                <select class="filter-select" id="featureFilter">
                                    <option value="all">All Features</option>
                                    <option value="covered">Covered Parking</option>
                                    <option value="security">24/7 Security</option>
                                    <option value="ev">EV Charging</option>
                                    <option value="valet">Valet Service</option>
                                </select>
                            </div>
                            
                            <button class="search-btn" id="performSearch">
                                <span>🔍</span>
                                <span>Search</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="search-suggestions" id="searchSuggestions" style="display: none;">
                        <div class="suggestions-header">Popular Searches</div>
                        <div class="suggestion-tags">
                            <span class="suggestion-tag" data-search="Kampala City Center">Kampala City Center</span>
                            <span class="suggestion-tag" data-search="Acacia Mall">Acacia Mall</span>
                            <span class="suggestion-tag" data-search="Nakasero Market">Nakasero Market</span>
                            <span class="suggestion-tag" data-search="Sheraton Hotel">Sheraton Hotel</span>
                            <span class="suggestion-tag" data-search="Jinja Town">Jinja Town</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="parking-grid" id="parkingGrid"></div>
        </div>

        <div id="reservationsContent" class="dashboard-content">
            <div class="reservations-header">
                <div>
                    <h1>My Reservations</h1>
                    <p class="subtitle">View and manage your parking bookings</p>
                </div>
                <button class="add-booking-btn" onclick="showDashboard()">
                    <span>➕</span>
                    <span>New Booking</span>
                </button>
            </div>
            
            <div class="reservation-filters">
              <div class="reservation-tabs">
                <button class="tab-btn active" onclick="filterReservations('active')">Upcoming & Active</button>
                <button class="tab-btn" onclick="filterReservations('past')">Past</button>
                <button class="tab-btn" onclick="filterReservations('cancelled')">Canceled</button>
            </div>

                {{-- <div class="filter-actions">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" placeholder="Search location..." id="searchReservation">
                    </div>
                    <button class="filter-btn">
                        <span>⚙️</span>
                        <span>Filters</span>
                    </button>
                </div> --}}
            </div>
            
            <div class="reservations-grid" id="reservationsList"></div>
        </div>

        <div id="paymentsContent" class="dashboard-content">
            <h1>Payment</h1>
            <div id="paymentArea"></div>
        </div>
        
        <div id="settingsContent" class="dashboard-content">
            <div class="settings-header">
                <h1>Settings</h1>
                <p class="subtitle">Manage your preferences and account settings</p>
            </div>
            
            <div class="settings-card">
                <h3>Account Details</h3>
                <form id="accountForm" onsubmit="saveAccountSettings(event)">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" id="settingName" class="settings-input" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" id="settingEmail" class="settings-input" placeholder="john@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" id="settingPhone" class="settings-input" placeholder="+256 700 000 000">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" id="settingAddress" class="settings-input" placeholder="Kampala, Uganda">
                    </div>
                    <button type="submit" class="save-btn">Save Changes</button>
                </form>
            </div>
            
            <div class="settings-card">
                <h3>Appearance</h3>
                <div class="setting-item">
                    <div>
                        <strong>Dark Mode</strong>
                        <p class="setting-desc">Enable dark theme for better viewing at night</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <div>
                        <strong>Theme Color</strong>
                        <p class="setting-desc">Choose your preferred accent color</p>
                    </div>
                    <div class="color-picker">
                        <button class="color-option active" data-color="primary" onclick="changeThemeColor('primary')" style="background: #6366f1;"></button>
                        <button class="color-option" data-color="green" onclick="changeThemeColor('green')" style="background: #10b981;"></button>
                        <button class="color-option" data-color="orange" onclick="changeThemeColor('orange')" style="background: #f59e0b;"></button>
                        <button class="color-option" data-color="red" onclick="changeThemeColor('red')" style="background: #ef4444;"></button>
                        <button class="color-option" data-color="purple" onclick="changeThemeColor('purple')" style="background: #a855f7;"></button>
                    </div>
                </div>
            </div>
            
            <div class="settings-card">
                <h3>Notifications</h3>
                <div class="setting-item">
                    <div>
                        <strong>Email Notifications</strong>
                        <p class="setting-desc">Receive booking confirmations via email</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="emailNotifications" checked onchange="saveNotificationSettings()">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <div>
                        <strong>SMS Alerts</strong>
                        <p class="setting-desc">Get parking reminders via SMS</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="smsNotifications" onchange="saveNotificationSettings()">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <div>
                        <strong>Push Notifications</strong>
                        <p class="setting-desc">Receive real-time updates on your device</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="pushNotifications" checked onchange="saveNotificationSettings()">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="settings-card">
                <h3>Vehicle Information</h3>
                <div id="vehiclesList">
                    <div class="vehicle-item">
                        <div>
                            <strong>Default Vehicle</strong>
                            <p class="setting-desc" id="defaultVehicle">No vehicle added</p>
                        </div>
                        <button class="edit-btn" onclick="addVehicle()">Add Vehicle</button>
                    </div>
                </div>
            </div>
            
            <div class="settings-card">
                <h3>Privacy & Security</h3>
                <div class="setting-item">
                    <div>
                        <strong>Two-Factor Authentication</strong>
                        <p class="setting-desc">Add an extra layer of security to your account</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="twoFactor" onchange="saveSecuritySettings()">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <div>
                        <strong>Share Location</strong>
                        <p class="setting-desc">Allow app to access your location for better results</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="shareLocation" checked onchange="saveSecuritySettings()">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="settings-card danger-zone">
                <h3>Danger Zone</h3>
                <div class="setting-item">
                    <div>
                        <strong>Delete Account</strong>
                        <p class="setting-desc">Permanently delete your account and all data</p>
                    </div>
                    <button class="delete-btn" onclick="deleteAccount()">Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Parking Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeBookingModal()">&times;</span>
            <div id="bookingContent"></div>
        </div>
    </div>
    <!-- Add this to your main app (index.html) -->
{{-- <button onclick="goToAdmin()"  class="admin-btn" style="position: fixed; bottom: 20px; right: 20px; background: #6366f1; color: white; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer;">
   <a href="/admin">Admin Dashboard</a> 
</button> --}}

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

.dark-mode .dialog-container {
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

.dark-mode .dialog-title {
    color: white;
}

.dialog-message {
    font-size: 16px;
    line-height: 1.6;
    color: #6b7280;
    margin: 0 0 24px 0;
    white-space: pre-line;
}

.dark-mode .dialog-message {
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
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
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
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}
</style>

<script>
function goToAdmin() {
    window.location.href = 'admin.html';
}

// Beautiful Dialog Functions
function showDialog(message, type = 'info', title = null) {
    const dialog = document.getElementById('customDialog');
    const dialogIcon = document.getElementById('dialogIcon');
    const dialogTitle = document.getElementById('dialogTitle');
    const dialogMessage = document.getElementById('dialogMessage');
    
    // Set icon based on type
    dialogIcon.className = 'dialog-icon ' + type;
    
    const icons = {
        success: '✓',
        error: '✕',
        info: 'ℹ',
        warning: '⚠'
    };
    
    dialogIcon.textContent = icons[type] || icons.info;
    
    // Set title
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
    
    // Set message
    dialogMessage.textContent = message;
    
    // Show dialog
    dialog.classList.add('show');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeDialog() {
    const dialog = document.getElementById('customDialog');
    dialog.classList.remove('show');
    document.body.style.overflow = '';
}

// Close dialog on overlay click
document.addEventListener('DOMContentLoaded', function() {
    const dialog = document.getElementById('customDialog');
    const overlay = dialog.querySelector('.dialog-overlay');
    
    overlay.addEventListener('click', closeDialog);
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && dialog.classList.contains('show')) {
            closeDialog();
        }
    });
});
</script>
    <script src="api.js"></script>
    <script src="d.js"></script>
</body>
</html>