/**
 * ============================================
 * UNIVERSAL TABLE FILTER
 * ============================================
 * 
 * Adds search/filter functionality to any table on the page.
 * 
 * Usage:
 * 1. Add class "filterable-table" to your table
 * 2. Add data-filter-id="uniqueId" to your table
 * 3. Add a search input with data-filter-target="uniqueId"
 * 
 * Example:
 * <input type="text" class="form-control table-filter-input" data-filter-target="myTable" placeholder="Search...">
 * <table class="table filterable-table" data-filter-id="myTable">
 */

(function() {
    'use strict';

    /**
     * Initialize table filtering for all filterable tables
     */
    function initTableFilters() {
        // Find all column filter dropdowns
        const columnFilters = document.querySelectorAll('.table-column-filter');
        
        columnFilters.forEach(select => {
            const tableId = select.getAttribute('data-filter-table');
            const columnIndex = parseInt(select.getAttribute('data-filter-column'));
            
            if (!tableId || isNaN(columnIndex)) return;
            
            // Add event listener for dropdown filtering
            select.addEventListener('change', function() {
                filterByColumn(tableId, columnIndex, this.value);
            });
        });
    }

    /**
     * Filter table rows by specific column value
     * 
     * @param {string} tableId - The data-filter-id of the table
     * @param {number} columnIndex - The column index to filter (0-based)
     * @param {string} filterValue - The value to filter by (empty string shows all)
     */
    function filterByColumn(tableId, columnIndex, filterValue) {
        const table = document.querySelector(`table[data-filter-id="${tableId}"]`);
        if (!table) return;
        
        const tbody = table.querySelector('tbody');
        if (!tbody) return;
        
        const rows = tbody.querySelectorAll('tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            // Skip empty state rows
            if (row.classList.contains('table-empty-state')) {
                return;
            }
            
            const cell = row.cells[columnIndex];
            if (!cell) {
                row.style.display = 'none';
                return;
            }
            
            // Get cell text content (handles badges and nested elements)
            const cellText = cell.textContent.trim() || cell.innerText.trim();
            
            // If filter value is empty, show all rows
            if (filterValue === '' || filterValue === 'all') {
                row.style.display = '';
                visibleCount++;
            } else {
                // Check if cell text matches filter value
                if (cellText === filterValue || cellText.includes(filterValue)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
        });
        
        // Show/hide empty state message
        const emptyState = tbody.querySelector('.table-empty-state');
        if (emptyState) {
            if (visibleCount === 0 && filterValue !== '' && filterValue !== 'all') {
                emptyState.style.display = '';
                emptyState.querySelector('td').textContent = 'No results found for selected filter.';
            } else {
                emptyState.style.display = 'none';
            }
        }
        
        // Update result count if exists
        const resultCount = document.querySelector(`[data-filter-count="${tableId}"]`);
        if (resultCount) {
            resultCount.textContent = visibleCount + ' result(s)';
        }
    }

    /**
     * Add column-specific filter dropdowns
     * 
     * @param {string} tableId - The data-filter-id of the table
     * @param {number} columnIndex - The column index (0-based)
     * @param {string} filterId - Unique ID for this filter
     */
    function addColumnFilter(tableId, columnIndex, filterId) {
        const table = document.querySelector(`table[data-filter-id="${tableId}"]`);
        if (!table) return;
        
        const tbody = table.querySelector('tbody');
        if (!tbody) return;
        
        // Get unique values from the column
        const values = new Set();
        tbody.querySelectorAll('tr').forEach(row => {
            const cell = row.cells[columnIndex];
            if (cell) {
                const value = cell.textContent.trim();
                if (value) values.add(value);
            }
        });
        
        // Create filter dropdown
        const filterSelect = document.getElementById(filterId);
        if (filterSelect) {
            // Clear existing options except the first one
            while (filterSelect.options.length > 1) {
                filterSelect.remove(1);
            }
            
            // Add options
            Array.from(values).sort().forEach(value => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                filterSelect.appendChild(option);
            });
            
            // Add event listener
            filterSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const rows = tbody.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const cell = row.cells[columnIndex];
                    if (!cell) return;
                    
                    const cellValue = cell.textContent.trim();
                    if (selectedValue === '' || cellValue === selectedValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTableFilters);
    } else {
        initTableFilters();
    }

    // Export functions for manual use
    window.TableFilter = {
        filterByColumn: filterByColumn,
        addColumnFilter: addColumnFilter,
        init: initTableFilters
    };
})();

