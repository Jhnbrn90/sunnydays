<template>
  <div class="week-chart">
    <canvas ref="weekChart"></canvas>
  </div>
</template>

<script>
import Chart from "chart.js";
import { WeekChartOptions } from '../charts/config';

export default {
  props: ['data'],

  data() {
    return {
      weekChart: {}
    };
  },

  mounted() {
    this.weekChart = new Chart(this.$refs.weekChart, {
      type: "bar",
      data: {
        datasets: this.data,
      },
      options: WeekChartOptions
    });
  }
};
</script>

