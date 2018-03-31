<template>
  <div class="energy-container">
    <div class="card circle-container">
        <div class="circle-content">
          <h3>Today</h3>
          <div class="power-text">{{ this.energy.eday ? this.energy.eday.split('kWh')[0] : '...'}} </div> kWh
        </div>
    </div>
    <div class="card circle-container">
      <div class="circle-content">
        <h3>Generating</h3>
        {{ nowGenerating }} W
      </div>
    </div>
    <div class="card circle-container">
      <div class="circle-content">
        <h3>Total</h3>
         {{ this.energy.etotal ? this.energy.etotal.split('kWh')[0] : '...' }} kWh
      </div>
    </div>
  </div>
</template>

<script>
import goodwe from "../services/goodwe/Goodwe";

export default {
  props: ["goodweId"],

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
      const power = await goodwe.current(this.goodweId);
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

