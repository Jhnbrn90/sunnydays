require("./bootstrap");

window.Vue = require("vue");

Vue.component("weather-time", require("./components/WeatherTime.vue").default);
Vue.component("solar-energy", require("./components/SolarEnergy.vue").default);
Vue.component("daily-graph", require("./components/DailyGraph.vue").default);
Vue.component("weekly-graph", require("./components/WeeklyGraph.vue").default);

const app = new Vue({
  el: "#app"
});
