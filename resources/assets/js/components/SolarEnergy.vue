<template>
  <div>
    <div class="w-full text-center mb-2">
      <span class="JL">&mdash; J&amp;L</span>
      <span class="MB">&mdash; M&amp;B</span>
      <span class="BE">&mdash; B&amp;E</span>
      <span class="RB">&mdash; Ron</span>
    </div>

    <div class="sm:w-1/2 w-full sm:mx-auto flex items-center justify-center">
      <div class="flex-1 flex justify-center">
        <div class="py-2 px-4 text-center">
          <span class="font-semibold sm:font-medium block text-lg sm:text-2xl tracking-wider">
            Today
          </span>
          <span class="font-hairline">
            kWh
          </span>

          <div class="py-6 w-full flex justify-center" v-if="loading">
              <breeding-rhombus-spinner
                  :animation-duration="2000"
                  :size="30"
                  color="#029ee3"
              />
          </div>

          <div class="sm:text-2xl font-thin" v-for="user in Object.keys(goodweIds)" v-else>
            <span :class='classes(user)'>
              {{ energy[user] ? energy[user].eday : '...' }}
            </span>
          </div>

        </div>
      </div>

      <div class="flex-1 flex justify-center">
        <div>
          <div class="py-2 px-4 sm:p-4 text-center">
          <span class="font-semibold sm:font-medium block text-lg sm:text-2xl tracking-wider">
            Now
          </span>
            <span class="font-hairline">
            W
          </span>

            <div class="py-6 w-full flex justify-center" v-if="loading">
              <breeding-rhombus-spinner
                  :animation-duration="2000"
                  :size="30"
                  color="#ffa578"
              />
            </div>

            <div class="sm:text-2xl font-thin" v-for="user in Object.keys(goodweIds)" v-else>
              <span :class="classes(user)">
                {{ nowGenerating(user) }}
              </span>
            </div>

          </div>
        </div>
      </div>

      <div class="flex-1 flex justify-center">
        <div>
          <div class="py-2 px-4 sm:p-4 text-center">
            <span class="font-semibold sm:font-medium block text-lg sm:text-2xl tracking-wider">
              Total
            </span>
            <span class="font-hairline">
              kWh
            </span>

            <div class="py-6 w-full flex justify-center" v-if="loading">
              <breeding-rhombus-spinner
                  :animation-duration="2000"
                  :size="30"
                  color="#009933"
              />
            </div>

            <div class="sm:text-2xl font-thin" v-for="user in Object.keys(goodweIds)" v-else>
              <span :class="classes(user)">
                {{ energy[user] ? energy[user].etotal : '...' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="flex-1 flex justify-center">
        <div>
          <div class="py-2 px-4 text-center">
            <span class="font-semibold sm:font-medium block text-lg sm:text-2xl tracking-wider">
              Average
            </span>
            <span class="font-hairline">
              kWh /day
            </span>

            <div class="py-6 w-full flex justify-center" v-if="loading">
              <breeding-rhombus-spinner
                  :animation-duration="2000"
                  :size="30"
                  color="#992300"
              />
            </div>

            <div class="sm:text-2xl font-thin" v-for="user in Object.keys(goodweIds)"  v-if="!loading">
              <span :class="classes(user)">
                {{ energy[user] ? energy[user].etotal : '...' }}
              </span>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {BreedingRhombusSpinner} from 'epic-spinners'

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
      axios.get('/api/average-yield').then(({data}) => {
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

<style>
.JL {
  color: rgb(255, 165, 120);
}

.MB {
  color: rgb(2, 158, 227);
}

.BE {
  color: rgb(0, 153, 51)
}

.RB {
  color: rgb(95, 66, 244);
}
</style>
