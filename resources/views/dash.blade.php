<!DOCTYPE html>
<html class="dark" lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>ParkWise – Owner Dashboard</title>

<!-- Tailwind & Icons -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        primary: "#137fec",
        backgroundDark: "#101922",
      },
      fontFamily: { display: ["Manrope", "sans-serif"] },
    },
  },
};
</script>

<style>
body { font-family: "Manrope", sans-serif; }
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
input[type="number"]::-webkit-inner-spin-button {
  opacity: 1;
}
</style>
</head>

<body class="bg-backgroundDark text-white">
<!-- ---------------- DASHBOARD ---------------- -->
<div id="dashboard" class="hidden">
  <!-- Side nav -->
  <div class="flex h-screen">
    <aside class="w-64 bg-white/5 border-r border-white/10 p-4 h-screen sticky top-0 flex flex-col overflow-hidden">
      <div class="flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined text-primary text-3xl">local_parking</span>
        <div class="min-w-0">
          <h2 class="text-xl font-bold leading-tight">ParkWise</h2>
          <p id="managerEmail" class="text-xs text-white/60 truncate"></p>
        </div>
      </div>
      <nav class="flex flex-col gap-2">
        <a onclick="scrollToSection('sectionDashboard')"
           class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 text-primary cursor-pointer">
          <span class="material-symbols-outlined">dashboard</span>Dashboard
        </a>
        <a onclick="scrollToSection('sectionBookings')"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/70 hover:bg-white/10 cursor-pointer">
          <span class="material-symbols-outlined">bookmarks</span>Bookings
        </a>
        <a onclick="scrollToSection('sectionLots')"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/70 hover:bg-white/10 cursor-pointer">
          <span class="material-symbols-outlined">location_on</span>Lots
        </a>
        <a onclick="scrollToSection('sectionKeepers')"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/70 hover:bg-white/10 cursor-pointer">
          <span class="material-symbols-outlined">group</span>Keepers
        </a>
        <a onclick="scrollToSection('sectionValidations')"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/70 hover:bg-white/10 cursor-pointer">
          <span class="material-symbols-outlined">verified</span>Validations
        </a>
      </nav>

      <div class="mt-4 rounded-xl bg-white/5 border border-white/10 p-3">
        <p class="text-xs text-white/60 mb-2">Quick edit lot</p>
        <select id="sidebarLotSelect"
                class="w-full rounded-lg bg-white/10 border border-white/20 h-9 px-3 text-sm focus:ring-primary focus:border-primary">
          <option value="">Select lot…</option>
        </select>
        <button type="button" onclick="openEditLotFromSidebar()"
                class="mt-2 w-full h-9 bg-primary rounded-lg text-sm font-semibold hover:bg-primary/80">
          Edit Lot
        </button>
      </div>

      <div class="mt-4 rounded-xl bg-white/5 border border-white/10 p-3">
        <div class="flex items-center justify-between">
          <p class="text-xs text-white/60">Keepers</p>
          <button type="button" onclick="scrollToSection('sectionKeepers')"
                  class="text-xs font-semibold text-primary hover:underline">
            Manage
          </button>
        </div>
        <p id="keepersSidebarSummary" class="mt-2 text-xs text-white/80"></p>
        <p id="keepersSidebarLastActivity" class="mt-1 text-xs text-white/60"></p>
      </div>
      <div class="mt-auto border-t border-white/10 pt-4">
        <a onclick="logout()"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/70 hover:bg-white/10 cursor-pointer">
          <span class="material-symbols-outlined">logout</span>Logout
        </a>
      </div>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-8 flex flex-col gap-8 overflow-y-auto">
      <!-- HEADER -->
      <div id="sectionDashboard" class="flex justify-between items-center flex-wrap gap-4 scroll-mt-6">
        <div>
          <h1 class="text-3xl font-bold">Parking Lot Dashboard</h1>
          <p class="text-white/60">Overview of your parking performance</p>
        </div>
        <button onclick="toggleRates()"
                class="flex items-center gap-2 bg-primary px-4 h-10 rounded-lg font-semibold hover:bg-primary/80">
          <span class="material-symbols-outlined">edit</span>Edit Rates
        </button>
      </div>

      <!-- STAT CARDS -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="rounded-xl bg-white/5 p-6 border border-white/10">
          <p class="text-white/80">Active Bookings</p>
          <p id="activeBookings" class="text-3xl font-bold">0</p>
        </div>
        <div class="rounded-xl bg-white/5 p-6 border border-white/10">
          <p class="text-white/80">Today's Revenue</p>
          <p id="revenue" class="text-3xl font-bold">UGX 0</p>
        </div>
        <div class="rounded-xl bg-white/5 p-6 border border-white/10">
          <p class="text-white/80">Occupancy Rate</p>
          <p id="occupancy" class="text-3xl font-bold">0%</p>
        </div>
        <div class="rounded-xl bg-white/5 p-6 border border-white/10">
          <p class="text-white/80">Avg. Duration</p>
          <p id="avgDuration" class="text-3xl font-bold">0h</p>
        </div>
      </div>

      <!-- SIMPLE CHART -->
      <div class="rounded-xl bg-white/5 border border-white/10 p-6">
        <p class="text-lg font-semibold mb-2">Booking Volume (Last 7 days)</p>
        <canvas id="chartBookings" height="120"></canvas>
      </div>

      <!-- BOOKINGS SECTION -->
      <div id="sectionBookings" class="rounded-xl bg-white/5 border border-white/10 p-6 scroll-mt-6">
        <div class="flex justify-between items-center mb-4">
          <div>
            <h2 class="text-lg font-semibold">Bookings</h2>
            <p class="text-xs text-white/60">All reservations for your lots.</p>
          </div>
          <button type="button" onclick="refreshManagerBookings()"
                  class="h-8 px-3 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-semibold">
            Refresh
          </button>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead>
              <tr class="border-b border-white/10 text-white/60">
                <th class="p-2">Customer</th>
                <th class="p-2">Lot</th>
                <th class="p-2">Vehicle</th>
                <th class="p-2">Date</th>
                <th class="p-2">Time</th>
                <th class="p-2">Amount</th>
                <th class="p-2">Status</th>
                <th class="p-2">Payment</th>
              </tr>
            </thead>
            <tbody id="managerBookingsTable"></tbody>
          </table>
        </div>
      </div>

      <!-- BOOKINGS TABLE -->
      <div class="rounded-xl bg-white/5 border border-white/10 p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold">Current Bookings (for your lots)</h2>
          <input type="text" placeholder="Search plate..."
                 class="w-56 bg-white/10 rounded-lg border border-white/20 h-9 px-3 text-sm focus:ring-primary focus:border-primary" />
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="border-b border-white/10 text-white/60 text-sm">
                <th class="p-2">Spot</th><th class="p-2">Vehicle</th>
                <th class="p-2">Arrival</th><th class="p-2">Time Left</th>
                <th class="p-2 text-right">Amount</th>
              </tr>
            </thead>
            <tbody id="bookingTable"></tbody>
          </table>
        </div>
      </div>

      <!-- LOTS & KEEPERS SECTION -->
      <div id="sectionLots" class="grid lg:grid-cols-2 gap-6 scroll-mt-6">
        <!-- Managed Lots -->
        <div class="rounded-xl bg-white/5 border border-white/10 p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Your Parking Lots</h2>
          </div>
          <div id="lotsList" class="space-y-3 text-sm text-white/80">
            <!-- Filled via JS -->
          </div>
        </div>

        <!-- Keepers -->
        <div id="sectionKeepers" class="rounded-xl bg-white/5 border border-white/10 p-6 scroll-mt-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Keepers / Staff</h2>
          </div>

          <form id="keeperForm" class="mb-4 grid grid-cols-1 gap-3">
            <input id="keeperName" type="text" placeholder="Keeper name"
                   class="w-full rounded-lg bg-white/10 border border-white/20 h-9 px-3 text-sm focus:ring-primary focus:border-primary" />
            <input id="keeperEmail" type="email" placeholder="Email address"
                   class="w-full rounded-lg bg-white/10 border border-white/20 h-9 px-3 text-sm focus:ring-primary focus:border-primary" />
            <input id="keeperPhone" type="text" placeholder="Mobile number"
                   class="w-full rounded-lg bg-white/10 border border-white/20 h-9 px-3 text-sm focus:ring-primary focus:border-primary" />
            <select id="keeperLot"
                    class="w-full rounded-lg bg-white/10 border border-white/20 h-9 px-3 text-sm focus:ring-primary focus:border-primary">
              <option value="">Assign to lot…</option>
            </select>
            <button type="submit"
                    class="h-9 bg-primary rounded-lg text-sm font-semibold hover:bg-primary/80">
              Add Keeper
            </button>
          </form>

          <div class="overflow-x-auto max-h-56">
            <table class="w-full text-left text-sm">
              <thead>
                <tr class="border-b border-white/10 text-white/60">
                  <th class="p-2">Name</th>
                  <th class="p-2">Email</th>
                  <th class="p-2">Phone</th>
                  <th class="p-2">Lot</th>
                  <th class="p-2">Status</th>
                  <th class="p-2 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="keepersTable"></tbody>
            </table>
          </div>

          <div class="mt-6 border-t border-white/10 pt-4">
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-sm font-semibold text-white/90">Recent Keeper Activity</h3>
              <button type="button" onclick="refreshKeeperActivity()"
                      class="h-8 px-3 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-semibold">
                Refresh
              </button>
            </div>
            <div class="overflow-x-auto max-h-56 text-sm">
              <table class="w-full text-left">
                <thead>
                  <tr class="border-b border-white/10 text-white/60">
                    <th class="p-2">Keeper</th>
                    <th class="p-2">Lot</th>
                    <th class="p-2">Plate</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Time</th>
                  </tr>
                </thead>
                <tbody id="keeperActivityTable"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- VALIDATIONS SECTION -->
      <div id="sectionValidations" class="rounded-xl bg-white/5 border border-white/10 p-6 scroll-mt-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold">Parking Validations</h2>
        </div>

        <form id="validationForm" class="mb-4 grid md:grid-cols-4 gap-3 text-sm">
          <input id="validationBusiness" type="text" placeholder="Business name"
                 class="rounded-lg bg-white/10 border border-white/20 h-9 px-3 focus:ring-primary focus:border-primary md:col-span-2" />
          <select id="validationLot"
                  class="rounded-lg bg-white/10 border border-white/20 h-9 px-3 focus:ring-primary focus:border-primary">
            <option value="">Select lot…</option>
          </select>
          <input id="validationMinutes" type="number" min="30" step="30" value="120"
                 class="rounded-lg bg-white/10 border border-white/20 h-9 px-3 focus:ring-primary focus:border-primary"
                 placeholder="Free minutes" />
          <button type="submit"
                  class="h-9 bg-primary rounded-lg font-semibold hover:bg-primary/80 md:col-span-4">
            Issue Validation
          </button>
        </form>

        <div class="overflow-x-auto max-h-56 text-sm">
          <table class="w-full text-left">
            <thead>
              <tr class="border-b border-white/10 text-white/60">
                <th class="p-2">Code</th>
                <th class="p-2">Business</th>
                <th class="p-2">Lot</th>
                <th class="p-2">Free Time</th>
                <th class="p-2">Uses</th>
                <th class="p-2">Expires</th>
              </tr>
            </thead>
            <tbody id="validationsTable"></tbody>
          </table>
        </div>
      </div>

      <!-- EDIT PRICE MODAL -->
      <div id="rateModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-40">
        <div class="bg-white/10 border border-white/20 rounded-xl p-6 w-80">
          <h3 class="text-xl font-bold mb-4">Edit Parking Rate</h3>
          <label class="block text-sm mb-1 text-white/70">Price per Hour (UGX)</label>
          <input id="rateInput" type="number" min="1" step="0.5"
                 class="w-full rounded-lg bg-white/10 border border-white/20 p-2 mb-4 text-center focus:ring-primary focus:border-primary" />
          <div class="flex justify-end gap-2">
            <button onclick="saveRate()" class="bg-primary px-4 py-2 rounded-lg font-semibold">Save</button>
            <button onclick="toggleRates()" class="bg-white/10 px-4 py-2 rounded-lg">Cancel</button>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Chart.js + API -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="/api.js"></script>

<script>
/* -------- STATE ---------- */
let hourlyRate = 2.5;
let managerStats = {};
let managerLots = [];
let managerBookings = [];
let managerKeepers = [];
let managerValidations = [];
let managerKeeperActivity = [];

/* -------- AUTH / ACCESS CONTROL ---------- */
document.addEventListener("DOMContentLoaded", async function () {
  // Ensure api.js is ready
  await new Promise(resolve => setTimeout(resolve, 100));

  if (typeof updateAuthToken === "function") {
    updateAuthToken();
  }

  const dashboard = document.getElementById("dashboard");

  const token = localStorage.getItem("authToken");
  if (!token) {
    // Not logged in at all – go back to main site login
    window.location.href = "/";
    return;
  }

  try {
    const profile = await getProfile();
    const user = profile.data?.user || profile.data || profile;
    const role = profile.data?.role || user.role || localStorage.getItem("role");

    if (role !== "lot_manager") {
      // If main admin, send to admin dashboard, else back home
      if (user.is_admin) {
        window.location.href = "/admin";
      } else {
        window.location.href = "/";
      }
      return;
    }

    // Authenticated Lot Manager – show dashboard
    if (dashboard) {
      dashboard.classList.remove("hidden");
    }

    const emailEl = document.getElementById("managerEmail");
    if (emailEl) {
      emailEl.textContent = user?.email || "";
      emailEl.title = user?.email || "";
    }
    await loadData();
  } catch (e) {
    console.error("Failed to verify manager session", e);
    window.location.href = "/";
  }
});

async function logout(){
  try {
    if (typeof updateAuthToken === "function") {
      updateAuthToken();
    }
    if (authToken) {
      await logoutUser();
    }
  } catch (e) {
    console.error("Logout error:", e);
  } finally {
    localStorage.removeItem("authToken");
    localStorage.removeItem("role");
    window.location.href = "/";
  }
}

/* -------- LOAD DATA FROM API ---------- */
async function loadData(){
  try{
    managerStats = await fetchManagerDashboard();
  }catch(e){
    managerStats = {};
  }

  try{
    managerLots = await fetchManagerLots();
  }catch(e){
    managerLots = [];
  }

  try{
    managerBookings = await fetchManagerBookings();
  }catch(e){
    managerBookings = [];
  }

  try{
    managerKeepers = await fetchManagerKeepers();
  }catch(e){
    managerKeepers = [];
  }

  try{
    managerKeeperActivity = await fetchManagerKeeperActivity();
  }catch(e){
    managerKeeperActivity = [];
  }

  try{
    managerValidations = await fetchManagerValidations();
  }catch(e){
    managerValidations = [];
  }

  updateStats();
  renderManagerBookings();
  renderLots();
  renderKeepers();
  renderKeeperActivity();
  updateKeepersSidebarSummary();
  renderValidations();
  drawChart();
}

function scrollToSection(id){
  const el = document.getElementById(id);
  if(!el) return;
  el.scrollIntoView({ behavior: "smooth", block: "start" });
}

function updateStats(){
  const stats = managerStats || {};

  document.getElementById("activeBookings").innerText =
    stats.active_bookings !== undefined ? stats.active_bookings : 0;

  const todayRev = stats.today_revenue !== undefined ? Number(stats.today_revenue) : 0;
  document.getElementById("revenue").innerText = "UGX " + todayRev.toFixed(2);

  const occ = stats.occupancy_rate !== undefined ? Number(stats.occupancy_rate) : 0;
  document.getElementById("occupancy").innerText = occ.toFixed(1) + "%";

  const avg = stats.avg_duration !== undefined ? Number(stats.avg_duration) : 0;
  document.getElementById("avgDuration").innerText = avg.toFixed(1) + "h";

  // Bookings table is still a simple visual; you can later wire it to
  // a dedicated manager bookings endpoint.
  renderCurrentBookings();
}

function isBookingActiveNow(booking) {
  if (!booking || !booking.date || !booking.start_time || !booking.end_time) return false;
  try {
    const startDateTime = new Date(`${booking.date}T${booking.start_time}`);
    const endDateTime = new Date(`${booking.date}T${booking.end_time}`);
    const now = new Date();
    return now >= startDateTime && now < endDateTime;
  } catch (e) {
    return false;
  }
}

function renderCurrentBookings(){
  const table = document.getElementById("bookingTable");
  if (!table) return;
  table.innerHTML = "";

  const activeBookings = (managerBookings || []).filter(b => {
    const status = String(b.status || '').toLowerCase();
    return status === 'active' || isBookingActiveNow(b);
  });

  if (activeBookings.length === 0) {
    table.innerHTML = `
      <tr class="text-white/60">
        <td class="p-2" colspan="5">No active bookings right now.</td>
      </tr>`;
    return;
  }

  activeBookings.forEach(b => {
    const timeRange = b.start_time && b.end_time ? `${b.start_time} - ${b.end_time}` : '-';
    const amount = Number(b.amount || 0).toFixed(2);
    const row = document.createElement("tr");
    row.className = "border-b border-white/10";
    row.innerHTML = `
      <td class="p-2">${b.parking || '-'}</td>
      <td class="p-2">${b.vehicle || '-'}</td>
      <td class="p-2">${b.start_time || '-'}</td>
      <td class="p-2">${timeRange}</td>
      <td class="p-2 text-right">UGX ${amount}</td>
    `;
    table.appendChild(row);
  });
}

function renderManagerBookings(){
  const tbody = document.getElementById("managerBookingsTable");
  if(!tbody) return;

  tbody.innerHTML = "";

  if(!managerBookings || managerBookings.length === 0){
    tbody.innerHTML = `
      <tr class="text-white/60">
        <td class="p-2" colspan="8">No bookings found for your lots.</td>
      </tr>`;
    return;
  }

  managerBookings.forEach(b => {
    const timeRange = b.start_time && b.end_time ? `${b.start_time} - ${b.end_time}` : '-';
    const amount = Number(b.amount || 0).toFixed(2);
    const status = (b.status || 'upcoming').toUpperCase();
    const paymentStatus = (b.payment_status || 'pending').toUpperCase();
    const row = document.createElement("tr");
    row.className = "border-b border-white/10";
    row.innerHTML = `
      <td class="p-2">${b.customer || 'Unknown'}</td>
      <td class="p-2">${b.parking || '-'}</td>
      <td class="p-2">${b.vehicle || '-'}</td>
      <td class="p-2">${b.date || '-'}</td>
      <td class="p-2">${timeRange}</td>
      <td class="p-2">UGX ${amount}</td>
      <td class="p-2">${status}</td>
      <td class="p-2">${paymentStatus}</td>
    `;
    tbody.appendChild(row);
  });
}

async function refreshManagerBookings(){
  try{
    managerBookings = await fetchManagerBookings();
  }catch(e){
    managerBookings = [];
  }
  renderManagerBookings();
}

/* -------- LOTS / KEEPERS / VALIDATIONS RENDERING ---------- */
function renderLots(){
  const container = document.getElementById("lotsList");
  const keeperLotSelect = document.getElementById("keeperLot");
  const validationLotSelect = document.getElementById("validationLot");
  const sidebarLotSelect = document.getElementById("sidebarLotSelect");

  if(!container) return;

  container.innerHTML = "";
  keeperLotSelect.innerHTML = '<option value="">Assign to lot…</option>';
  validationLotSelect.innerHTML = '<option value="">Select lot…</option>';
  if (sidebarLotSelect) {
    sidebarLotSelect.innerHTML = '<option value="">Select lot…</option>';
  }

  if(!managerLots || managerLots.length === 0){
    container.innerHTML = '<p class="text-white/60 text-sm">No lots assigned to your account yet.</p>';
    return;
  }

  managerLots.forEach(lot => {
    const div = document.createElement("div");
    div.className = "border border-white/10 rounded-lg p-3";
    div.innerHTML = `
      <div class="flex justify-between items-center">
        <div>
          <p class="font-semibold">${lot.name}</p>
          <p class="text-xs text-white/60">${lot.address}</p>
        </div>
        <div class="text-right text-xs text-white/70">
          <p>Total: ${lot.total}</p>
          <p>Available: ${lot.available}</p>
        </div>
      </div>
      <div class="mt-3 flex justify-end">
        <button type="button" class="h-8 px-3 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-semibold"
                onclick="openEditLot(${lot.id})">
          Edit Lot
        </button>
      </div>
    `;
    container.appendChild(div);

    const opt1 = document.createElement("option");
    opt1.value = lot.id;
    opt1.textContent = lot.name;
    keeperLotSelect.appendChild(opt1);

    const opt2 = document.createElement("option");
    opt2.value = lot.id;
    opt2.textContent = lot.name;
    validationLotSelect.appendChild(opt2);

    if (sidebarLotSelect) {
      const opt3 = document.createElement("option");
      opt3.value = lot.id;
      opt3.textContent = lot.name;
      sidebarLotSelect.appendChild(opt3);
    }
  });
}

function renderKeepers(){
  const tbody = document.getElementById("keepersTable");
  if(!tbody) return;

  tbody.innerHTML = "";

  if(!managerKeepers || managerKeepers.length === 0){
    tbody.innerHTML = `
      <tr class="text-white/60">
        <td class="p-2" colspan="6">No keepers added yet.</td>
      </tr>`;
    return;
  }

  managerKeepers.forEach(k => {
    const tr = document.createElement("tr");
    tr.className = "border-b border-white/10";
    tr.innerHTML = `
      <td class="p-2">${k.name || "-"}</td>
      <td class="p-2">${k.email || "-"}</td>
      <td class="p-2">${k.phone || "-"}</td>
      <td class="p-2">${k.parking_location_name || "-"}</td>
      <td class="p-2">${k.status}</td>
      <td class="p-2 text-right">
        <button type="button"
                class="h-8 px-3 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-semibold"
                onclick="openEditKeeper(${k.id})">
          Edit
        </button>
        <button type="button"
                class="h-8 px-3 rounded-lg bg-red-500/20 hover:bg-red-500/30 text-xs font-semibold ml-2"
                onclick="confirmDeleteKeeper(${k.id})">
          Delete
        </button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

function renderKeeperActivity(){
  const tbody = document.getElementById("keeperActivityTable");
  if(!tbody) return;

  tbody.innerHTML = "";

  if(!managerKeeperActivity || managerKeeperActivity.length === 0){
    tbody.innerHTML = `
      <tr class="text-white/60">
        <td class="p-2" colspan="5">No keeper activity yet.</td>
      </tr>`;
    return;
  }

  managerKeeperActivity.forEach(a => {
    const tr = document.createElement("tr");
    tr.className = "border-b border-white/10";
    const keeperLabel = (a.keeper_name || "-") + (a.keeper_email ? ` (${a.keeper_email})` : "");
    tr.innerHTML = `
      <td class="p-2">${keeperLabel}</td>
      <td class="p-2">${a.parking_location_name || "-"}</td>
      <td class="p-2">${a.license_plate || "-"}</td>
      <td class="p-2">${a.status || "-"}</td>
      <td class="p-2">${a.created_at || "-"}</td>
    `;
    tbody.appendChild(tr);
  });
}

function updateKeepersSidebarSummary(){
  const summaryEl = document.getElementById("keepersSidebarSummary");
  const lastEl = document.getElementById("keepersSidebarLastActivity");
  if(!summaryEl || !lastEl) return;

  const keepers = managerKeepers || [];
  const pending = keepers.filter(k => k.status === "pending").length;
  const active = keepers.filter(k => k.status === "active").length;
  const inactive = keepers.filter(k => k.status === "inactive").length;
  summaryEl.textContent = `Active: ${active} • Pending: ${pending} • Inactive: ${inactive}`;

  const last = (managerKeeperActivity && managerKeeperActivity[0]) ? managerKeeperActivity[0] : null;
  if (!last) {
    lastEl.textContent = "Last activity: -";
    return;
  }

  const keeper = last.keeper_name || "-";
  const plate = last.license_plate || "-";
  const time = last.created_at || "-";
  lastEl.textContent = `Last activity: ${keeper} • ${plate} • ${time}`;
}

async function refreshKeeperActivity(){
  try{
    managerKeeperActivity = await fetchManagerKeeperActivity();
    renderKeeperActivity();
    updateKeepersSidebarSummary();
  }catch(e){
    showDialog(e.message || "Unable to refresh keeper activity.", "error", "Error");
  }
}

function openEditLotFromSidebar(){
  const sel = document.getElementById("sidebarLotSelect");
  const lotId = sel ? Number(sel.value || 0) : 0;
  if(!lotId){
    showDialog("Please select a lot first.", "warning", "Missing details");
    return;
  }
  openEditLot(lotId);
}

function openEditLot(lotId){
  const lot = (managerLots || []).find(l => Number(l.id) === Number(lotId));
  if(!lot){
    showDialog("Lot not found.", "error", "Error");
    return;
  }
  const modal = document.getElementById("lotEditModal");
  if(!modal) return;

  document.getElementById("lotEditId").value = lot.id;
  document.getElementById("lotEditName").value = lot.name || "";
  document.getElementById("lotEditPrice").value = lot.price ?? "";
  document.getElementById("lotEditImageUrl").value = lot.image || "";
  const imgFile = document.getElementById("lotEditImageFile");
  if (imgFile) imgFile.value = "";

  modal.classList.add("show");
  document.body.style.overflow = "hidden";
}

function closeLotEditModal(){
  const modal = document.getElementById("lotEditModal");
  if(!modal) return;
  modal.classList.remove("show");
  document.body.style.overflow = "";
}

async function saveLotEdits(){
  const id = Number(document.getElementById("lotEditId").value || 0);
  const name = document.getElementById("lotEditName").value.trim();
  const price = document.getElementById("lotEditPrice").value;
  const imageUrl = document.getElementById("lotEditImageUrl").value.trim();
  const imageFile = document.getElementById("lotEditImageFile").files?.[0] || null;

  if(!id || !name || price === ""){
    showDialog("Please provide lot name and price.", "warning", "Missing details");
    return;
  }

  try{
    const fd = new FormData();
    fd.append("name", name);
    fd.append("price", String(price));
    if (imageFile) {
      fd.append("image", imageFile);
    } else if (imageUrl) {
      fd.append("image", imageUrl);
    }

    await updateManagerLot(id, fd);
    closeLotEditModal();
    await loadData();
    showDialog("Lot updated successfully.", "success", "Lot Updated");
  }catch(e){
    showDialog(e.message || "Unable to update lot.", "error", "Error");
  }
}

function openEditKeeper(assignmentId){
  const k = (managerKeepers || []).find(x => Number(x.id) === Number(assignmentId));
  if(!k){
    showDialog("Keeper not found.", "error", "Error");
    return;
  }

  const modal = document.getElementById("keeperEditModal");
  if(!modal) return;

  document.getElementById("keeperEditAssignmentId").value = k.id;
  document.getElementById("keeperEditName").value = k.name || "";
  document.getElementById("keeperEditEmail").value = k.email || "";
  document.getElementById("keeperEditPhone").value = k.phone || "";
  document.getElementById("keeperEditStatus").value = k.status || "pending";

  const lotSel = document.getElementById("keeperEditLot");
  if (lotSel) {
    lotSel.innerHTML = '<option value="">Assign to lot…</option>';
    (managerLots || []).forEach(l => {
      const opt = document.createElement("option");
      opt.value = l.id;
      opt.textContent = l.name;
      lotSel.appendChild(opt);
    });
    lotSel.value = k.parking_location_id || "";
  }

  modal.classList.add("show");
  document.body.style.overflow = "hidden";
}

function closeKeeperEditModal(){
  const modal = document.getElementById("keeperEditModal");
  if(!modal) return;
  modal.classList.remove("show");
  document.body.style.overflow = "";
}

async function saveKeeperEdits(){
  const assignmentId = Number(document.getElementById("keeperEditAssignmentId").value || 0);
  const name = document.getElementById("keeperEditName").value.trim();
  const email = document.getElementById("keeperEditEmail").value.trim();
  const phone = document.getElementById("keeperEditPhone").value.trim();
  const status = document.getElementById("keeperEditStatus").value;
  const lotId = document.getElementById("keeperEditLot").value;

  if(!assignmentId || !name || !email || !phone || !status || !lotId){
    showDialog("Please provide name, email, phone, status and lot.", "warning", "Missing details");
    return;
  }

  try{
    await updateManagerKeeper(assignmentId, {
      name,
      email,
      phone,
      status,
      parking_location_id: Number(lotId),
    });
    closeKeeperEditModal();
    await loadData();
    showDialog("Keeper updated successfully.", "success", "Keeper Updated");
  }catch(e){
    showDialog(e.message || "Unable to update keeper.", "error", "Error");
  }
}

function confirmDeleteKeeper(assignmentId){
  const k = (managerKeepers || []).find(x => Number(x.id) === Number(assignmentId));
  const label = k ? `${k.name || "Keeper"} (${k.email || "no email"})` : "this keeper";
  showDialog(`Delete ${label}? This removes their assignment from your lot.`, "warning", "Confirm Delete");

  const dialogButton = document.getElementById("dialogButton");
  if (!dialogButton) return;

  dialogButton.onclick = async function(){
    closeDialog();
    try{
      await deleteManagerKeeper(assignmentId);
      await loadData();
      showDialog("Keeper removed successfully.", "success", "Keeper Deleted");
    }catch(e){
      showDialog(e.message || "Unable to delete keeper.", "error", "Error");
    } finally {
      dialogButton.onclick = closeDialog;
      dialogButton.textContent = "OK";
    }
  };
  dialogButton.textContent = "Delete";
}

function renderValidations(){
  const tbody = document.getElementById("validationsTable");
  if(!tbody) return;

  tbody.innerHTML = "";

  if(!managerValidations || managerValidations.length === 0){
    tbody.innerHTML = `
      <tr class="text-white/60">
        <td class="p-2" colspan="6">No validations issued yet.</td>
      </tr>`;
    return;
  }

  managerValidations.forEach(v => {
    const tr = document.createElement("tr");
    tr.className = "border-b border-white/10";
    const free = (v.free_minutes || 0) / 60;
    const uses = v.max_uses
      ? `${v.uses_count || 0}/${v.max_uses}`
      : (v.uses_count || 0);
    tr.innerHTML = `
      <td class="p-2">${v.code}</td>
      <td class="p-2">${v.business_name}</td>
      <td class="p-2">${(managerLots.find(l => l.id === v.parking_location_id) || {}).name || "-"}</td>
      <td class="p-2">${free.toFixed(1)}h</td>
      <td class="p-2">${uses}</td>
      <td class="p-2">${v.expires_at || "-"}</td>
    `;
    tbody.appendChild(tr);
  });
}

/* -------- CHART ---------- */
function drawChart(){
  const ctx = document.getElementById("chartBookings");
  if(!ctx) return;

  // Simple placeholder trend for now; you can later wire an API
  // for manager-specific booking trends.
  new Chart(ctx,{
    type:"line",
    data:{
      labels:["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],
      datasets:[{
        label:"Bookings",
        data:[12,15,9,14,20,18,22],
        borderColor:"#137fec",
        backgroundColor:"rgba(19,127,236,0.2)",
        fill:true,
        tension:0.3
      }]
    },
    options:{
      plugins:{legend:{display:false}},
      scales:{x:{ticks:{color:"#ccc"}},y:{ticks:{color:"#ccc"}}}
    }
  });
}

/* -------- RATE EDIT ---------- */
function toggleRates(){
  document.getElementById("rateModal").classList.toggle("hidden");
  document.getElementById("rateInput").value = hourlyRate;
}
function saveRate(){
  hourlyRate = parseFloat(document.getElementById("rateInput").value);
  showDialog("New rate set: UGX " + hourlyRate.toFixed(2) + " per hour", 'success', 'Rate Updated');
  toggleRates();
}

// Beautiful Dialog Functions
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

// Forms wiring
document.addEventListener("DOMContentLoaded", function(){
  const keeperForm = document.getElementById("keeperForm");
  if(keeperForm){
    keeperForm.addEventListener("submit", async function(e){
      e.preventDefault();
      const name = document.getElementById("keeperName").value.trim();
      const email = document.getElementById("keeperEmail").value.trim();
      const phone = document.getElementById("keeperPhone").value.trim();
      const lotId = document.getElementById("keeperLot").value;

      if(!name || !email || !phone || !lotId){
        showDialog("Please provide name, email, phone and lot.", "warning", "Missing details");
        return;
      }

      try{
        await createManagerKeeper({
          name,
          email,
          phone,
          parking_location_id: Number(lotId),
        });
        showDialog("Keeper added successfully. Share the login email and initial PIN (their phone number) so they can log in and set a new password.", "success", "Keeper Created");
        document.getElementById("keeperName").value = "";
        document.getElementById("keeperEmail").value = "";
        document.getElementById("keeperPhone").value = "";
        document.getElementById("keeperLot").value = "";
        await loadData();
      }catch(e){
        showDialog(e.message || "Unable to add keeper.", "error", "Error");
      }
    });
  }

  const validationForm = document.getElementById("validationForm");
  if(validationForm){
    validationForm.addEventListener("submit", async function(e){
      e.preventDefault();
      const business = document.getElementById("validationBusiness").value.trim();
      const lotId = document.getElementById("validationLot").value;
      const minutes = Number(document.getElementById("validationMinutes").value || 0);

      if(!business || !lotId || !minutes){
        showDialog("Please provide business name, lot and free minutes.", "warning", "Missing details");
        return;
      }

      try{
        await createManagerValidation({
          business_name: business,
          parking_location_id: Number(lotId),
          free_minutes: minutes,
        });
        showDialog("Validation issued successfully.", "success", "Validation Created");
        document.getElementById("validationBusiness").value = "";
        document.getElementById("validationMinutes").value = "120";
        document.getElementById("validationLot").value = "";
        await loadData();
      }catch(e){
        showDialog(e.message || "Unable to issue validation.", "error", "Error");
      }
    });
  }
});
</script>

<!-- Beautiful Dialog Component -->
<div id="customDialog" class="custom-dialog">
    <div class="dialog-overlay"></div>
    <div class="dialog-container text-center">
        <div class="dialog-icon" id="dialogIcon"></div>
        <h3 class="dialog-title" id="dialogTitle">Notification</h3>
        <p class="dialog-message" id="dialogMessage"></p>
        <button class="dialog-button" id="dialogButton" onclick="closeDialog()">OK</button>
    </div>
</div>

<!-- Keeper Edit Modal -->
<div id="keeperEditModal" class="custom-dialog">
    <div class="dialog-overlay" onclick="closeKeeperEditModal()"></div>
    <div class="dialog-container text-left">
        <h3 class="dialog-title text-center">Edit Keeper</h3>
        <input type="hidden" id="keeperEditAssignmentId" />
        <div class="space-y-3">
            <div>
                <label class="text-sm text-gray-700 block mb-1">Name</label>
                <input id="keeperEditName" type="text" class="w-full border rounded-lg p-2" />
            </div>
            <div>
                <label class="text-sm text-gray-700 block mb-1">Email</label>
                <input id="keeperEditEmail" type="email" class="w-full border rounded-lg p-2" />
            </div>
            <div>
                <label class="text-sm text-gray-700 block mb-1">Phone</label>
                <input id="keeperEditPhone" type="text" class="w-full border rounded-lg p-2" />
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm text-gray-700 block mb-1">Status</label>
                    <select id="keeperEditStatus" class="w-full border rounded-lg p-2">
                        <option value="pending">pending</option>
                        <option value="active">active</option>
                        <option value="inactive">inactive</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-700 block mb-1">Lot</label>
                    <select id="keeperEditLot" class="w-full border rounded-lg p-2"></select>
                </div>
            </div>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-3">
            <button class="dialog-button bg-gray-200 text-gray-900 shadow-none" onclick="closeKeeperEditModal()">Cancel</button>
            <button class="dialog-button" onclick="saveKeeperEdits()">Save</button>
        </div>
    </div>
</div>

<!-- Lot Edit Modal -->
<div id="lotEditModal" class="custom-dialog">
    <div class="dialog-overlay" onclick="closeLotEditModal()"></div>
    <div class="dialog-container text-left">
        <h3 class="dialog-title text-center">Edit Lot</h3>
        <input type="hidden" id="lotEditId" />
        <div class="space-y-3">
            <div>
                <label class="text-sm text-gray-700 block mb-1">Name</label>
                <input id="lotEditName" type="text" class="w-full border rounded-lg p-2" />
            </div>
            <div>
                <label class="text-sm text-gray-700 block mb-1">Price (per hour)</label>
                <input id="lotEditPrice" type="number" min="0" step="0.01" class="w-full border rounded-lg p-2" />
            </div>
            <div>
                <label class="text-sm text-gray-700 block mb-1">Image URL (optional)</label>
                <input id="lotEditImageUrl" type="text" class="w-full border rounded-lg p-2" />
            </div>
            <div>
                <label class="text-sm text-gray-700 block mb-1">Upload image (optional)</label>
                <input id="lotEditImageFile" type="file" accept="image/*" class="w-full" />
            </div>
            <p class="text-xs text-gray-500">If you upload a file it overrides the URL.</p>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-3">
            <button class="dialog-button bg-gray-200 text-gray-900 shadow-none" onclick="closeLotEditModal()">Cancel</button>
            <button class="dialog-button" onclick="saveLotEdits()">Save</button>
        </div>
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
    animation: slideUp 0.3s ease;
    z-index: 10001;
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

.dialog-message {
    font-size: 16px;
    line-height: 1.6;
    color: #6b7280;
    margin: 0 0 24px 0;
    white-space: pre-line;
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
</body>
</html>
