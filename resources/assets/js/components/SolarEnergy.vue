<template>
  <div class="grid-container">
    <div class="grid-item">
      <strong>Today</strong><br>
      <span class="number">{{ this.energy.eday ? this.energy.eday.split('kWh')[0] : '...' }}</span> <br> kWh
    </div>
        <div class="grid-item">
      <strong>Now</strong><br>
      <span class="number">{{ nowGenerating }}</span> <br> W
    </div>
        <div class="grid-item">
      <strong>Total</strong><br>
        <span class="number">{{ this.energy.etotal ? this.energy.etotal.split('kWh')[0] : '...' }}</span> <br> kWh
    </div>
  </div>
</template>

<script>
import goodwe from "../services/goodwe/Goodwe";

export default {
  props: ["goodweId", "api"],

  data() {
    return {
      energy: {}
    };
  },

  created() {
    this.getYield();
    setInterval(this.getYield, 10 * 1000);
  },

  methods: {
    async getYield() {
      const power = await goodwe.current(this.goodweId, this.api);
      this.energy = power;
    }
  },

  computed: {
    nowGenerating() {
      return this.energy.curpower.split("kW")[0] * 1000;
    }
  }
};
</script>

