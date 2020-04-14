<template>
  <div>
    <center><strong>
      <span class="JL">&mdash; J&amp;L</span>
      <span class="MB">&mdash; M&amp;B</span>
      <span class="BE">&mdash; B&amp;E</span>
      <span class="RB">&mdash; Ron</span>
      </strong>
    </center>
    <br>
  <div class="grid-container">
      <div class="grid-item">
      <strong>Today</strong><br>
      kWh
      <div v-for="user in Object.keys(goodweIds)" v-if="!loading">
        <span :class='classes(user)'>
          {{ energy[user] ? energy[user].eday : '...' }}
        </span> <br>
      </div>
      <div v-if="loading">
        <br>
        <center>
        <breeding-rhombus-spinner
          :animation-duration="2000"
          :size="30"
          color="#029ee3"
        />
        </center>
        <br>
        Loading...
      </div>

    </div>

    <div class="grid-item">
      <strong>Now</strong><br>
      W
      <div v-for="user in Object.keys(goodweIds)"  v-if="!loading">
      <span :class="classes(user)">
        {{ nowGenerating(user) }}
      </span> <br>
      </div>

      <div v-if="loading">
        <br>
        <center>
        <breeding-rhombus-spinner
          :animation-duration="2000"
          :size="30"
          color="#ffa578"
        />
        </center>
        <br>
        Loading...
      </div>

    </div>

    <div class="grid-item">
      <strong>Total</strong><br>
      kWh
      <div v-for="user in Object.keys(goodweIds)"  v-if="!loading">
        <span :class="classes(user)">
          {{ energy[user] ? energy[user].etotal : '...' }}
        </span> <br>
      </div>
      
      <div v-if="loading">
        <br>
        <center>
        <breeding-rhombus-spinner
          :animation-duration="2000"
          :size="30"
          color="#009933"
        />
        </center>
        <br>
        Loading...
      </div>

    </div>

        <div class="grid-item">
      <strong>Average</strong><br>
      kWh /day
      <div v-for="user in Object.keys(goodweIds)"  v-if="!loading">
        <span :class="classes(user)">
          {{ average[user] ? average[user] : '' }}
        </span> <br>
      </div>
      
      <div v-if="loading">
        <br>
        <center>
        <breeding-rhombus-spinner
          :animation-duration="2000"
          :size="30"
          color="#992300"
        />
        </center>
        <br>
        Loading...
      </div>

    </div>

  </div>
  </div>
</template>

<script>
import axios from 'axios';
import { BreedingRhombusSpinner } from 'epic-spinners'

export default {
  props: ['goodweIds', 'api'],

  components: {
    BreedingRhombusSpinner
  },

  data() {
    return {
      loading: true,

      energy: {
        JL: '',
        MB: '',
        BE: '',
        RB: '',
      },
      average: {}
    };
  },

  created() {
    this.initialize();
  },

  mounted() {
    setInterval(this.initialize, 20 * 1000);
  },

  methods: {
    initialize() {
      this.getAverage();

      axios.get('/api/powerstations')
        .then(response => {
          this.energy = response.data;
          this.loading = false;
      });
    },

    getAverage() {
      axios.get('/api/average-yield').then(({ data }) => {
        this.average = data;
      });
    },

    classes(goodweId) {
      return 'number' + ' ' + goodweId;
    },

    nowGenerating(user) {
      return Math.round(this.energy[user].pac);
    }

  }
};
</script>

