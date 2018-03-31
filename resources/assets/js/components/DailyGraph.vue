<template>
<div>
  <canvas id="dailyChart" width="200" height="50"></canvas>
</div>

</template>

<script>
import Chart from "chart.js";

export default {
  props: ["data"],

  data() {
    return {
      labels: Object.keys(this.data),
      powerArray: [],
      weatherCondition: {},
      temperatures: {}
    };
  },

  created() {
    this.getInitialData();
  },

  mounted() {
    var ctx = document.getElementById("dailyChart");
    new Chart(ctx, {
      type: "line",
      data: {
        labels: this.labels,
        datasets: [
          {
            label: "Watt (W)",
            data: this.powerArray,
            weatherCondition: this.weatherCondition,
            temperatures: this.temperatures,
            fill: false,
            borderColor: "#ffa500"
          }
        ]
      },
      options: {
        title: {
          display: true,
          text: "Energy produced per hour (in Watt)"
        },
        legend: {
          display: false
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              return (
                tooltipItem.yLabel +
                " " +
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
  },

  methods: {
    getInitialData() {
      Object.keys(this.data).forEach(i => {
        this.powerArray.push(this.data[i]["power"]);
        this.weatherCondition[String(i)] = this.data[i]["weather_condition"];
        this.temperatures[String(i)] = this.data[i]["temperature"];
      });
    }
  }
};
</script>

