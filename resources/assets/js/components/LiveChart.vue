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
  props: ['data', 'initialDate'],

  data() {
    return {
      liveChart: {},
      date: moment(this.initialDate, 'DD-MM-YYYY'),
      chartInterval: null,
      updateFrequency: 10000, // update frequency in ms
      dateFormat: "dddd DD MMMM YYYY"
    };
  },

  watch: {
    date() {
      this.updateChart();
    }
  },

  computed: {
    endpoint() {
      return `api/live-chart/${this.date.format(this.dateFormat)}`;
    },

    today() {
      return moment(this.initialDate, 'DD-MM-YYYY').format(this.dateFormat);
    },

    isToday() {
      return this.date.format(this.dateFormat) === this.today;
    }
  },

  mounted() {
    this.initializeChart();
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
    },

    nextDate() {
      this.setNextDay();

      if (this.isToday) {
        this.enableUpdates();
      }
    },

    enableUpdates() {
      this.chartInterval = setInterval(this.updateChart, this.updateFrequency);
    },

    disableUpdates() {
      clearInterval(this.chartInterval);
    },

    setDateToday() {
      this.date = moment(this.initialDate, 'DD-MM-YYYY');
      this.chartInterval = setInterval(this.updateChart, this.updateFrequency);
    },

    updateChart() {
      axios.get(this.endpoint).then(response => {
        this.liveChart.data.datasets = response.data;
        this.liveChart.update();
      });
    },

    initializeChart() {
      this.liveChart = new Chart(this.$refs.dailyChart, {
        type: "line",
        data: {
          datasets: this.data
        },
        options: LiveChartOptions
      });

      this.enableUpdates();
    }
  }
};
</script>

