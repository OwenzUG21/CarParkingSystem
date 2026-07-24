// api.js - Updated to match Laravel routes
const API_URL = (function resolveApiUrl() {
    if (typeof window !== 'undefined') {
        const base = window.API_BASE_URL || window.location?.origin;
        if (base && base !== 'null') {
            return `${base}/api`;
        }
    }
    return 'http://localhost:8000/api';
})();
let authToken = localStorage.getItem('authToken') || null;

// Function to update authToken from localStorage
function updateAuthToken() {
    authToken = localStorage.getItem('authToken') || null;
    return authToken;
}

// API Helper Functions
async function apiRequest(endpoint, options = {}) {
    const headers = {
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

    // Body handling:
    // - If body is FormData: do NOT set Content-Type (browser will set boundary)
    // - Else assume JSON
    if (options.body instanceof FormData) {
        config.body = options.body;
        // Remove any JSON content-type if present
        if (config.headers && config.headers['Content-Type']) {
            delete config.headers['Content-Type'];
        }
    } else if (options.body !== undefined) {
        config.headers['Content-Type'] = 'application/json';
        config.body = JSON.stringify(options.body);
    } else {
        config.headers['Content-Type'] = 'application/json';
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

        const isAdmin = !!data.data.is_admin;
        const role = data.data.role || data.data.user?.role || 'user';

        // Store flags for role-based dashboards
        if (isAdmin) {
            localStorage.setItem('isAdmin', 'true');
        } else {
            localStorage.removeItem('isAdmin');
        }

        localStorage.setItem('role', role);

        return {
            ...data.data.user,
            is_admin: isAdmin,
            role,
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

async function payReservation(reservationId, paymentData) {
    const data = await apiRequest(`/payments/reservations/${reservationId}`, {
        method: 'POST',
        body: paymentData
    });
    return data.data;
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

// Chat API
async function sendChatMessage(message, meta = {}) {
    const data = await apiRequest('/chat/messages', {
        method: 'POST',
        body: { message, ...meta }
    });
    return data.data;
}

async function fetchUserChatMessages() {
    const data = await apiRequest('/chat/messages');
    return data.data || [];
}

async function fetchAdminChatMessages() {
    const data = await apiRequest('/admin/chat/messages');
    return data.data || [];
}

async function fetchAdminManagers() {
    const data = await apiRequest('/admin/managers');
    return data.data || [];
}

async function createAdminLot(payload) {
    const data = await apiRequest('/admin/lots', {
        method: 'POST',
        body: payload,
    });
    return data.data;
}

async function createAdminManager(payload) {
    const data = await apiRequest('/admin/managers', {
        method: 'POST',
        body: payload,
    });
    return data.data;
}

async function updateAdminManager(id, payload) {
    const data = await apiRequest(`/admin/managers/${id}`, {
        method: 'PUT',
        body: payload,
    });
    return data.data;
}

async function deleteAdminManager(id) {
    return await apiRequest(`/admin/managers/${id}`, {
        method: 'DELETE',
    });
}

async function fetchAdminLots() {
    const data = await apiRequest('/admin/lots');
    return data.data || [];
}

async function updateAdminLot(id, payload) {
    const data = await apiRequest(`/admin/lots/${id}`, {
        method: 'PUT',
        body: payload,
    });
    return data.data || {};
}

async function deleteAdminLot(id) {
    return await apiRequest(`/admin/lots/${id}`, {
        method: 'DELETE',
    });
}

// Lot Manager (Spot Admin) API
async function fetchManagerDashboard() {
    const data = await apiRequest('/manager/dashboard');
    return data.data || {};
}

async function fetchManagerLots() {
    const data = await apiRequest('/manager/lots');
    return data.data || [];
}

async function fetchManagerBookings() {
    const data = await apiRequest('/manager/bookings');
    return data.data || [];
}

async function fetchManagerSlots(lotId) {
    const data = await apiRequest(`/manager/lots/${lotId}/slots`);
    return data.data || [];
}

async function configureManagerSlots(lotId, payload) {
    const data = await apiRequest(`/manager/lots/${lotId}/slots/configure`, {
        method: 'POST',
        body: payload,
    });
    return data.data || [];
}

async function updateManagerLot(lotId, payload) {
    const isFormData = payload instanceof FormData;
    if (isFormData && !payload.has('_method')) {
        payload.append('_method', 'PUT');
    }

    const data = await apiRequest(`/manager/lots/${lotId}`, {
        // PHP only parses multipart/form-data for POST; use method spoofing for uploads.
        method: isFormData ? 'POST' : 'PUT',
        body: payload,
    });
    return data.data || {};
}

async function fetchManagerActiveBookings(lotId) {
    const data = await apiRequest(`/manager/lots/${lotId}/bookings/active`);
    return data.data || [];
}

async function fetchManagerKeepers() {
    const data = await apiRequest('/manager/keepers');
    return data.data || [];
}

async function createManagerKeeper(payload) {
    const data = await apiRequest('/manager/keepers', {
        method: 'POST',
        body: payload,
    });
    return data.data;
}

async function fetchManagerKeeperActivity() {
    const data = await apiRequest('/manager/keepers/activity');
    return data.data || [];
}

async function updateManagerKeeper(assignmentId, payload) {
    const data = await apiRequest(`/manager/keepers/${assignmentId}`, {
        method: 'PUT',
        body: payload,
    });
    return data.data;
}

async function deleteManagerKeeper(assignmentId) {
    const data = await apiRequest(`/manager/keepers/${assignmentId}`, {
        method: 'DELETE',
    });
    return data;
}

async function fetchManagerValidations() {
    const data = await apiRequest('/manager/validations');
    return data.data || [];
}

async function createManagerValidation(payload) {
    const data = await apiRequest('/manager/validations', {
        method: 'POST',
        body: payload,
    });
    return data.data;
}

// Keeper API
async function fetchKeeperLot() {
    const data = await apiRequest('/keeper/lot');
    return data.data || {};
}

async function fetchKeeperActiveBookings() {
    const data = await apiRequest('/keeper/bookings/active');
    return data.data || [];
}

async function keeperCheckIn(payload) {
    const data = await apiRequest('/keeper/check-in', {
        method: 'POST',
        body: payload,
    });
    return data.data;
}

async function keeperCheckOut(payload) {
    const data = await apiRequest('/keeper/check-out', {
        method: 'POST',
        body: payload,
    });
    return data.data;
}

async function keeperPaymentStatus(licensePlate) {
    const params = new URLSearchParams({ license_plate: licensePlate });
    const data = await apiRequest(`/keeper/payment-status?${params.toString()}`);
    return data.data;
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
