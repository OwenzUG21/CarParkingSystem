// Update your handleLogin function
async function handleLogin(event) {
    event.preventDefault();
    const email = event.target.querySelector('input[type="email"]').value;
    const password = event.target.querySelector('input[type="password"]').value;
    
    try {
        // Try to login directly - if it fails, we'll get a proper error message
        currentUser = await loginUser(email, password);
        localStorage.removeItem('userProfile');
        localStorage.removeItem('bookings');
        localStorage.removeItem('allBookings');
        localStorage.removeItem('payments');
        
        // Check if user is admin and redirect to admin dashboard
        if (currentUser.is_admin) {
            window.location.href = '/admin';
            return;
        }
        
        updateUserProfile();
        
        // Check if user was trying to book a parking spot before login
        if (selectedParking && !document.getElementById('detailsModal').classList.contains('active')) {
            // User was trying to view parking details, show them now
            showParkingDetails(selectedParking);
        } else {
            // Normal login flow - go to dashboard
            showDashboard();
        }
        
        // Fetch data from backend
        const locations = await fetchParkingLocations();
        parkingLocations.length = 0;
        parkingLocations.push(...locations);
        displayParkingLocations();
        
        const reservations = await fetchReservations();
        userReservations.length = 0;
        userReservations.push(...reservations);
        
    } catch (error) {
        showDialog('Login failed: ' + error.message, 'error');
    }
}

// Update your handleSignup function
async function handleSignup(event) {
    event.preventDefault();
    const name = event.target.querySelector('input[type="text"]').value;
    const email = event.target.querySelector('input[type="email"]').value;
    const password = event.target.querySelector('input[type="password"]').value;
    
    try {
        // Try to register directly - if it fails, we'll get a proper error message
        currentUser = await registerUser(name, email, password);
        localStorage.removeItem('userProfile');
        localStorage.removeItem('bookings');
        localStorage.removeItem('allBookings');
        localStorage.removeItem('payments');
        updateUserProfile();
        showDashboard();
        
        // Fetch initial data
        const locations = await fetchParkingLocations();
        parkingLocations.length = 0;
        parkingLocations.push(...locations);
        displayParkingLocations();
        
    } catch (error) {
        showDialog('Registration failed: ' + error.message, 'error');
    }
}

// Store pending booking details
let pendingBooking = null;

// Update your confirmBooking function - now it goes to payment first
async function confirmBooking(event) {
    event.preventDefault();
    
    const date = document.getElementById('bookingDate').value;
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;
    const vehicle = event.target.querySelector('input[placeholder*="ABC"]').value;
    
    const start = new Date(`2000-01-01T${startTime}`);
    const end = new Date(`2000-01-01T${endTime}`);
    const hours = (end - start) / (1000 * 60 * 60);
    const amount = hours * selectedParking.price;
    const fees = amount * 0.2;
    const total = amount + fees;
    
    // Store booking details for after payment
    pendingBooking = {
        parking_location_id: selectedParking.id,
        date: date,
        start_time: startTime,
        end_time: endTime,
        license_plate: vehicle,
        total_amount: amount,
        amount: amount,
        fees: fees,
        total: total,
        hours: hours,
        parking: selectedParking
    };
    
    // Close booking modal and go to payment page
    closeBookingModal();
    showPayments();
}

// Update your saveAccountSettings function
async function saveAccountSettings(event) {
    event.preventDefault();
    
    const profileData = {
        name: document.getElementById('settingName').value,
        email: document.getElementById('settingEmail').value,
        phone: document.getElementById('settingPhone').value,
        address: document.getElementById('settingAddress').value,
    };
    
    try {
        const response = await updateUserProfile(profileData);
        currentUser = response.user || response;
        updateUserProfile();
            showDialog('Account settings saved successfully!', 'success');
    } catch (error) {
            showDialog('Failed to save settings: ' + error.message, 'error');
    }
}

// Add this function to test your API connection
async function testApiConnection() {
    try {
        console.log('Testing API connection...');
        const healthy = await checkApiHealth();
        if (healthy) {
            console.log('✅ API is connected and healthy');
            return true;
        } else {
            console.log('❌ API connection failed');
            return false;
        }
    } catch (error) {
        console.log('❌ API connection error:', error.message);
        return false;
    }
}

// Call this in your console to test
window.testApi = testApiConnection;

// Update logout function to use API
async function logout() {
    try {
        updateAuthToken();
        if (authToken) {
            await logoutUser();
        }
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        clearAuthState();
        closeAllDropdowns();
        hideAllPages();
        document.getElementById('landingPage').classList.add('active');
    }
}

// Update cancelReservation function to use API
async function cancelReservation(id) {
    if (confirm('Are you sure you want to cancel this reservation?')) {
        try {
            await cancelReservationAPI(id);
            const reservation = userReservations.find(r => r.id === id);
            if (reservation) {
                reservation.status = 'cancelled';
                displayReservations();
            }
        } catch (error) {
            showDialog('Cancellation failed: ' + error.message, 'error');
        }
    }
}

// Sample parking data with realistic locations (fallback data)
const parkingLocations = [
    {
        id: 1,
        name: "Central City Parking",
        address: "123 Main Street, Downtown",
        lat: 0.3476,
        lng: 32.5825,
        rating: 4.5,
        price: 5,
        available: 45,
        total: 100,
        distance: 0.8,
        image: "https://images.unsplash.com/photo-1590674899484-d5640e854abe?w=800",
        features: ["24/7 Security", "CCTV", "Covered", "EV Charging"]
    },
    {
        id: 2,
        name: "Garden City Mall Parking",
        address: "Garden City Shopping Mall",
        lat: 0.3136,
        lng: 32.5811,
        rating: 4.8,
        price: 8,
        available: 12,
        total: 150,
        distance: 2.1,
        image: "https://images.unsplash.com/photo-1506521781263-d8422e82f27a?w=800",
        features: ["Mall Access", "Covered Parking", "Security Guards", "Wheelchair Accessible"]
    },
    {
        id: 3,
        name: "Nakasero Market Parking",
        address: "Nakasero Road, Kampala",
        lat: 0.3280,
        lng: 32.5792,
        rating: 4.2,
        price: 3,
        available: 28,
        total: 60,
        distance: 1.5,
        image: "https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=800",
        features: ["Open Air", "CCTV", "Near Market", "Motorcycle Parking"]
    },
    {
        id: 4,
        name: "Acacia Mall Parking",
        address: "Kisementi, Kololo",
        lat: 0.3367,
        lng: 32.5958,
        rating: 4.7,
        price: 10,
        available: 67,
        total: 200,
        distance: 3.2,
        image: "https://images.unsplash.com/photo-1589578527966-fdac0f44566c?w=800",
        features: ["Multi-level", "Valet Service", "EV Charging", "Premium Security"]
    },
    {
        id: 5,
        name: "Kampala Road Parking",
        address: "Kampala Road, City Center",
        lat: 0.3163,
        lng: 32.5811,
        rating: 3.9,
        price: 4,
        available: 8,
        total: 80,
        distance: 1.2,
        image: "https://images.unsplash.com/photo-1506521781263-d8422e82f27a?w=800",
        features: ["City Center", "24/7 Access", "CCTV", "Pay & Display"]
    },
    {
        id: 6,
        name: "Sheraton Hotel Parking",
        address: "Ternan Avenue, Kampala",
        lat: 0.3239,
        lng: 32.5758,
        rating: 4.6,
        price: 12,
        available: 34,
        total: 120,
        distance: 1.8,
        image: "https://images.unsplash.com/photo-1520656773907-3088dcf6b5e1?w=800",
        features: ["Hotel Guest Priority", "Valet Available", "Covered", "Premium Security"]
    }
];

// User state management
let currentUser = null;
let userReservations = [];
let userPayments = [];
let selectedParking = null;
let currentReservationFilter = 'active';

function clearAuthState() {
    currentUser = null;
    userReservations = [];
    userPayments = [];
    localStorage.removeItem('authToken');
    localStorage.removeItem('userProfile');
    localStorage.removeItem('bookings');
    localStorage.removeItem('allBookings');
    localStorage.removeItem('payments');
    localStorage.removeItem('isAdmin');
    updateUserProfileSection();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    detectUserLocation();
    initializeApp();
    loadDarkMode();
    renderNotifications();
    const notificationToggle = document.getElementById('notificationToggle');
    const profileToggle = document.getElementById('profileToggle');
    if (notificationToggle) {
        notificationToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleDropdown('notificationMenu', 'notificationToggle');
        });
    }
    if (profileToggle) {
        profileToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleDropdown('profileMenu', 'profileToggle');
        });
    }
    document.addEventListener('click', () => {
        closeAllDropdowns();
    });
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', (event) => {
            event.stopPropagation();
        });
    });
});

// Initialize app - check if user is logged in via API
async function initializeApp() {
    const token = localStorage.getItem('authToken');
    if (token) {
        try {
            // Verify token is still valid
            const profile = await getProfile();
            currentUser = profile.data?.user || profile;
            updateUserProfile();
            showDashboard();
            
            // Load data from backend
            await loadBackendData();
        } catch (error) {
            console.error('Token invalid, clearing storage:', error);
            clearAuthState();
            loadParkingLocations();
        }
    } else {
        clearAuthState();
        // Load parking locations even if not logged in
        loadParkingLocations();
    }
}

// Load parking locations (public, no auth required)
async function loadParkingLocations() {
    try {
        const locations = await fetchParkingLocations();
        if (locations && locations.length > 0) {
            parkingLocations.length = 0;
            parkingLocations.push(...locations);
            displayParkingLocations();
        }
    } catch (error) {
        console.error('Error loading parking locations:', error);
        // Continue with fallback data
    }
}

// Load backend data
async function loadBackendData() {
    try {
        const [locations, reservations] = await Promise.all([
            fetchParkingLocations(),
            fetchReservations()
        ]);
        
        // Use backend data if available, otherwise keep fallback data
        if (locations && locations.length > 0) {
            parkingLocations.length = 0;
            parkingLocations.push(...locations);
        }
        
        if (reservations && reservations.length > 0) {
            userReservations.length = 0;
            // Normalize all reservations from backend
            userReservations.push(...reservations.map(r => normalizeReservation(r)));
        }
        
        // Load payment history from database
        await loadPaymentHistory();
        
        displayParkingLocations();
    } catch (error) {
        console.error('Error loading backend data:', error);
        // Continue with fallback data
    }
}

// Load data from localStorage (fallback)
function loadFromLocalStorage() {
    const token = localStorage.getItem('authToken');
    const savedUser = localStorage.getItem('userProfile');
    const savedReservations = localStorage.getItem('bookings');
    const savedPayments = localStorage.getItem('payments');
    
    if (token && savedUser && !currentUser) {
        currentUser = JSON.parse(savedUser);
        updateUserProfile();
    }
    
    if (token && savedReservations) {
        userReservations = JSON.parse(savedReservations);
    }
    
    if (token && savedPayments) {
        userPayments = JSON.parse(savedPayments);
    }
}

// Save data to localStorage
function saveToLocalStorage() {
    if (currentUser) {
        localStorage.setItem('userProfile', JSON.stringify(currentUser));
    }
    
    // Save active bookings
    const activeBookings = userReservations.filter(r => r.status === 'active' || r.status === 'upcoming');
    localStorage.setItem('bookings', JSON.stringify(activeBookings));
    
    // Save all bookings
    localStorage.setItem('allBookings', JSON.stringify(userReservations));
    
    // Save payments
    localStorage.setItem('payments', JSON.stringify(userPayments));
}

// Page Navigation Functions
function showLogin() {
    hideAllPages();
    document.getElementById('loginPage').classList.add('active');
}

function showSignup() {
    hideAllPages();
    document.getElementById('signupPage').classList.add('active');
}

function showDashboard() {
    hideAllPages();
    document.getElementById('dashboardPage').classList.add('active');
    showDashboardContent();
    updateNavLinks('Find Parking');
    
    // Load parking locations even if not logged in
    loadParkingLocations();
}

function showReservations() {
    // Check if user is logged in
    if (!currentUser) {
        showDialog('Please login to view your reservations', 'info', 'Login Required');
        showLogin();
        return;
    }
    
    hideAllDashboardContent();
    document.getElementById('reservationsContent').classList.add('active');
    updateNavLinks('My Reservations');
    displayReservations();
}

function showPayments() {
    // Check if user is logged in
    if (!currentUser) {
        showDialog('Please login to view your payments', 'info', 'Login Required');
        showLogin();
        return;
    }
    
    hideAllDashboardContent();
    document.getElementById('paymentsContent').classList.add('active');
    updateNavLinks('Payments');
    
    // If there's a pending booking, show payment form, otherwise show history
    if (pendingBooking) {
        displayPayments();
    } else {
        displayPaymentHistory();
    }
}

function hideAllPages() {
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });
}

function hideAllDashboardContent() {
    document.querySelectorAll('.dashboard-content').forEach(content => {
        content.classList.remove('active');
    });
}

function updateNavLinks(activeLink) {
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.textContent === activeLink) {
            link.classList.add('active');
        }
    });
}

// Authentication Functions (Updated to use API)
function updateUserProfile() {
    const userNameEl = document.getElementById('userName');
    const userEmailEl = document.getElementById('userEmail');
    const userAvatarEl = document.getElementById('userAvatar');
    
    if (currentUser) {
        userNameEl.textContent = currentUser.name || 'User';
        userEmailEl.textContent = currentUser.email || '';
        userAvatarEl.textContent = (currentUser.name || 'U').charAt(0).toUpperCase();
        
        // Save to localStorage as backup
        localStorage.setItem('userProfile', JSON.stringify(currentUser));
    }
    
    // Update the profile section
    updateUserProfileSection();
}

function showSettings() {
    // Check if user is logged in
    if (!currentUser) {
        showDialog('Please login to access your settings', 'info', 'Login Required');
        showLogin();
        return;
    }
    
    hideAllDashboardContent();
    document.getElementById('settingsContent').classList.add('active');
    updateNavLinks('Settings');
    loadSettings();
}

function loadSettings() {
    // Load account details
    if (currentUser) {
        document.getElementById('settingName').value = currentUser.name || '';
        document.getElementById('settingEmail').value = currentUser.email || '';
        document.getElementById('settingPhone').value = currentUser.phone || '';
        document.getElementById('settingAddress').value = currentUser.address || '';
        
        // Update default vehicle
        if (currentUser.vehicle) {
            document.getElementById('defaultVehicle').textContent = currentUser.vehicle;
        }
    }
    
    // Load notification preferences
    const emailNotif = localStorage.getItem('emailNotifications') !== 'false';
    const smsNotif = localStorage.getItem('smsNotifications') === 'true';
    const pushNotif = localStorage.getItem('pushNotifications') !== 'false';
    
    document.getElementById('emailNotifications').checked = emailNotif;
    document.getElementById('smsNotifications').checked = smsNotif;
    document.getElementById('pushNotifications').checked = pushNotif;
    
    // Load security settings
    const twoFactor = localStorage.getItem('twoFactor') === 'true';
    const shareLocation = localStorage.getItem('shareLocation') !== 'false';
    
    document.getElementById('twoFactor').checked = twoFactor;
    document.getElementById('shareLocation').checked = shareLocation;
    
    // Load theme color
    const themeColor = localStorage.getItem('themeColor') || 'primary';
    document.querySelectorAll('.color-option').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.color === themeColor) {
            btn.classList.add('active');
        }
    });
}

function saveNotificationSettings() {
    const emailNotif = document.getElementById('emailNotifications').checked;
    const smsNotif = document.getElementById('smsNotifications').checked;
    const pushNotif = document.getElementById('pushNotifications').checked;
    
    localStorage.setItem('emailNotifications', emailNotif);
    localStorage.setItem('smsNotifications', smsNotif);
    localStorage.setItem('pushNotifications', pushNotif);
}

function saveSecuritySettings() {
    const twoFactor = document.getElementById('twoFactor').checked;
    const shareLocation = document.getElementById('shareLocation').checked;
    
    localStorage.setItem('twoFactor', twoFactor);
    localStorage.setItem('shareLocation', shareLocation);
    
    if (twoFactor) {
        showDialog('Two-factor authentication enabled. You will receive a verification code on your next login.', 'success', '2FA Enabled');
    }
}

function changeThemeColor(color) {
    const colors = {
        primary: '#6366f1',
        green: '#10b981',
        orange: '#f59e0b',
        red: '#ef4444',
        purple: '#a855f7'
    };
    
    document.documentElement.style.setProperty('--primary', colors[color]);
    localStorage.setItem('themeColor', color);
    
    // Update active color button
    document.querySelectorAll('.color-option').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.color === color) {
            btn.classList.add('active');
        }
    });
}

function addVehicle() {
    const vehicle = prompt('Enter your vehicle details (e.g., Toyota Camry - UAH 123X):');
    if (vehicle && currentUser) {
        currentUser.vehicle = vehicle;
        document.getElementById('defaultVehicle').textContent = vehicle;
        localStorage.setItem('userProfile', JSON.stringify(currentUser));
    }
}

function deleteAccount() {
    const confirmation = confirm('Are you sure you want to delete your account? This action cannot be undone.');
    if (confirmation) {
        const finalConfirmation = prompt('Type "DELETE" to confirm account deletion:');
        if (finalConfirmation === 'DELETE') {
            // Clear all data
            localStorage.clear();
            currentUser = null;
            userReservations = [];
            userPayments = [];
            
            showDialog('Your account has been permanently deleted.', 'info', 'Account Deleted');
            logout();
        }
    }
}

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark);
}

// Load dark mode preference and theme color
function loadDarkMode() {
    const isDark = localStorage.getItem('darkMode') === 'true';
    if (isDark) {
        document.body.classList.add('dark-mode');
        const toggle = document.getElementById('darkModeToggle');
        if (toggle) toggle.checked = true;
    }
    
    // Load theme color
    const themeColor = localStorage.getItem('themeColor');
    if (themeColor) {
        changeThemeColor(themeColor);
    }
}

// Location Functions
function detectUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                updateLocationDisplay('Kampala, Uganda');
            },
            (error) => {
                updateLocationDisplay('Kampala, Uganda (Default)');
            }
        );
    } else {
        updateLocationDisplay('Location not available');
    }
}

function updateLocationDisplay(location) {
    const locationElement = document.getElementById('userLocation');
    if (locationElement) {
        locationElement.textContent = location;
    }
}

// Dashboard Functions
function showDashboardContent() {
    hideAllDashboardContent();
    document.getElementById('dashboardContent').classList.add('active');
    displayParkingLocations();
    
    // Update user profile section based on login status
    updateUserProfileSection();
}

function updateUserProfileSection() {
    const userAvatar = document.getElementById('userAvatar');
    const userName = document.getElementById('userName');
    const userEmail = document.getElementById('userEmail');
    const profileLoginItem = document.getElementById('profileLoginItem');
    const profileLogoutItem = document.getElementById('profileLogoutItem');
    
    if (currentUser) {
        // User is logged in - show their info
        userName.textContent = currentUser.name || 'User';
        userEmail.textContent = currentUser.email || '';
        userAvatar.textContent = (currentUser.name || 'U').charAt(0).toUpperCase();
        if (profileLoginItem) profileLoginItem.style.display = 'none';
        if (profileLogoutItem) profileLogoutItem.style.display = 'flex';
    } else {
        // User is not logged in - show login prompt
        userName.textContent = 'Guest User';
        userEmail.textContent = 'Please login to book parking';
        userAvatar.textContent = 'G';
        if (profileLoginItem) profileLoginItem.style.display = 'flex';
        if (profileLogoutItem) profileLogoutItem.style.display = 'none';
    }
}

// Notification UI
const notifications = [
    {
        id: 1,
        title: 'Booking Confirmed',
        message: 'Your parking spot is reserved for today at 3:00 PM.',
        time: '5m ago',
        read: false
    },
    {
        id: 2,
        title: 'Payment Receipt',
        message: 'UGX 8,000 paid successfully for Acacia Mall parking.',
        time: '1h ago',
        read: false
    },
    {
        id: 3,
        title: 'Reminder',
        message: 'Your reservation starts in 30 minutes.',
        time: 'Yesterday',
        read: true
    }
];

function renderNotifications() {
    const list = document.getElementById('notificationList');
    const count = document.getElementById('notificationCount');
    if (!list || !count) return;
    const unreadCount = notifications.filter(n => !n.read).length;
    count.textContent = unreadCount;
    count.style.display = unreadCount > 0 ? 'flex' : 'none';
    list.innerHTML = '';
    if (!notifications.length) {
        list.innerHTML = '<div class="notification-empty">No notifications yet.</div>';
        return;
    }
    notifications.forEach(notification => {
        const item = document.createElement('div');
        item.className = `notification-item ${notification.read ? '' : 'unread'}`;
        item.innerHTML = `
            <div class="notification-content">
                <div class="notification-title">${notification.title}</div>
                <div class="notification-message">${notification.message}</div>
            </div>
            <div class="notification-time">${notification.time}</div>
        `;
        item.addEventListener('click', () => {
            notification.read = true;
            renderNotifications();
        });
        list.appendChild(item);
    });
}

function markAllNotificationsRead() {
    notifications.forEach(notification => {
        notification.read = true;
    });
    renderNotifications();
}

function toggleDropdown(menuId, toggleId) {
    const menu = document.getElementById(menuId);
    const toggle = document.getElementById(toggleId);
    if (!menu || !toggle) return;
    const isOpen = menu.classList.contains('open');
    closeAllDropdowns();
    if (!isOpen) {
        menu.classList.add('open');
        toggle.classList.add('active');
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.remove('open');
    });
    document.querySelectorAll('.icon-btn, .profile-btn').forEach(button => {
        button.classList.remove('active');
    });
}

function displayParkingLocations() {
    const grid = document.getElementById('parkingGrid');
    if (!grid) return;
    
    grid.innerHTML = '';
    
    parkingLocations.forEach(parking => {
        const card = createParkingCard(parking);
        grid.appendChild(card);
    });
}

function createParkingCard(parking) {
    const card = document.createElement('div');
    card.className = 'parking-card';
    card.onclick = () => showParkingDetails(parking);
    
    const availabilityPercent = (parking.available / parking.total) * 100;
    const statusClass = availabilityPercent > 30 ? 'available' : 'limited';
    const statusText = availabilityPercent > 30 ? 'Available' : 'Limited';
    
    card.innerHTML = `
        <div class="parking-image" style="background-image: url('${parking.image}'); background-size: cover; background-position: center;">
            <div class="parking-badge ${statusClass}">${statusText}</div>
        </div>
        <div class="parking-info">
            <div class="parking-header">
                <div>
                    <div class="parking-name">${parking.name}</div>
                    <div class="parking-address">${parking.address}</div>
                </div>
                <div class="parking-rating">
                    <span>⭐</span>
                    <span>${parking.rating}</span>
                </div>
            </div>
            <div class="parking-details">
                <div class="detail-item">
                    <div class="detail-value">${parking.available}/${parking.total}</div>
                    <div class="detail-label">Available</div>
                </div>
                <div class="detail-item">
                    <div class="detail-value">UGX ${parking.price}/hr</div>
                    <div class="detail-label">Price</div>
                </div>
                <div class="detail-item">
                    <div class="detail-value">${parking.distance} km</div>
                    <div class="detail-label">Distance</div>
                </div>
            </div>
        </div>
    `;
    
    return card;
}

// Modal Functions
function showParkingDetails(parking) {
    // Check if user is authenticated before showing parking details
    if (!currentUser) {
        // Store the parking location they tried to view
        selectedParking = parking;
        // Show login page with a message
        showDialog('Please login to view parking details and make bookings', 'info', 'Login Required');
        showLogin();
        return;
    }
    
    selectedParking = parking;
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('modalContent');
    
    if (!modal || !content) return;
    
    content.innerHTML = `
        <div class="modal-header">
            <h2 class="modal-title">${parking.name}</h2>
            <p class="modal-subtitle">${parking.address}</p>
        </div>
        
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Available Spaces</div>
                <div class="info-value">${parking.available} / ${parking.total}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Price per Hour</div>
                <div class="info-value">UGX ${parking.price}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Rating</div>
                <div class="info-value">⭐ ${parking.rating}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Distance</div>
                <div class="info-value">${parking.distance} km</div>
            </div>
        </div>
        
        <div class="map-container">
            <iframe 
                width="100%" 
                height="100%" 
                frameborder="0" 
                style="border:0; border-radius: 10px;" 
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=${parking.lat},${parking.lng}&zoom=15"
                allowfullscreen>
            </iframe>
        </div>
        
        <div style="margin: 2rem 0;">
            <h3 style="margin-bottom: 1rem;">Features</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                ${parking.features.map(feature => 
                    `<span style="padding: 0.5rem 1rem; background: var(--light); border-radius: 20px; font-size: 0.9rem;">
                        ✓ ${feature}
                    </span>`
                ).join('')}
            </div>
        </div>
        
        <div class="modal-actions">
            <button class="modal-btn btn-secondary" onclick="openGoogleMaps(${parking.lat}, ${parking.lng})">
                Get Directions
            </button>
            <button class="modal-btn btn-primary" onclick="openBookingModal()">
                Book Now
            </button>
        </div>
    `;
    
    modal.classList.add('active');
}

function closeModal() {
    const modal = document.getElementById('detailsModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function openGoogleMaps(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, '_blank');
}

// Booking Functions
function openBookingModal() {
    closeModal();
    const modal = document.getElementById('bookingModal');
    const content = document.getElementById('bookingContent');

    if (!modal || !content) return;

    content.innerHTML = "";
    
    const now = new Date();
    const currentTime = now.toTimeString().slice(0, 5);
    const endTime = new Date(now.getTime() + 2 * 60 * 60 * 1000).toTimeString().slice(0, 5);
    
    content.innerHTML = `
        <div class="modal-header">
            <h2 class="modal-title">Book Parking Spot</h2>
            <p class="modal-subtitle">${selectedParking.name}</p>
        </div>
        
        <form class="booking-form" onsubmit="confirmBooking(event)">
            <div class="form-group">
                <label>Select Date</label>
                <input type="date" class="time-input" id="bookingDate" 
                    min="${now.toISOString().split('T')[0]}" 
                    value="${now.toISOString().split('T')[0]}" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Start Time</label>
                    <input type="time" class="time-input" id="startTime" 
                        value="${currentTime}" required onchange="calculatePrice()">
                </div>
                <div class="form-group">
                    <label>End Time</label>
                    <input type="time" class="time-input" id="endTime" 
                        value="${endTime}" required onchange="calculatePrice()">
                </div>
            </div>
            
            <div class="form-group">
                <label>Vehicle Number</label>
                <input type="text" class="time-input" placeholder="e.g., ABC-1234" required>
            </div>
            
            <div class="price-summary">
                <div class="price-row">
                    <span>Duration:</span>
                    <span id="duration">2 hours</span>
                </div>
                <div class="price-row">
                    <span>Rate:</span>
                    <span>UGX ${selectedParking.price}/hour</span>
                </div>
                <div class="price-row price-total">
                    <span>Total:</span>
                    <span id="totalPrice">UGX ${selectedParking.price * 2}</span>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="modal-btn btn-secondary" onclick="closeBookingModal()">
                    Cancel
                </button>
                <button type="submit" class="modal-btn btn-primary">
                    Proceed to Payment
                </button>
            </div>
        </form>
    `;
    
    modal.classList.add('active');
}

function closeBookingModal() {
    const modal = document.getElementById('bookingModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function calculatePrice() {
    const startTime = document.getElementById('startTime');
    const endTime = document.getElementById('endTime');
    
    if (!startTime || !endTime || !selectedParking) return;
    
    const startVal = startTime.value;
    const endVal = endTime.value;
    
    if (startVal && endVal) {
        const start = new Date(`2000-01-01T${startVal}`);
        const end = new Date(`2000-01-01T${endVal}`);
        const hours = (end - start) / (1000 * 60 * 60);
        
        if (hours > 0) {
            const total = hours * selectedParking.price;
            const durationEl = document.getElementById('duration');
            const totalPriceEl = document.getElementById('totalPrice');
            
            if (durationEl) durationEl.textContent = `${hours.toFixed(1)} hours`;
            if (totalPriceEl) totalPriceEl.textContent = `UGX ${total.toFixed(2)}`;
        }
    }
}

// Auto-update booking statuses based on current time
function updateBookingStatuses() {
    const now = new Date();
    let updated = false;
    
    userReservations.forEach(r => {
        const reservation = normalizeReservation(r);
        if (!reservation.date) return;
        
        // Skip if already cancelled
        if (reservation.status === 'cancelled') {
            if (r.status !== 'cancelled') {
                r.status = 'cancelled';
                updated = true;
            }
            return;
        }
        
        const startTime = reservation.startTime || reservation.start_time || '';
        const endTime = reservation.endTime || reservation.end_time || '';
        
        if (!startTime || !endTime) {
            return;
        }

        // Parse date and time - handle both date-only and datetime strings
        let startDateTime, endDateTime;
        try {
            // Get just the date part (handle both "2024-01-01" and "2024-01-01T10:00:00" formats)
            const dateStr = reservation.date.split('T')[0];
            
            // Ensure time is in HH:MM format
            const startTimeStr = startTime.length === 5 ? startTime : startTime.substring(0, 5);
            const endTimeStr = endTime.length === 5 ? endTime : endTime.substring(0, 5);
            
            startDateTime = new Date(`${dateStr}T${startTimeStr}`);
            endDateTime = new Date(`${dateStr}T${endTimeStr}`);
            
            // Validate dates
            if (isNaN(startDateTime.getTime()) || isNaN(endDateTime.getTime())) {
                console.warn('Invalid date/time for reservation:', reservation.id, dateStr, startTimeStr, endTimeStr);
                return; // Invalid dates, skip
            }
        } catch (e) {
            console.warn('Error parsing date/time for reservation:', reservation.id, e);
            return; // Error parsing, skip
        }

        // Determine new status based on current time
        let newStatus;
        if (now < startDateTime) {
            // Reservation hasn't started yet
            newStatus = 'upcoming';
        } else if (now >= startDateTime && now < endDateTime) {
            // Reservation is currently active
            newStatus = 'active';
        } else if (now >= endDateTime) {
            // Reservation has ended
            newStatus = 'completed';
        }
        
        // Update status if it changed
        if (r.status !== newStatus) {
            r.status = newStatus;
            updated = true;
        }
    });
    
    if (updated) {
        saveToLocalStorage();
    }
}

// Display Functions
function displayReservations(filter = 'active') {
    const container = document.getElementById('reservationsList');
    if (!container) return;

    // Update statuses before filtering
    updateBookingStatuses();

    // Filter reservations - use the actual status from the reservation object
    let filteredReservations = [];
    switch (filter) {
        case 'past':
            filteredReservations = userReservations.filter(r => {
                // Use the status directly from r (which was updated by updateBookingStatuses)
                const status = r.status || normalizeReservation(r).status;
                return status === 'completed' || status === 'cancelled';
            });
            break;
        case 'cancelled':
            filteredReservations = userReservations.filter(r => {
                const status = r.status || normalizeReservation(r).status;
                return status === 'cancelled';
            });
            break;
        default:
            // Default shows both upcoming and active
            filteredReservations = userReservations.filter(r => {
                const status = r.status || normalizeReservation(r).status;
                return status === 'active' || status === 'upcoming';
            });
    }

    // No reservations
    if (filteredReservations.length === 0) {
        container.innerHTML = `
            <div style="text-align:center; padding:4rem; color:var(--gray);">
                <p style="font-size:3rem;">🚗</p>
                <h3>No ${filter === 'past' ? 'Past' : filter === 'cancelled' ? 'Canceled' : 'Active'} Bookings</h3>
                <p>Book a parking spot to see it here.</p>
            </div>`;
        return;
    }

    // Build reservation cards
    container.innerHTML = filteredReservations.map(r => {
        // Normalize reservation data
        const reservation = normalizeReservation(r);
        
        const isPast = reservation.status === 'completed';
        const isCanceled = reservation.status === 'cancelled';
        const statusColor =
            reservation.status === 'active' ? 'var(--success)' :
            reservation.status === 'upcoming' ? 'var(--warning)' :
            isCanceled ? 'var(--danger)' : 'var(--gray)';
        const statusBg =
            reservation.status === 'active' ? 'rgba(16,185,129,0.1)' :
            reservation.status === 'upcoming' ? 'rgba(245,158,11,0.15)' :
            isCanceled ? 'rgba(239,68,68,0.15)' : 'rgba(100,116,139,0.15)';
        const qrId = `qr-${reservation.id}`;
        const barcodeId = `barcode-${reservation.id}`;
        
        // Get parking name and address
        const parkingName = reservation.parking?.name || reservation.parking_location?.name || reservation.parkingLocation?.name || 'Unknown Parking';
        const parkingAddress = reservation.parking?.address || reservation.parking_location?.address || reservation.parkingLocation?.address || '';
        
        // Get time values
        const startTime = reservation.startTime || reservation.start_time || 'N/A';
        const endTime = reservation.endTime || reservation.end_time || 'N/A';
        const date = reservation.date || 'N/A';
        const vehicle = reservation.vehicle || reservation.license_plate || 'N/A';
        const amount = reservation.amount || reservation.total_amount || 0;

        return `
        <div class="reservation-card" style="
            background: var(--light);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            border: 1px solid var(--border);
            opacity: ${isPast ? 0.8 : 1};
            transition: all 0.3s ease;
        ">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <h3 style="font-size:1.1rem; font-weight:700; color:var(--dark);">${parkingName}</h3>
                    <p style="font-size:0.9rem; color:var(--gray);">${parkingAddress}</p>
                </div>
                <span style="
                    background:${statusBg};
                    color:${statusColor};
                    font-weight:600;
                    border-radius:20px;
                    padding:0.3rem 0.8rem;
                    font-size:0.8rem;">
                    ${reservation.status.toUpperCase()}
                </span>
            </div>

            <div style="border-top:1px solid var(--border); margin-top:1rem; padding-top:1rem;
                display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; color:var(--gray); font-size:0.9rem;">
                <div>📅 ${date}</div>
                <div>⏰ ${startTime} - ${endTime}</div>
                <div>🚗 ${vehicle}</div>
                <div>💰 UGX ${(parseFloat(amount)).toFixed(2)}</div>
            </div>

            <div class="ticket-section">
                <div class="qr-wrapper">
                    <div id="${qrId}" class="qr-box"></div>
                    <p class="qr-label">Scan Entry</p>
                </div>

                <div class="barcode-wrapper">
                    <svg id="${barcodeId}" class="barcode"></svg>
                    <p class="booking-code">${reservation.bookingCode || "PKE" + reservation.id}</p>
                    <p class="booking-label">Booking ID</p>
                </div>
            </div>

            <div style="margin-top:1rem; display:flex; justify-content:flex-end; gap:0.5rem;">
                ${!isPast && !isCanceled ? `
                    <button onclick="cancelReservation(${reservation.id})"
                        style="background:rgba(239,68,68,0.1); color:var(--danger);
                        border:none; border-radius:8px; padding:0.5rem 1rem; cursor:pointer; font-weight:600;">
                        Cancel
                    </button>
                ` : ''}
                ${isPast ? `
                    <button onclick="printReservation(${reservation.id})"
                        style="background:var(--primary); color:white; border:none; border-radius:8px; padding:0.5rem 1rem; cursor:pointer; font-weight:600;">
                        View Receipt
                    </button>
                ` : `
                    <button onclick="printReservation(${reservation.id})"
                        style="background:var(--primary); color:white; border:none; border-radius:8px; padding:0.5rem 1rem; cursor:pointer; font-weight:600;">
                        Print
                    </button>
                `}
            </div>
        </div>`;
    }).join('');

    // Generate barcodes & QR codes
    filteredReservations.forEach(r => {
        const reservation = normalizeReservation(r);
        const parkingName = reservation.parking?.name || reservation.parking_location?.name || reservation.parkingLocation?.name || 'Parking';
        generateBarcode(reservation.id);
        generateQRCode(reservation.id, `PKE${reservation.id}-${parkingName}`);
    });
}

// Normalize reservation data to handle both frontend and backend formats
function normalizeReservation(reservation) {
    if (!reservation) return reservation;
    
    // Handle nested data structure from API
    const data = reservation.data || reservation;
    
    // Normalize parking location
    const parking = data.parking || data.parking_location || data.parkingLocation || null;
    
    // Normalize time fields
    const startTime = data.startTime || data.start_time || '';
    const endTime = data.endTime || data.end_time || '';
    
    // Normalize vehicle field
    const vehicle = data.vehicle || data.license_plate || '';
    
    // Normalize amount
    const amount = data.amount || data.total_amount || 0;
    
    return {
        ...data,
        id: data.id,
        parking: parking,
        parking_location: parking,
        parkingLocation: parking,
        startTime: startTime,
        start_time: startTime,
        endTime: endTime,
        end_time: endTime,
        vehicle: vehicle,
        license_plate: vehicle,
        amount: amount,
        total_amount: amount,
        date: data.date || '',
        status: data.status || 'upcoming'
    };
}

function filterReservations(filterType) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    const activeBtn = [...document.querySelectorAll('.tab-btn')]
        .find(b => b.textContent.toLowerCase().includes(filterType));
    if (activeBtn) activeBtn.classList.add('active');
    displayReservations(filterType);
}

// Generate barcode using SVG
function generateBarcode(reservationId) {
    const barcodeElement = document.getElementById(`barcode-${reservationId}`);
    if (!barcodeElement) return;
    
    const code = `PKE${reservationId}`;
    const width = 2;
    const height = 60;
    const svgWidth = code.length * 12 * width;
    
    barcodeElement.setAttribute('width', svgWidth);
    barcodeElement.setAttribute('height', height + 30);
    barcodeElement.setAttribute('viewBox', `0 0 ${svgWidth} ${height + 30}`);
    
    let x = 10;
    let bars = '';
    
    // Simple barcode pattern
    for (let i = 0; i < code.length; i++) {
        const charCode = code.charCodeAt(i);
        const pattern = (charCode % 2 === 0) ? '101' : '110';
        
        for (let bit of pattern) {
            if (bit === '1') {
                bars += `<rect x="${x}" y="0" width="${width}" height="${height}" fill="#000"/>`;
            }
            x += width;
        }
        x += width;
    }
    
    barcodeElement.innerHTML = `
        ${bars}
        <text x="${svgWidth/2}" y="${height + 20}" text-anchor="middle" font-size="14" font-family="monospace">${code}</text>
    `;
}

// Generate QR Code
function generateQRCode(id, text) {
    const qrContainer = document.getElementById(`qr-${id}`);
    if (!qrContainer) return;

    // Use Google's free QR API
    const encoded = encodeURIComponent(text);
    qrContainer.innerHTML = `
        <img src="https://api.qrserver.com/v1/create-qr-code/?data=${encoded}&size=80x80"
             alt="QR Code" style="border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.2); background:white;">
    `;
}

// Print reservation
function printReservation(reservationId) {
    const reservation = normalizeReservation(userReservations.find(r => r.id === reservationId));
    if (!reservation) return;
    
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Parking Reservation - ${reservation.id}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 40px;
                    max-width: 800px;
                    margin: 0 auto;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 3px solid #6366f1;
                    padding-bottom: 20px;
                }
                .logo {
                    font-size: 2rem;
                    font-weight: bold;
                    color: #6366f1;
                    margin-bottom: 10px;
                }
                .title {
                    font-size: 1.5rem;
                    color: #1e293b;
                    margin: 10px 0;
                }
                .info-section {
                    margin: 30px 0;
                }
                .info-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                    margin: 20px 0;
                }
                .info-item {
                    padding: 15px;
                    background: #f8fafc;
                    border-radius: 8px;
                }
                .info-label {
                    color: #64748b;
                    font-size: 0.9rem;
                    margin-bottom: 5px;
                }
                .info-value {
                    font-weight: bold;
                    font-size: 1.1rem;
                    color: #1e293b;
                }
                .status {
                    display: inline-block;
                    padding: 8px 16px;
                    background: #10b981;
                    color: white;
                    border-radius: 20px;
                    font-weight: bold;
                    margin-top: 10px;
                }
                .barcode-section {
                    text-align: center;
                    margin: 40px 0;
                    padding: 30px;
                    background: #f8fafc;
                    border-radius: 10px;
                }
                .footer {
                    margin-top: 40px;
                    padding-top: 20px;
                    border-top: 2px solid #e2e8f0;
                    text-align: center;
                    color: #64748b;
                    font-size: 0.9rem;
                }
                @media print {
                    body { padding: 20px; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="logo">ParkOwenz</div>
                <div class="title">Parking Reservation Confirmation</div>
                <div class="status">${reservation.status.toUpperCase()}</div>
            </div>
            
            <div class="info-section">
                <h3>Parking Location</h3>
                <div style="margin: 10px 0;">
                    <div style="font-size: 1.2rem; font-weight: bold; margin-bottom: 5px;">
                        ${reservation.parking?.name || reservation.parking_location?.name || reservation.parkingLocation?.name || 'Unknown Parking'}
                    </div>
                    <div style="color: #64748b;">
                        ${reservation.parking?.address || reservation.parking_location?.address || reservation.parkingLocation?.address || ''}
                    </div>
                </div>
            </div>
            
            <div class="info-section">
                <h3>Reservation Details</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Booking Date</div>
                        <div class="info-value">${reservation.bookingDate}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Reservation ID</div>
                        <div class="info-value">PKE${reservation.id}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Parking Date</div>
                        <div class="info-value">${reservation.date}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Time</div>
                        <div class="info-value">${reservation.startTime || reservation.start_time || 'N/A'} - ${reservation.endTime || reservation.end_time || 'N/A'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Vehicle Number</div>
                        <div class="info-value">${reservation.vehicle || reservation.license_plate || 'N/A'}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Amount Paid</div>
                        <div class="info-value" style="color: #6366f1;">UGX ${(parseFloat(reservation.amount || reservation.total_amount || 0)).toFixed(2)}</div>
                    </div>
                </div>
            </div>
            
            <div class="barcode-section">
                <p style="color: #64748b; margin-bottom: 15px;">Scan this code at the entrance</p>
                <svg id="printBarcode" width="300" height="90"></svg>
            </div>
            
            <div class="footer">
                <p>Thank you for using ParkOwenz Parking System</p>
                <p>For assistance, contact support@parkowenz.com</p>
                <p style="margin-top: 10px; font-size: 0.8rem;">
                    Printed on ${new Date().toLocaleString()}
                </p>
            </div>
            
            <script>
                // Generate barcode in print window
                const code = 'PKE${reservation.id}';
                const svg = document.getElementById('printBarcode');
                const width = 3;
                const height = 60;
                const svgWidth = code.length * 12 * width;
                
                svg.setAttribute('width', svgWidth);
                svg.setAttribute('height', height + 30);
                svg.setAttribute('viewBox', '0 0 ' + svgWidth + ' ' + (height + 30));
                
                let x = 10;
                let bars = '';
                
                for (let i = 0; i < code.length; i++) {
                    const charCode = code.charCodeAt(i);
                    const pattern = (charCode % 2 === 0) ? '101' : '110';
                    
                    for (let bit of pattern) {
                        if (bit === '1') {
                            bars += '<rect x="' + x + '" y="0" width="' + width + '" height="' + height + '" fill="#000"/>';
                        }
                        x += width;
                    }
                    x += width;
                }
                
                svg.innerHTML = bars + '<text x="' + (svgWidth/2) + '" y="' + (height + 20) + '" text-anchor="middle" font-size="16" font-family="monospace">' + code + '</text>';
                
                // Auto print
                window.onload = function() {
                    window.print();
                };
            </script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function displayPayments() {
    const container = document.getElementById('paymentArea');
    if (!container) return;
    
    // Check if there's a pending booking, otherwise show payment history
    if (!pendingBooking) {
        displayPaymentHistory();
        return;
    }
    
    // Use pending booking data
    const booking = pendingBooking;
    const hours = booking.hours || 2;
    const subtotal = booking.amount || (hours * booking.parking.price);
    const fees = booking.fees || (subtotal * 0.2);
    const total = booking.total || (subtotal + fees);
    
    container.innerHTML = `
        <div class="payment-container">
            <div class="payment-summary-card">
                <h2>Booking Summary</h2>
                <div class="summary-item">
                    <span class="label">Location</span>
                    <span class="value">${booking.parking.name}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Address</span>
                    <span class="value">${booking.parking.address}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Date</span>
                    <span class="value">${booking.date}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Time</span>
                    <span class="value">${booking.start_time} - ${booking.end_time}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Duration</span>
                    <span class="value">${hours.toFixed(1)} hours</span>
                </div>
                <div class="divider"></div>
                <div class="summary-item">
                    <span class="label">Subtotal (${hours.toFixed(1)} hrs @ ${booking.parking.price}/hr)</span>
                    <span class="value">UGX ${subtotal.toFixed(2)}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Taxes & Fees</span>
                    <span class="value">UGX ${fees.toFixed(2)}</span>
                </div>
                <div class="divider"></div>
                <div class="summary-total">
                    <span class="label">Total Amount</span>
                    <span class="value">UGX ${total.toFixed(2)}</span>
                </div>
            </div>
            
            <div class="payment-form-card">
                <h2>Payment Details</h2>
                
                <div class="payment-methods">
                    <button class="payment-method active" data-method="Mobile Payment">
                        <span>📱</span>
                        <span>Mobile Payment</span>
                    </button>
                    <button class="payment-method" data-method="Card">
                        <span>💳</span>
                        <span>Card</span>
                    </button>
                    <button class="payment-method" data-method="PayPal">
                        <span>🅿️</span>
                        <span>PayPal</span>
                    </button>
                    <button class="payment-method" data-method="Google Pay">
                        <span>🔵</span>
                        <span>Google Pay</span>
                    </button>
                </div>
                
                <form class="payment-form" onsubmit="processPayment(event)" id="paymentForm">
                    <div id="mobilePaymentFields">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <div class="input-with-icon">
                                <span class="icon">📱</span>
                                <input type="tel" placeholder="+256 700 000 000" required id="phoneNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mobile Money Provider</label>
                            <div class="input-with-icon">
                                <span class="icon">💰</span>
                                <select required id="mobileProvider" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; background: white;">
                                    <option value="">Select Provider</option>
                                    <option value="MTN Mobile Money">MTN Mobile Money</option>
                                    <option value="Airtel Money">Airtel Money</option>
                                    <option value="Africell Money">Africell Money</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="cardPaymentFields" style="display: none;">
                        <div class="form-group">
                            <label>Name on Card</label>
                            <div class="input-with-icon">
                                <span class="icon">👤</span>
                                <input type="text" placeholder="John M. Doe" id="cardName">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Card Number</label>
                            <div class="input-with-icon">
                                <span class="icon">💳</span>
                                <input type="text" placeholder="0000 0000 0000 0000" id="cardNumber">
                                <span class="icon-right">✓</span>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiration Date</label>
                                <div class="input-with-icon">
                                    <span class="icon">📅</span>
                                    <input type="text" placeholder="MM/YY" id="cardExpiry">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>CVV</label>
                                <div class="input-with-icon">
                                    <span class="icon">🔒</span>
                                    <input type="text" placeholder="123" id="cardCVV">
                                </div>
                            </div>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="saveCard">
                            <label for="saveCard">Save this card for future payments</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="pay-btn">
                        <span>🔒</span>
                        <span>Pay UGX ${total.toFixed(2)} & Confirm Booking</span>
                    </button>
                    
                    <div class="secure-note">
                        <span>🛡️</span>
                        <span>Your payment information is encrypted and secure</span>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    // Setup payment method switching after form is rendered
    setTimeout(() => {
        const paymentMethodButtons = document.querySelectorAll('.payment-method');
        if (paymentMethodButtons.length > 0) {
            paymentMethodButtons.forEach(btn => {
                // Remove any existing listeners by cloning
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);
                
                newBtn.addEventListener('click', function() {
                    document.querySelectorAll('.payment-method').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const method = this.getAttribute('data-method');
                    const mobileFields = document.getElementById('mobilePaymentFields');
                    const cardFields = document.getElementById('cardPaymentFields');
                    
                    if (method === 'Mobile Payment') {
                        if (mobileFields) mobileFields.style.display = 'block';
                        if (cardFields) cardFields.style.display = 'none';
                        // Make mobile fields required, card fields not required
                        const phoneInput = document.getElementById('phoneNumber');
                        const providerSelect = document.getElementById('mobileProvider');
                        if (phoneInput) phoneInput.required = true;
                        if (providerSelect) providerSelect.required = true;
                        const cardName = document.getElementById('cardName');
                        const cardNumber = document.getElementById('cardNumber');
                        const cardExpiry = document.getElementById('cardExpiry');
                        const cardCVV = document.getElementById('cardCVV');
                        if (cardName) cardName.required = false;
                        if (cardNumber) cardNumber.required = false;
                        if (cardExpiry) cardExpiry.required = false;
                        if (cardCVV) cardCVV.required = false;
                    } else {
                        if (mobileFields) mobileFields.style.display = 'none';
                        if (cardFields) cardFields.style.display = 'block';
                        // Make card fields required, mobile fields not required
                        const phoneInput = document.getElementById('phoneNumber');
                        const providerSelect = document.getElementById('mobileProvider');
                        if (phoneInput) phoneInput.required = false;
                        if (providerSelect) providerSelect.required = false;
                        const cardName = document.getElementById('cardName');
                        const cardNumber = document.getElementById('cardNumber');
                        const cardExpiry = document.getElementById('cardExpiry');
                        const cardCVV = document.getElementById('cardCVV');
                        if (cardName) cardName.required = true;
                        if (cardNumber) cardNumber.required = true;
                        if (cardExpiry) cardExpiry.required = true;
                        if (cardCVV) cardCVV.required = true;
                    }
                });
            });
        }
    }, 100);
}

async function processPayment(event) {
    event.preventDefault();
    
    if (!pendingBooking) {
        showDialog('No booking pending. Please select a parking spot first.', 'error');
        return;
    }
    
    // Get selected payment method
    const activeMethod = document.querySelector('.payment-method.active');
    const paymentMethod = activeMethod ? activeMethod.getAttribute('data-method') : 'Mobile Payment';
    
    // Get payment form data based on method
    const form = event.target;
    let paymentDetails = {};
    
    if (paymentMethod === 'Mobile Payment') {
        const phoneNumber = document.getElementById('phoneNumber').value;
        const mobileProvider = document.getElementById('mobileProvider').value;
        paymentDetails = {
            method: paymentMethod,
            phone: phoneNumber,
            provider: mobileProvider
        };
    } else {
        const cardName = document.getElementById('cardName').value;
        const cardNumber = document.getElementById('cardNumber').value;
        const expiry = document.getElementById('cardExpiry').value;
        const cvv = document.getElementById('cardCVV').value;
        paymentDetails = {
            method: paymentMethod,
            cardName: cardName,
            cardNumber: cardNumber,
            expiry: expiry,
            cvv: cvv
        };
    }
    
    try {
        // Process payment (simulate payment processing)
        // In a real app, you would call a payment API here
        await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API call
        
        // Create reservation after payment is successful
        const reservationData = {
            parking_location_id: pendingBooking.parking_location_id,
            date: pendingBooking.date,
            start_time: pendingBooking.start_time,
            end_time: pendingBooking.end_time,
            license_plate: pendingBooking.license_plate,
            total_amount: pendingBooking.total_amount,
            amount: pendingBooking.total_amount,
            payment_method: paymentMethod,
        };
        
        // Add payment-specific details
        if (paymentMethod === 'Mobile Payment') {
            reservationData.payment_phone = paymentDetails.phone;
            reservationData.payment_provider = paymentDetails.provider;
        } else if (paymentDetails.cardNumber) {
            reservationData.payment_card_last4 = paymentDetails.cardNumber.slice(-4);
        }
        
        const reservation = await createReservation(reservationData);
        
        // Normalize reservation data from backend
        const normalizedReservation = normalizeReservation(reservation);
        
        // Add reservation to list
        userReservations.push(normalizedReservation);
        
        // Payment is now saved in database via ReservationController
        // Reload payment history from database
        await loadPaymentHistory();
        
        // Clear pending booking
        const bookingDetails = { ...pendingBooking };
        pendingBooking = null;
        selectedParking = null;
        
        // Show success dialog
        showDialog(
            `Booking confirmed!\n\nParking: ${bookingDetails.parking.name}\nDate: ${bookingDetails.date}\nTime: ${bookingDetails.start_time} - ${bookingDetails.end_time}\nAmount: UGX ${bookingDetails.total.toFixed(2)}`,
            'success',
            'Booking Confirmed'
        );
        
        // Show payment history
        setTimeout(() => {
            displayPaymentHistory();
        }, 500);
        
    } catch (error) {
        showDialog('Payment failed: ' + error.message, 'error');
    }
}

async function loadPaymentHistory() {
    try {
        const payments = await fetchPayments();
        userPayments = payments.map(p => ({
            id: p.id,
            reservation_id: p.reservation_id,
            amount: p.amount,
            method: p.method,
            status: p.status,
            date: p.date,
            time: p.time,
            parking: p.parking,
            phone: p.phone,
            provider: p.provider,
            cardLast4: p.card_last4
        }));
        // Keep localStorage as backup
        localStorage.setItem('payments', JSON.stringify(userPayments));
    } catch (error) {
        console.error('Failed to load payment history:', error);
        // Fallback to localStorage if API fails
        const stored = localStorage.getItem('payments');
        if (stored) {
            userPayments = JSON.parse(stored);
        }
    }
}

// Parking Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('parkingSearch');
    const clearBtn = document.getElementById('clearSearch');
    const searchBtn = document.getElementById('performSearch');
    const suggestions = document.getElementById('searchSuggestions');
    const suggestionTags = document.querySelectorAll('.suggestion-tag');
    
    // Show suggestions when input is focused
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            if (suggestions) {
                suggestions.style.display = 'block';
            }
        });
        
        // Hide suggestions when input is blurred (with delay to allow clicking)
        searchInput.addEventListener('blur', function() {
            setTimeout(() => {
                if (suggestions) {
                    suggestions.style.display = 'none';
                }
            }, 200);
        });
        
        // Show/hide clear button based on input
        searchInput.addEventListener('input', function() {
            if (clearBtn) {
                clearBtn.style.display = this.value ? 'block' : 'none';
            }
        });
        
        // Handle Enter key press
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performParkingSearch();
            }
        });
    }
    
    // Clear search functionality
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                this.style.display = 'none';
                searchInput.focus();
            }
        });
    }
    
    // Search button functionality
    if (searchBtn) {
        searchBtn.addEventListener('click', performParkingSearch);
    }
    
    // Suggestion tag clicks
    suggestionTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const searchText = this.getAttribute('data-search');
            if (searchInput && searchText) {
                searchInput.value = searchText;
                if (clearBtn) {
                    clearBtn.style.display = 'block';
                }
                performParkingSearch();
            }
        });
    });
    
    // Filter change handlers
    const filters = ['distanceFilter', 'priceFilter', 'featureFilter'];
    filters.forEach(filterId => {
        const filter = document.getElementById(filterId);
        if (filter) {
            filter.addEventListener('change', performParkingSearch);
        }
    });
});

// Main search function
function performParkingSearch() {
    const searchInput = document.getElementById('parkingSearch');
    const distanceFilter = document.getElementById('distanceFilter');
    const priceFilter = document.getElementById('priceFilter');
    const featureFilter = document.getElementById('featureFilter');
    
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const distance = distanceFilter ? distanceFilter.value : 'all';
    const priceRange = priceFilter ? priceFilter.value : 'all';
    const feature = featureFilter ? featureFilter.value : 'all';
    
    // Filter parking locations based on search criteria
    let filteredLocations = parkingLocations.filter(location => {
        // Search term filter (name, address, features)
        if (searchTerm) {
            const matchesName = location.name.toLowerCase().includes(searchTerm);
            const matchesAddress = location.address.toLowerCase().includes(searchTerm);
            const matchesFeatures = location.features && location.features.some(f => 
                f.toLowerCase().includes(searchTerm)
            );
            
            if (!matchesName && !matchesAddress && !matchesFeatures) {
                return false;
            }
        }
        
        // Distance filter
        if (distance !== 'all') {
            const maxDistance = parseFloat(distance);
            if (location.distance > maxDistance) {
                return false;
            }
        }
        
        // Price filter
        if (priceRange !== 'all') {
            const price = parseFloat(location.price);
            if (priceRange === '0-2000' && price > 2000) return false;
            if (priceRange === '2000-5000' && (price < 2000 || price > 5000)) return false;
            if (priceRange === '5000-10000' && (price < 5000 || price > 10000)) return false;
            if (priceRange === '10000+' && price <= 10000) return false;
        }
        
        // Feature filter
        if (feature !== 'all') {
            if (!location.features || !Array.isArray(location.features)) {
                return false;
            }
            
            const featureMap = {
                'covered': ['covered', 'Covered', 'COVERED'],
                'security': ['security', 'Security', '24/7', 'CCTV'],
                'ev': ['ev', 'EV', 'charging', 'Charging'],
                'valet': ['valet', 'Valet', 'VALET']
            };
            
            const searchFeatures = featureMap[feature] || [feature];
            const hasFeature = location.features.some(f => 
                searchFeatures.some(sf => f.toLowerCase().includes(sf.toLowerCase()))
            );
            
            if (!hasFeature) {
                return false;
            }
        }
        
        return true;
    });
    
    // Display filtered results
    displayFilteredParkingLocations(filteredLocations, searchTerm);
    
    // Show search feedback
    showSearchFeedback(filteredLocations.length, searchTerm);
}

// Display filtered parking locations
function displayFilteredParkingLocations(locations, searchTerm) {
    const grid = document.getElementById('parkingGrid');
    if (!grid) return;
    
    if (locations.length === 0) {
        grid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--gray);">
                <p style="font-size: 3rem; margin-bottom: 1rem;">🔍</p>
                <h3>No parking spots found</h3>
                <p>Try adjusting your search criteria or filters</p>
                <button onclick="clearAllFilters()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: var(--primary); color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Clear Filters
                </button>
            </div>
        `;
        return;
    }
    
    // Use existing displayParkingLocations function but with filtered data
    const originalLocations = [...parkingLocations];
    parkingLocations.length = 0;
    parkingLocations.push(...locations);
    
    // Call the existing display function
    if (typeof displayParkingLocations === 'function') {
        displayParkingLocations();
    }
    
    // Restore original data
    parkingLocations.length = 0;
    parkingLocations.push(...originalLocations);
}

// Show search feedback
function showSearchFeedback(count, searchTerm) {
    const grid = document.getElementById('parkingGrid');
    if (!grid) return;
    
    // Create feedback element if it doesn't exist
    let feedback = document.getElementById('searchFeedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.id = 'searchFeedback';
        feedback.style.cssText = `
            grid-column: 1 / -1;
            padding: 1rem;
            background: var(--light);
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
            color: var(--gray);
            font-size: 0.9rem;
        `;
        grid.parentNode.insertBefore(feedback, grid);
    }
    
    if (searchTerm) {
        feedback.textContent = `Found ${count} parking spot${count !== 1 ? 's' : ''} for "${searchTerm}"`;
    } else {
        feedback.textContent = `Showing ${count} parking spot${count !== 1 ? 's' : ''}`;
    }
    
    // Auto-hide feedback after 3 seconds
    setTimeout(() => {
        if (feedback && feedback.parentNode) {
            feedback.parentNode.removeChild(feedback);
        }
    }, 3000);
}

// Clear all filters
function clearAllFilters() {
    const searchInput = document.getElementById('parkingSearch');
    const clearBtn = document.getElementById('clearSearch');
    const distanceFilter = document.getElementById('distanceFilter');
    const priceFilter = document.getElementById('priceFilter');
    const featureFilter = document.getElementById('featureFilter');
    
    if (searchInput) {
        searchInput.value = '';
    }
    if (clearBtn) {
        clearBtn.style.display = 'none';
    }
    if (distanceFilter) {
        distanceFilter.value = 'all';
    }
    if (priceFilter) {
        priceFilter.value = 'all';
    }
    if (featureFilter) {
        featureFilter.value = 'all';
    }
    
    // Reset to show all parking locations
    if (typeof displayParkingLocations === 'function') {
        displayParkingLocations();
    }
}

// Add this to the end of your d.js file
async function displayPaymentHistory() {
    const container = document.getElementById('paymentArea');
    if (!container) return;
    
    // Load from database first
    await loadPaymentHistory();
    
    if (!userPayments || userPayments.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 4rem; color: var(--gray);">
                <p style="font-size: 3rem; margin-bottom: 1rem;">💳</p>
                <h3>No Payment History</h3>
                <p>Your payment history will appear here after making a payment</p>
            </div>
        `;
        return;
    }
    
    // Sort payments by date (newest first)
    const sortedPayments = [...userPayments].sort((a, b) => {
        const dateA = new Date(a.date + ' ' + (a.time || '00:00'));
        const dateB = new Date(b.date + ' ' + (b.time || '00:00'));
        return dateB - dateA;
    });
    
    container.innerHTML = `
        <div class="payment-history-container">
            <h2 style="margin-bottom: 2rem; color: var(--text-primary);">Payment History</h2>
            <div class="payment-history-list">
                ${sortedPayments.map(payment => `
                    <div class="payment-history-item">
                        <div class="payment-history-icon">
                            ${payment.method === 'Mobile Payment' ? '📱' : payment.method === 'Card' ? '💳' : payment.method === 'PayPal' ? '🅿️' : payment.method === 'Google Pay' ? '🔵' : '💰'}
                        </div>
                        <div class="payment-history-details">
                            <div class="payment-history-header">
                                <h3>${payment.parking || 'Parking Payment'}</h3>
                                <span class="payment-status ${payment.status}">${payment.status}</span>
                            </div>
                            <p class="payment-history-meta">
                                ${payment.date} at ${payment.time || 'N/A'}
                                ${payment.method === 'Mobile Payment' && payment.phone ? ` • ${payment.phone} (${payment.provider || ''})` : ''}
                                ${payment.cardLast4 ? ` • Card ending in ${payment.cardLast4}` : ''}
                            </p>
                        </div>
                        <div class="payment-history-amount">
                            <span>UGX ${parseFloat(payment.amount).toFixed(2)}</span>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

// Auto-update statuses every minute
setInterval(() => updateBookingStatuses(), 60 * 1000);