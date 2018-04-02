<template>
  <div class="energy-container">
    <div class="card circle-container">
        <div class="circle-content">
          <h3>Today</h3>
          <div class="power-text"><span class="power-value">{{ this.energy.eday ? this.energy.eday.split('kWh')[0] : '...'}}</span></div> kWh
        </div>
    </div>
    <div class="card circle-container">
      <div class="circle-content">
        <h3 class="desktop">Generating</h3>
        <h3 class="mobile">Now</h3>
        <span class="power-value">{{ nowGenerating }}</span> W
      </div>
    </div>
    <div class="card circle-container">
      <div class="circle-content">
        <h3>Total</h3>
         <span class="power-value">{{ this.energy.etotal ? this.energy.etotal.split('kWh')[0] : '...' }}</span> kWh
      </div>
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

