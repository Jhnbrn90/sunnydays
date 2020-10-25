<template>

<div class="daily-graph">

  <span class="daily-graph-date">
    <center>
      <strong>{{ date.format(dateFormat) }}</strong>
    </center>
  </span>

  <button class="btn btn-sm btn-outline-secondary" v-on:click="previousDate">
    Previous
  </button>

  <button class="btn btn-sm btn-outline-secondary" v-on:click="nextDate" :disabled="isToday">
    Next
  </button>

  <button
      class="btn btn-sm"
      :class="isToday ? 'btn-outline-secondary' : 'btn-outline-primary'"
      v-on:click="setDateToday"
      :disabled="isToday"
  >Today</button>

  <canvas ref="dailyChart"></canvas>
</div>
</template>

<script>
import Chart from "chart.js";
import moment from "moment";
import { LiveChartOptions } from '../charts/config';

export default {
  props: ['data'],

  data() {
    return {
      liveChart: {},
      date: moment(),
      chartInterval: "",
      updateFrequency: 10000, // update frequency in ms
      dateFormat: "dddd DD MMMM YYYY"
    };
  },

  computed: {
    isToday() {
      return this.date.format(this.dateFormat) === this.today;
    },

    today() {
      return moment().format(this.dateFormat);
    },

    endpoint() {
      return `api/live-chart/${this.date.format(this.dateFormat)}`;
    }
  },

  mounted() {
    this.liveChart = new Chart(this.$refs.dailyChart, {
      type: "line",
      data: {
        datasets: this.data
      },
      options: LiveChartOptions
    });

    this.enableUpdates();
  },

  methods: {
    setPreviousDay() {
      this.date = this.date.clone().subtract(1, "days");
    },

    setNextDay() {
      this.date = this.date.clone().add(1, "days");
    },

    previousDate() {
      this.setPreviousDay();
      this.disableUpdates();
      this.updateChart();
    },

    nextDate() {
      this.setNextDay();

      if (this.isToday) {
        this.enableUpdates();
      }

      this.updateChart();
    },

    enableUpdates() {
      this.chartInterval = setInterval(this.updateChart, this.updateFrequency);
    },

    disableUpdates() {
      clearInterval(this.chartInterval);
    },

    setDateToday() {
      this.date = moment();
      this.chartInterval = setInterval(this.updateChart, this.updateFrequency);
      this.updateChart();
    },

    updateChart() {
      axios.get(this.endpoint).then(response => {
        this.liveChart.data.datasets = response.data;
        this.liveChart.update();
      });
    }
  }
};
</script>

