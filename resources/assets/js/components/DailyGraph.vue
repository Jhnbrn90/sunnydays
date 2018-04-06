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
      data: [],
      powerArray: [],
      weatherCondition: [],
      temperatures: [],
      myChart: "",
      fetchUpdates: true,
      date: "",
      chartInterval: "",
      updateFrequency: 4000,
      dateFormat: "dddd DD MMMM YYYY"
    };
  },

  computed: {
    labels() {
      return Object.keys(this.data);
    },

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
        labels: this.labels,
        datasets: [
          {
            label: "(J&L)",
            data: this.powerArray,
            weatherCondition: this.weatherCondition,
            temperatures: this.temperatures,
            fill: false,
            borderColor: "rgba(255, 165, 120, 1.0)",
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

    window.Echo.channel("periodic-update").listen("PeriodicLogUpdated", e => {
      if (this.fetchUpdates) {
        this.myChart.data.labels.push(e.time);
        this.myChart.data.datasets.forEach(dataset => {
          dataset.data.push(e.power);
          dataset.weatherCondition[e.time] = e.weather;
          dataset.temperatures[e.time] = e.temperature;
        });
        this.myChart.update();
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
        this.myChart.data.labels = Object.keys(response.data);

        this.powerArray = [];

        this.myChart.data.datasets.forEach(dataset => {
          Object.keys(response.data).forEach(i => {
            this.powerArray.push(response.data[i].power);
            dataset.weatherCondition[i] = response.data[i].weather_condition;
            dataset.temperatures[i] = response.data[i].temperature;
          });

          dataset.data = this.powerArray;
        });

        this.myChart.update();
      });
    }
  }
};
</script>

