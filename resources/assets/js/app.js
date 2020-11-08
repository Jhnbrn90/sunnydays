window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require("vue");

Vue.component("weather-time", require("./components/WeatherTime.vue").default);
Vue.component("solar-energy", require("./components/SolarEnergy.vue").default);
Vue.component("live-chart", require("./components/LiveChart.vue").default);
Vue.component("weekly-graph", require("./components/WeeklyGraph.vue").default);
Vue.component("data-panel", require("./components/Aggregates/DataPanel.vue").default);

const app = new Vue({
  el: "#app"
});