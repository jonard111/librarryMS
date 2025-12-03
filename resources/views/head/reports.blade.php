<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports</title>
  @vite(['resources/js/app.js', 'resources/css/design.css'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  </head>
<body>
    
  <input type="checkbox" id="sidebar-toggle">

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#headlibrarianProfileModal">
        <div class="profile-info text-center mb-4">
            <i class="fas fa-user-circle fa-3x mb-2"></i>
            <h2 class="mt-2">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        </div>
    </a>
    <nav class="nav flex-column text-start">
      <a href="{{ route('head.dashboard') }}" class="nav-link">
        <i class="fas fa-chart-bar me-2"></i> <span>Dashboard</span>
      </a>
      <a href="{{ route('head.studentRecord') }}" class="nav-link">
        <i class="fas fa-users me-2"></i> <span>Student Record</span>
      </a>
      <a href="{{ route('head.announcement') }}" class="nav-link">
        <i class="fas fa-bullhorn me-2"></i> <span>Announcements</span>
      </a>
      <a href="{{ route('head.reports') }}" class="nav-link active">
        <i class="fas fa-file-alt me-2"></i> <span>Reports</span>
      </a>
      <a href="{{ route('head.books') }}" class="nav-link">
        <i class="fas fa-book-open me-2"></i> <span>Books</span>
      </a>
      <a href="{{ route('head.reservation') }}" class="nav-link">
        <i class="fas fa-bookmark me-2"></i> <span>Reservation</span>
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

    <!-- Top Header -->
    <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
      <div class="d-flex align-items-center gap-2">
        <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
        <h3 class="mb-0">Report</h3>
      </div>
      <div class="d-flex align-items-center gap-2">
        <div class="d-flex flex-column text-end">
          <span class="fw-bold text-success d-block">Library MS</span>
          <small class="text-muted" style="font-size:0.85rem;">Management System</small>
        </div>
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
      </div>
    </div>

    <!-- Generate Reports Card -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-transparent border-0">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-success"></i> Generate Reports</h5>
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#generateReportModal">
            <i class="fas fa-plus me-2"></i> Generate New Report
          </button>
        </div>
      </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm dashboard-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted small mb-1">Books Borrowed</p>
                <h3 class="mb-0">{{ $booksBorrowed }}</h3>
              </div>
              <i class="fas fa-book text-success fs-3"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm dashboard-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted small mb-1">Books Returned</p>
                <h3 class="mb-0">{{ $booksReturned }}</h3>
              </div>
              <i class="fas fa-book-reader text-primary fs-3"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm dashboard-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted small mb-1">Penalties Issued</p>
                <h3 class="mb-0">{{ $penaltiesIssued }}</h3>
              </div>
              <i class="fas fa-exclamation-triangle text-warning fs-3"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm dashboard-card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted small mb-1">Active Borrowers</p>
                <h3 class="mb-0">{{ $activeBorrowers }}</h3>
              </div>
              <i class="fas fa-user-graduate text-info fs-3"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Report Results Table -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3"><i class="fas fa-table me-2 text-success"></i> Report Results</h5>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Report Type</th>
                <th>Details</th>
                <th>Generated By</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentReports as $report)
                <tr>
                  <td>{{ $report['date'] }}</td>
                  <td>{{ $report['type'] }}</td>
                  <td>{{ $report['details'] }}</td>
                  <td>{{ $report['generated_by'] }}</td>
                  <td>
                    <div class="d-flex gap-2 flex-wrap">
                      <button class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-danger" title="Download"><i class="fas fa-download"></i></button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">No reports available.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>



  </div>

  <!-- Generate Report Modal - Vue Component -->
<div id="app">
    <report-modal
        generate-url="{{ url('/head/reports/generate') }}"
        modal-id="generateReportModal"
    ></report-modal>
</div>

  <!-- Report Results Modal -->
  <div class="modal fade" id="reportResultsModal" tabindex="-1" aria-labelledby="reportResultsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="reportResultsModalLabel">
            <i class="fas fa-table me-2"></i> Report Results
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="reportSummary" class="mb-3"></div>
          <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-hover align-middle" id="reportResultsTable">
              <thead class="table-light sticky-top">
                <tr>
                  <th>Date</th>
                  <th>User</th>
                  <th>Book</th>
                  <th>Status</th>
                  <th>Due Date</th>
                  <th>Return Date</th>
                </tr>
              </thead>
              <tbody id="reportResultsBody">
                <!-- Results will be populated here -->
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" id="downloadReportBtn">
            <i class="fas fa-download me-2"></i> Download CSV
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const generateForm = document.getElementById('generateReportForm');
      const reportResultsModal = new bootstrap.Modal(document.getElementById('reportResultsModal'));
      let currentReportData = null;

      generateForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(generateForm);
        const submitBtn = generateForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating...';

        fetch('{{ route("head.reports.generate") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            currentReportData = data;
            displayReportResults(data);
            bootstrap.Modal.getInstance(document.getElementById('generateReportModal')).hide();
            reportResultsModal.show();
          } else {
            alert('Error generating report: ' + (data.message || 'Unknown error'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error generating report. Please try again.');
        })
        .finally(() => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        });
      });

      function displayReportResults(data) {
        const summary = document.getElementById('reportSummary');
        const tbody = document.getElementById('reportResultsBody');
        const modalTitle = document.getElementById('reportResultsModalLabel');

        // Update modal title
        modalTitle.innerHTML = `<i class="fas fa-table me-2"></i> ${data.report_type}`;

        // Display summary
        summary.innerHTML = `
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <strong>Report Type:</strong> ${data.report_type}
                </div>
                <div class="col-md-4">
                  <strong>Date Range:</strong> ${data.start_date || 'All Time'} - ${data.end_date || 'All Time'}
                </div>
                <div class="col-md-4">
                  <strong>Total Records:</strong> ${data.total_records}
                </div>
              </div>
            </div>
          </div>
        `;

        // Clear previous results
        tbody.innerHTML = '';

        if (data.data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No records found for the selected criteria.</td></tr>';
          return;
        }

        // Populate table
        data.data.forEach(record => {
          const row = document.createElement('tr');
          const statusBadge = getStatusBadge(record.status, record.is_overdue);
          row.innerHTML = `
            <td>${record.date}</td>
            <td>
              <div>${record.user_name}</div>
              <small class="text-muted">${record.user_email}</small>
            </td>
            <td>
              <div><strong>${record.book_title}</strong></div>
              <small class="text-muted">${record.book_author}</small>
            </td>
            <td>${statusBadge}</td>
            <td>${record.due_date || '-'}</td>
            <td>${record.return_date || '-'}</td>
          `;
          tbody.appendChild(row);
        });
      }

      function getStatusBadge(status, isOverdue) {
        const badges = {
          'pending': '<span class="badge bg-warning">Pending</span>',
          'approved': '<span class="badge bg-info">Approved</span>',
          'picked_up': isOverdue ? '<span class="badge bg-danger">Overdue</span>' : '<span class="badge bg-primary">Picked Up</span>',
          'returned': '<span class="badge bg-success">Returned</span>',
          'rejected': '<span class="badge bg-danger">Rejected</span>',
          'cancelled': '<span class="badge bg-secondary">Cancelled</span>',
        };
        return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
      }

      // Download CSV functionality
      document.getElementById('downloadReportBtn').addEventListener('click', function() {
        if (!currentReportData || !currentReportData.data || currentReportData.data.length === 0) {
          alert('No data to download');
          return;
        }

        const csv = convertToCSV(currentReportData);
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${currentReportData.report_type.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
      });

      function convertToCSV(data) {
        const headers = ['Date', 'User Name', 'User Email', 'Book Title', 'Book Author', 'Status', 'Reservation Date', 'Due Date', 'Return Date', 'Is Overdue'];
        const rows = data.data.map(record => [
          record.date,
          record.user_name,
          record.user_email,
          record.book_title,
          record.book_author,
          record.status,
          record.reservation_date || '',
          record.due_date || '',
          record.return_date || '',
          record.is_overdue ? 'Yes' : 'No'
        ]);

        const csvContent = [
          headers.join(','),
          ...rows.map(row => row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(','))
        ].join('\n');

        return csvContent;
      }
    });
  </script>
@include('head.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
