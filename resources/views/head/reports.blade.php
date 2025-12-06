<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports</title>
  {{-- Ensure app.js and the component are loaded --}}
  @vite(['resources/js/app.js', 'resources/css/design.css'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    
  <input type="checkbox" id="sidebar-toggle">

  <div class="sidebar">
    <a href="javascript:void(0)" class="profile-info-link" data-bs-toggle="modal" data-bs-target="#headlibrarianProfileModal">
        <div class="profile-info text-center mb-4">
            <i class="fas fa-user-circle fa-3x mb-2"></i>
            <h2>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
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

  <div class="content flex-grow-1">

    <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
      <div class="d-flex align-items-center gap-2">
        <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
        <h3 class="mb-0 text-success">Report</h3> 
      </div>
      <div class="d-flex align-items-center gap-2">
        <div class="d-flex flex-column text-end">
          <span class="fw-bold text-success d-block">Library MS</span> 
          <small class="text-muted" style="font-size:0.85rem;">Management System</small>
        </div>
        <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" alt="DNSC Logo" style="height:50px;">
      </div>
    </div>
    
    {{-- Vue Root for Report Generation --}}
    <div id="app">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-success"></i> Generate Reports</h5> 
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                        <i class="fas fa-plus me-2"></i> Generate New Report
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
          <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm dashboard-card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <p class="text-muted small mb-1">Books Borrowed</p>
                    <h3 class="mb-0">{{ $booksBorrowed }}</h3>
                  </div>
                  <i class="fas fa-book text-info fs-3"></i> 
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
                  <i class="fas fa-book-reader text-success fs-3"></i>
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
        
        {{-- Report Results Display Area --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3"><i class="fas fa-table me-2 text-success"></i> Report Results</h5> 
                <div id="reportDisplayArea">
                    <p class="text-muted text-center py-4" v-if="!currentReportData">Generate a new report using the button above to view results here.</p>
                    
                    {{-- Dynamically render the report data if available --}}
                    <div v-else>
                        <div id="reportSummary" class="mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4"><strong>Report Type:</strong> @{{ currentReportData.report_type }}</div>
                                        <div class="col-md-4"><strong>Date Range:</strong> @{{ currentReportData.start_date || 'All Time' }} - @{{ currentReportData.end_date || 'All Time' }}</div>
                                        <div class="col-md-4"><strong>Total Records:</strong> @{{ currentReportData.total_records }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Date Created</th>
                                        <th>User</th>
                                        <th>Book</th>
                                        <th>Status</th>
                                        <th>Fine (₱)</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="record in currentReportData.data" :key="record.id">
                                        <td>@{{ record.date }}</td>
                                        <td>
                                            <div>@{{ record.user_name }}</div>
                                            <small class="text-muted">@{{ record.user_email }}</small>
                                        </td>
                                        <td>
                                            <div><strong>@{{ record.book_title }}</strong></div>
                                            <small class="text-muted">@{{ record.book_author }}</small>
                                        </td>
                                        <td>
                                            <span :class="getStatusBadgeClass(record.status, record.is_overdue)" class="badge">
                                                @{{ getStatusBadgeLabel(record.status, record.is_overdue) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span v-if="record.fine_amount > 0" class="text-danger fw-bold">₱@{{ record.fine_amount.toFixed(2) }}</span>
                                            <span v-else class="text-muted">₱0.00</span>
                                        </td>
                                        <td>@{{ record.due_date || '-' }}</td>
                                        <td>@{{ record.return_date || '-' }}</td>
                                    </tr>
                                    <tr v-if="!currentReportData.data.length">
                                        <td colspan="7" class="text-center text-muted py-4">No records found for the generated report.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="modal-footer justify-content-end border-0 pt-0">
                            <button type="button" class="btn btn-primary" @click="downloadReport">
                                <i class="fas fa-download me-2"></i> Download CSV
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Recent Activity Log (Static for now, but wrapped in its own card) --}}
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="fas fa-table me-2 text-primary"></i> Recent Activity Log (Last 10 Days)</h5> 
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Report Type</th>
                                        <th>Details</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentReports as $report)
                                        <tr>
                                            <td>{{ $report['date'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    match($report['type']) {
                                                        'Borrow Transactions' => 'primary',
                                                        'Return Reports' => 'success',
                                                        'New Requests' => 'warning',
                                                        default => 'secondary'
                                                    }
                                                }}">{{ $report['type'] }}</span>
                                            </td>
                                            <td>{!! $report['details'] !!}</td>
                                            <td>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <button class="btn btn-sm btn-outline-primary" disabled title="Details are above"><i class="fas fa-eye"></i></button>
                                                    <button class="btn btn-sm btn-outline-danger" disabled title="Download requires regeneration"><i class="fas fa-download"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No recent activity available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Vue Component Instance --}}
        <report-modal
            modal-id="generateReportModal"
            generate-url="{{ route('head.reports.generate') }}"
            @report-generated="handleReportGenerated"
        ></report-modal>

    </div> {{-- End Vue Root #app --}}


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // --- Vue Initialization and Data Handling ---
    const app = Vue.createApp({
        data() {
            return {
                currentReportData: null,
                // The recentReportsData is passed by the backend PHP but handled separately for simplicity
            };
        },
        methods: {
            handleReportGenerated(data) {
                // This method receives the report data from the Vue component
                this.currentReportData = data;
                
                // Show the main display area/modal (assuming you want to show the results in the main area)
                // If you prefer a modal, you'd integrate the modal show logic here, but using the main area is simpler.
            },
            getStatusBadgeClass(status, isOverdue) {
                // Matches the logic in the native JavaScript implementation
                if (status === 'pending') return 'bg-warning text-dark';
                if (status === 'approved') return 'bg-info';
                if (status === 'picked_up') return isOverdue ? 'bg-danger' : 'bg-primary';
                if (status === 'returned') return 'bg-success';
                if (status === 'rejected' || status === 'cancelled') return 'bg-secondary';
                return 'bg-secondary';
            },
            getStatusBadgeLabel(status, isOverdue) {
                if (status === 'picked_up') return isOverdue ? 'Overdue' : 'Picked Up';
                return status.charAt(0).toUpperCase() + status.slice(1);
            },
            convertToCSV(data) {
                const headers = ['Date Created', 'User Name', 'User Email', 'Book Title', 'Book Author', 'Status', 'Fine Amount (PHP)', 'Reservation Date', 'Due Date', 'Return Date', 'Is Overdue'];
                
                const rows = data.data.map(record => [
                    record.date,
                    record.user_name,
                    record.user_email,
                    record.book_title,
                    record.book_author,
                    record.status,
                    record.fine_amount,
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
            },
            downloadReport() {
                if (!this.currentReportData || !this.currentReportData.data || this.currentReportData.data.length === 0) {
                    alert('No data to download');
                    return;
                }

                const csv = this.convertToCSV(this.currentReportData);
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const filename = `${this.currentReportData.report_type.replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
                
                if (navigator.msSaveOrOpenBlob) {
                    navigator.msSaveOrOpenBlob(blob, filename);
                } else {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    a.style.display = 'none';
                    
                    document.body.appendChild(a);
                    a.click();
                    
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                }
            }
        },
        mounted() {
             // You can optionally trigger the modal here if needed
        }
    });

    // Assume ReportModal is globally available or imported in app.js
    // app.component('report-modal', ReportModal); // This must be done in app.js
    app.mount('#app');

  </script>
@include('head.partials.profile-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>