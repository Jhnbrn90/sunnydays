<template>
<div class="weekly-graph">
  <canvas id="weeklyChart" width="600" height="20"></canvas>
</div>
</template>

<script>
import Chart from "chart.js";
import axios from "axios";
import moment from "moment";

export default {
  data() {
    return {
      powerObject: {},
      days: [],
      weeklyChart: ""
    };
  },

  created() {
    this.setDays();
    this.getGraphData();
  },

  methods: {
    getGraphData() {
      axios.get("/api/production").then(response => {
        Object.keys(response.data).forEach(user => {
          var powerArray = [];
          var data = response.data[user];
          Object.keys(data).forEach(day => {
            powerArray.push({
              x: day,
              y: data[day]
            });
            if (user === "LJ") {
              this.days.push(day);
            }
          });

          this.powerObject[user] = powerArray;
        });

        this.weeklyChart.data.labels = this.days;

        this.weeklyChart.data.datasets.forEach((dataset, index) => {
          var user = Object.keys(this.powerObject)[index];
          dataset.data = this.powerObject[user];
        });

        this.weeklyChart.update();
      });
    },

    setDays() {
      var startOfWeek = moment().subtract(7, "days");
      var endOfWeek = moment();

      var days = [];
      var day = startOfWeek;

      while (day <= endOfWeek) {
        days.push(day.format("DD-MM-Y"));
        day = day.clone().add(1, "d");
      }

      this.days = days;
    }
  },

  mounted() {
    var ctx = document.getElementById("weeklyChart").getContext('2d');
    this.weeklyChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: "",
        datasets: [
          {
            label: "(J&L)",
            data: [],
            fill: false,
            backgroundColor: "rgba(255, 159, 64, 0.75)"
          },
          {
            label: "(M&B)",
            data: [],
            fill: false,
            backgroundColor: "rgba(2, 158, 227, 1)"
          },
          {
            label: "(B&E)",
            data: [],
            fill: false,
            backgroundColor: "rgba(0, 153, 51, 1)"
          },
          {
            label: "(Ron)",
            data: [],
            fill: false,
            backgroundColor: "rgba(95, 66, 244, 1)"
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
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              return tooltipItem.yLabel + " kWh ";
            }
          }
        },
        scales: {
          xAxes: [
            {
              // type: "time",
              // time: {
              //   unit: "day",
              //   unitStepSize: 1,
              //   displayFormats: {
              //     day: "dd DD MMM"
              //   }
              // }
            }
          ],
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

