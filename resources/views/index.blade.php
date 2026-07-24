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
            <div class="logo">
                <span class="logo-mark">P</span>
                <span class="logo-text">ParkOwenz</span>
            </div>
            <button class="cta-btn" onclick="showDashboard()">Get Started</button>
        </nav>
        
        <section class="hero">
            <div class="hero-content">
                <div class="hero-badge">Now live in 1 city</div>
                <h1 class="hero-title">Parking, <br><span class="hero-highlight">Reimagined.</span></h1>
                <p class="hero-subtitle">Effortlessly find, book, and navigate to your spot in seconds with smart parking for modern cities.</p>
                <button class="hero-btn" onclick="showDashboard()">Book Your Spot</button>
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

        <!-- Mission Section -->
        <section class="mission" id="mission">
            <div class="mission-container">
                <div class="mission-grid">
                    <div class="mission-image">
                        <img src="park/own.webp" alt="Futuristic city planning">
                    </div>
                    <div class="mission-content">
                        <span class="mission-tag">OUR MISSION</span>
                        <h2 class="mission-title">Building a world where urban mobility is seamless.</h2>
                        <p class="mission-text">Urban congestion is one of the biggest challenges of the 21st century. At ParkOwenz, we are leveraging high-precision sensors and predictive AI to optimize existing infrastructure, reducing traffic and making cities more livable.</p>
                        <div class="mission-points">
                            <div class="mission-point">
                                <span class="point-mark">D</span>
                                <div>
                                    <h4>Data Driven</h4>
                                    <p>Real-time analytics for city planners.</p>
                                </div>
                            </div>
                            <div class="mission-point">
                                <span class="point-mark">S</span>
                                <div>
                                    <h4>Secure Access</h4>
                                    <p>End-to-end encrypted reservations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- How It Works Section -->
        <section class="how-it-works">
            <div class="how-container">
                <div class="how-header">
                    <span class="how-tag">Process</span>
                    <h2 class="how-title">Park in 3 simple steps</h2>
                    <p class="how-subtitle">Find and reserve parking in seconds.</p>
                </div>
                
                <div class="how-steps">
                    <div class="how-step">
                        <div class="step-number">1</div>
                        <div class="step-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M12 2C8.14 2 5 5.06 5 8.92c0 4.64 5.4 10.44 6.4 11.5a.86.86 0 0 0 1.2 0c1-1.06 6.4-6.86 6.4-11.5C19 5.06 15.86 2 12 2zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"></path>
                            </svg>
                        </div>
                        <h3>FIND YOUR SPOT</h3>
                        <p>Use our real-time map to locate open spaces near your destination.</p>
                    </div>
                    
                    <div class="how-step">
                        <div class="step-number">2</div>
                        <div class="step-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M7 2a1 1 0 0 0-1 1v1H5a3 3 0 0 0-3 3v11a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3h-1V3a1 1 0 1 0-2 0v1H8V3a1 1 0 0 0-1-1zm12 8H5v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-8zm-3.3 2.7a1 1 0 0 1 0 1.4l-4.2 4.2a1 1 0 0 1-1.4 0l-2.1-2.1a1 1 0 1 1 1.4-1.4l1.4 1.4 3.5-3.5a1 1 0 0 1 1.4 0z"></path>
                            </svg>
                        </div>
                        <h3>INSTANT BOOKING</h3>
                        <p>Reserve your spot with one tap and pay securely in the app.</p>
                    </div>

                    <div class="how-step">
                        <div class="step-number">3</div>
                        <div class="step-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M2 11.5a1 1 0 0 1 1-1h15.6l-4.3-4.3a1 1 0 0 1 1.4-1.4l6 6a1 1 0 0 1 0 1.4l-6 6a1 1 0 0 1-1.4-1.4l4.3-4.3H3a1 1 0 0 1-1-1z"></path>
                            </svg>
                        </div>
                        <h3>SMART NAVIGATION</h3>
                        <p>Get guided directions to your bay with live arrival sync.</p>
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
                        <div class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M12 1.75a4.25 4.25 0 0 1 4.25 4.25 1 1 0 1 1-2 0 2.25 2.25 0 1 0-2.25 2.25h1a5.25 5.25 0 1 1 0 10.5h-1.25v1.5a1 1 0 1 1-2 0v-1.5H8a1 1 0 1 1 0-2h4.75a3.25 3.25 0 1 0 0-6.5h-1A4.25 4.25 0 0 1 12 1.75z"></path>
                            </svg>
                        </div>
                        <h3>SAVE MONEY</h3>
                        <p>Compare prices and find the best deals</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M12 2a1 1 0 0 1 1 1v3.2l2.7-1.6a1 1 0 0 1 1 1.74l-3.7 2.1V12a1 1 0 0 1-1.55.83l-3-2a1 1 0 1 1 1.1-1.66l1.45 1V3a1 1 0 0 1 1-1zm0 4.5a7.5 7.5 0 1 1-7.5 7.5A7.51 7.51 0 0 1 12 6.5zm0 2a5.5 5.5 0 1 0 5.5 5.5A5.5 5.5 0 0 0 12 8.5z"></path>
                            </svg>
                        </div>
                        <h3>SAVE TIME</h3>
                        <p>No more circling for parking spots</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M12 1.75a5.25 5.25 0 0 1 5.25 5.25V9h.5a2 2 0 0 1 2 2v9.25a2 2 0 0 1-2 2H6.25a2 2 0 0 1-2-2V11a2 2 0 0 1 2-2h.5V7A5.25 5.25 0 0 1 12 1.75zM8.75 9h6.5V7a3.25 3.25 0 1 0-6.5 0v2zm3.25 4.25a1.25 1.25 0 0 0-.75 2.25V18a.75.75 0 0 0 1.5 0v-2.5a1.25 1.25 0 0 0-.75-2.25z"></path>
                            </svg>
                        </div>
                        <h3>SECURE</h3>
                        <p>Safe and secure parking locations</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="img" focusable="false">
                                <path d="M8.5 1.75h7A3.25 3.25 0 0 1 18.75 5v14A3.25 3.25 0 0 1 15.5 22.25h-7A3.25 3.25 0 0 1 5.25 19V5A3.25 3.25 0 0 1 8.5 1.75zm0 2A1.25 1.25 0 0 0 7.25 5v14c0 .69.56 1.25 1.25 1.25h7c.69 0 1.25-.56 1.25-1.25V5c0-.69-.56-1.25-1.25-1.25h-7zm3.5 14a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5z"></path>
                            </svg>
                        </div>
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
                <div class="mobile-menu-wrapper">
                    <button class="icon-btn mobile-menu-btn" id="mobileMenuToggle" aria-label="Open menu">
                        <svg viewBox="0 0 24 24" role="img" focusable="false" aria-hidden="true">
                            <path d="M3 6.5h18a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2zm0 5.5h18a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2zm0 5.5h18a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2z"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu mobile-menu" id="mobileMenu">
                        <button class="dropdown-item mobile-menu-item" onclick="showDashboard(); closeAllDropdowns();">
                            <span class="menu-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M12 3.2a1.2 1.2 0 0 1 .8.3l7 6.3a1 1 0 1 1-1.34 1.49L18 10.2V19a2 2 0 0 1-2 2h-3.5a1 1 0 0 1-1-1v-4.5H12V20a1 1 0 0 1-1 1H7.5a2 2 0 0 1-2-2v-8.8l-.46.4a1 1 0 1 1-1.34-1.49l7-6.3a1.2 1.2 0 0 1 .8-.3z"></path>
                                </svg>
                            </span>
                            <span>Find Parking</span>
                        </button>
                        <button class="dropdown-item mobile-menu-item" onclick="showReservations(); closeAllDropdowns();">
                            <span class="menu-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M7 3a1 1 0 0 0-1 1v1H5a3 3 0 0 0-3 3v11a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3h-1V4a1 1 0 1 0-2 0v1H8V4a1 1 0 0 0-1-1zm12 8H5v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-8zm-3.3 2.7a1 1 0 0 1 0 1.4l-4.2 4.2a1 1 0 0 1-1.4 0l-2.1-2.1a1 1 0 1 1 1.4-1.4l1.4 1.4 3.5-3.5a1 1 0 0 1 1.4 0z"></path>
                                </svg>
                            </span>
                            <span>My Reservations</span>
                        </button>
                        <button class="dropdown-item mobile-menu-item" onclick="showPayments(); closeAllDropdowns();">
                            <span class="menu-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm0 3v9h16V8H4zm2 6h5a1 1 0 1 1 0 2H6a1 1 0 1 1 0-2z"></path>
                                </svg>
                            </span>
                            <span>Payments</span>
                        </button>
                        <button class="dropdown-item mobile-menu-item" onclick="showSettings(); closeAllDropdowns();">
                            <span class="menu-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" role="img" focusable="false">
                                    <path d="M12 7.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm9 4.5a7.9 7.9 0 0 0-.12-1.4l2.02-1.57a1 1 0 0 0 .25-1.29l-1.9-3.3a1 1 0 0 0-1.22-.44l-2.38.96a8.05 8.05 0 0 0-2.42-1.4l-.36-2.54A1 1 0 0 0 11.88 0h-3.8a1 1 0 0 0-.99.84l-.36 2.54a8.07 8.07 0 0 0-2.42 1.4l-2.38-.96a1 1 0 0 0-1.22.44l-1.9 3.3a1 1 0 0 0 .25 1.29l2.02 1.57A7.9 7.9 0 0 0 3 12c0 .47.04.95.12 1.4l-2.02 1.57a1 1 0 0 0-.25 1.29l1.9 3.3a1 1 0 0 0 1.22.44l2.38-.96a8.05 8.05 0 0 0 2.42 1.4l.36 2.54a1 1 0 0 0 .99.84h3.8a1 1 0 0 0 .99-.84l.36-2.54a8.05 8.05 0 0 0 2.42-1.4l2.38.96a1 1 0 0 0 1.22-.44l1.9-3.3a1 1 0 0 0-.25-1.29l-2.02-1.57c.08-.45.12-.93.12-1.4z"></path>
                                </svg>
                            </span>
                            <span>Settings</span>
                        </button>
                    </div>
                </div>
                <button class="cta-btn nav-login-btn" id="navLoginButton" type="button" onclick="showLogin()">Login</button>
                <div class="notification-wrapper" id="notificationWrapper">
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
                <div class="profile-wrapper" id="profileWrapper">
                    <button class="profile-btn" id="profileToggle" aria-label="Profile menu">
                        <div class="user-avatar" id="userAvatar"></div>
                    </button>
                    <div class="dropdown-menu profile-menu" id="profileMenu">
                        <div class="profile-menu-header">
                            <p class="profile-menu-name" id="userName">John Doe</p>
                            <p class="profile-menu-email" id="userEmail">john@email.com</p>
                        </div>
                        <div class="profile-menu-section">
                            <button class="profile-menu-item" onclick="showProfile()">
                                <span class="profile-menu-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <path d="M12 2a5 5 0 1 1-5 5 5 5 0 0 1 5-5zm0 12c4.42 0 8 2.24 8 5v2H4v-2c0-2.76 3.58-5 8-5z"></path>
                                    </svg>
                                </span>
                                <span>Profile</span>
                            </button>
                            <button class="profile-menu-item" onclick="showReservations()">
                                <span class="profile-menu-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <path d="M7 2a1 1 0 0 0-1 1v1H5a3 3 0 0 0-3 3v11a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3h-1V3a1 1 0 1 0-2 0v1H8V3a1 1 0 0 0-1-1zm12 8H5v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-8z"></path>
                                    </svg>
                                </span>
                                <span>Reservations</span>
                            </button>
                        </div>
                        <div class="profile-menu-section">
                            <div class="profile-menu-title">Theme</div>
                            <button class="profile-menu-item" onclick="setThemeMode('light')">
                                <span class="profile-menu-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <path d="M12 4.5a1 1 0 0 1 1-1h.5V2a1 1 0 1 1 2 0v1.5H16a1 1 0 1 1 0 2h-1.5V7a1 1 0 1 1-2 0V5.5H11a1 1 0 0 1-1-1zm0 3.5a4.5 4.5 0 1 1-4.5 4.5A4.5 4.5 0 0 1 12 8zm8-1a1 1 0 0 1 1 1v.5h1.5a1 1 0 1 1 0 2H21v1.5a1 1 0 1 1-2 0V10.5H17.5a1 1 0 1 1 0-2H19V8a1 1 0 0 1 1-1z"></path>
                                    </svg>
                                </span>
                                <span>Light</span>
                            </button>
                            <button class="profile-menu-item" onclick="setThemeMode('system')">
                                <span class="profile-menu-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <path d="M4 4h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H13l1.5 2h2a1 1 0 1 1 0 2H7.5a1 1 0 0 1 0-2h2l1.5-2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm0 2v9h16V6z"></path>
                                    </svg>
                                </span>
                                <span>System</span>
                            </button>
                            <button class="profile-menu-item" onclick="setThemeMode('dark')">
                                <span class="profile-menu-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <path d="M20.7 15.1A8.5 8.5 0 0 1 8.9 3.3a1 1 0 0 1 1.05-.2 6.5 6.5 0 1 0 10.15 10.15 1 1 0 0 1 .2 1.05z"></path>
                                    </svg>
                                </span>
                                <span>Dark</span>
                            </button>
                        </div>
                        <div class="profile-menu-section profile-menu-danger">
                            <button class="profile-menu-item logout-item" id="profileLogoutItem" onclick="logout()">
                                <span class="profile-menu-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" role="img" focusable="false">
                                        <path d="M10 2h8a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2h-8a2 2 0 0 1-2-2v-3a1 1 0 1 1 2 0v3h8V4h-8v3a1 1 0 1 1-2 0V4a2 2 0 0 1 2-2zm2.3 6.3a1 1 0 0 1 1.4 0l3.3 3.3a1 1 0 0 1 0 1.4l-3.3 3.3a1 1 0 0 1-1.4-1.4l1.6-1.6H4a1 1 0 1 1 0-2h9.9l-1.6-1.6a1 1 0 0 1 0-1.4z"></path>
                                    </svg>
                                </span>
                                <span>Sign Out</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <button type="button" aria-label="Chat widget" title="Chat widget" class="tawk-button" id="tawkButton">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800" height="28" width="28" role="img" aria-labelledby="tawkIconTitle tawkIconDesc" class="tawk-icon">
                <title id="tawkIconTitle">Opens Chat</title>
                <desc id="tawkIconDesc">This icon opens the chat window.</desc>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M400 26.2c-193.3 0-350 156.7-350 350 0 136.2 77.9 254.3 191.5 312.1 15.4 8.1 31.4 15.1 48.1 20.8l-16.5 63.5c-2 7.8 5.4 14.7 13 12.1l229.8-77.6c14.6-5.3 28.8-11.6 42.4-18.7C672 630.6 750 512.5 750 376.2c0-193.3-156.7-350-350-350zm211.1 510.7c-10.8 26.5-41.9 77.2-121.5 77.2-79.9 0-110.9-51-121.6-77.4-2.8-6.8 5-13.4 13.8-11.8 76.2 13.7 147.7 13 215.3.3 8.9-1.8 16.8 4.8 14 11.7z"></path>
            </svg>
        </button>
        <div class="tawk-panel" id="tawkPanel" aria-hidden="true">
            <div class="tawk-panel-header">
                <div>
                    <p class="tawk-panel-title">Chat with ParkOwenz</p>
                    <p class="tawk-panel-subtitle">We reply in a few minutes</p>
                </div>
                <button class="tawk-panel-close" type="button" aria-label="Close chat" id="tawkClose">×</button>
            </div>
            <div class="tawk-panel-body" id="tawkMessages">
                <div class="tawk-message bot">
                    <span class="tawk-avatar">P</span>
                    <div class="tawk-bubble">
                        Hi there! Need help finding parking?
                        <span class="tawk-meta">08:12</span>
                    </div>
                </div>
                <div class="tawk-message user">
                    <div class="tawk-bubble">
                        Yes, I need a spot near downtown.
                        <span class="tawk-meta">08:13</span>
                        <span class="tawk-status" aria-hidden="true">✓✓</span>
                    </div>
                </div>
            </div>
            <div class="tawk-panel-footer">
                <input type="text" id="tawkInput" placeholder="Type your message..." aria-label="Chat message" />
                <button type="button" class="tawk-send-btn" id="tawkSend">Send</button>
            </div>
        </div>

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
            <div class="reservations-shell">
                <header class="reservations-hero">
                    <div>
                        <h1>My Reservations</h1>
                        <p class="reservations-subtitle" id="reservationsSummary">Loading your reservations...</p>
                    </div>
                    <button class="reservations-new" onclick="showDashboard()">New Reservation</button>
                </header>

                <div class="reservations-tabs">
                    <button class="reservations-tab active" onclick="filterReservations('active')" data-filter="active">
                        Active Sessions (<span id="reservationsActiveCount">0</span>)
                    </button>
                    <button class="reservations-tab" onclick="filterReservations('upcoming')" data-filter="upcoming">
                        Upcoming (<span id="reservationsUpcomingCount">0</span>)
                    </button>
                    <button class="reservations-tab" onclick="filterReservations('history')" data-filter="history">
                        History (<span id="reservationsHistoryCount">0</span>)
                    </button>
                </div>

                <section class="reservations-section" id="reservationsActiveSection">
                    <div class="reservations-section-head">
                        <span class="reservations-dot"></span>
                        <h3>Currently Parked</h3>
                    </div>
                    <div class="reservations-list" id="reservationsActiveList"></div>
                </section>

                <section class="reservations-section" id="reservationsUpcomingSection">
                    <div class="reservations-section-head">
                        <span class="reservations-badge">UPCOMING</span>
                        <h3>Upcoming Reservations</h3>
                    </div>
                    <div class="reservations-list" id="reservationsUpcomingList"></div>
                </section>

                <section class="reservations-section" id="reservationsHistorySection">
                    <div class="reservations-section-head history">
                        <h3>Recent History</h3>
                        <button class="reservations-link" type="button">View All History</button>
                    </div>
                    <div class="reservations-table-wrap">
                        <table class="reservations-table">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Date</th>
                                    <th>Duration</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="reservationsHistoryBody"></tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>

        <div id="paymentsContent" class="dashboard-content">
            <div class="payments-shell">
                <header class="payments-header">
                    <div>
                        <h1>Payments &amp; Billing</h1>
                        <p class="payments-subtitle">Manage your payment methods and view transaction history.</p>
                    </div>
                    <div class="payments-badge">
                        <span class="payments-badge-dot"></span>
                        <span>Secure Connection</span>
                    </div>
                </header>

                <section class="payments-checkout" id="paymentCheckout" style="display: none;"></section>

                <div class="payments-billing" id="paymentsBilling">
                <section class="payments-stats">
                    <article class="payments-card">
                        <div class="payments-card-head">
                            <p>Total Spent (Monthly)</p>
                            <span class="payments-icon">UP</span>
                        </div>
                        <h3 id="paymentsTotalSpent">UGX 0.00</h3>
                        <p class="payments-meta positive" id="paymentsTotalTrend">0% from last month</p>
                    </article>
                    <article class="payments-card">
                        <div class="payments-card-head">
                            <p>Active Session Cost</p>
                            <span class="payments-icon">HR</span>
                        </div>
                        <h3 id="paymentsActiveCost">UGX 0.00</h3>
                        <p class="payments-meta" id="paymentsActiveMeta">No active session</p>
                    </article>
                    <article class="payments-card">
                        <div class="payments-card-head">
                            <p>Pending Invoices</p>
                            <span class="payments-icon">DOC</span>
                        </div>
                        <h3 id="paymentsPendingTotal">UGX 0.00</h3>
                        <p class="payments-meta" id="paymentsPendingMeta">All clear for now</p>
                    </article>
                </section>

                <section class="payments-methods">
                    <div class="payments-section-head">
                        <h2>Saved Payment Methods</h2>
                        <button class="payments-link" type="button">Add New Method</button>
                    </div>
                    <div class="payments-method-grid" id="paymentMethodsGrid"></div>
                </section>

                <section class="payments-history">
                    <div class="payments-section-head">
                        <h2>Billing History</h2>
                        <div class="payments-actions">
                            <input class="payments-search" type="text" placeholder="Search transactions...">
                            <button class="payments-filter" type="button">Filter</button>
                        </div>
                    </div>
                    <div class="payments-table-wrap">
                        <table class="payments-table">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Date &amp; Time</th>
                                    <th>Location</th>
                                    <th>Duration</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="right">Receipt</th>
                                </tr>
                            </thead>
                            <tbody id="paymentsTableBody"></tbody>
                        </table>
                    </div>
                    <div class="payments-footer">
                        <p id="paymentsCountText">Showing 0 of 0 transactions</p>
                        <div class="payments-pagination">
                            <button type="button" disabled>Previous</button>
                            <button type="button">Next</button>
                        </div>
                    </div>
                </section>
                </div>
            </div>
        </div>
        
        <div id="settingsContent" class="dashboard-content">
            <div class="settings-shell">
                <div class="settings-top">
                    <div>
                        <h1>System Settings</h1>
                        <p class="settings-subtitle">Configure your appearance, privacy, and account safety preferences.</p>
                    </div>
                    <div class="settings-actions">
                        <button type="button" class="settings-btn ghost">Discard</button>
                        <button type="button" class="settings-btn primary">Save Changes</button>
                    </div>
                </div>

                <div class="settings-tabs">
                    <a href="#appearance" class="settings-tab active">Appearance</a>
                    <a href="#privacy" class="settings-tab">Privacy &amp; Security</a>
                    <a href="#danger" class="settings-tab">Danger Zone</a>
                </div>

                <section id="appearance" class="settings-section">
                    <div class="settings-section-title">
                        <span class="settings-icon">A</span>
                        <h3>Appearance</h3>
                    </div>
                    <div class="settings-grid">
                        <div class="settings-card-modern">
                            <h4>Application Theme</h4>
                            <div class="theme-grid">
                                <button type="button" class="theme-option" onclick="toggleDarkMode()">
                                    <div class="theme-preview light"></div>
                                    <span>Light</span>
                                </button>
                                <button type="button" class="theme-option active" onclick="toggleDarkMode()">
                                    <div class="theme-preview dark"></div>
                                    <span>Dark</span>
                                </button>
                                <button type="button" class="theme-option" onclick="toggleDarkMode()">
                                    <div class="theme-preview system"></div>
                                    <span>System</span>
                                </button>
                            </div>
                        </div>
                        <div class="settings-card-modern">
                            <h4>Dark Mode</h4>
                            <div class="settings-row">
                                <div>
                                    <strong>Enable dark theme</strong>
                                    <p>Reduce glare and improve night visibility.</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="privacy" class="settings-section">
                    <div class="settings-section-title">
                        <span class="settings-icon">P</span>
                        <h3>Privacy &amp; Security</h3>
                    </div>
                    <div class="settings-grid">
                        <div class="settings-card-modern">
                            <h4>Two-Factor Authentication</h4>
                            <div class="settings-row">
                                <div>
                                    <strong>Extra account protection</strong>
                                    <p>Require a security code when signing in.</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="twoFactor" onchange="saveSecuritySettings()">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="settings-card-modern">
                            <h4>Location Access</h4>
                            <div class="settings-row">
                                <div>
                                    <strong>Share location</strong>
                                    <p>Use location for real-time parking results.</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="shareLocation" checked onchange="saveSecuritySettings()">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="notifications" class="settings-section">
                    <div class="settings-section-title">
                        <span class="settings-icon">N</span>
                        <h3>Notifications</h3>
                    </div>
                    <div class="settings-grid">
                        <div class="settings-card-modern">
                            <h4>Email Notifications</h4>
                            <div class="settings-row">
                                <div>
                                    <strong>Booking confirmations</strong>
                                    <p>Receive booking confirmations via email.</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="emailNotifications" checked onchange="saveNotificationSettings()">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="settings-card-modern">
                            <h4>SMS Alerts</h4>
                            <div class="settings-row">
                                <div>
                                    <strong>Parking reminders</strong>
                                    <p>Get parking reminders via SMS.</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="smsNotifications" onchange="saveNotificationSettings()">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="settings-card-modern">
                            <h4>Push Notifications</h4>
                            <div class="settings-row">
                                <div>
                                    <strong>Real-time updates</strong>
                                    <p>Receive instant updates on your device.</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="pushNotifications" checked onchange="saveNotificationSettings()">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="colors" class="settings-section">
                    <div class="settings-section-title">
                        <span class="settings-icon">C</span>
                        <h3>Theme Colors</h3>
                    </div>
                    <div class="settings-card-modern">
                        <h4>Accent Color</h4>
                        <p>Pick a color for buttons, highlights, and headings.</p>
                        <div class="color-picker">
                            <button class="color-option active" data-color="primary" onclick="changeThemeColor('primary')" style="background: #1392ec;"></button>
                            <button class="color-option" data-color="green" onclick="changeThemeColor('green')" style="background: #10b981;"></button>
                            <button class="color-option" data-color="orange" onclick="changeThemeColor('orange')" style="background: #f59e0b;"></button>
                            <button class="color-option" data-color="red" onclick="changeThemeColor('red')" style="background: #ef4444;"></button>
                            <button class="color-option" data-color="purple" onclick="changeThemeColor('purple')" style="background: #a855f7;"></button>
                        </div>
                    </div>
                </section>

                <section id="danger" class="settings-section">
                    <div class="settings-section-title danger">
                        <span class="settings-icon">!</span>
                        <h3>Danger Zone</h3>
                    </div>
                    <div class="settings-card-modern danger">
                        <div>
                            <strong>Delete Account</strong>
                            <p>Permanently delete your account and all data.</p>
                        </div>
                        <button class="delete-btn" onclick="deleteAccount()">Delete Account</button>
                    </div>
                </section>
            </div>
        </div>

        <div id="profileContent" class="dashboard-content">
            <div class="profile-shell">
                <div class="profile-header">
                    <div>
                        <h1>Account Settings</h1>
                        <p class="profile-subtitle">Manage your identity and vehicles for automated gate entry.</p>
                    </div>
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <span class="profile-stat-label">Wallet</span>
                            <span class="profile-stat-value">$142.50</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat-label">Parkings</span>
                            <span class="profile-stat-value">24</span>
                        </div>
                    </div>
                </div>

                <div class="profile-grid">
                    <div class="profile-card profile-details">
                        <div class="profile-avatar">
                            <div class="profile-avatar-image" id="profileAvatar">JD</div>
                            <button class="profile-avatar-edit" type="button">Edit</button>
                        </div>
                        <h2 class="profile-name" id="profileName">John Doe</h2>
                        <p class="profile-email" id="profileEmail">john@email.com</p>
                        <span class="profile-badge">Member since Jan 2023</span>
                        <form class="profile-form" onsubmit="saveAccountSettings(event)">
                            <label>
                                Phone Number
                                <input type="text" id="settingPhone" placeholder="+1 (555) 000-1234">
                            </label>
                            <label>
                                Emergency Contact
                                <input type="text" id="settingAddress" placeholder="Name & Phone">
                            </label>
                            <label>
                                Full Name
                                <input type="text" id="settingName" placeholder="John Doe" required>
                            </label>
                            <label>
                                Email Address
                                <input type="email" id="settingEmail" placeholder="john@email.com" required>
                            </label>
                            <button class="profile-save" type="submit">Update Details</button>
                        </form>
                    </div>

                    <div class="profile-card profile-vehicles">
                        <div class="profile-card-head">
                            <div>
                                <h3>Vehicle Fleet</h3>
                                <p>Manage cars authorized for auto-entry.</p>
                            </div>
                            <button class="profile-add" type="button">Add Vehicle</button>
                        </div>
                        <div class="vehicle-list">
                            <div class="vehicle-item">
                                <div class="vehicle-icon">A</div>
                                <div class="vehicle-info">
                                    <h4>Tesla Model 3</h4>
                                    <p>Midnight Silver • 2022</p>
                                    <span class="vehicle-plate">CAL 782X-9</span>
                                </div>
                                <div class="vehicle-actions">
                                    <span class="vehicle-status active">Active</span>
                                    <button class="vehicle-toggle active" type="button">Enabled</button>
                                </div>
                            </div>
                            <div class="vehicle-item">
                                <div class="vehicle-icon">B</div>
                                <div class="vehicle-info">
                                    <h4>Audi Q5</h4>
                                    <p>Glacier White • 2020</p>
                                    <span class="vehicle-plate">NYC B-4421</span>
                                </div>
                                <div class="vehicle-actions">
                                    <span class="vehicle-status active">Active</span>
                                    <button class="vehicle-toggle active" type="button">Enabled</button>
                                </div>
                            </div>
                        </div>
                        <div class="vehicle-form">
                            <h4>Register New Vehicle</h4>
                            <div class="vehicle-form-row">
                                <input type="text" placeholder="Make & Model (e.g. BMW X5)">
                                <input type="text" placeholder="License Plate">
                                <button type="button">Add to Fleet</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-card profile-security">
                    <div class="profile-security-icon">!</div>
                    <div>
                        <h4>Security & Data Privacy</h4>
                        <p>Your vehicle data is stored using high-level encryption. License plate information is used only for automated gate entry.</p>
                    </div>
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

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.4);
        opacity: 0.6;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.payments-shell {
    display: flex;
    flex-direction: column;
    gap: 44px;
    padding: 8px 0 24px;
}

.payments-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.payments-header h1 {
    margin: 0 0 6px;
    font-size: 32px;
    font-weight: 700;
    letter-spacing: -0.02em;
}

.payments-subtitle {
    margin: 0;
    color: #6b7280;
    font-size: 15px;
}

.payments-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 999px;
    background: rgba(16, 185, 129, 0.12);
    color: #059669;
    font-size: 12px;
    font-weight: 600;
}

.payments-badge-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 1.6s infinite;
}

.payments-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.payments-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
}

.payments-card h3 {
    margin: 8px 0 0;
    font-size: 24px;
}

.payments-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #6b7280;
    font-size: 13px;
    font-weight: 600;
}

.payments-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 12px;
    background: rgba(59, 130, 246, 0.12);
    color: #2563eb;
    font-size: 10px;
    font-weight: 700;
}

.payments-meta {
    margin: 8px 0 0;
    font-size: 12px;
    color: #9ca3af;
}

.payments-meta.positive {
    color: #10b981;
    font-weight: 600;
}

.payments-methods,
.payments-history {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 24px;
    margin-top: 10px;
}

.payments-section-head {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
}

.payments-section-head h2 {
    margin: 0;
    font-size: 20px;
}

.payments-link {
    border: none;
    background: none;
    color: #2563eb;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.payments-method-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
}

.payment-card {
    border-radius: 18px;
    padding: 20px;
    color: #ffffff;
    min-height: 180px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 16px 30px rgba(15, 23, 42, 0.2);
}

.payment-card.visa {
    background: linear-gradient(135deg, #0ea5e9, #1e40af);
}

.payment-card.master {
    background: linear-gradient(135deg, #2563eb, #0f172a);
}

.payment-card.airtel {
    background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.4)), url('pa/aritel.png');
    background-size: cover;
    background-position: center;
}

.payment-card.mtn {
    background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.4)), url('pa/mtn.png');
    background-size: cover;
    background-position: center;
}

.payment-card.wallet {
    background: #f8fafc;
    color: #0f172a;
    border: 2px dashed #e5e7eb;
    align-items: center;
    text-align: center;
    box-shadow: none;
}

.payment-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payment-card-number {
    font-size: 18px;
    letter-spacing: 0.18em;
}

.payment-card-bottom {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
}

.payment-card-bottom .label {
    margin: 0 0 4px;
    color: rgba(255, 255, 255, 0.6);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.payment-card.wallet .label {
    color: #94a3b8;
}

.chip {
    width: 34px;
    height: 24px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.2);
    display: inline-block;
}

.brand {
    font-weight: 700;
    font-size: 16px;
    letter-spacing: 0.1em;
}

.brand .dot {
    display: inline-block;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin-left: 4px;
}

.brand .dot.red {
    background: #ef4444;
}

.brand .dot.yellow {
    background: #f59e0b;
    margin-left: -6px;
}

.wallet-icon {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.3em;
    color: #94a3b8;
}

.wallet-title {
    margin: 8px 0 4px;
    font-weight: 700;
}

.wallet-subtitle {
    margin: 0;
    color: #94a3b8;
    font-size: 12px;
}

.wallet-btn {
    margin-top: 16px;
    padding: 8px 16px;
    border-radius: 8px;
    border: none;
    background: #0f172a;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.payments-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

.payments-search {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 14px;
    min-width: 220px;
}

.payments-filter {
    border: 1px solid #e5e7eb;
    background: #f8fafc;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.payments-table-wrap {
    overflow-x: auto;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
}

.payments-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.payments-table thead {
    background: #f8fafc;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.08em;
    color: #6b7280;
}

.payments-table th,
.payments-table td {
    padding: 14px 16px;
    text-align: left;
}

.payments-table tbody tr {
    border-top: 1px solid #e5e7eb;
}

.payments-table tbody tr:hover {
    background: #f8fafc;
}

.payments-table .muted {
    color: #9ca3af;
    font-size: 11px;
}

.payments-table .strong {
    font-weight: 700;
}

.payments-table .right {
    text-align: right;
}

.status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.status.paid {
    background: rgba(16, 185, 129, 0.12);
    color: #059669;
}

.status.failed {
    background: rgba(239, 68, 68, 0.12);
    color: #dc2626;
}

.status.pending {
    background: rgba(245, 158, 11, 0.12);
    color: #b45309;
}

.receipt-btn {
    border: none;
    background: rgba(37, 99, 235, 0.12);
    color: #2563eb;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.receipt-btn.alt {
    background: rgba(15, 23, 42, 0.08);
    color: #475569;
}

.payments-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 18px;
    font-size: 13px;
    color: #6b7280;
}

.payments-pagination {
    display: flex;
    gap: 10px;
}

.payments-pagination button {
    border: 1px solid #e5e7eb;
    background: #ffffff;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13px;
    cursor: pointer;
}

.payments-pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.dark-mode .payments-subtitle {
    color: #9aa6b2;
}

.dark-mode .payments-card,
.dark-mode .payments-methods,
.dark-mode .payments-history {
    background: #101a22;
    border-color: #1f2a36;
    box-shadow: 0 16px 30px rgba(0, 0, 0, 0.35);
}

.dark-mode .reservation-card-modern,
.dark-mode .reservations-table-wrap {
    background: #101a22;
    border-color: #1f2a36;
    box-shadow: 0 16px 30px rgba(0, 0, 0, 0.35);
}

.dark-mode .reservations-table thead,
.dark-mode .reservations-table tbody tr {
    background: #0f1419;
    border-top-color: #1f2a36;
}

.dark-mode .reservation-details,
.dark-mode .reservation-meta,
.dark-mode .reservation-pass-meta,
.dark-mode .reservations-table {
    color: #9aa6b2;
}

.dark-mode .reservation-card-title h4,
.dark-mode .reservation-details strong,
.dark-mode .reservations-table .strong {
    color: #ffffff;
}

.dark-mode .payments-card-head,
.dark-mode .payments-meta,
.dark-mode .payments-table thead {
    color: #9aa6b2;
}

.dark-mode .payments-card h3,
.dark-mode .payments-section-head h2,
.dark-mode .payments-table,
.dark-mode .payments-footer,
.dark-mode .payments-table .strong {
    color: #ffffff;
}

.dark-mode .payments-table-wrap {
    border-color: #1f2a36;
}

.dark-mode .payments-table thead {
    background: #0f1419;
}

.dark-mode .payments-table tbody tr {
    border-top-color: #1f2a36;
}

.dark-mode .payments-table tbody tr:hover {
    background: #0f1419;
}

.dark-mode .payments-search {
    background: #0f1419;
    border-color: #1f2a36;
    color: #ffffff;
}

.dark-mode .payments-filter,
.dark-mode .payments-pagination button {
    background: #101a22;
    border-color: #1f2a36;
    color: #e2e8f0;
}

.dark-mode .payment-card.wallet {
    background: #0f1419;
    border-color: #1f2a36;
    color: #e2e8f0;
}

@media (max-width: 768px) {
    .payments-header h1 {
        font-size: 26px;
    }

    .payments-table th,
    .payments-table td {
        padding: 12px;
    }
}

.reservations-shell {
    display: flex;
    flex-direction: column;
    gap: 28px;
    padding: 8px 0 32px;
}

.reservations-hero {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.reservations-hero h1 {
    margin: 0 0 6px;
    font-size: 32px;
    font-weight: 800;
    letter-spacing: -0.02em;
}

.reservations-subtitle {
    margin: 0;
    color: #6b7280;
    font-size: 15px;
}

.reservations-new {
    border: none;
    background: #2563eb;
    color: #ffffff;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 14px 24px rgba(37, 99, 235, 0.25);
}

.reservations-tabs {
    display: flex;
    gap: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.reservations-tab {
    background: none;
    border: none;
    padding: 10px 4px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #9ca3af;
    cursor: pointer;
    border-bottom: 3px solid transparent;
}

.reservations-tab.active {
    color: #2563eb;
    border-bottom-color: #2563eb;
    font-weight: 700;
}

.reservations-section {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.reservations-section-head {
    display: flex;
    align-items: center;
    gap: 10px;
}

.reservations-section-head h3 {
    margin: 0;
    font-size: 18px;
}

.reservations-section-head.history {
    justify-content: space-between;
}

.reservations-dot {
    width: 10px;
    height: 10px;
    border-radius: 999px;
    background: #10b981;
    box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.15);
}

.reservations-badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.12em;
    background: rgba(37, 99, 235, 0.12);
    color: #2563eb;
    padding: 4px 8px;
    border-radius: 999px;
}

.reservations-link {
    background: none;
    border: none;
    color: #2563eb;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.reservations-list {
    display: grid;
    gap: 16px;
}

.reservation-card-modern {
    display: flex;
    gap: 18px;
    padding: 18px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
    flex-wrap: wrap;
}

.reservation-card-image {
    width: 220px;
    height: 140px;
    border-radius: 14px;
    background: linear-gradient(135deg, #e2e8f0, #cbd5f5);
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
}

.reservation-card-image span {
    position: absolute;
    left: 12px;
    bottom: 12px;
    background: rgba(37, 99, 235, 0.85);
    color: #ffffff;
    padding: 4px 8px;
    font-size: 11px;
    font-weight: 700;
    border-radius: 8px;
}

.reservation-card-body {
    flex: 1;
    min-width: 220px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.reservation-card-title {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.reservation-card-title h4 {
    margin: 0;
    font-size: 18px;
}

.reservation-meta {
    font-size: 12px;
    color: #6b7280;
}

.reservation-status {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    padding: 4px 8px;
    border-radius: 999px;
    text-transform: uppercase;
}

.reservation-status.active {
    background: rgba(16, 185, 129, 0.12);
    color: #059669;
}

.reservation-status.upcoming {
    background: rgba(245, 158, 11, 0.12);
    color: #b45309;
}

.reservation-status.completed {
    background: rgba(148, 163, 184, 0.15);
    color: #64748b;
}

.reservation-status.cancelled {
    background: rgba(239, 68, 68, 0.12);
    color: #dc2626;
}

.reservation-payment {
    margin-top: 6px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.reservation-payment.paid {
    color: #059669;
}

.reservation-payment.pending {
    color: #b45309;
}

.reservation-payment.expired {
    color: #dc2626;
}

.reservation-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
    font-size: 13px;
    color: #6b7280;
}

.reservation-details strong {
    display: block;
    color: #111827;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.reservation-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: auto;
}

.reservation-actions button {
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid #e5e7eb;
    background: #f8fafc;
    cursor: pointer;
}

.reservation-actions .primary {
    background: #2563eb;
    color: #ffffff;
    border-color: transparent;
}

.reservation-actions .danger {
    background: rgba(239, 68, 68, 0.12);
    color: #dc2626;
    border-color: rgba(239, 68, 68, 0.2);
}

.reservation-pass {
    margin-top: 12px;
    padding: 12px;
    border-radius: 12px;
    border: 1px dashed #e5e7eb;
    background: #f8fafc;
    display: none;
    align-items: center;
    gap: 16px;
}

.reservation-pass.active {
    display: flex;
    flex-wrap: wrap;
}

.reservation-pass img {
    width: 120px;
    height: 120px;
    border-radius: 10px;
    background: #ffffff;
    padding: 6px;
    border: 1px solid #e5e7eb;
}

.reservation-empty {
    padding: 20px;
    border-radius: 12px;
    border: 1px dashed #e5e7eb;
    color: #6b7280;
    background: #f8fafc;
    font-size: 13px;
}

.reservation-pass-meta {
    font-size: 12px;
    color: #6b7280;
}

.reservations-table-wrap {
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    overflow-x: auto;
    background: #ffffff;
}

.reservations-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.reservations-table thead {
    background: #f8fafc;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 0.08em;
    color: #6b7280;
}

.reservations-table th,
.reservations-table td {
    padding: 14px 16px;
    text-align: left;
}

.reservations-table tbody tr {
    border-top: 1px solid #e5e7eb;
}

.reservations-table tbody tr:hover {
    background: #f8fafc;
}

.reservation-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    background: rgba(148, 163, 184, 0.15);
    color: #64748b;
}

.reservation-status-pill.completed {
    background: rgba(16, 185, 129, 0.12);
    color: #059669;
}

.reservation-status-pill.cancelled {
    background: rgba(239, 68, 68, 0.12);
    color: #dc2626;
}

.reservation-status-pill.payment-expired {
    background: rgba(239, 68, 68, 0.12);
    color: #dc2626;
}

.reservation-status-pill span {
    width: 6px;
    height: 6px;
    border-radius: 999px;
    background: currentColor;
}

@media (max-width: 768px) {
    .reservations-hero h1 {
        font-size: 26px;
    }

    .reservation-card-image {
        width: 100%;
        height: 160px;
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







