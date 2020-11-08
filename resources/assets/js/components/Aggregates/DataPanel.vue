<template>
  <div class="flex-1 flex justify-center">
    <div>
      <div class="py-2 px-4 sm:p-4 text-center">
        <span class="font-semibold sm:font-medium block text-lg sm:text-2xl tracking-wider">
          {{ title }}
        </span>
        <span class="font-hairline">
          {{ subtitle }}
        </span>

        <div class="py-6 w-full flex justify-center" v-if="loading">
          <breeding-rhombus-spinner :animation-duration="2000" :size="30" :color="loadingColor"/>
        </div>

        <div class="sm:text-2xl text-xl font-thin" v-for="powerStation in powerStations" v-else>
          <span :style="color(powerStation.owner.color)">
            {{ powerStation[property] }}
          </span>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import {BreedingRhombusSpinner} from 'epic-spinners'

export default {
  props: [
    'title',
    'subtitle',
    'powerStations',
    'property',
    'loadingColor'
  ],

  components: {
    BreedingRhombusSpinner
  },

  methods: {
    color(value) {
      return `color: rgba(${value})`;
    },
  },

  computed: {
    loading() {
      return this.powerStations === {};
    }
  }
}
</script>