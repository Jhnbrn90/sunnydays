<template>
<div class="daily-graph">
  <canvas id="dailyChart"></canvas>
</div>

</template>

<script>
import Chart from "chart.js";
import axios from "axios";

export default {
  props: ["data"],

  data() {
    return {
      work: this.data,
      powerArray: [],
      weatherCondition: [],
      temperatures: []
    };
  },

  computed: {
    labels() {
      return Object.keys(this.work);
    }
  },

  created() {
    this.getInitialData();
  },

  mounted() {
    var ctx = document.getElementById("dailyChart");
    var myChart = new Chart(ctx, {
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
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: "Energy produced today (in Watt)"
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

    window.Echo.channel("periodic-update").listen("PeriodicLogUpdated", e => {
      myChart.data.labels.push(e.time);
      myChart.data.datasets.forEach(dataset => {
        dataset.data.push(e.power);
        dataset.weatherCondition[e.time] = e.weather;
        dataset.temperatures[e.time] = e.temperature;
      });
      myChart.update();
    });
  },

  methods: {
    getInitialData() {
      Object.keys(this.data).forEach(i => {
        this.powerArray.push(this.data[i]["power"]);
        this.weatherCondition[String(i)] = this.data[i]["weather_condition"];
        this.temperatures[String(i)] = this.data[i]["temperature"];
      });
    },

    updateData() {
      this.powerArray = [];
      this.weatherCondition = [];
      this.temperatures = [];
      axios.get("/api/data").then(response => (this.work = response.data));
      this.getInitialData();
    }
  }
};
</script>

