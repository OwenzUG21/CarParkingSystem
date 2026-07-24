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
        
        // Redirect based on role
        if (currentUser.is_admin) {
            window.location.href = '/admin';
            return;
        }

        if (currentUser.role === 'lot_manager') {
            window.location.href = '/manager';
            return;
        }

        if (currentUser.role === 'keeper') {
            window.location.href = '/keeper';
            return;
        }
        
        updateUserProfile();
        startUserChatPolling();
        
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
        userReservations.push(...reservations.map(r => normalizeReservation(r)));
        // Update statuses immediately after fetching
        updateBookingStatuses();
        
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
        startUserChatPolling();
        
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

// Update your confirmBooking function - book first, pay later
async function confirmBooking(event) {
    event.preventDefault();

    if (!selectedParking || selectedParking.available <= 0) {
        showDialog('No parking slots available for this location right now.', 'info', 'Fully Booked');
        return;
    }
    
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
    
    try {
        const reservationData = {
            parking_location_id: selectedParking.id,
            date: date,
            start_time: startTime,
            end_time: endTime,
            license_plate: vehicle,
            total_amount: amount,
            amount: amount
        };

        const reservation = await createReservation(reservationData);
        const normalizedReservation = normalizeReservation(reservation);
        // Ensure status is 'upcoming' for new reservations (don't let frontend override backend status)
        if (normalizedReservation.status !== 'upcoming' && normalizedReservation.status !== 'active') {
            normalizedReservation.status = 'upcoming';
        }
        userReservations.push(normalizedReservation);
        
        // Immediately update status based on current time (in case booking is for current time)
        updateBookingStatuses();

        const bookedLocation = parkingLocations.find(loc => loc.id === selectedParking.id);
        if (bookedLocation && bookedLocation.available > 0) {
            bookedLocation.available -= 1;
        }
        if (selectedParking && selectedParking.available > 0) {
            selectedParking.available -= 1;
        }
        displayParkingLocations();

        closeBookingModal();
        showDialog(
            `Reservation created!\n\nYou have 20 minutes to complete payment.\n\nParking: ${selectedParking.name}\nDate: ${date}\nTime: ${startTime} - ${endTime}\nAmount: UGX ${total.toFixed(2)}`,
            'success',
            'Reservation Confirmed'
        );
        showReservations();
    } catch (error) {
        showDialog('Booking failed: ' + error.message, 'error');
    }
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
                const locationId = reservation.parking_location_id || reservation.parking?.id || reservation.parking_location?.id;
                if (locationId) {
                    const lot = parkingLocations.find(loc => loc.id === locationId);
                    if (lot) {
                        lot.available = Math.min(lot.available + 1, lot.total);
                        displayParkingLocations();
                    }
                }
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
let chatPollInterval = null;
let chatSeenMessageIds = new Set();
let tawkMessagesEl = null;
let tawkInputEl = null;
let tawkSendEl = null;
const localChatStorageKey = 'chatHistory';
const PAYMENT_WINDOW_MINUTES = 20;

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
    stopUserChatPolling();
    chatSeenMessageIds.clear();
    updateUserProfileSection();
}

function formatChatTimestamp(value) {
    if (!value) {
        const now = new Date();
        return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }
    const parsed = new Date(value);
    if (Number.isNaN(parsed.getTime())) {
        return value;
    }
    return parsed.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function appendChatMessage(text, isUser, timestamp) {
    if (!tawkMessagesEl) return;
    const wrapper = document.createElement('div');
    wrapper.className = `tawk-message ${isUser ? 'user' : 'bot'}`;

    if (!isUser) {
        const avatar = document.createElement('span');
        avatar.className = 'tawk-avatar';
        avatar.textContent = 'P';
        wrapper.appendChild(avatar);
    }

    const bubble = document.createElement('div');
    bubble.className = 'tawk-bubble';
    bubble.textContent = text;

    const meta = document.createElement('span');
    meta.className = 'tawk-meta';
    meta.textContent = formatChatTimestamp(timestamp);
    bubble.appendChild(meta);

    if (isUser) {
        const status = document.createElement('span');
        status.className = 'tawk-status';
        status.textContent = 'OK';
        bubble.appendChild(status);
    }

    wrapper.appendChild(bubble);
    tawkMessagesEl.appendChild(wrapper);
    tawkMessagesEl.scrollTop = tawkMessagesEl.scrollHeight;
}

async function loadUserChatHistory() {
    if (!currentUser || !tawkMessagesEl) return;
    try {
        const messages = await fetchUserChatMessages();
        const sorted = messages.slice().sort((a, b) => {
            const aTime = a.created_at ? new Date(a.created_at).getTime() : 0;
            const bTime = b.created_at ? new Date(b.created_at).getTime() : 0;
            return aTime - bTime;
        });
        chatSeenMessageIds.clear();
        tawkMessagesEl.innerHTML = '';
        sorted.forEach((message) => {
            if (!message.id) return;
            chatSeenMessageIds.add(message.id);
            const isUser = message.sender_role !== 'admin';
            appendChatMessage(message.message || '', isUser, message.created_at);
        });
    } catch (error) {
        console.error('Failed to load chat history:', error);
    }
}

async function pollUserChatMessages() {
    if (!currentUser) return;
    try {
        const messages = await fetchUserChatMessages();
        messages.forEach((message) => {
            if (!message.id || chatSeenMessageIds.has(message.id)) return;
            chatSeenMessageIds.add(message.id);
            const isUser = message.sender_role !== 'admin';
            appendChatMessage(message.message || '', isUser, message.created_at);
        });
    } catch (error) {
        console.error('Failed to load chat messages:', error);
    }
}

function startUserChatPolling() {
    stopUserChatPolling();
    loadUserChatHistory();
    chatPollInterval = setInterval(pollUserChatMessages, 10000);
}

function stopUserChatPolling() {
    if (chatPollInterval) {
        clearInterval(chatPollInterval);
        chatPollInterval = null;
    }
}

function loadLocalChatHistory() {
    if (currentUser || !tawkMessagesEl) return;
    try {
        const stored = JSON.parse(localStorage.getItem(localChatStorageKey) || '[]');
        tawkMessagesEl.innerHTML = '';
        stored.forEach((message) => {
            const isUser = message.sender_role !== 'admin';
            appendChatMessage(message.message || '', isUser, message.created_at);
        });
    } catch (error) {
        console.error('Failed to load local chat history:', error);
    }
}

function saveLocalChatMessage(message) {
    if (currentUser) return;
    try {
        const stored = JSON.parse(localStorage.getItem(localChatStorageKey) || '[]');
        stored.push(message);
        localStorage.setItem(localChatStorageKey, JSON.stringify(stored));
    } catch (error) {
        console.error('Failed to save local chat history:', error);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    detectUserLocation();
    initializeApp();
    loadDarkMode();
    renderNotifications();
    const redirectToFindParking = localStorage.getItem('redirectToFindParking');
    if (redirectToFindParking) {
        localStorage.removeItem('redirectToFindParking');
        showDashboard();
    }
    const notificationToggle = document.getElementById('notificationToggle');
    const profileToggle = document.getElementById('profileToggle');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const tawkButton = document.getElementById('tawkButton');
    const tawkPanel = document.getElementById('tawkPanel');
    const tawkClose = document.getElementById('tawkClose');
    tawkMessagesEl = document.getElementById('tawkMessages');
    tawkInputEl = document.getElementById('tawkInput');
    tawkSendEl = document.getElementById('tawkSend');
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
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleDropdown('mobileMenu', 'mobileMenuToggle');
        });
    }
    if (tawkButton && tawkPanel) {
        tawkButton.addEventListener('click', (event) => {
            event.stopPropagation();
            tawkPanel.classList.toggle('open');
            tawkPanel.setAttribute('aria-hidden', tawkPanel.classList.contains('open') ? 'false' : 'true');
        });
    }
    if (tawkClose && tawkPanel) {
        tawkClose.addEventListener('click', (event) => {
            event.stopPropagation();
            tawkPanel.classList.remove('open');
            tawkPanel.setAttribute('aria-hidden', 'true');
        });
    }
    if (tawkPanel) {
        tawkPanel.addEventListener('click', (event) => {
            event.stopPropagation();
        });
    }
    if (tawkSendEl && tawkInputEl && tawkMessagesEl) {
        const sendMessage = async () => {
            const message = tawkInputEl.value.trim();
            if (!message) return;
            const meta = { name: currentUser?.name || 'Guest' };
            if (currentUser?.email) {
                meta.email = currentUser.email;
            }
            try {
                const sentMessage = await sendChatMessage(message, meta);
                if (sentMessage?.id) {
                    chatSeenMessageIds.add(sentMessage.id);
                }
                appendChatMessage(sentMessage?.message || message, true, sentMessage?.created_at);
                if (!currentUser) {
                    saveLocalChatMessage({
                        message: sentMessage?.message || message,
                        sender_role: 'user',
                        created_at: sentMessage?.created_at || new Date().toISOString(),
                    });
                }
                tawkInputEl.value = '';
            } catch (error) {
                console.error('Chat send failed:', error);
                const details = error?.message ? ` ${error.message}` : '';
                showDialog(`Message failed to send.${details}`, 'error', 'Chat Error');
            }
        };

        tawkSendEl.addEventListener('click', (event) => {
            event.stopPropagation();
            sendMessage();
        });

        tawkInputEl.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });
    }
    document.addEventListener('click', () => {
        closeAllDropdowns();
        if (tawkPanel) {
            tawkPanel.classList.remove('open');
            tawkPanel.setAttribute('aria-hidden', 'true');
        }
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
            startUserChatPolling();
        } catch (error) {
            console.error('Token invalid, clearing storage:', error);
            clearAuthState();
            loadParkingLocations();
        }
    } else {
        clearAuthState();
        // Load parking locations even if not logged in
        loadParkingLocations();
        loadLocalChatHistory();
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
            // Immediately update statuses based on current time
            updateBookingStatuses();
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

async function showPayments() {
    // Check if user is logged in
    if (!currentUser) {
        showDialog('Please login to view your payments', 'info', 'Login Required');
        showLogin();
        return;
    }
    
    hideAllDashboardContent();
    document.getElementById('paymentsContent').classList.add('active');
    updateNavLinks('Payments');
    
    // If there's a pending booking, show payment form, otherwise hide it
    if (pendingBooking) {
        displayPayments();
    } else {
        hidePaymentCheckout();
    }

    await updatePaymentsDashboard();
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
    if (document.getElementById('profileContent')?.classList.contains('active')) {
        loadProfile();
    }
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
        const settingName = document.getElementById('settingName');
        const settingEmail = document.getElementById('settingEmail');
        const settingPhone = document.getElementById('settingPhone');
        const settingAddress = document.getElementById('settingAddress');
        const defaultVehicle = document.getElementById('defaultVehicle');

        if (settingName) settingName.value = currentUser.name || '';
        if (settingEmail) settingEmail.value = currentUser.email || '';
        if (settingPhone) settingPhone.value = currentUser.phone || '';
        if (settingAddress) settingAddress.value = currentUser.address || '';

        if (currentUser.vehicle && defaultVehicle) {
            defaultVehicle.textContent = currentUser.vehicle;
        }
    }
    
    // Load notification preferences
    const emailNotif = localStorage.getItem('emailNotifications') !== 'false';
    const smsNotif = localStorage.getItem('smsNotifications') === 'true';
    const pushNotif = localStorage.getItem('pushNotifications') !== 'false';
    const emailEl = document.getElementById('emailNotifications');
    const smsEl = document.getElementById('smsNotifications');
    const pushEl = document.getElementById('pushNotifications');

    if (emailEl) emailEl.checked = emailNotif;
    if (smsEl) smsEl.checked = smsNotif;
    if (pushEl) pushEl.checked = pushNotif;
    
    // Load security settings
    const twoFactor = localStorage.getItem('twoFactor') === 'true';
    const shareLocation = localStorage.getItem('shareLocation') !== 'false';
    const twoFactorEl = document.getElementById('twoFactor');
    const shareLocationEl = document.getElementById('shareLocation');

    if (twoFactorEl) twoFactorEl.checked = twoFactor;
    if (shareLocationEl) shareLocationEl.checked = shareLocation;
    
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
    const emailEl = document.getElementById('emailNotifications');
    const smsEl = document.getElementById('smsNotifications');
    const pushEl = document.getElementById('pushNotifications');

    if (emailEl) localStorage.setItem('emailNotifications', emailEl.checked);
    if (smsEl) localStorage.setItem('smsNotifications', smsEl.checked);
    if (pushEl) localStorage.setItem('pushNotifications', pushEl.checked);
}

function saveSecuritySettings() {
    const twoFactorEl = document.getElementById('twoFactor');
    const shareLocationEl = document.getElementById('shareLocation');
    const twoFactor = twoFactorEl ? twoFactorEl.checked : false;
    const shareLocation = shareLocationEl ? shareLocationEl.checked : false;

    if (twoFactorEl) localStorage.setItem('twoFactor', twoFactor);
    if (shareLocationEl) localStorage.setItem('shareLocation', shareLocation);

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

function applyThemeMode(mode) {
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const useDark = mode === 'dark' || (mode === 'system' && prefersDark);
    document.body.classList.toggle('dark-mode', useDark);
    const toggle = document.getElementById('darkModeToggle');
    if (toggle) toggle.checked = useDark;
}

function setThemeMode(mode) {
    localStorage.setItem('themeMode', mode);
    applyThemeMode(mode);
}

// Load dark mode preference and theme color
function loadDarkMode() {
    const themeMode = localStorage.getItem('themeMode');
    if (themeMode) {
        applyThemeMode(themeMode);
    } else {
        const isDark = localStorage.getItem('darkMode') === 'true';
        if (isDark) {
            document.body.classList.add('dark-mode');
            const toggle = document.getElementById('darkModeToggle');
            if (toggle) toggle.checked = true;
        }
    }
    
    // Load theme color
    const themeColor = localStorage.getItem('themeColor');
    if (themeColor) {
        changeThemeColor(themeColor);
    }
}

if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const themeMode = localStorage.getItem('themeMode');
        if (themeMode === 'system') {
            applyThemeMode('system');
        }
    });
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
    const navLoginButton = document.getElementById('navLoginButton');
    const notificationWrapper = document.getElementById('notificationWrapper');
    const profileWrapper = document.getElementById('profileWrapper');
    
    if (currentUser) {
        // User is logged in - show their info
        userName.textContent = currentUser.name || 'User';
        userEmail.textContent = currentUser.email || '';
        userAvatar.textContent = (currentUser.name || 'U').charAt(0).toUpperCase();
        if (profileLoginItem) profileLoginItem.style.display = 'none';
        if (profileLogoutItem) profileLogoutItem.style.display = 'flex';
        if (navLoginButton) navLoginButton.style.display = 'none';
        if (notificationWrapper) notificationWrapper.style.display = '';
        if (profileWrapper) profileWrapper.style.display = '';
    } else {
        // User is not logged in - show login prompt
        userName.textContent = 'Guest User';
        userEmail.textContent = 'Please login to book parking';
        userAvatar.textContent = 'G';
        if (profileLoginItem) profileLoginItem.style.display = 'flex';
        if (profileLogoutItem) profileLogoutItem.style.display = 'none';
        if (navLoginButton) navLoginButton.style.display = 'inline-flex';
        if (notificationWrapper) notificationWrapper.style.display = 'none';
        if (profileWrapper) profileWrapper.style.display = 'none';
    }
}

function showProfile() {
    if (!currentUser) {
        showDialog('Please login to access your profile', 'info', 'Login Required');
        showLogin();
        return;
    }
    hideAllDashboardContent();
    const profileContent = document.getElementById('profileContent');
    if (profileContent) {
        profileContent.classList.add('active');
    }
    updateNavLinks('Profile');
    loadProfile();
}

function loadProfile() {
    if (!currentUser) return;
    const profileName = document.getElementById('profileName');
    const profileEmail = document.getElementById('profileEmail');
    const profileAvatar = document.getElementById('profileAvatar');
    const settingName = document.getElementById('settingName');
    const settingEmail = document.getElementById('settingEmail');
    const settingPhone = document.getElementById('settingPhone');
    const settingAddress = document.getElementById('settingAddress');

    if (profileName) profileName.textContent = currentUser.name || 'User';
    if (profileEmail) profileEmail.textContent = currentUser.email || '';
    if (profileAvatar) profileAvatar.textContent = (currentUser.name || 'U').charAt(0).toUpperCase();
    if (settingName) settingName.value = currentUser.name || '';
    if (settingEmail) settingEmail.value = currentUser.email || '';
    if (settingPhone) settingPhone.value = currentUser.phone || '';
    if (settingAddress) settingAddress.value = currentUser.address || '';
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
    
    const availabilityPercent = parking.total ? (parking.available / parking.total) * 100 : 0;
    const statusClass = parking.available <= 0 ? 'unavailable' : availabilityPercent >= 20 ? 'available' : 'limited';
    const statusText = parking.available <= 0 ? 'Unavailable' : availabilityPercent >= 20 ? 'Available' : 'Limited';
    
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
    
    const availabilityPercent = parking.total ? (parking.available / parking.total) * 100 : 0;
    const isUnavailable = parking.available <= 0;
    const availabilityLabel = isUnavailable ? 'Unavailable' : availabilityPercent >= 20 ? 'Available' : 'Limited';

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
                    `<span class="feature-tag">
                        ✓ ${feature}
                    </span>`
                ).join('')}
            </div>
        </div>
        
        <div class="modal-actions">
            <button class="modal-btn btn-secondary" onclick="openGoogleMaps(${parking.lat}, ${parking.lng})">
                Get Directions
            </button>
            <button class="modal-btn btn-primary" onclick="openBookingModal()" ${isUnavailable ? 'disabled' : ''}>
                ${isUnavailable ? 'No Slots Available' : 'Book Now'}
            </button>
            <span style="margin-left:auto; font-size:0.9rem; color:var(--gray);">${availabilityLabel}</span>
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
    if (selectedParking && selectedParking.available <= 0) {
        showDialog('No parking slots available for this location right now.', 'info', 'Fully Booked');
        return;
    }
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

function calculateHours(startTime, endTime) {
    if (!startTime || !endTime) return 0;
    const parseTime = (value) => {
        const trimmed = String(value).trim();
        const parts = trimmed.split(':');
        if (parts.length < 2) return null;
        const hours = Number(parts[0]);
        const minutes = Number(parts[1]);
        if (!Number.isFinite(hours) || !Number.isFinite(minutes)) return null;
        if (hours < 0 || hours > 23 || minutes < 0 || minutes > 59) return null;
        return hours * 60 + minutes;
    };
    const startMinutes = parseTime(startTime);
    const endMinutes = parseTime(endTime);
    if (startMinutes === null || endMinutes === null) return 0;
    const diffMinutes = endMinutes - startMinutes;
    return diffMinutes > 0 ? diffMinutes / 60 : 0;
}

// Parse reservation date to YYYY-MM-DD (handles API formats: string, object, Date)
function parseReservationDate(dateVal) {
    if (!dateVal) return null;
    
    // Handle string formats: "2025-02-19", "2025-02-19T00:00:00.000000Z", "2025-02-19 00:00:00"
    if (typeof dateVal === 'string') {
        // Extract date part before T or space
        const datePart = dateVal.split('T')[0].split(' ')[0];
        // Validate format YYYY-MM-DD
        if (/^\d{4}-\d{2}-\d{2}$/.test(datePart)) {
            return datePart;
        }
    }
    
    // Handle Date objects
    if (dateVal instanceof Date) {
        const y = dateVal.getFullYear();
        const m = String(dateVal.getMonth() + 1).padStart(2, '0');
        const d = String(dateVal.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }
    
    // Handle Laravel Carbon serialized objects: {date: "2025-02-19 00:00:00", timezone_type: 3, timezone: "UTC"}
    if (typeof dateVal === 'object' && dateVal !== null) {
        if (dateVal.date) {
            const datePart = String(dateVal.date).split('T')[0].split(' ')[0];
            if (/^\d{4}-\d{2}-\d{2}$/.test(datePart)) {
                return datePart;
            }
        }
        // Try to extract from any string property
        for (let key in dateVal) {
            if (typeof dateVal[key] === 'string' && /^\d{4}-\d{2}-\d{2}/.test(dateVal[key])) {
                return dateVal[key].split('T')[0].split(' ')[0];
            }
        }
    }
    
    return null;
}

// Parse time string to HH:MM (handles "11:27", "11:27:00", "11:27:00.000000")
function parseReservationTime(timeStr) {
    if (!timeStr) return null;
    const parts = String(timeStr).split(':');
    if (parts.length >= 2) {
        return `${parts[0].padStart(2, '0')}:${parts[1].padStart(2, '0')}`;
    }
    return null;
}

// Create Date in LOCAL time - avoids timezone confusion
function reservationToLocalDate(dateStr, timeStr) {
    const [y, m, d] = dateStr.split('-').map(Number);
    const timeParts = String(timeStr || '').split(':').map(function(x) { return parseInt(x, 10) || 0; });
    const h = timeParts[0] || 0;
    const min = timeParts[1] || 0;
    const sec = timeParts[2] || 0;
    return new Date(y, m - 1, d, h, min, sec);
}

// Auto-update booking statuses based on current time
function updateBookingStatuses() {
    const now = new Date();
    let updated = false;
    
    if (!Array.isArray(userReservations) || userReservations.length === 0) return;
    
    userReservations.forEach(r => {
        try {
        const reservation = normalizeReservation(r);
        
        // Skip if already cancelled or completed (from backend - don't override)
        if (r.status === 'cancelled') return;
        if (r.status === 'completed') return;

        // Get raw date value - could be string, Date object, or object with date property
        const rawDate = reservation.date || r.date;
        const dateStr = parseReservationDate(rawDate);
        const startTimeStr = parseReservationTime(reservation.startTime || reservation.start_time || r.startTime || r.start_time);
        const endTimeStr = parseReservationTime(reservation.endTime || reservation.end_time || r.endTime || r.end_time);
        
        if (!dateStr || !startTimeStr || !endTimeStr) {
            return;
        }
        
        // Validate date format
        if (!/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
            return;
        }

        // Use explicit local time construction (avoids timezone issues)
        // startTimeStr/endTimeStr are "HH:MM" - append :00 if needed for seconds
        let startDateTime, endDateTime;
        try {
            const startFull = startTimeStr.split(':').length >= 3 ? startTimeStr : startTimeStr + ':00';
            const endFull = endTimeStr.split(':').length >= 3 ? endTimeStr : endTimeStr + ':00';
            startDateTime = reservationToLocalDate(dateStr, startFull);
            endDateTime = reservationToLocalDate(dateStr, endFull);
            if (isNaN(startDateTime.getTime()) || isNaN(endDateTime.getTime())) {
                return;
            }
        } catch (e) {
            return;
        }
        
        const nowTime = now.getTime();
        const startTimeMs = startDateTime.getTime();
        const endTimeMs = endDateTime.getTime();
        
        // Determine status based on current time
        let newStatus;
        if (nowTime < startTimeMs) {
            newStatus = 'upcoming';
        } else if (nowTime >= startTimeMs && nowTime < endTimeMs) {
            newStatus = 'active';
        } else {
            newStatus = 'completed';
        }
        
        if (r.status !== newStatus) {
            r.status = newStatus;
            updated = true;
        }
        } catch (err) {
            console.error('Error updating reservation status:', r?.id, err);
        }
    });
    
    if (updated) {
        try {
            saveToLocalStorage();
            // Re-render reservations view so user sees upcoming → active transition
            const reservationsContent = document.getElementById('reservationsContent');
            if (reservationsContent && reservationsContent.classList.contains('active')) {
                const activeTab = document.querySelector('.reservations-tab.active');
                const currentFilter = activeTab?.getAttribute('data-filter') || 'active';
                // Only refresh if we're on the reservations page
                displayReservations(currentFilter);
            }
        } catch (err) {
            console.error('Error updating reservations display:', err);
        }
    }
}

// Display Functions
function displayReservations(filter = 'active') {
    const activeList = document.getElementById('reservationsActiveList');
    const upcomingList = document.getElementById('reservationsUpcomingList');
    const historyBody = document.getElementById('reservationsHistoryBody');
    const summaryEl = document.getElementById('reservationsSummary');
    const activeCountEl = document.getElementById('reservationsActiveCount');
    const upcomingCountEl = document.getElementById('reservationsUpcomingCount');
    const historyCountEl = document.getElementById('reservationsHistoryCount');

    if (!activeList || !upcomingList || !historyBody) return;

    // Always update statuses before displaying (ensures fresh status)
    updateBookingStatuses();

    const normalized = userReservations.map(r => normalizeReservation(r));
    const active = normalized.filter(r => r.status === 'active');
    const upcoming = normalized.filter(r => r.status === 'upcoming');
    const history = normalized.filter(r => r.status === 'completed' || r.status === 'cancelled');

    if (summaryEl) {
        if (active.length === 0 && upcoming.length === 0) {
            summaryEl.textContent = 'No active or upcoming reservations right now.';
        } else {
            summaryEl.textContent = `You have ${active.length} active and ${upcoming.length} upcoming parking sessions.`;
        }
    }

    if (activeCountEl) activeCountEl.textContent = active.length;
    if (upcomingCountEl) upcomingCountEl.textContent = upcoming.length;
    if (historyCountEl) historyCountEl.textContent = history.length;

    activeList.innerHTML = active.length
        ? active.map(r => renderReservationCard(r, 'active')).join('')
        : '<div class="reservation-empty">No active sessions.</div>';

    upcomingList.innerHTML = upcoming.length
        ? upcoming.map(r => renderReservationCard(r, 'upcoming')).join('')
        : '<div class="reservation-empty">No upcoming reservations.</div>';

    historyBody.innerHTML = history.length
        ? history.map(r => renderHistoryRow(r)).join('')
        : '<tr><td colspan="6">No past reservations yet.</td></tr>';

    const activeSection = document.getElementById('reservationsActiveSection');
    const upcomingSection = document.getElementById('reservationsUpcomingSection');
    const historySection = document.getElementById('reservationsHistorySection');

    if (activeSection && upcomingSection && historySection) {
        activeSection.style.display = filter === 'active' ? 'flex' : 'none';
        upcomingSection.style.display = filter === 'upcoming' ? 'flex' : 'none';
        historySection.style.display = filter === 'history' ? 'flex' : 'none';
    }
}

function getPaymentInfo(reservation) {
    const payment = reservation.payment || reservation.payment_data || null;
    const status = (payment?.status || '').toLowerCase();
    if (status === 'completed') {
        return { status: 'paid', label: 'Paid' };
    }
    if (status === 'failed') {
        if (reservation.status === 'cancelled') {
            return { status: 'expired', label: 'Payment expired' };
        }
        return { status: 'failed', label: 'Payment failed' };
    }
    return { status: 'pending', label: 'Payment pending' };
}

function beginReservationPayment(reservationId) {
    const reservation = normalizeReservation(userReservations.find(r => r.id === reservationId));
    if (!reservation) {
        showDialog('Reservation not found.', 'error');
        return;
    }

    if (reservation.status === 'cancelled' || reservation.status === 'completed') {
        showDialog('This reservation is no longer eligible for payment.', 'info');
        return;
    }

    const paymentInfo = getPaymentInfo(reservation);
    if (paymentInfo.status === 'paid') {
        showDialog('This reservation is already paid.', 'info');
        return;
    }
    if (paymentInfo.status === 'expired') {
        showDialog('Payment window expired. Please book again.', 'warning');
        return;
    }

    const parking = reservation.parking
        || parkingLocations.find(loc => loc.id === reservation.parking_location_id)
        || { name: 'Parking', address: '', price: 0 };
    const startTime = reservation.startTime || reservation.start_time || '';
    const endTime = reservation.endTime || reservation.end_time || '';
    const hours = calculateHours(startTime, endTime);
    const rawAmount = Number(reservation.amount || reservation.total_amount || 0);
    const subtotal = Math.max(0, Math.abs(rawAmount));
    const fees = subtotal * 0.2;
    const total = subtotal + fees;

    pendingBooking = {
        reservation_id: reservation.id,
        parking_location_id: reservation.parking_location_id || parking.id,
        date: reservation.date,
        start_time: startTime,
        end_time: endTime,
        license_plate: reservation.vehicle || reservation.license_plate || '',
        total_amount: subtotal,
        amount: subtotal,
        fees: fees,
        total: total,
            hours: hours,
        parking: parking,
        created_at: reservation.created_at
    };

    showPayments();
}

function renderReservationCard(reservation, type) {
    const parkingName = reservation.parking?.name || 'Unknown Parking';
    const parkingAddress = reservation.parking?.address || '';
    const vehicle = reservation.vehicle || reservation.license_plate || 'Not set';
    const date = reservation.date || 'N/A';
    const startTime = reservation.startTime || reservation.start_time || 'N/A';
    const endTime = reservation.endTime || reservation.end_time || 'N/A';
    const amount = reservation.amount || reservation.total_amount || 0;
    const image = reservation.parking?.image || '';
    const duration = getReservationDuration(reservation);
    const statusClass = reservation.status || type;
    const statusLabel = reservation.status ? reservation.status.toUpperCase() : type.toUpperCase();
    const passId = `reservation-pass-${reservation.id}`;
    const paymentInfo = getPaymentInfo(reservation);
    const showPayButton = paymentInfo.status === 'pending' && reservation.status !== 'cancelled';
    const paymentBadgeClass = paymentInfo.status === 'paid' ? 'paid' : paymentInfo.status === 'expired' ? 'expired' : 'pending';

    return `
        <div class="reservation-card-modern">
            <div class="reservation-card-image" style="${image ? `background-image:url('${image}'); background-size:cover; background-position:center;` : ''}">
                <span>${reservation.id ? `RES-${reservation.id}` : 'RESERVATION'}</span>
            </div>
            <div class="reservation-card-body">
                <div class="reservation-card-title">
                    <div>
                        <div class="reservation-meta">${vehicle}</div>
                        <h4>${parkingName}</h4>
                        <div class="reservation-meta">${parkingAddress}</div>
                    </div>
                    <div>
                        <span class="reservation-status ${statusClass}">${statusLabel}</span>
                        <div class="reservation-payment ${paymentBadgeClass}">${paymentInfo.label}</div>
                        <div class="reservation-meta" style="text-align:right; margin-top:6px;">${formatUGX(amount)}</div>
                    </div>
                </div>
                <div class="reservation-details">
                    <div><strong>Date</strong>${date}</div>
                    <div><strong>Time</strong>${startTime} - ${endTime}</div>
                    <div><strong>Duration</strong>${duration}</div>
                    <div><strong>Vehicle</strong>${vehicle}</div>
                    <div><strong>Payment</strong>${paymentInfo.label}</div>
                </div>
                <div class="reservation-actions">
                    <button class="primary" type="button" onclick="toggleReservationPass(${reservation.id})">View Pass</button>
                    <button type="button" onclick="printReservation(${reservation.id})">Print</button>
                    ${showPayButton ? `<button class="primary" type="button" onclick="beginReservationPayment(${reservation.id})">Pay Now</button>` : ''}
                    ${type === 'active' ? `<button class="danger" type="button" onclick="cancelReservation(${reservation.id})">End Session</button>` : ''}
                    ${type === 'upcoming' ? `<button class="danger" type="button" onclick="cancelReservation(${reservation.id})">Cancel</button>` : ''}
                </div>
                <div class="reservation-pass" id="${passId}">
                    <div id="qr-${reservation.id}"></div>
                    <div class="reservation-pass-meta">
                        <div><strong>Pass ID</strong> ${reservation.id ? `PKE${reservation.id}` : 'N/A'}</div>
                        <div>Show this QR code at entry.</div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderHistoryRow(reservation) {
    const parkingName = reservation.parking?.name || 'Unknown Parking';
    const date = reservation.date || 'N/A';
    const amount = reservation.amount || reservation.total_amount || 0;
    const duration = getReservationDuration(reservation);
    const status = reservation.status || 'completed';
    const paymentInfo = getPaymentInfo(reservation);
    const statusLabel = paymentInfo.status === 'expired' ? 'PAYMENT EXPIRED' : status.toUpperCase();
    const statusClass = paymentInfo.status === 'expired' ? 'payment-expired' : status;

    return `
        <tr>
            <td>
                <div class="reservation-meta" style="font-weight:700; color:#111827;">${parkingName}</div>
                <div class="reservation-meta">${reservation.vehicle || reservation.license_plate || 'Not set'}</div>
            </td>
            <td>${date}</td>
            <td>${duration}</td>
            <td style="font-weight:700;">${formatUGX(amount)}</td>
            <td>
                <span class="reservation-status-pill ${statusClass}"><span></span>${statusLabel}</span>
            </td>
            <td style="text-align:right;">
                <button class="reservations-link" type="button" onclick="printReservation(${reservation.id})">Print</button>
            </td>
        </tr>
    `;
}

function getReservationDuration(reservation) {
    const start = reservation.startTime || reservation.start_time;
    const end = reservation.endTime || reservation.end_time;
    if (!start || !end) return 'N/A';

    const startDate = new Date(`2000-01-01T${start}`);
    const endDate = new Date(`2000-01-01T${end}`);
    if (Number.isNaN(startDate.getTime()) || Number.isNaN(endDate.getTime())) return 'N/A';

    const diffMs = endDate - startDate;
    if (diffMs <= 0) return 'N/A';

    const totalMinutes = Math.round(diffMs / 60000);
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    return `${hours}h ${minutes}m`;
}

function toggleReservationPass(reservationId) {
    const pass = document.getElementById(`reservation-pass-${reservationId}`);
    if (!pass) return;

    const isActive = pass.classList.contains('active');
    document.querySelectorAll('.reservation-pass').forEach(panel => panel.classList.remove('active'));

    if (!isActive) {
        pass.classList.add('active');
        generateQRCode(reservationId, `PKE${reservationId}`);
    }
}

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
    
    // Preserve backend status - don't default to 'completed'
    const status = data.status || 'upcoming';
    
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
        status: status // Use preserved status, default to 'upcoming' if missing
    };
}

function filterReservations(filterType) {
    document.querySelectorAll('.reservations-tab').forEach(btn => btn.classList.remove('active'));
    const activeBtn = document.querySelector(`.reservations-tab[data-filter="${filterType}"]`);
    if (activeBtn) activeBtn.classList.add('active');
    displayReservations(filterType);
}
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
    const container = document.getElementById('paymentCheckout');
    if (!container) return;
    
    if (!pendingBooking) {
        hidePaymentCheckout();
        return;
    }

    togglePaymentsBilling(false);
    container.style.display = 'block';
    
    // Use pending booking data
    const booking = pendingBooking;
    const isExistingReservation = !!booking.reservation_id;
    const hours = booking.hours || 2;
    const parkingName = booking.parking?.name || 'Parking';
    const parkingAddress = booking.parking?.address || '';
    const parkingRate = Number(booking.parking?.price || 0);
    const rawAmount = booking.amount || (hours * parkingRate);
    const subtotal = Math.max(0, Math.abs(Number(rawAmount || 0)));
    const fees = booking.fees || (subtotal * 0.2);
    const total = booking.total || (subtotal + fees);
    
    container.innerHTML = `
        <div class="payment-container">
            <div class="payment-summary-card">
                <h2>${isExistingReservation ? 'Reservation Summary' : 'Booking Summary'}</h2>
                <div class="summary-item">
                    <span class="label">Location</span>
                    <span class="value">${parkingName}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Address</span>
                    <span class="value">${parkingAddress}</span>
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
                    <span class="label">Subtotal (${hours.toFixed(1)} hrs @ ${parkingRate}/hr)</span>
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
                    <button class="payment-method active" data-method="Mobile Money">
                        <span>Mobile Money</span>
                    </button>
                    <button class="payment-method" data-method="Visa Card">
                        <span>Visa Card</span>
                    </button>
                    <button class="payment-method" data-method="Mastercard">
                        <span>Mastercard</span>
                    </button>
                </div>

                <div class="payment-method-label" id="paymentMethodLabel">Mobile Money</div>
                
                <form class="payment-form" onsubmit="processPayment(event)" id="paymentForm">
                    <div id="mobilePaymentFields">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <div class="input-with-icon">
                                <input type="tel" placeholder="+256 700 000 000" required id="phoneNumber">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mobile Money Provider</label>
                            <div class="input-with-icon">
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
                                <span class="icon">NAME</span>
                                <input type="text" placeholder="John M. Doe" id="cardName">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Card Number</label>
                            <div class="input-with-icon">
                                <span class="icon">CARD</span>
                                <input type="text" placeholder="0000 0000 0000 0000" id="cardNumber">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Expiration Date</label>
                                <div class="input-with-icon">
                                    <span class="icon">EXP</span>
                                    <input type="text" placeholder="MM/YY" id="cardExpiry">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>CVV</label>
                                <div class="input-with-icon">
                                    <span class="icon">CVV</span>
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
                        <span>Pay UGX ${total.toFixed(2)}</span>
                    </button>
                    
                    <div class="secure-note">
                        <span>Secure</span>
                        <span>Your payment information is encrypted and secure</span>
                    </div>
                </form>
            </div>
        </div>
    `;

    const lastMobile = userPayments.find(p => (p.method || '').toLowerCase().includes('mobile'));
    const phoneInput = document.getElementById('phoneNumber');
    const providerSelect = document.getElementById('mobileProvider');
    if (phoneInput && !phoneInput.value) {
        phoneInput.value = (lastMobile && lastMobile.phone) || currentUser?.phone || '';
    }
    if (providerSelect && lastMobile && lastMobile.provider) {
        providerSelect.value = lastMobile.provider;
    }
    
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
                    
                    const methodLabel = document.getElementById('paymentMethodLabel');
                    if (methodLabel) {
                        methodLabel.textContent = method;
                    }

                    if (method === 'Mobile Money') {
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

function hidePaymentCheckout() {
    const container = document.getElementById('paymentCheckout');
    if (!container) return;
    container.style.display = 'none';
    container.innerHTML = '';
}

function togglePaymentsBilling(showBilling) {
    const billing = document.getElementById('paymentsBilling');
    if (!billing) return;
    billing.style.display = showBilling ? 'block' : 'none';
}

function formatUGX(amount) {
    const value = Number(amount || 0);
    return `UGX ${value.toFixed(2)}`;
}

function parsePaymentDate(payment) {
    const date = payment.date || '';
    const time = payment.time || '00:00';
    const parsed = new Date(`${date}T${time}`);
    return Number.isNaN(parsed.getTime()) ? new Date() : parsed;
}

function getActiveReservationSummary() {
    if (!Array.isArray(userReservations) || userReservations.length === 0) {
        return null;
    }

    const active = userReservations
        .map(r => normalizeReservation(r))
        .filter(r => r.status === 'active' || r.status === 'upcoming');

    if (active.length === 0) return null;

    active.sort((a, b) => {
        const aDate = new Date(a.date || 0);
        const bDate = new Date(b.date || 0);
        return aDate - bDate;
    });

    return active[0];
}

function updatePaymentsStats() {
    const totalEl = document.getElementById('paymentsTotalSpent');
    const trendEl = document.getElementById('paymentsTotalTrend');
    const activeEl = document.getElementById('paymentsActiveCost');
    const activeMetaEl = document.getElementById('paymentsActiveMeta');
    const pendingEl = document.getElementById('paymentsPendingTotal');
    const pendingMetaEl = document.getElementById('paymentsPendingMeta');

    if (!totalEl || !trendEl || !activeEl || !activeMetaEl || !pendingEl || !pendingMetaEl) {
        return;
    }

    const now = new Date();
    const currentStart = new Date();
    currentStart.setDate(now.getDate() - 30);
    const previousStart = new Date();
    previousStart.setDate(now.getDate() - 60);

    const completedPayments = userPayments.filter(p => (p.status || '').toLowerCase() === 'completed');
    const currentTotal = completedPayments
        .filter(p => parsePaymentDate(p) >= currentStart)
        .reduce((sum, p) => sum + Number(p.amount || 0), 0);

    const previousTotal = completedPayments
        .filter(p => {
            const date = parsePaymentDate(p);
            return date >= previousStart && date < currentStart;
        })
        .reduce((sum, p) => sum + Number(p.amount || 0), 0);

    let change = 0;
    if (previousTotal > 0) {
        change = ((currentTotal - previousTotal) / previousTotal) * 100;
    } else if (currentTotal > 0) {
        change = 100;
    }

    totalEl.textContent = formatUGX(currentTotal);
    trendEl.textContent = `${Math.round(change)}% from last month`;

    if (pendingBooking) {
        activeEl.textContent = formatUGX(pendingBooking.total_amount || pendingBooking.total || 0);
        activeMetaEl.textContent = pendingBooking.parking?.name || 'Pending booking';
    } else {
        const activeReservation = getActiveReservationSummary();
        if (activeReservation) {
            activeEl.textContent = formatUGX(activeReservation.amount || 0);
            activeMetaEl.textContent = activeReservation.parking?.name || 'Active reservation';
        } else {
            activeEl.textContent = formatUGX(0);
            activeMetaEl.textContent = 'No active session';
        }
    }

    const pendingTotal = userPayments
        .filter(p => (p.status || '').toLowerCase() === 'pending')
        .reduce((sum, p) => sum + Number(p.amount || 0), 0);

    pendingEl.textContent = formatUGX(pendingTotal);
    pendingMetaEl.textContent = pendingTotal > 0 ? 'Pending payments' : 'All clear for now';
}

function updatePaymentMethods() {
    const grid = document.getElementById('paymentMethodsGrid');
    if (!grid) return;

    const sorted = [...userPayments].sort((a, b) => parsePaymentDate(b) - parsePaymentDate(a));
    const latestByType = {};

    sorted.forEach(payment => {
        const method = (payment.method || '').toLowerCase();
        const provider = (payment.provider || '').toLowerCase();
        if (!latestByType.mobile && (method.includes('mobile money') || method.includes('mobile payment'))) {
            latestByType.mobile = payment;
        }
        if (!latestByType.airtel && (provider.includes('airtel') || method.includes('airtel'))) {
            latestByType.airtel = payment;
        }
        if (!latestByType.mtn && (provider.includes('mtn') || method.includes('mtn'))) {
            latestByType.mtn = payment;
        }
        if (!latestByType.visa && method.includes('visa')) {
            latestByType.visa = payment;
        }
        if (!latestByType.mastercard && method.includes('mastercard')) {
            latestByType.mastercard = payment;
        }
        if (!latestByType.card && method === 'card') {
            latestByType.card = payment;
        }
    });

    const cards = [];

    if (latestByType.visa) {
        cards.push(`
            <div class="payment-card visa">
                <div class="payment-card-top">
                    <span class="chip"></span>
                    <span class="brand">VISA</span>
                </div>
                <div class="payment-card-number">**** **** **** ${latestByType.visa.cardLast4 || '----'}</div>
                <div class="payment-card-bottom">
                    <div>
                        <p class="label">Card Holder</p>
                        <p>${currentUser?.name || 'Card Holder'}</p>
                    </div>
                    <div>
                        <p class="label">Expires</p>
                        <p>--/--</p>
                    </div>
                </div>
            </div>
        `);
    }

    if (latestByType.mastercard) {
        cards.push(`
            <div class="payment-card master">
                <div class="payment-card-top">
                    <span class="chip"></span>
                    <span class="brand">
                        <span class="dot red"></span>
                        <span class="dot yellow"></span>
                    </span>
                </div>
                <div class="payment-card-number">**** **** **** ${latestByType.mastercard.cardLast4 || '----'}</div>
                <div class="payment-card-bottom">
                    <div>
                        <p class="label">Card Holder</p>
                        <p>${currentUser?.name || 'Card Holder'}</p>
                    </div>
                    <div>
                        <p class="label">Expires</p>
                        <p>--/--</p>
                    </div>
                </div>
            </div>
        `);
    }

    if (latestByType.airtel) {
        const label = `${latestByType.airtel.phone || ''} ${latestByType.airtel.provider || 'Airtel'}`.trim();
        cards.push(`
            <div class="payment-card airtel">
                <div class="payment-card-top">
                    <span class="chip"></span>
                    <span class="brand">AIRTEL</span>
                </div>
                <div class="payment-card-number">${label || 'Mobile Money'}</div>
                <div class="payment-card-bottom">
                    <div>
                        <p class="label">Account</p>
                        <p>${label || 'Airtel Money'}</p>
                    </div>
                    <div>
                        <p class="label">Type</p>
                        <p>Mobile</p>
                    </div>
                </div>
            </div>
        `);
    }

    if (latestByType.mtn) {
        const label = `${latestByType.mtn.phone || ''} ${latestByType.mtn.provider || 'MTN'}`.trim();
        cards.push(`
            <div class="payment-card mtn">
                <div class="payment-card-top">
                    <span class="chip"></span>
                    <span class="brand">MTN</span>
                </div>
                <div class="payment-card-number">${label || 'Mobile Money'}</div>
                <div class="payment-card-bottom">
                    <div>
                        <p class="label">Account</p>
                        <p>${label || 'MTN Mobile Money'}</p>
                    </div>
                    <div>
                        <p class="label">Type</p>
                        <p>Mobile</p>
                    </div>
                </div>
            </div>
        `);
    }

    if (!latestByType.visa && !latestByType.mastercard && latestByType.card) {
        cards.push(`
            <div class="payment-card visa">
                <div class="payment-card-top">
                    <span class="chip"></span>
                    <span class="brand">CARD</span>
                </div>
                <div class="payment-card-number">**** **** **** ${latestByType.card.cardLast4 || '----'}</div>
                <div class="payment-card-bottom">
                    <div>
                        <p class="label">Card Holder</p>
                        <p>${currentUser?.name || 'Card Holder'}</p>
                    </div>
                    <div>
                        <p class="label">Expires</p>
                        <p>--/--</p>
                    </div>
                </div>
            </div>
        `);
    }

    const mobilePayment = latestByType.mobile;
    cards.push(`
        <div class="payment-card wallet">
            <div class="wallet-icon">MOBILE</div>
            <p class="wallet-title">${mobilePayment ? 'Mobile Money Connected' : 'Add Mobile Money'}</p>
            <p class="wallet-subtitle">${mobilePayment ? `${mobilePayment.phone || ''} ${mobilePayment.provider || ''}`.trim() : 'Select a provider during payment'}</p>
            <button class="wallet-btn" type="button">Manage</button>
        </div>
    `);

    if (cards.length === 1) {
        cards.unshift(`
            <div class="payment-card wallet">
                <div class="wallet-icon">CARD</div>
                <p class="wallet-title">Add Visa or Mastercard</p>
                <p class="wallet-subtitle">Add a card during payment</p>
                <button class="wallet-btn" type="button">Add</button>
            </div>
        `);
    }

    grid.innerHTML = cards.join('');
}

function updatePaymentHistoryTable() {
    const body = document.getElementById('paymentsTableBody');
    const countText = document.getElementById('paymentsCountText');

    if (!body || !countText) return;

    if (!userPayments || userPayments.length === 0) {
        body.innerHTML = `
            <tr>
                <td colspan="7">No transactions yet.</td>
            </tr>
        `;
        countText.textContent = 'Showing 0 of 0 transactions';
        return;
    }

    const sortedPayments = [...userPayments].sort((a, b) => parsePaymentDate(b) - parsePaymentDate(a));
    const rows = sortedPayments.map(payment => {
        const status = (payment.status || 'completed').toLowerCase();
        const statusLabel = status === 'completed' ? 'Paid' : status === 'failed' ? 'Failed' : 'Pending';
        const statusClass = status === 'completed' ? 'paid' : status === 'failed' ? 'failed' : 'pending';
        const dateLabel = payment.date || '';
        const timeLabel = payment.time || 'N/A';
        const location = payment.parking || 'Unknown Parking';
        const receiptText = status === 'failed' ? 'Retry' : 'Download';
        const receiptClass = status === 'failed' ? 'receipt-btn alt' : 'receipt-btn';

        return `
            <tr>
                <td>#PK-${payment.id}</td>
                <td>
                    <div>${dateLabel}</div>
                    <div class="muted">${timeLabel}</div>
                </td>
                <td>${location}</td>
                <td>-</td>
                <td class="strong">${formatUGX(payment.amount)}</td>
                <td><span class="status ${statusClass}">${statusLabel}</span></td>
                <td class="right"><button class="${receiptClass}" type="button">${receiptText}</button></td>
            </tr>
        `;
    }).join('');

    body.innerHTML = rows;
    countText.textContent = `Showing ${sortedPayments.length} of ${sortedPayments.length} transactions`;
}

async function updatePaymentsDashboard() {
    if (pendingBooking) {
        togglePaymentsBilling(false);
        return;
    }

    togglePaymentsBilling(true);
    await loadPaymentHistory();
    updatePaymentsStats();
    updatePaymentMethods();
    updatePaymentHistoryTable();
}

async function processPayment(event) {
    event.preventDefault();
    
    if (!pendingBooking) {
        showDialog('No booking pending. Please select a parking spot first.', 'error');
        return;
    }
    
    // Get selected payment method
    const activeMethod = document.querySelector('.payment-method.active');
    const paymentMethod = activeMethod ? activeMethod.getAttribute('data-method') : 'Mobile Money';
    
    // Get payment form data based on method
    const form = event.target;
    let paymentDetails = {};
    
    if (paymentMethod === 'Mobile Money') {
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
        await new Promise(resolve => setTimeout(resolve, 1000));

        const paymentPayload = {
            method: paymentMethod,
        };

        if (paymentMethod === 'Mobile Money') {
            paymentPayload.phone = paymentDetails.phone;
            paymentPayload.provider = paymentDetails.provider;
        } else if (paymentDetails.cardNumber) {
            paymentPayload.card_last4 = paymentDetails.cardNumber.slice(-4);
        }

        if (pendingBooking.reservation_id) {
            const response = await payReservation(pendingBooking.reservation_id, paymentPayload);
            const updatedReservation = normalizeReservation(response.reservation);
            userReservations = userReservations.map(r => (r.id === updatedReservation.id ? updatedReservation : r));

            await loadPaymentHistory();

            pendingBooking = null;
            showDialog(
                `Payment completed!\n\nParking: ${updatedReservation.parking?.name || 'Reservation'}\nAmount: ${formatUGX(updatedReservation.amount || 0)}`,
                'success',
                'Payment Confirmed'
            );
            showReservations();
        } else {
            // Legacy flow (fallback)
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

            if (paymentMethod === 'Mobile Money') {
                reservationData.payment_phone = paymentDetails.phone;
                reservationData.payment_provider = paymentDetails.provider;
            } else if (paymentDetails.cardNumber) {
                reservationData.payment_card_last4 = paymentDetails.cardNumber.slice(-4);
            }

            const reservation = await createReservation(reservationData);

            const bookedLocation = parkingLocations.find(loc => loc.id === pendingBooking.parking_location_id);
            if (bookedLocation && bookedLocation.available > 0) {
                bookedLocation.available -= 1;
            }
            if (selectedParking && selectedParking.id === pendingBooking.parking_location_id && selectedParking.available > 0) {
                selectedParking.available -= 1;
            }
            displayParkingLocations();

            const normalizedReservation = normalizeReservation(reservation);
            userReservations.push(normalizedReservation);
            await loadPaymentHistory();

            const bookingDetails = { ...pendingBooking };
            pendingBooking = null;
            selectedParking = null;

            showDialog(
                `Booking confirmed!\n\nParking: ${bookingDetails.parking.name}\nDate: ${bookingDetails.date}\nTime: ${bookingDetails.start_time} - ${bookingDetails.end_time}\nAmount: UGX ${bookingDetails.total.toFixed(2)}`,
                'success',
                'Booking Confirmed'
            );

            setTimeout(() => {
                displayPaymentHistory();
            }, 500);
        }
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
    hidePaymentCheckout();
    await updatePaymentsDashboard();
}

// Auto-update statuses every 15 seconds (upcoming → active when start time passes)
// Use try-catch to prevent crashes
setInterval(() => {
    try {
        updateBookingStatuses();
    } catch (err) {
        console.error('Error in status update interval:', err);
    }
}, 15 * 1000);



