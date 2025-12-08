<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head Librarian Reports Dashboard</title>

    {{-- Asset Loading: Load CSS and main JavaScript bundle --}}
    @vite(['resources/js/app.js', 'resources/css/design.css'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>

<input type="checkbox" id="sidebar-toggle">

{{-- START SIDEBAR NAVIGATION: Head Librarian --}}
<div class="sidebar">
    {{-- CORRECTED PROFILE MODAL TARGET --}}
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
{{-- END SIDEBAR NAVIGATION --}}

<div class="sidebar-overlay"></div>

{{-- START PAGE CONTENT --}}
<div class="content flex-grow-1">

    {{-- Header --}}
    <div class="top-header d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <div class="d-flex align-items-center gap-2">
            <label for="sidebar-toggle" class="toggle-btn d-lg-none">☰</label>
            <h3 class="mb-0 text-success">Report</h3> 
        </div>
        <div class="d-flex align-items-center">
            <div class="text-end">
                <span class="fw-bold text-success d-block">Library MS</span> 
                <small class="text-muted">Management System</small>
            </div>
            <img src="{{ Vite::asset('resources/images/dnsc_logo.png') }}" style="height:50px;">
        </div>
    </div>

    {{-- Report Generation Button --}}
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

    {{-- Stats Cards (Passed from Controller) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Books</p>
                            <h3 class="mb-0">{{ $totalBooks ?? 0 }}</h3>
                        </div>
                       <div class="stats-icon"> <i class="fas fa-book text-primary fs-3"></i> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Students</p>
                            <h3 class="mb-0">{{ $totalStudents ?? 0 }}</h3>
                        </div>
                     <div class="stats-icon">   <i class="fas fa-user-graduate text-info fs-3"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Active Reservations</p>
                            <h3 class="mb-0">{{ $activeReservations ?? 0 }}</h3>
                        </div>
                     <div class="stats-icon"> <i class="fas fa-bookmark text-warning fs-3"></i></div>  
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total E-Books</p>
                            <h3 class="mb-0">{{ $totalEbooks ?? 0 }}</h3>
                        </div>
                      <div class="stats-icon"> <i class="fas fa-laptop text-success fs-3"></i></div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Reports Log --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-table me-2 text-primary"></i> Recent Activity Log
                </h5>
                
                <select class="form-select form-select-sm table-column-filter" 
                        data-filter-table="recentActivityTable" 
                        data-filter-column="1" 
                        style="max-width: 200px;">
                    <option value="">All Activity</option>
                    <option value="Borrow Transactions">Borrow Transactions</option>
                    <option value="Return Reports">Return Reports</option>
                    <option value="Penalty Summary">Penalty Summary</option>
                    <option value="User Activity">User Activity</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" data-filter-id="recentActivityTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 20%;">Report Type</th>
                            <th style="width: 35%;">Details</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($recentReports ?? [] as $report)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($report['date'])->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $reportType = $report['type'];
                                        $badgeClass = match($reportType) {
                                            'Borrow Transactions' => 'bg-primary',
                                            'Return Reports' => 'bg-success',
                                            'Penalty Summary' => 'bg-danger',
                                            'User Activity' => 'bg-info',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $reportType }}</span>
                                </td>
                                <td>
                                    {{ Illuminate\Support\Str::limit($report['details'], 70) }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4 table-empty-state">No recent report generation available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>  
            </div>
        </div>
    </div>
</div>
{{-- END PAGE CONTENT --}}


{{-- START GENERATE REPORT MODAL (Native JS/AJAX) --}}
<div class="modal fade" id="generateReportModal" tabindex="-1" aria-labelledby="generateReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="generateReportModalLabel">
                    <i class="fas fa-chart-line me-2"></i> Configure Report
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="generateReportForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reportType" class="form-label">Report Type <span class="text-danger">*</span></label>
                        <select 
                            class="form-select"
                            id="reportType" 
                            name="report_type"
                            required
                        >
                            <option value="">Select Report Type</option>
                            <option value="borrow">Borrow Transactions</option>
                            <option value="return">Return Reports</option>
                            <option value="penalty">Penalty Summary</option>
                            <option value="user">User Activity</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input 
                                type="date" 
                                name="start_date"
                                class="form-control" 
                                id="startDate"
                            />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input 
                                type="date" 
                                name="end_date"
                                class="form-control" 
                                id="endDate"
                            />
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Leave dates empty to generate report for all time.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitReportBtn">
                        <i class="fas fa-download me-2"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- END GENERATE REPORT MODAL --}}


{{-- Report Results Modal (Acts as the Preview before CSV download) --}}
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

                {{-- FILTER AND TABLE CONTAINER --}}
                <div class="d-flex justify-content-end mb-3">
                    <select class="form-select form-select-sm" id="reportStatusFilter" style="max-width: 200px;">
                        <option value="">Show All Statuses</option>
                        <option value="Overdue">Overdue</option>
                        <option value="Picked Up">Picked Up (Active)</option>
                        <option value="Approved">Approved</option>
                        <option value="Pending">Pending</option>
                        <option value="Returned">Returned</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover align-middle" id="reportResultsTable">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Book</th>
                                <th>Status</th>
                                <th>Fine (₱)</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                            </tr>
                        </thead>
                        <tbody id="reportResultsBody">
                            </tbody>
                    </table>
                </div>
                {{-- END FILTER AND TABLE CONTAINER --}}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close Preview</button>
                <button type="button" class="btn btn-primary" id="downloadReportBtn">
                    <i class="fas fa-download me-2"></i> Confirm Download CSV
                </button>
            </div>
        </div>
    </div>
</div>

{{-- NOTE: Include the appropriate profile modal (e.g., head.partials.profile-modal) --}}
{{-- @include('head.partials.profile-modal') --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- START NATIVE JAVASCRIPT/AJAX LOGIC --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateForm = document.getElementById('generateReportForm');
        const generateFormModal = new bootstrap.Modal(document.getElementById('generateReportModal'));
        const reportResultsModal = new bootstrap.Modal(document.getElementById('reportResultsModal'));
        let currentReportData = null; // Global storage for downloaded data

        // ============================================
        // 1. AJAX FORM SUBMISSION HANDLER
        // ============================================
        generateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(generateForm);
            const submitBtn = document.getElementById('submitReportBtn');
            const originalText = submitBtn.innerHTML;
            
            // Disable button and show loader
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating...';

            // IMPORTANT: Use the HEAD Librarian route
            fetch('{{ route("head.reports.generate") }}', { 
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    // Check if response is not JSON (e.g., HTML error page)
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") === -1) {
                        throw new Error(`Server returned status ${response.status}. Check PHP logs.`);
                    }
                    
                    return response.json().then(errorData => {
                        // Throw Laravel validation/server error message
                        throw new Error(errorData.message || 'Server error occurred.');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    currentReportData = data;
                    displayReportResults(data);
                    
                    // Reset filter to show all status upon new report generation
                    document.getElementById('reportStatusFilter').value = "";
                    
                    generateFormModal.hide();
                    reportResultsModal.show();
                } else {
                    alert('Error generating report: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                // Display specific message based on common failure types
                if (error.message.includes("Check PHP logs")) {
                     alert("Fatal PHP Error detected. Please check storage/logs/laravel.log.");
                } else {
                     alert('Failed to generate report: ' + error.message);
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // ============================================
        // 2. DISPLAY FUNCTIONS (PREVIEW)
        // ============================================
        function displayReportResults(data) {
            const summary = document.getElementById('reportSummary');
            const tbody = document.getElementById('reportResultsBody');
            const modalTitle = document.getElementById('reportResultsModalLabel');

            modalTitle.innerHTML = `<i class="fas fa-table me-2"></i> ${data.report_type}`;

            // 1. Render Summary Card
            summary.innerHTML = `
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4"><strong>Report Type:</strong> ${data.report_type}</div>
                            <div class="col-md-4"><strong>Date Range:</strong> ${data.start_date || 'All Time'} - ${data.end_date || 'All Time'}</div>
                            <div class="col-md-4"><strong>Total Records:</strong> ${data.total_records}</div>
                        </div>
                    </div>
                </div>
            `;

            // 2. Render Table Rows
            tbody.innerHTML = '';

            if (data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No records found for the selected criteria.</td></tr>';
                return;
            }

            data.data.forEach(record => {
                const row = document.createElement('tr');
                // Use lowercase status for data-status attribute
                const statusLower = record.status ? record.status.toLowerCase().replace(/\s+/g, '_') : 'unknown';
                
                row.setAttribute('data-status', statusLower); 
                row.setAttribute('data-overdue', record.is_overdue ? 'true' : 'false');
                
                const statusBadge = getStatusBadge(record.status, record.is_overdue);
                
                const fineDisplay = record.fine_amount && parseFloat(record.fine_amount) > 0 
                    ? `<span class="text-danger fw-bold">₱${parseFloat(record.fine_amount).toFixed(2)}</span>` 
                    : `<span class="text-muted">₱0.00</span>`;
                    
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
                    <td>${fineDisplay}</td>
                    <td>${record.due_date || '-'}</td>
                    <td>${record.return_date || '-'}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function getStatusBadge(status, isOverdue) {
            const badges = {
                'pending': '<span class="badge bg-warning text-dark">Pending</span>',
                'approved': '<span class="badge bg-info">Approved</span>',
                'picked_up': isOverdue ? '<span class="badge bg-danger">Overdue</span>' : '<span class="badge bg-primary">Picked Up</span>',
                'returned': '<span class="badge bg-success">Returned</span>',
                'rejected': '<span class="badge bg-danger">Rejected</span>',
                'cancelled': '<span class="badge bg-secondary">Cancelled</span>',
            };
            // Use status from DB if known, otherwise try to guess from the text (though DB status is preferred)
            const statusKey = status ? status.toLowerCase().replace(/\s+/g, '_') : 'unknown';
            return badges[statusKey] || `<span class="badge bg-secondary">${status}</span>`;
        }


        // ============================================
        // 3. FILTERING LOGIC
        // ============================================

        /**
         * Applies filter to hide/show table rows based on the selected status.
         */
        function applyStatusFilter() {
            const filterSelect = document.getElementById('reportStatusFilter');
            const filterValue = filterSelect.value.toLowerCase().replace(/\s+/g, '_');
            const tbody = document.getElementById('reportResultsBody');
            
            tbody.querySelectorAll('tr').forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const isOverdue = row.getAttribute('data-overdue') === 'true';
                
                let match = false;
                
                if (filterValue === "" || filterValue === "show_all_statuses") {
                    match = true; // Show all
                } else if (filterValue === "overdue" && isOverdue) {
                    match = true;
                } else if (filterValue !== "overdue" && rowStatus === filterValue) {
                    match = true;
                }

                row.style.display = match ? '' : 'none';
            });
        }

        // Attach filter event listener
        document.getElementById('reportStatusFilter').addEventListener('change', applyStatusFilter);


        // ============================================
        // 4. CSV DOWNLOAD LOGIC
        // ============================================

        function convertToCSV(data) {
            const headers = ['Date', 'User Name', 'User Email', 'Book Title', 'Book Author', 'Status', 'Fine Amount (PHP)', 'Reservation Date', 'Due Date', 'Return Date', 'Is Overdue'];
            
            const rows = data.data.map(record => [
                record.date,
                record.user_name,
                record.user_email,
                record.book_title,
                record.book_author,
                record.status,
                record.fine_amount || '0.00', 
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

        // Download CSV Listener (Attached to the "Confirm Download CSV" button)
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
    });
</script>
{{-- END NATIVE JAVASCRIPT/AJAX LOGIC --}}

</body>
</html>