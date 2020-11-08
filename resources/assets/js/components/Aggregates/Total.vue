<template>
  <div>
    <div class="py-2 px-4 sm:p-4 text-center">
      <span class="font-semibold sm:font-medium block text-lg sm:text-2xl tracking-wider">
        Total
      </span>
      <span class="font-hairline">kWh</span>

      <div class="sm:text-2xl text-xl font-thin" v-for="powerStation in powerStations">
        <span>
          {{ powerStation.total }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['powerStations']
}
</script>

