<template>
  <div>
    <div class="w-full text-center mb-2">
      <span :style="color(powerStation.owner.color)" v-for="powerStation in powerStations">
        &mdash; {{ powerStation.owner.name }}
      </span>
    </div>

    <div class="sm:w-1/2 w-full sm:mx-auto flex items-center justify-center">

      <data-panel
          title="Today"
          subtitle="kWh"
          property="today"
          loadingColor="#029ee3"
          :powerStations="powerStations"
      />

      <data-panel
          title="Now"
          subtitle="W"
          property="generating"
          loadingColor="#ffa578"
          :powerStations="powerStations"
      />

      <data-panel
          title="Total"
          subtitle="kWh"
          property="total"
          loadingColor="#009933"
          :powerStations="powerStations"
      />

      <data-panel
          title="Average"
          subtitle="kWh / day"
          property="average"
          loadingColor="#992300"
          :powerStations="powerStations"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ['endpoint'],

  data() {
    return {
      powerStations: {},
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
      });
    },

    color(value) {
      return `color: rgba(${value})`;
    },
  }
};
</script>