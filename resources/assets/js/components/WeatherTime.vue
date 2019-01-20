<template>
    <div class="container weather-time">
        <h1> {{ time }} </h1>
        {{ date }} <br>
        {{ weather.temperature }} &deg;C
        <i class="wi" :class="weather.iconClass"></i>
    </div>
</template>

<script>
import moment from "moment";

import axios from "axios";

export default {

  data() {
    return {
      time: "",
      date: "",
      weather: {
        temperature: "",
        iconClass: ""
      }
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
        this.weather.temperature = response.data.temperature;
        this.weather.iconClass = `wi-yahoo-${response.data.code}`;
      });
    }

  }
};
</script>
