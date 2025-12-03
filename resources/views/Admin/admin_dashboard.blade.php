<!DOCTYPE html>
<html lang="en">
<head>
  @php use Illuminate\Support\Facades\Auth; @endphp

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  @vite(['resources/css/design.css', 'resources/js/app.js'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<input type="checkbox" id="sidebar-toggle">

<!-- Sidebar -->
<div class="sidebar">
  <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#adminProfileModal">
  <div class="profile-info">
    <i class="fas fa-user-circle"></i>
    <div class="profile-text">
     <h2>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
    </div>
  </div>
  </a>

  <nav class="nav flex-column text-start">
    <a href="{{ url('/admin') }}" class="nav-link active">
      <i class="fas fa-chart-bar me-2"></i><span>Dashboard</span> 
    </a>
    <a href="{{ url('/admin/users') }}" class="nav-link">
      <i class="fas fa-users me-2"></i><span>Manage Users</span> 
    </a>
    <a href="{{ url('/admin/announcement') }}" class="nav-link">
      <i class="fas fa-bullhorn me-2"></i> <span>Announcements</span>
    </a>
    <a href="{{ url('/admin/reports') }}" class="nav-link">
      <i class="fas fa-file-alt me-2"></i> <span>Reports</span>
    </a>
    <a href="{{ url('/admin/books') }}" class="nav-link">
      <i class="fas fa-book-open me-2"></i> <span>Books</span>
    </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link fw-bold logoutLink">
    <i class="fas fa-sign-out-alt me-2"></i> <span>Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
    </form>
  </nav>
</div>

<div class="sidebar-overlay"></div>

<!-- Content -->
<div class="content flex-grow-1">
  <div class="top-header d-flex justify-content-between align-items-center mb-4 bg-white border-bottom">
    <div class="d-flex align-items-center gap-2">
      <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
      <h3 class="mb-0 fw-semibold text-success">Welcome Admin</h3>
    </div>
    
    <div class="d-flex align-items-center">
      <div class="text-end">
        <span class="fw-bold text-success d-block">Library MS</span>
        <small class="text-muted">Management System</small>
      </div>
      <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
    </div>
  </div>

  <!-- Dashboard Cards -->
  <div class="row g-4">
    <div class="col-md-6 col-lg-3">
      <div class="card border-0 shadow-sm stats-card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted small mb-1">Total Users</p>
            <h3 class="mb-2">{{ $totalUsers }}</h3>
            <div class="d-flex align-items-center">
              <span class="text-success small">+{{ $newUsersThisWeek }}</span>
              <span class="text-muted small ms-1">new this week</span>
            </div>
          </div>
          <div class="stats-icon">
            <i class="fas fa-users"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card border-0 shadow-sm stats-card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted small mb-1">Pending User Approvals</p>
            <h3 class="mb-2 text-warning">{{ $pendingApprovals }}</h3>
            <small class="text-muted">Awaiting review</small>
          </div>
          <div class="stats-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-clock"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card border-0 shadow-sm stats-card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted small mb-1">Total Books</p>
            <h3 class="mb-2">{{ $totalBooks }}</h3>
            <small class="text-muted">Available in library</small>
          </div>
          <div class="stats-icon">
            <i class="fas fa-book"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card border-0 shadow-sm stats-card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted small mb-1">Active Borrowers</p>
            <h3 class="mb-2">{{ $activeBorrowers }}</h3>
            <small class="text-muted">Currently borrowing</small>
          </div>
          <div class="stats-icon">
            <i class="fas fa-book-open"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card border-0 shadow-sm stats-card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted small mb-1">Overdue Books</p>
            <h3 class="mb-2 text-danger">{{ $overdueBooks }}</h3>
            <small class="text-muted">Needs attention</small>
          </div>
          <div class="stats-icon" style="background-color: rgba(220, 38, 38, 0.1); color: #dc2626;">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card border-0 shadow-sm stats-card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted small mb-1">System Reports</p>
            <h3 class="mb-2">{{ $totalReports }}</h3>
            <small class="text-muted">Reports generated</small>
          </div>
          <div class="stats-icon">
            <i class="fas fa-file-alt"></i>
      </div>
    </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-3">
      <div class="card border-0 shadow-sm stats-card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted small mb-1">Announcements</p>
            <h3 class="mb-2">{{ $activeAnnouncements }}</h3>
            <small class="text-muted">Last: {{ $lastAnnouncementDate }}</small>
          </div>
          <div class="stats-icon">
            <i class="fas fa-bullhorn"></i>
          </div>
      </div>
    </div>
  </div>
    <div class="col-md-6 col-lg-3">
      <div class="card border-0 shadow-sm stats-card">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <p class="text-muted small mb-1">E-Books</p>
            <h3 class="mb-2">{{ $totalEbooks }}</h3>
            <small class="text-muted">Available e-books</small>
          </div>
          <div class="stats-icon">
            <i class="fas fa-laptop"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions Section -->
  @if($pendingApprovals > 0)
  <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>{{ $pendingApprovals }} user(s)</strong> are waiting for approval.
      </div>
      <a href="{{ url('/admin/users') }}" class="btn btn-warning btn-sm">
        <i class="fas fa-check-circle me-1"></i>Review Now
      </a>
    </div>
  </div>
  @endif

  <!-- Analytics Section Header -->
  <div class="mt-5 mb-3">
    <h4 class="fw-semibold text-success">
      <i class="fas fa-chart-line me-2"></i>Analytics & Insights
    </h4>
    <p class="text-muted small mb-0">Comprehensive system performance metrics</p>
  </div>

  <!-- Charts Section -->
  <div class="row g-4">
    <!-- User Registration Chart -->
    <div class="col-12 col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-0 fw-semibold">
              <i class="fas fa-chart-line text-success me-2"></i>User Registration Trends
            </h5>
            <div class="btn-group btn-group-sm" role="group" aria-label="Time Range">
              <button type="button" class="btn btn-outline-success active" onclick="updateRegistrationChart('week', event)">Week</button>
              <button type="button" class="btn btn-outline-success" onclick="updateRegistrationChart('month', event)">Month</button>
              <button type="button" class="btn btn-outline-success" onclick="updateRegistrationChart('year', event)">Year</button>
            </div>
          </div>
        </div>
        <div class="card-body p-4">
          <canvas id="registrationChart" style="max-height: 300px;"></canvas>
        </div>
      </div>
    </div>

    <!-- User Role Distribution Chart -->
    <div class="col-12 col-lg-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="mb-0 fw-semibold">
            <i class="fas fa-users text-success me-2"></i>User Role Distribution
          </h5>
        </div>
        <div class="card-body p-4">
          <canvas id="roleDistributionChart" style="max-height: 250px;"></canvas>
        </div>
      </div>
    </div>

    <!-- Account Status Overview Chart -->
    <div class="col-12 col-lg-6">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="mb-0 fw-semibold">
            <i class="fas fa-user-check text-success me-2"></i>Account Status Overview
          </h5>
        </div>
        <div class="card-body p-4">
          <canvas id="accountStatusChart" style="max-height: 280px;"></canvas>
        </div>
      </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="col-12 col-lg-6">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="mb-0 fw-semibold">
            <i class="fas fa-history text-success me-2"></i>Recent Activity
          </h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
            <table class="table table-hover mb-0">
              <thead class="table-light sticky-top">
                <tr>
                  <th style="width: 40px;"></th>
                  <th>Activity</th>
                  <th>Status</th>
                  <th style="width: 120px;">Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentActivities as $activity)
                <tr>
                  <td class="text-center">
                    <i class="fas {{ $activity['icon'] }} {{ $activity['color'] }}"></i>
                  </td>
                  <td>
                    <div class="fw-semibold small">{{ $activity['title'] }}</div>
                    <div class="text-muted small">{{ Str::limit($activity['description'], 50) }}</div>
                  </td>
                  <td>
                    @if($activity['status'] === 'approved')
                      <span class="badge bg-success">Approved</span>
                    @elseif($activity['status'] === 'pending')
                      <span class="badge bg-warning">Pending</span>
                    @elseif($activity['status'] === 'rejected')
                      <span class="badge bg-danger">Rejected</span>
                    @elseif($activity['status'] === 'published')
                      <span class="badge bg-success">Published</span>
                    @elseif($activity['status'] === 'picked_up')
                      <span class="badge bg-primary">Borrowed</span>
                    @else
                      <span class="badge bg-secondary">{{ ucfirst($activity['status']) }}</span>
                    @endif
                  </td>
                  <td class="text-muted small">
                    {{ $activity['date']->format('M d, Y') }}<br>
                    <small>{{ $activity['date']->format('h:i A') }}</small>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted py-4">No recent activity</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  // Registration Chart Data
  const registrationChartData = {
    week: @json($usageWeek),
    month: @json($usageMonth),
    year: @json($usageYear)
  };

  // Role Distribution Data
  const roleDistributionData = @json($roleDistribution);
  const roleLabels = Object.keys(roleDistributionData);
  const roleCounts = Object.values(roleDistributionData);
  const roleColors = ['#047857', '#059669', '#10b981', '#34d399', '#6ee7b7'];

  // Account Status Data
  const accountStatusData = @json($accountStatusData);
  const statusLabels = Object.keys(accountStatusData);
  const statusCounts = Object.values(accountStatusData);
  const statusColors = {
    'approved': '#047857',
    'pending': '#f59e0b',
    'rejected': '#dc2626'
  };

  // ===== USER REGISTRATION CHART =====
  const registrationCtx = document.getElementById('registrationChart').getContext('2d');
  
  // Prepare week data
  const weekLabels = Object.keys(registrationChartData.week);
  const weekData = Object.values(registrationChartData.week);
  const maxRegValue = Math.max(...weekData, ...Object.values(registrationChartData.month), ...Object.values(registrationChartData.year), 10);

  let registrationChart = new Chart(registrationCtx, {
    type: 'line',
    data: {
      labels: weekLabels,
      datasets: [{
        label: 'User Registrations',
        data: weekData,
        borderColor: '#047857',
        backgroundColor: 'rgba(4, 120, 87, 0.2)',
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointBackgroundColor: '#047857',
        pointHoverRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      aspectRatio: 2,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: { size: 14, weight: 'bold' },
          bodyFont: { size: 13 }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          max: maxRegValue > 0 ? Math.ceil(maxRegValue * 1.2) : 10,
          ticks: { stepSize: maxRegValue > 50 ? 10 : 5 },
          grid: { color: 'rgba(0, 0, 0, 0.05)' }
        },
        x: { grid: { display: false } }
      }
    }
  });

  function updateRegistrationChart(period, event) {
    const periodData = registrationChartData[period];
    registrationChart.data.labels = Object.keys(periodData);
    registrationChart.data.datasets[0].data = Object.values(periodData);
    const periodMax = Math.max(...Object.values(periodData), 10);
    registrationChart.options.scales.y.max = periodMax > 0 ? Math.ceil(periodMax * 1.2) : 10;
    registrationChart.options.scales.y.ticks.stepSize = periodMax > 50 ? 10 : 5;
    registrationChart.update();
    const cardElement = event.target.closest('.card');
    if (cardElement) {
      cardElement.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
    }
    event.target.classList.add('active');
  }

  // ===== ROLE DISTRIBUTION CHART =====
  const roleCtx = document.getElementById('roleDistributionChart').getContext('2d');
  new Chart(roleCtx, {
    type: 'doughnut',
    data: {
      labels: roleLabels.map(role => role.charAt(0).toUpperCase() + role.slice(1)),
      datasets: [{
        data: roleCounts,
        backgroundColor: roleColors.slice(0, roleLabels.length),
        borderWidth: 2,
        borderColor: '#ffffff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      aspectRatio: 1,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 10,
            font: { size: 11 }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((context.parsed / total) * 100).toFixed(1);
              return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
            }
          }
        }
      }
    }
  });

  // ===== ACCOUNT STATUS CHART =====
  const statusCtx = document.getElementById('accountStatusChart').getContext('2d');
  new Chart(statusCtx, {
    type: 'bar',
    data: {
      labels: statusLabels.map(status => status.charAt(0).toUpperCase() + status.slice(1)),
      datasets: [{
        label: 'Users',
        data: statusCounts,
        backgroundColor: statusLabels.map(status => statusColors[status] || '#6b7280'),
        borderRadius: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      aspectRatio: 1.5,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Users: ' + context.parsed.y;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 },
          grid: { color: 'rgba(0, 0, 0, 0.05)' }
        },
        x: {
          grid: { display: false }
        }
      }
    }
  });
  </script>

@include('Admin.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
