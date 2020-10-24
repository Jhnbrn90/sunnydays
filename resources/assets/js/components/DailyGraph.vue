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
  props: ['data'],

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
    let ctx = document.getElementById("dailyChart");

    this.myChart = new Chart(ctx, {
      type: "line",
      data: {
        datasets: this.data
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
                beginAtZero: true,
                max: 3500
              }
            }
          ]
        },
        legend: {
          display: true
        },
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
        this.myChart.data.datasets = response.data;

        this.myChart.update();
      });
    }
  }
};
</script>

