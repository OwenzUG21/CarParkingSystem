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
<!-- ---------------- LOGIN OVERLAY ---------------- -->
<div id="loginScreen"
     class="fixed inset-0 flex flex-col items-center justify-center bg-black/90 z-50">
  <div class="bg-white/10 border border-white/20 rounded-xl p-8 w-80 text-center">
    <span class="material-symbols-outlined text-primary text-5xl mb-4">local_parking</span>
    <h1 class="text-2xl font-bold mb-4">ParkWise Admin</h1>
    <input id="adminPass" type="password"
           placeholder="Enter admin password"
           class="w-full rounded-lg border border-white/20 bg-white/5 p-2 text-center mb-4 focus:ring-primary focus:border-primary" />
    <button onclick="checkLogin()"
            class="w-full h-10 bg-primary rounded-lg font-semibold hover:bg-primary/80">Login</button>
    <p id="loginError" class="text-red-400 text-sm mt-2 hidden">Incorrect password</p>
  </div>
</div>

<!-- ---------------- DASHBOARD ---------------- -->
<div id="dashboard" class="hidden">
  <!-- Side nav -->
  <div class="flex min-h-screen">
    <aside class="w-64 bg-white/5 border-r border-white/10 p-4">
      <div class="flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined text-primary text-3xl">local_parking</span>
        <h2 class="text-xl font-bold">ParkWise</h2>
      </div>
      <nav class="flex flex-col gap-2">
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/20 text-primary">
          <span class="material-symbols-outlined">dashboard</span>Dashboard
        </a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/70 hover:bg-white/10">
          <span class="material-symbols-outlined">analytics</span>Analytics
        </a>
        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/70 hover:bg-white/10">
          <span class="material-symbols-outlined">settings</span>Settings
        </a>
      </nav>
      <div class="mt-auto border-t border-white/10 pt-4">
        <a onclick="logout()"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-white/70 hover:bg-white/10 cursor-pointer">
          <span class="material-symbols-outlined">logout</span>Logout
        </a>
      </div>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-8 flex flex-col gap-8">
      <!-- HEADER -->
      <div class="flex justify-between items-center flex-wrap gap-4">
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

      <!-- BOOKINGS TABLE -->
      <div class="rounded-xl bg-white/5 border border-white/10 p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-semibold">Current Bookings</h2>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
/* -------- LOGIN ---------- */
function checkLogin(){
  const pass = document.getElementById("adminPass").value;
  if(pass === "admin"){
    document.getElementById("loginScreen").classList.add("hidden");
    document.getElementById("dashboard").classList.remove("hidden");
    loadData();
  }else{
    document.getElementById("loginError").classList.remove("hidden");
  }
}
function logout(){
  document.getElementById("dashboard").classList.add("hidden");
  document.getElementById("loginScreen").classList.remove("hidden");
}

/* -------- MOCK DATA ---------- */
let bookings = [
  {spot:"A-05", vehicle:"LUV-456", arrival:"10:32 AM", left:"1h 25m", amount:5.5},
  {spot:"B-12", vehicle:"XYZ-789", arrival:"11:15 AM", left:"45m", amount:3.0},
  {spot:"C-01", vehicle:"PARK-IT", arrival:"12:01 PM", left:"15m", amount:2.0},
  {spot:"A-08", vehicle:"FAST-24", arrival:"12:30 PM", left:"2h 10m", amount:6.5}
];
let hourlyRate = 2.5;

/* -------- LOAD DATA ---------- */
function loadData(){
  document.getElementById("activeBookings").innerText = bookings.length;
  const total = bookings.reduce((s,b)=>s+b.amount,0);
  document.getElementById("revenue").innerText = "UGX "+total.toFixed(2);
  document.getElementById("occupancy").innerText = "85%";
  document.getElementById("avgDuration").innerText = "2.5h";

  const table = document.getElementById("bookingTable");
  table.innerHTML = bookings.map(b=>`
    <tr class="border-b border-white/10 hover:bg-white/5 text-sm">
      <td class="p-2">${b.spot}</td>
      <td class="p-2">${b.vehicle}</td>
      <td class="p-2 text-white/70">${b.arrival}</td>
      <td class="p-2 text-white/70">${b.left}</td>
      <td class="p-2 text-right">UGX ${b.amount.toFixed(2)}</td>
    </tr>`).join("");

  drawChart();
}

/* -------- CHART ---------- */
function drawChart(){
  const ctx=document.getElementById("chartBookings");
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
