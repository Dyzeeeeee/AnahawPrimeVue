<template>
  <div>
    <div class="card">
      <Chart type="line" :data="filteredChartData" :options="chartOptions" class="h-30rem" />
    </div>
    <div class="filter-buttons">
      <button @click="filterMonth('January')">January</button>
      <button @click="filterMonth('February')">February</button>
      <button @click="filterMonth('March')">March</button>
      <button @click="filterMonth('April')">April</button>
      <button @click="filterMonth('May')">May</button>
      <button @click="filterMonth('June')">June</button>
      <button @click="filterMonth('July')">July</button>
      <button @click="resetFilter">Show All</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

onMounted(() => {
  chartData.value = setChartData();
  chartOptions.value = setChartOptions();
  filteredChartData.value = chartData.value; // Initialize with all data
});

const chartData = ref();
const filteredChartData = ref();
const chartOptions = ref();

const setChartData = () => {
  const documentStyle = getComputedStyle(document.documentElement);

  return {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
          {
              label: 'First Dataset',
              data: [65, 59, 80, 81, 56, 55, 40],
              fill: false,
              tension: 0.4,
              borderColor: documentStyle.getPropertyValue('--cyan-500')
          },
          {
              label: 'Second Dataset',
              data: [28, 48, 40, 19, 86, 27, 90],
              fill: false,
              borderDash: [5, 5],
              tension: 0.4,
              borderColor: documentStyle.getPropertyValue('--orange-500')
          },
          {
              label: 'Third Dataset',
              data: [12, 51, 62, 33, 21, 62, 45],
              fill: true,
              borderColor: documentStyle.getPropertyValue('--gray-500'),
              tension: 0.4,
              backgroundColor: 'rgba(107, 114, 128, 0.2)'
          }
      ]
  };
};

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue('--text-color');
  const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
  const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

  return {
      maintainAspectRatio: false,
      aspectRatio: 0.6,
      plugins: {
          legend: {
              labels: {
                  color: textColor
              }
          }
      },
      scales: {
          x: {
              ticks: {
                  color: textColorSecondary
              },
              grid: {
                  color: surfaceBorder
              }
          },
          y: {
              ticks: {
                  color: textColorSecondary
              },
              grid: {
                  color: surfaceBorder
              }
          }
      }
  };
}

const filterMonth = (month) => {
  const monthIndex = chartData.value.labels.indexOf(month);
  if (monthIndex === -1) return;

  const startDayIndex = monthIndex * 7;
  const endDayIndex = startDayIndex + 6;

  filteredChartData.value = {
    labels: chartData.value.labels.slice(startDayIndex, endDayIndex + 1),
    datasets: chartData.value.datasets.map(dataset => ({
      ...dataset,
      data: dataset.data.slice(startDayIndex, endDayIndex + 1)
    }))
  };
};

const resetFilter = () => {
  filteredChartData.value = chartData.value;
};
</script>

<style>
.filter-buttons {
  margin-top: 1rem;
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

button {
  padding: 0.5rem 1rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>
