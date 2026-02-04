// api.js - Updated to match Laravel routes
const API_URL = 'http://localhost:8000/api';
let authToken = localStorage.getItem('authToken') || null;

// Function to update authToken from localStorage
function updateAuthToken() {
    authToken = localStorage.getItem('authToken') || null;
    return authToken;
}

// API Helper Functions
async function apiRequest(endpoint, options = {}) {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    if (authToken) {
        headers['Authorization'] = `Bearer ${authToken}`;
    }

    const config = {
        ...options,
        headers: {
            ...headers,
            ...options.headers,
        },
    };

    if (options.body) {
        config.body = JSON.stringify(options.body);
    }

    try {
        const response = await fetch(`${API_URL}${endpoint}`, config);
        
        // Check if response is JSON
        let data;
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            data = await response.json();
        } else {
            const text = await response.text();
            throw new Error(`Server returned non-JSON response: ${text.substring(0, 100)}`);
        }

        if (!response.ok) {
            throw new Error(data.message || data.error || `API request failed: ${response.status}`);
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        // Provide more helpful error messages
        if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
            throw new Error('Cannot connect to server. Please make sure the Laravel backend is running at http://localhost:8000');
        }
        throw error;
    }
}

// Auth API - Updated endpoints to match Laravel
async function registerUser(name, email, password) {
    const data = await apiRequest('/auth/register', {
        method: 'POST',
        body: { name, email, password, password_confirmation: password }
    });
    
    if (data.data && data.data.access_token) {
        authToken = data.data.access_token;
        localStorage.setItem('authToken', authToken);
        return data.data.user;
    }
    throw new Error('Registration failed: No token received');
}

async function loginUser(email, password) {
    const data = await apiRequest('/auth/login', {  // Changed from /login to /auth/login
        method: 'POST',
        body: { email, password }
    });
    
    if (data.data && data.data.access_token) {
        authToken = data.data.access_token;
        localStorage.setItem('authToken', authToken);
        // Store is_admin flag
        if (data.data.is_admin) {
            localStorage.setItem('isAdmin', 'true');
        } else {
            localStorage.removeItem('isAdmin');
        }
        return {
            ...data.data.user,
            is_admin: data.data.is_admin || false
        };
    }
    throw new Error('Login failed: No token received');
}

async function logoutUser() {
    await apiRequest('/auth/logout', { method: 'POST' });  // Changed from /logout to /auth/logout
    authToken = null;
    localStorage.removeItem('authToken');
}

async function getProfile() {
    return await apiRequest('/auth/profile');  // Changed from /user/profile to /auth/profile
}

// Parking Locations API
async function fetchParkingLocations() {
    const data = await apiRequest('/parking-locations');
    return data.data || [];
}

async function searchParkingLocations(filters = {}) {
    const params = new URLSearchParams(filters).toString();
    const data = await apiRequest(`/parking-locations/search?${params}`);
    return data.data || [];
}

async function getParkingLocation(id) {
    const data = await apiRequest(`/parking-locations/${id}`);
    return data.data;
}

// Reservations API
async function fetchReservations() {
    const data = await apiRequest('/reservations');
    return data.data || [];
}

async function createReservation(reservationData) {
    const data = await apiRequest('/reservations', {
        method: 'POST',
        body: reservationData
    });
    return data.data;
}

async function cancelReservationAPI(id) {
    const data = await apiRequest(`/reservations/${id}/cancel`, {
        method: 'POST'
    });
    return data.data;
}

// User API - Updated endpoints
async function updateUserProfile(profileData) {
    const data = await apiRequest('/user', {  // Changed from /user/profile to /user
        method: 'PUT',
        body: profileData
    });
    return data.data;
}

async function updateUserSettings(settingsData) {
    const data = await apiRequest('/settings', {  // Changed from /user/settings to /settings
        method: 'PUT',
        body: settingsData
    });
    return data.data;
}

// Payment API
async function processPayment(paymentData) {
    const data = await apiRequest('/payments/process', {
        method: 'POST',
        body: paymentData
    });
    return data.data;
}

async function fetchPayments() {
    const data = await apiRequest('/payments');
    return data.data || [];
}

// Admin API
async function fetchAllBookings() {
    const data = await apiRequest('/admin/bookings');
    return data.data || [];
}

async function fetchAllPayments() {
    const data = await apiRequest('/admin/payments');
    return data.data || [];
}

async function fetchAdminStats() {
    const data = await apiRequest('/admin/stats');
    return data.data || {};
}

// Check API connection
async function checkApiHealth() {
    try {
        const response = await fetch(`${API_URL}/`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        });
        return response.ok;
    } catch (error) {
        console.error('API Health Check Failed:', error);
        console.error('API URL:', `${API_URL}/`);
        return false;
    }
}

// Initialize auth token on load
document.addEventListener('DOMContentLoaded', function() {
    updateAuthToken();
    if (authToken) {
        // Verify token is still valid
        getProfile().catch(() => {
            // Token is invalid, clear it
            authToken = null;
            localStorage.removeItem('authToken');
            localStorage.removeItem('isAdmin');
        });
    }
});




















// // api.js - API Integration
// const API_URL = 'http://localhost:8000/api'; // Change this to your Laravel URL
// let authToken = localStorage.getItem('authToken');

// // API Helper Functions
// async function apiRequest(endpoint, options = {}) {
//     const headers = {
//         'Content-Type': 'application/json',
//         'Accept': 'application/json',
//     };

//     if (authToken) {
//         headers['Authorization'] = `Bearer ${authToken}`;
//     }

//     const config = {
//         ...options,
//         headers: {
//             ...headers,
//             ...options.headers,
//         },
//     };

//     try {
//         const response = await fetch(`${API_URL}${endpoint}`, config);
//         const data = await response.json();

//         if (!response.ok) {
//             throw new Error(data.message || 'API request failed');
//         }

//         return data;
//     } catch (error) {
//         console.error('API Error:', error);
//         throw error;
//     }
// }

// // Auth API
// async function registerUser(name, email, password) {
//     const data = await apiRequest('/register', {
//         method: 'POST',
//         body: JSON.stringify({ name, email, password }),
//     });
    
//     authToken = data.token;
//     localStorage.setItem('authToken', authToken);
//     return data.user;
// }

// async function loginUser(email, password) {
//     const data = await apiRequest('/login', {
//         method: 'POST',
//         body: JSON.stringify({ email, password }),
//     });
    
//     authToken = data.token;
//     localStorage.setItem('authToken', authToken);
//     return data.user;
// }

// async function logoutUser() {
//     await apiRequest('/logout', { method: 'POST' });
//     authToken = null;
//     localStorage.removeItem('authToken');
// }

// // Parking Locations API
// async function fetchParkingLocations() {
//     return await apiRequest('/parking-locations');
// }

// // Reservations API
// async function fetchReservations() {
//     return await apiRequest('/reservations');
// }

// async function createReservation(reservationData) {
//     return await apiRequest('/reservations', {
//         method: 'POST',
//         body: JSON.stringify(reservationData),
//     });
// }

// async function cancelReservationAPI(id) {
//     return await apiRequest(`/reservations/${id}/cancel`, {
//         method: 'POST',
//     });
// }

// // User Settings API
// async function updateUserProfile(profileData) {
//     return await apiRequest('/user/profile', {
//         method: 'PUT',
//         body: JSON.stringify(profileData),
//     });
// }

// async function updateUserSettings(settingsData) {
//     return await apiRequest('/user/settings', {
//         method: 'PUT',
//         body: JSON.stringify(settingsData),
//     });
// }