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
        <div class="py-6 w-full flex justify-center" v-if="loading">
          <breeding-rhombus-spinner :animation-duration="2000" :size="30" color="#029ee3"/>
        </div>
        <solar-energy-today :powerStations="powerStations" v-else />
      </div>

      <div class="flex-1 flex justify-center">
        <div class="py-6 w-full flex justify-center" v-if="loading">
          <breeding-rhombus-spinner :animation-duration="2000" :size="30" color="#ffa578"/>
        </div>
        <solar-energy-now :powerStations="powerStations" v-else />
      </div>

      <div class="flex-1 flex justify-center">
        <div class="py-6 w-full flex justify-center" v-if="loading">
          <breeding-rhombus-spinner :animation-duration="2000" :size="30" color="#009933"/>
        </div>
        <solar-energy-total :powerStations="powerStations" v-else />
      </div>

      <div class="flex-1 flex justify-center">
        <div class="py-6 w-full flex justify-center" v-if="loading">
          <breeding-rhombus-spinner :animation-duration="2000" :size="30" color="#992300"/>
        </div>
        <solar-energy-averages :powerStations="powerStations" v-else />
      </div>



<!--      <div class="flex-1 flex justify-center">-->
<!--        <div>-->
<!--          <div class="py-2 px-4 text-center">-->
<!--            <span class="font-semibold sm:font-medium block text-lg sm:text-2xl tracking-wider">-->
<!--              Average-->
<!--            </span>-->
<!--            <span class="font-hairline">-->
<!--              kWh /day-->
<!--            </span>-->

<!--            <div class="py-6 w-full flex justify-center" v-if="loading">-->
<!--              <breeding-rhombus-spinner-->
<!--                  :animation-duration="2000"-->
<!--                  :size="30"-->
<!--                  color="#992300"-->
<!--              />-->
<!--            </div>-->

<!--            <div class="sm:text-2xl text-xl font-thin" v-for="user in Object.keys(goodweIds)"  v-if="!loading">-->
<!--              <span :class="classes(user)">-->
<!--                {{ average[user] ? average[user] : '' }}-->
<!--              </span>-->
<!--            </div>-->

<!--          </div>-->
<!--        </div>-->
<!--      </div>-->

    </div>
  </div>
</template>

<script>
import {BreedingRhombusSpinner} from 'epic-spinners'

export default {
  props: ['endpoint'],

  components: {
    BreedingRhombusSpinner
  },

  data() {
    return {
      loading: true,

      powerStations: {},

      average: {}
    };
  },

  created() {
    this.initialize();
  },

  mounted() {
    setInterval(this.initialize, 7000);
  },

  methods: {
    initialize() {
      axios.get(this.endpoint).then(response => {
        this.powerStations = response.data
        this.loading = false;
      });

    },

    classes(goodweId) {
      return 'number' + ' ' + goodweId;
    },
  }
};
</script>