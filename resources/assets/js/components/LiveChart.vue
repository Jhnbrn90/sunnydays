<template>
  <div class="live-chart">
    <h2 class="text-center font-medium tracking-wide text-lg text-gray-700">
      {{ date.format(dateFormat) }}
    </h2>

    <div class="my-2 text-center">
      <button
          class="text-xs bg-transparent hover:bg-gray-700 text-gray-700 hover:text-white py-1 px-2 border border-gray-700 hover:border-transparent rounded"
          v-on:click="previousDate"
      >
        Previous
      </button>

      <button
          class="text-xs bg-transparent py-1 px-2 border rounded text-gray-700 border-gray-700"
          :class="isToday ? 'opacity-50 cursor-not-allowed' : 'hover:border-transparent hover:text-white hover:bg-gray-700 text-gray-700 border-gray-700'"
          v-on:click="nextDate"
          :disabled="isToday"
      >
        Next
      </button>

      <button
          class="text-xs bg-transparent py-1 px-2 border rounded"
          :class="isToday ? 'text-gray-700 border-gray-700 opacity-50 cursor-not-allowed' : 'hover:border-transparent hover:text-white hover:bg-blue-600 text-blue-700 border-blue-700'"
          v-on:click="setDateToday"
          :disabled="isToday"
      >
        Today
      </button>
    </div>

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
