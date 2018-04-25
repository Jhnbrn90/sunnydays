<template>

<div class="daily-graph">
  <span class="daily-graph-date"><center><strong>{{ date }}</strong></center></span>
  <button class="btn btn-sm btn-outline-secondary" v-on:click="previousDate">Previous</button>
  <button class="btn btn-sm btn-outline-secondary" v-on:click="nextDate" :disabled="isToday">Next</button>
  <button class="btn btn-sm" :class="isToday ? 'btn-outline-secondary' : 'btn-outline-primary'" v-on:click="nextDateToday" :disabled="isToday">Today</button>
  <canvas id="dailyChart"></canvas>
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
      weatherObject: {},
      temperatureObject: {},
      myChart: "",
      fetchUpdates: true,
      date: "",
      chartInterval: "",
      updateFrequency: 4000,
      dateFormat: "dddd DD MMMM YYYY"
    };
  },

  computed: {
    isToday() {
      return this.date === this.today;
    },

    today() {
      return moment().format("dddd DD MMMM YYYY");
    }
  },

  created() {
    this.date = this.today;
    this.updateChart();
  },

  mounted() {
    var ctx = document.getElementById("dailyChart");
    this.myChart = new Chart(ctx, {
      type: "line",
      data: {
        labels: "",
        datasets: [
          {
            label: "(J&L)",
            data: this.powerObject.JL,
            weatherCondition: [],
            temperatures: [],
            fill: false,
            borderColor: "rgba(255, 165, 120, 1.0)",
            backgroundColor: "rgba(255, 255, 255, 0.1)"
          },
          {
            label: "(M&B)",
            data: this.powerObject.MB,
            weatherCondition: [],
            temperatures: [],
            fill: false,
            borderColor: "rgba(2, 158, 227, 1)",
            backgroundColor: "rgba(255, 255, 255, 0.1)"
          },
          {
            label: "(B&E)",
            data: this.powerObject.BE,
            weatherCondition: [],
            temperatures: [],
            fill: false,
            borderColor: "rgba(0, 153, 51, 1)",
            backgroundColor: "rgba(255, 255, 255, 0.1)"
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: "Generated energy (W)"
        },
        scales: {
          xAxes: [
            {
              type: "time",
              time: {
                round: true,
                parser: "HH:mm",
                unit: "minute",
                unitStepSize: 60,
                displayFormats: {
                  minute: "HH:mm",
                  hour: "HH:mm"
                }
              }
            }
          ],
          yAxes: [
            {
              ticks: {
                beginAtZero: true
              }
            }
          ]
        },
        legend: {
          display: true
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              return (
                tooltipItem.yLabel +
                " W " +
                data.datasets[tooltipItem.datasetIndex].label +
                ", " +
                data.datasets[tooltipItem.datasetIndex].weatherCondition[
                  tooltipItem.xLabel
                ] +
                ", " +
                data.datasets[tooltipItem.datasetIndex].temperatures[
                  tooltipItem.xLabel
                ] +
                " °C"
              );
            }
          }
        }
      }
    });

    this.chartInterval = setInterval(this.updateChart, this.updateFrequency);
  },

  methods: {
    setPreviousDay() {
      this.date = moment(this.date, this.dateFormat)
        .subtract(1, "days")
        .format(this.dateFormat);
    },

    setNextDay() {
      this.date = moment(this.date, this.dateFormat)
        .add(1, "days")
        .format(this.dateFormat);
    },

    previousDate() {
      if (this.isToday) {
        this.disableUpdates();
      }

      this.setPreviousDay();

      this.updateChart();
    },

    nextDate() {
      this.setNextDay();

      this.updateChart();

      if (this.isToday) {
        this.enableUpdates();
      }
    },

    enableUpdates() {
      this.chartInterval = setInterval(this.updateChart, this.updateFrequency);
      this.fetchUpdates = true;
    },

    disableUpdates() {
      clearInterval(this.chartInterval);
      this.fetchUpdates = false;
    },

    nextDateToday() {
      this.date = this.today;
      this.updateChart();
      this.chartInterval = setInterval(this.updateChart, this.updateFrequency);
      this.fetchUpdates = true;
    },

    updateChart() {
      axios.get("/api/dailygraph/" + this.date).then(response => {
        var powerArray = [];
        var weatherArray = [];
        var temperatureArray = [];

        this.myChart.data.labels = Object.keys(response.data.JL);

        Object.keys(response.data).forEach(user => {
          var data = response.data[user];
          Object.keys(data).forEach(time => {
            powerArray.push({
              x: time,
              y: data[time].power
            });

            weatherArray[time] = data[time].weather_condition;
            temperatureArray[time] = data[time].temperature;
          });

          this.powerObject[user] = powerArray;
          this.weatherObject[user] = weatherArray;
          this.temperatureObject[user] = temperatureArray;
          powerArray = [];
          weatherArray = [];
          temperatureArray = [];
        });

        this.myChart.data.datasets.forEach((dataset, index) => {
          var user = Object.keys(this.powerObject)[index];
          dataset.data = this.powerObject[user];

          dataset.weatherCondition = this.weatherObject[user];
          dataset.temperatures = this.temperatureObject[user];
        });

        this.myChart.update();
      });
    }
  }
};
</script>

