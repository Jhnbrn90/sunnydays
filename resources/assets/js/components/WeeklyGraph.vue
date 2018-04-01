<template>
<div style="margin-left: -80px; padding-right: 10px; height: 50vh; width: 90vw;">
  <canvas id="weeklyChart" width="200" height="50"></canvas>
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
            backgroundColor: "rgba(255, 159, 64, 0.4)"
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: "Energy produced per day (in Watt)"
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

