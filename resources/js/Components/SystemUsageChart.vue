<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
  week: { type: Object, required: true },
  month: { type: Object, required: true },
  year: { type: Object, required: true },
});

const currentPeriod = ref('week');
const chartRef = ref(null);
let chart = null;

// Compute current dataset
const currentData = computed(() => props[currentPeriod.value] || {});

// Draw or update chart
const drawChart = () => {
  if (!chartRef.value) return;
  const ctx = chartRef.value.getContext('2d');

  if (chart) chart.destroy();

  chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: Object.keys(currentData.value),
      datasets: [
        {
          label: 'New Users',
          data: Object.values(currentData.value),
          borderColor: '#10B981',
          backgroundColor: 'rgba(16,185,129,0.2)',
          fill: true,
          tension: 0.3,
          pointRadius: 5,
          pointHoverRadius: 7,
          pointBackgroundColor: '#059669',
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { padding: { top: 10, right: 10, bottom: 30, left: 10 } },
      plugins: {
        legend: { display: false },
        tooltip: { mode: 'index', intersect: false },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            precision: 0,
          },
          grid: {
            drawBorder: false,
            color: 'rgba(0,0,0,0.05)',
          }
        },
        x: {
          grid: { display: false },
          ticks: {
            autoSkip: false,
            maxRotation: 0,
            minRotation: 0,
          }
        }
      },
      interaction: { mode: 'nearest', axis: 'x', intersect: false }
    }
  });
};

onMounted(drawChart);
watch(currentPeriod, drawChart);
</script>

<template>
  <div class="card p-3 mt-2 shadow-sm w-100" style="height: 350px;">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
      <h6 class="mb-1">System Usage</h6>
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
      <canvas ref="chartRef"></canvas>
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

.active {
  background-color: #10B981;
  color: white;
  border-color: #10B981;
}
</style>
