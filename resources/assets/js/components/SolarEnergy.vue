<template>
  <div>
    <center><strong>
      <span class="JL">&mdash; J&amp;L</span>
      <span class="MB">&mdash; M&amp;B</span>
      <span class="BE">&mdash; B&amp;E</span>
      </strong>
    </center>
    <br>
  <div class="grid-container">
      <div class="grid-item">
      <strong>Today</strong><br>
      kWh
      <div v-for="user in Object.keys(goodweIds)">
        <span :class='classes(user)'>
          {{ energy[user] ? energy[user].eday.split('kWh')[0] : '...' }}
        </span> <br>
      </div>

    </div>

    <div class="grid-item">
      <strong>Now</strong><br>
      W
      <div v-for="user in Object.keys(goodweIds)">
      <span :class="classes(user)">
        {{ nowGenerating(user) }}
      </span> <br>
      </div>
    </div>

    <div class="grid-item">
      <strong>Total</strong><br>
      kWh
      <div v-for="user in Object.keys(goodweIds)">
        <span :class="classes(user)">
          {{ energy[user] ? energy[user].etotal.split('kWh')[0] : '...' }}
        </span> <br>
      </div>
    </div>

        <div class="grid-item">
      <strong>Average</strong><br>
      kWh /day
      <div v-for="user in Object.keys(goodweIds)">
        <span :class="classes(user)">
          {{ average[user] ? average[user] : '' }}
        </span> <br>
      </div>
    </div>

  </div>
  </div>
</template>

<script>
import goodwe from '../services/goodwe/Goodwe';
import axios from 'axios';

export default {
  props: ['goodweIds', 'api'],

  data() {
    return {
      energy: {
        JL: '',
        MB: '',
        BE: ''
      },
      average: {}
    };
  },

  created() {
    this.getYields();
    this.getAverage();
    setInterval(this.getYields, 10 * 1000);
  },

  methods: {
    async getYieldFor(user) {
      const power = await goodwe.current(this.goodweIds[user], this.api);
      this.energy[user] = power;
    },

    getYields() {
      Object.keys(this.goodweIds).forEach(user => {
        this.getYieldFor(user);
      });
    },

    getAverage() {
      axios.get('/api/average').then(({ data }) => {
        this.average = data;
      });
    },

    classes(goodweId) {
      return 'number' + ' ' + goodweId;
    },
    nowGenerating(user) {
      return Math.round(this.energy[user].curpower.split('kW')[0] * 1000);
    }
  }
};
</script>

