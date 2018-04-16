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
            label: "(J&L)",
            data: this.power.JL,
            fill: false,
            backgroundColor: "rgba(255, 159, 64, 0.75)"
          },
          {
            label: "(M&B)",
            data: this.power.MB,
            fill: false,
            backgroundColor: "rgba(2, 158, 227, 1)"
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
          display: true
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

