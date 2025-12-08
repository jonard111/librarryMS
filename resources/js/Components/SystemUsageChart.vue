<script setup>
import { ref, onMounted, watch, computed, nextTick } from 'vue';
// Import only the necessary components from chart.js/auto for efficiency
import { Chart, registerables } from 'chart.js';

// Register all chart components explicitly
Chart.register(...registerables);

const props = defineProps({
  // Data structure is assumed to be: { label: count, ... }
  week: { type: Object, required: true },
  month: { type: Object, required: true },
  year: { type: Object, required: true },
  // Optional: Add a prop for the chart's main title
  chartTitle: { type: String, default: 'New System Users Registration' },
  // Optional: Add a prop for the metric label
  metricLabel: { type: String, default: 'New Users' }
});

const currentPeriod = ref('week');
const chartRef = ref(null);
let chart = null;

// Compute current dataset
const currentData = computed(() => {
  const data = props[currentPeriod.value] || {};
  // Ensure an object is always returned, even if empty
  return data;
});

// Compute total users for the current period (for display/context)
const totalUsers = computed(() => {
  return Object.values(currentData.value).reduce((sum, count) => sum + count, 0);
});

// Draw or update chart
const drawChart = async () => {
  // Wait for the DOM to update, ensuring chartRef.value exists
  await nextTick();
  if (!chartRef.value) return;

  const data = currentData.value;
  const labels = Object.keys(data);
  const values = Object.values(data);
  const ctx = chartRef.value.getContext('2d');

  if (chart) chart.destroy();

  // Condition to check for empty data and avoid drawing the chart
  if (labels.length === 0 || totalUsers.value === 0) {
    // We destroy the chart if it exists, and let the template handle the empty message.
    return;
  }

  chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: props.metricLabel,
          data: values,
          // Enhanced color variables for better maintainability
          borderColor: '#10B981', // Tailwind green-500/600 equivalent
          backgroundColor: 'rgba(16, 185, 129, 0.4)', // Slightly stronger fill
          fill: true,
          tension: 0.3,
          pointRadius: 5,
          pointHoverRadius: 7,
          pointBackgroundColor: '#059669', // Darker point color
          pointHitRadius: 10, // Increased hit area for touch devices
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { 
        padding: { top: 10, right: 10, bottom: 20, left: 10 } 
      },
      plugins: {
        legend: { display: false },
        tooltip: { 
          mode: 'index', 
          intersect: false,
          // Customizing tooltip title based on the period (e.g., 'Monday', 'Week 4', 'Jan')
          callbacks: {
            title: (tooltipItems) => {
              const label = tooltipItems[0].label;
              const unitMap = {
                'week': 'day',
                'month': 'week',
                'year': 'month'
              };
              const unit = unitMap[currentPeriod.value];
              return `${unit.charAt(0).toUpperCase() + unit.slice(1)}: ${label}`;
            }
          }
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: props.metricLabel, // Use prop for dynamic Y-axis title
            color: '#374151'
          },
          ticks: {
            stepSize: 1,
            precision: 0,
            color: '#6B7280',
          },
          grid: {
            drawBorder: false,
            color: 'rgba(0,0,0,0.05)',
          }
        },
        x: {
          grid: { display: false },
          ticks: {
            color: '#6B7280',
            // Increase rotation slightly for longer labels (e.g., month names)
            maxRotation: labels.length > 7 ? 45 : 0,
            minRotation: labels.length > 7 ? 45 : 0,
          }
        }
      },
      interaction: { mode: 'nearest', axis: 'x', intersect: false }
    }
  });
};

onMounted(drawChart);
// Watch both the period selection and the incoming data object for changes
watch([currentPeriod, currentData], drawChart, { deep: true });
</script>

<template>
  <div class="card p-3 mt-2 shadow-sm w-100" style="height: 350px;">
    
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
      <h6 class="mb-1 fw-bold">{{ props.chartTitle }} 
        <small v-if="totalUsers > 0" class="text-success ms-2">({{ totalUsers }} Total)</small>
      </h6>
      <div class="btn-group btn-group-sm">
        <button
          class="btn btn-outline-success"
          :class="{ active: currentPeriod==='week' }"
          @click="currentPeriod='week'"
        >
          This Week
        </button>
        <button
          class="btn btn-outline-success"
          :class="{ active: currentPeriod==='month' }"
          @click="currentPeriod='month'"
        >
          This Month
        </button>
        <button
          class="btn btn-outline-success"
          :class="{ active: currentPeriod==='year' }"
          @click="currentPeriod='year'"
        >
          This Year
        </button>
      </div>
    </div>
    
    <div class="chart-container" style="height: 260px;">
      <div v-if="totalUsers === 0" class="d-flex align-items-center justify-content-center h-100 text-muted">
        <p class="text-center">
          <i class="fas fa-chart-line me-2"></i>
          No data available for the selected period.
        </p>
      </div>
      <canvas v-show="totalUsers > 0" ref="chartRef"></canvas>
    </div>
  </div>
</template>

<style scoped>
.chart-container {
  position: relative;
  width: 100%;
  max-width: 100%;
  height: 260px;
  overflow-x: hidden;
}

/* Updated active class definition for clarity */
.btn-outline-success.active {
  background-color: #10B981;
  color: white;
  border-color: #10B981;
  box-shadow: none !important;
}
.btn-outline-success:hover {
    color: #10B981;
    background-color: transparent;
    border-color: #10B981;
}
.btn-group-sm > .btn, .btn-sm {
    font-size: 0.75rem; /* Smaller, cleaner buttons */
}
</style>