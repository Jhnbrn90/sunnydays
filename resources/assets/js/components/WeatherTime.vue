<template>
    <div>
        <h3 class="text-lg font-medium">
          {{ time }}
        </h3>

      <span class="block">
        {{ date }}
      </span>

      <span>
        {{ weather.temperature }} &deg;C
      </span>

      <i class="wi" :class="weather.iconClass"></i>
    </div>
</template>

<script>
import moment from "moment";

export default {

  data() {
    return {
      time: "",
      date: "",
      weather: {},
    };
  },

  created() {
    this.refreshTime();

    setInterval(this.refreshTime, 1000);

    this.date = moment().format("dddd, DD MMMM YYYY");

    this.fetchWeather();

    setInterval(this.fetchWeather, 15 * 60 * 1000);
  },

  methods: {
    refreshTime() {
      this.time = moment().format("HH:mm");
    },

    fetchWeather() {
      axios.get('/api/weather').then(response => {
        this.weather = {
          'temperature': response.data.temperature,
          'iconClass': `wi-yahoo-${response.data.code}`
        };
      });
    }

  }
};
</script>
