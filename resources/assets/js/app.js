window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require("vue");

Vue.component("live-chart", require("./components/LiveChart.vue").default);
Vue.component("week-chart", require("./components/WeekChart.vue").default);

const app = new Vue({
  el: "#app"
});