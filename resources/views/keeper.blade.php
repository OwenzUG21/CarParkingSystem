<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkOwenz – Keeper Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100">
    <div id="keeperDashboard" class="hidden min-h-screen flex flex-col">
        <!-- Top bar -->
        <header class="border-b border-slate-800 bg-slate-900/80 backdrop-blur px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold" id="keeperAvatar">K</div>
                <div>
                    <div class="text-sm font-semibold" id="keeperName">Keeper</div>
                    <div class="text-xs text-slate-400" id="keeperEmail">email@example.com</div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-xs text-slate-400" id="keeperLotLabel">No lot assigned</div>
                <button id="keeperLogoutBtn"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-800 text-sm hover:bg-slate-700">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    <span>Logout</span>
                </button>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-6 space-y-6">
            <!-- Live occupancy & quick actions -->
            <section class="grid gap-4 lg:grid-cols-3">
                <div class="col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-400">Live Occupancy</p>
                            <p class="text-2xl font-bold mt-1" id="liveOccupied">0 / 0</p>
                            <p class="text-xs text-slate-400 mt-1" id="liveAvailableLabel">0 spots available</p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="text-xs text-slate-400">Lot</span>
                            <span class="text-sm font-semibold" id="liveLotName">—</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden mb-4">
                        <div id="liveOccupancyBar" class="h-2 bg-emerald-500" style="width: 0%"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <button id="btnCheckIn"
                                class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 bg-emerald-600 text-sm font-semibold hover:bg-emerald-500">
                            <span class="material-symbols-outlined text-sm">qr_code_scanner</span>
                            <span>Check-In / Scan QR</span>
                        </button>
                        <button id="btnCheckOut"
                                class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 bg-amber-600 text-sm font-semibold hover:bg-amber-500">
                            <span class="material-symbols-outlined text-sm">directions_car</span>
                            <span>Check-Out / Enter Plate</span>
                        </button>
                    </div>
                </div>

                <!-- Payment verification -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Payment Verification</p>
                    <p class="text-sm text-slate-200 mt-1 mb-3">Check if a vehicle has paid.</p>
                    <form id="paymentCheckForm" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">License Plate</label>
                            <input id="paymentPlate" type="text" placeholder="e.g. UBA 123X"
                                   class="w-full rounded-lg bg-slate-800 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Slot (optional)</label>
                            <input id="paymentSlot" type="text" placeholder="e.g. Slot 14"
                                   class="w-full rounded-lg bg-slate-800 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 bg-indigo-600 text-sm font-semibold hover:bg-indigo-500">
                            <span class="material-symbols-outlined text-sm">receipt_long</span>
                            <span>Verify Payment</span>
                        </button>
                    </form>
                    <div id="paymentResult" class="mt-4 text-xs text-slate-300"></div>
                </div>
            </section>

            <!-- Active bookings / recent activity -->
            <section class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold">Active Bookings in Your Lot</h2>
                    <button id="refreshBookings"
                            class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-slate-800 text-xs hover:bg-slate-700">
                        <span class="material-symbols-outlined text-xs">refresh</span>
                        <span>Refresh</span>
                    </button>
                </div>
                <div class="overflow-x-auto max-h-80">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-slate-800 text-slate  -400">
                            <tr>
                                <th class="py-2 pr-2">Plate</th>
                                <th class="py-2 pr-2">Time</th>
                                <th class="py-2 pr-2">Status</th>
                                <th class="py-2 pr-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="keeperBookingsTable" class="divide-y divide-slate-800">
                            <!-- rows injected -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Check-In/Out Modal -->
    <div id="keeperModal" class="fixed inset-0 bg-black/60 flex items-center justify-center hidden z-50">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" id="keeperModalTitle">Check-In</h3>
                <button onclick="closeKeeperModal()" class="text-slate-400 hover:text-slate-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="keeperModalForm" class="space-y-4">
                <input type="hidden" id="keeperActionType" value="checkin">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">License Plate</label>
                    <input id="keeperPlateInput" type="text" placeholder="e.g. UBA 123X"
                           class="w-full rounded-lg bg-slate-800 border border-slate-700 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <p class="text-xs text-slate-400">
                    Use this when sensors or automatic check-in/out fail. This will mark the car as
                    <span class="font-semibold" id="keeperActionLabel">checked in</span> in your lot.
                </p>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeKeeperModal()"
                            class="px-3 py-1.5 rounded-lg bg-slate-800 text-xs hover:bg-slate-700">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-3 py-1.5 rounded-lg bg-emerald-600 text-xs font-semibold hover:bg-emerald-500">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dialog (reuse admin/index styles) -->
    <div id="keeperDialog" class="fixed inset-0 hidden items-center justify-center z-50">
        <div class="absolute inset-0 bg-black/60" onclick="closeKeeperDialog()"></div>
        <div class="relative bg-white rounded-xl p-6 max-w-sm w-full mx-4 text-slate-900">
            <div id="keeperDialogIcon" class="w-10 h-10 mb-3 rounded-full flex items-center justify-center bg-indigo-600 text-white"></div>
            <h3 id="keeperDialogTitle" class="font-semibold mb-2">Notification</h3>
            <p id="keeperDialogMessage" class="text-sm text-slate-600 mb-4"></p>
            <button onclick="closeKeeperDialog()"
                    class="w-full px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">
                OK
            </button>
        </div>
    </div>

    <script src="/api.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            // Small delay to ensure api.js is ready
            await new Promise(r => setTimeout(r, 100));

            if (typeof updateAuthToken === 'function') {
                updateAuthToken();
            }

            const token = localStorage.getItem('authToken');
            if (!token) {
                window.location.href = '/';
                return;
            }

            try {
                const profile = await getProfile();
                const user = profile.data?.user || profile.data || profile;
                const role = profile.data?.role || user.role || localStorage.getItem('role');

                if (role !== 'keeper') {
                    if (user.is_admin) {
                        window.location.href = '/admin';
                    } else if (role === 'lot_manager') {
                        window.location.href = '/manager';
                    } else {
                        window.location.href = '/';
                    }
                    return;
                }

                // Set header info
                const name = user.name || 'Keeper';
                const email = user.email || '';
                const phone = user.phone || '';
                document.getElementById('keeperName').textContent = name;
                document.getElementById('keeperEmail').textContent = email || phone;
                document.getElementById('keeperAvatar').textContent = (name[0] || 'K').toUpperCase();

                document.getElementById('keeperDashboard').classList.remove('hidden');

                await loadKeeperData();
            } catch (e) {
                console.error('Keeper auth failed', e);
                window.location.href = '/';
            }
        });

        async function loadKeeperData() {
            try {
                const lotData = await fetchKeeperLot();
                const lot = lotData.lot;
                const occ = lotData.live_occupancy;

                const lotLabel = document.getElementById('keeperLotLabel');
                const lotNameEl = document.getElementById('liveLotName');
                if (lot && occ) {
                    lotNameEl.textContent = lot.name || 'Your Lot';
                    lotLabel.textContent = lot.name || 'Your Lot';
                    const total = occ.total || 0;
                    const occupied = occ.occupied || 0;
                    const available = occ.available || 0;
                    document.getElementById('liveOccupied').textContent = `${occupied} / ${total}`;
                    document.getElementById('liveAvailableLabel').textContent = `${available} spot${available === 1 ? '' : 's'} available`;
                    const pct = total > 0 ? Math.min(100, Math.round((occupied / total) * 100)) : 0;
                    document.getElementById('liveOccupancyBar').style.width = pct + '%';
                } else {
                    lotNameEl.textContent = 'No lot assigned';
                    lotLabel.textContent = 'No lot assigned';
                    document.getElementById('liveOccupied').textContent = '0 / 0';
                    document.getElementById('liveAvailableLabel').textContent = '0 spots available';
                    document.getElementById('liveOccupancyBar').style.width = '0%';
                }
            } catch (e) {
                console.error('Failed to load keeper lot', e);
            }

            await loadKeeperBookings();
        }

        async function loadKeeperBookings() {
            try {
                const bookings = await fetchKeeperActiveBookings();
                const tbody = document.getElementById('keeperBookingsTable');
                tbody.innerHTML = '';
                if (!bookings || bookings.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-slate-500 text-xs">No active bookings.</td></tr>';
                    return;
                }

                bookings.forEach(b => {
                    const tr = document.createElement('tr');
                    const amount = parseFloat(b.amount || 0).toFixed(2);
                    tr.innerHTML = `
                        <td class="py-2 pr-2 text-slate-200">${b.vehicle || 'N/A'}</td>
                        <td class="py-2 pr-2 text-slate-400">${b.start_time || ''} – ${b.end_time || ''}</td>
                        <td class="py-2 pr-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                ${b.status === 'active' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300'}">
                                ${b.status || 'active'}
                            </span>
                        </td>
                        <td class="py-2 pl-2 text-right text-slate-200">UGX ${amount}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch (e) {
                console.error('Failed to load keeper bookings', e);
            }
        }

        // Logout
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('keeperLogoutBtn');
            if (btn) {
                btn.addEventListener('click', async function () {
                    try {
                        if (typeof updateAuthToken === 'function') {
                            updateAuthToken();
                        }
                        if (authToken) {
                            await logoutUser();
                        }
                    } catch (e) {
                        console.error('Logout error', e);
                    } finally {
                        localStorage.removeItem('authToken');
                        localStorage.removeItem('role');
                        window.location.href = '/';
                    }
                });
            }
        });

        // Check-in / Check-out modal
        document.addEventListener('DOMContentLoaded', function () {
            const btnIn = document.getElementById('btnCheckIn');
            const btnOut = document.getElementById('btnCheckOut');
            const form = document.getElementById('keeperModalForm');

            if (btnIn) {
                btnIn.addEventListener('click', function () {
                    openKeeperModal('checkin');
                });
            }
            if (btnOut) {
                btnOut.addEventListener('click', function () {
                    openKeeperModal('checkout');
                });
            }

            if (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const action = document.getElementById('keeperActionType').value;
                    const plate = document.getElementById('keeperPlateInput').value.trim();
                    if (!plate) return;

                    try {
                        const lotData = await fetchKeeperLot();
                        const lot = lotData.lot;
                        if (!lot) {
                            showKeeperDialog('⚠', 'No Lot', 'You are not assigned to any parking lot.');
                            return;
                        }
                        const payload = {
                            parking_location_id: lot.id,
                            license_plate: plate,
                        };
                        if (action === 'checkin') {
                            await keeperCheckIn(payload);
                            showKeeperDialog('✓', 'Check-In Recorded', `Vehicle ${plate} has been checked in.`);
                        } else {
                            await keeperCheckOut(payload);
                            showKeeperDialog('✓', 'Check-Out Recorded', `Vehicle ${plate} has been checked out.`);
                        }
                        closeKeeperModal();
                        await loadKeeperData();
                    } catch (err) {
                        showKeeperDialog('✕', 'Error', err.message || 'Unable to record action.');
                    }
                });
            }

            const refreshBtn = document.getElementById('refreshBookings');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', async function () {
                    await loadKeeperBookings();
                });
            }

            const paymentForm = document.getElementById('paymentCheckForm');
            if (paymentForm) {
                paymentForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const plate = document.getElementById('paymentPlate').value.trim();
                    const slot = document.getElementById('paymentSlot').value.trim();
                    const resultEl = document.getElementById('paymentResult');
                    resultEl.textContent = 'Checking...';
                    try {
                        const data = await keeperPaymentStatus(plate);
                        if (!data.paid) {
                            resultEl.innerHTML = `<span class="text-red-400 font-semibold">NOT PAID</span> for ${plate}${slot ? ' in ' + slot : ''}.`;
                        } else {
                            resultEl.innerHTML = `
                                <span class="text-emerald-400 font-semibold">PAID</span> UGX ${data.amount.toFixed(2)}
                                ${slot ? ' for ' + slot : ''}<br>
                                <span class="text-slate-400">Method: ${data.method || 'N/A'} • At: ${data.paid_at || 'N/A'}</span>
                            `;
                        }
                    } catch (err) {
                        resultEl.textContent = err.message || 'Unable to check payment.';
                    }
                });
            }
        });

        function openKeeperModal(action) {
            const modal = document.getElementById('keeperModal');
            document.getElementById('keeperActionType').value = action;
            document.getElementById('keeperModalTitle').textContent =
                action === 'checkin' ? 'Manual Check-In' : 'Manual Check-Out';
            document.getElementById('keeperActionLabel').textContent =
                action === 'checkin' ? 'checked in' : 'checked out';
            document.getElementById('keeperPlateInput').value = '';
            modal.classList.remove('hidden');
        }

        function closeKeeperModal() {
            const modal = document.getElementById('keeperModal');
            modal.classList.add('hidden');
        }

        function showKeeperDialog(icon, title, message) {
            const d = document.getElementById('keeperDialog');
            document.getElementById('keeperDialogIcon').textContent = icon;
            document.getElementById('keeperDialogTitle').textContent = title;
            document.getElementById('keeperDialogMessage').textContent = message;
            d.classList.remove('hidden');
            d.classList.add('flex');
        }

        function closeKeeperDialog() {
            const d = document.getElementById('keeperDialog');
            d.classList.add('hidden');
            d.classList.remove('flex');
        }
    </script>
</body>
</html>

