<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head Librarian Dashboard</title>
    @vite(['resources/js/app.js', 'resources/css/design.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
    <input type="checkbox" id="sidebar-toggle">

    <div class="sidebar">
        <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#headlibrarianProfileModal">
            <div class="profile-info">
                <i class="fas fa-user-circle"></i>
                <div class="profile-text">
                    <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
                </div>
            </div>
        </a>

        <nav class="nav flex-column text-start">
            <a href="{{ route('head.dashboard') }}" class="nav-link active">
                <i class="fas fa-chart-bar me-2"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('head.studentRecord') }}" class="nav-link">
                <i class="fas fa-users me-2"></i> <span>Student Record</span>
            </a>
            <a href="{{ route('head.announcement') }}" class="nav-link">
                <i class="fas fa-bullhorn me-2"></i><span>Announcements</span>
            </a>
            <a href="{{ route('head.reports') }}" class="nav-link">
                <i class="fas fa-file-alt me-2"></i> <span>Reports</span>
            </a>
            <a href="{{ route('head.books') }}" class="nav-link">
                <i class="fas fa-book-open me-2"></i><span>Books</span>
            </a>
            <a href="{{ route('head.reservation') }}" class="nav-link">
                <i class="fas fa-bookmark me-2"></i><span>Reservation</span>
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

    <div class="content flex-grow-1">

        <div class="top-header d-flex justify-content-between align-items-center mb-4 bg-white border-bottom">
            <div class="d-flex align-items-center gap-2">
                <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
                <h3 class="mb-0 fw-semibold text-success">Welcome Head Librarian</h3>
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
                            <p class="text-muted small mb-1">Student Records</p>
                            <h3 class="mb-2">{{ $totalStudents }}</h3>
                            <small class="text-muted">Manage and update students</small>
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
                            <p class="text-muted small mb-1">Active Reservations</p>
                            <h3 class="mb-2">{{ $activeReservations }}</h3>
                            <small class="text-muted">Track borrowing history</small>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-bookmark"></i>
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
                            <small class="text-muted">Access library e-books</small>
                        </div>
                        <div class="stats-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Analytics Section Header -->
        <div class="mt-5 mb-3">
            <h4 class="fw-semibold text-success">
                <i class="fas fa-chart-line me-2"></i>Analytics & Insights
            </h4>
            <p class="text-muted small mb-0">Comprehensive library performance metrics</p>
        </div>

        <!-- Charts Section -->
        <div class="row g-4">
            <!-- Row 1: Main Charts -->
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-chart-line text-success me-2"></i>Borrow Overview
                            </h5>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Time Range">
                                <button type="button" class="btn btn-outline-success active" onclick="updateBorrowChart('week', event)">Week</button>
                                <button type="button" class="btn btn-outline-success" onclick="updateBorrowChart('month', event)">Month</button>
                                <button type="button" class="btn btn-outline-success" onclick="updateBorrowChart('year', event)">Year</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="borrowChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-clock text-success me-2"></i>Return Rate
                        </h5>
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="flex-grow-1">
                            <canvas id="returnRateChart" style="max-height: 220px;"></canvas>
                        </div>
                        <div class="mt-3 pt-3 border-top text-center">
                            <h3 class="text-success mb-1 fw-bold">{{ $returnRateData['returnRate'] }}%</h3>
                            <small class="text-muted">On-Time Return Rate</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Distribution Charts -->
            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-bookmark text-success me-2"></i>Book Category Distribution
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="categoryChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-users text-success me-2"></i>User Activity (Last 6 Months)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="userActivityChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Row 3: Popular Books -->
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-star text-success me-2"></i>Most Popular Books (Top 10)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="popularBooksChart" style="max-height: 200px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from database
const borrowChartData = {
  week: {
    labels: @json($weekData['labels']),
    data: @json($weekData['data'])
  },
  month: {
    labels: @json($monthData['labels']),
    data: @json($monthData['data'])
  },
  year: {
    labels: @json($yearData['labels']),
    data: @json($yearData['data'])
  }
};

const returnRateData = @json($returnRateData);
const categoryData = @json($categoryData);
const userActivityData = @json($userActivityData);
const popularBooksData = @json($popularBooksData);

// ===== BORROW OVERVIEW CHART =====
const borrowCtx = document.getElementById('borrowChart').getContext('2d');
const maxBorrowValue = Math.max(
  ...borrowChartData.week.data,
  ...borrowChartData.month.data,
  ...borrowChartData.year.data,
  10
);

let borrowChart = new Chart(borrowCtx, {
  type: 'line',
  data: {
    labels: borrowChartData.week.labels,
    datasets: [{
      label: 'Borrow Transactions',
      data: borrowChartData.week.data,
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
        max: maxBorrowValue > 0 ? Math.ceil(maxBorrowValue * 1.2) : 10,
        ticks: { stepSize: maxBorrowValue > 50 ? 10 : 5 },
        grid: { color: 'rgba(0, 0, 0, 0.05)' }
      },
      x: { grid: { display: false } }
    }
  }
});

function updateBorrowChart(period, event) {
  borrowChart.data.labels = borrowChartData[period].labels;
  borrowChart.data.datasets[0].data = borrowChartData[period].data;
  const periodMax = Math.max(...borrowChartData[period].data, 10);
  borrowChart.options.scales.y.max = periodMax > 0 ? Math.ceil(periodMax * 1.2) : 10;
  borrowChart.options.scales.y.ticks.stepSize = periodMax > 50 ? 10 : 5;
  borrowChart.update();
  const cardElement = event.target.closest('.card');
  if (cardElement) {
    cardElement.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
  }
  event.target.classList.add('active');
}

// ===== RETURN RATE CHART =====
const returnRateCtx = document.getElementById('returnRateChart').getContext('2d');
new Chart(returnRateCtx, {
  type: 'doughnut',
  data: {
    labels: ['On-Time Returns', 'Overdue Returns', 'Currently Overdue'],
    datasets: [{
      data: [
        returnRateData.onTime,
        returnRateData.overdue,
        returnRateData.currentOverdue
      ],
      backgroundColor: [
        '#047857',
        '#dc2626',
        '#f59e0b'
      ],
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
            return context.label + ': ' + context.parsed;
          }
        }
      }
    }
  }
});

// ===== CATEGORY DISTRIBUTION CHART =====
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
  type: 'doughnut',
  data: {
    labels: categoryData.labels,
    datasets: [{
      data: categoryData.data,
      backgroundColor: categoryData.colors,
      borderWidth: 2,
      borderColor: '#ffffff'
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    aspectRatio: 1.5,
    plugins: {
      legend: {
        position: 'right',
        labels: {
          padding: 10,
          font: { size: 11 },
          boxWidth: 12
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

// ===== USER ACTIVITY CHART =====
const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
new Chart(userActivityCtx, {
  type: 'bar',
  data: {
    labels: userActivityData.labels,
    datasets: [
      {
        label: 'Students',
        data: userActivityData.student,
        backgroundColor: '#047857',
        borderRadius: 4
      },
      {
        label: 'Faculty',
        data: userActivityData.faculty,
        backgroundColor: '#f59e0b',
        borderRadius: 4
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    aspectRatio: 1.5,
    plugins: {
      legend: {
        position: 'top',
        labels: { padding: 10, font: { size: 12 } }
      },
      tooltip: {
        mode: 'index',
        intersect: false
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

// ===== POPULAR BOOKS CHART =====
const popularBooksCtx = document.getElementById('popularBooksChart').getContext('2d');
new Chart(popularBooksCtx, {
  type: 'bar',
  data: {
    labels: popularBooksData.labels,
    datasets: [{
      label: 'Borrow Count',
      data: popularBooksData.data,
      backgroundColor: '#047857',
      borderRadius: 4
    }]
  },
  options: {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    aspectRatio: 3,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function(context) {
            return 'Borrowed: ' + context.parsed.x + ' times';
          }
        }
      }
    },
    scales: {
      x: {
        beginAtZero: true,
        ticks: { stepSize: 1 },
        grid: { color: 'rgba(0, 0, 0, 0.05)' }
      },
      y: {
        grid: { display: false }
      }
    }
  }
});
</script>

@include('head.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
