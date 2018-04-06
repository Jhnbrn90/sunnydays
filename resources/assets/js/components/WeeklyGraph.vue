<template>
<div class="weekly-graph">
  <canvas id="weeklyChart" width="600" height="20"></canvas>
</div>
</template>

<script>
import Chart from "chart.js";

export default {
  props: ["days", "power"],

  data() {
    return {
      data: ""
    };
  },

  mounted() {
    var ctx = document.getElementById("weeklyChart");
    var weeklyChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: this.days,
        datasets: [
          {
            label: "kWh",
            data: this.power,
            fill: false,
            backgroundColor: "rgba(255, 159, 64, 0.75)"
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: "Energy produced per day (in kWh)"
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [
            {
              display: true,
              ticks: {
                beginAtZero: true
              }
            }
          ]
        }
      }
    });
  }
};
</script>

