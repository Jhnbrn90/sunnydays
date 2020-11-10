<template>
  <div class="weekly-graph">
    <canvas ref="weeklyChart"></canvas>
  </div>
</template>

<script>
import Chart from "chart.js";
import { WeeklyGraphOptions } from '../charts/config';

export default {
  props: ['data'],

  data() {
    return {
      weeklyChart: {}
    };
  },

  mounted() {
    this.weeklyChart = new Chart(this.$refs.weeklyChart, {
      type: "bar",
      data: {
        datasets: this.data,
      },
      options: WeeklyGraphOptions
    });
  }
};
</script>

