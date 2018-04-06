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
import weather from "../services/weather/Weather";

export default {
  props: ["weatherCity"],

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

    async fetchWeather() {
      const conditions = await weather.conditions(this.weatherCity);

      this.weather.temperature = conditions.temp;
      this.weather.iconClass = `wi-yahoo-${conditions.code}`;
    }
  }
};
</script>
