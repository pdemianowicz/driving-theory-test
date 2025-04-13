<template>
  <div class="relative w-full bg-gray-200 dark:bg-neutral-800 overflow-hidden h-3 rounded-full transition-border-bg duration-500">
    <div
      class="absolute top-0 left-0 bottom-0 bg-blue-500 rounded-full transition-all duration-1000"
      :style="{ width: progress + '%', transition: timer === maxTime ? 'none' : 'width 1s linear' }"></div>
  </div>
</template>

<!-- <template>
  <div class="relative w-full bg-gray-200 dark:bg-neutral-800 overflow-hidden h-3 rounded-full transition-colors duration-500">
    <div
      class="absolute top-0 left-0 bottom-0 bg-blue-500 rounded-full transition-transform duration-1000 ease-linear"
      :style="{ transform: `scaleX(${progress})`, transformOrigin: 'left' }"></div>
  </div>
</template> -->

<!-- <template>
  <div class="relative w-full bg-gray-200 overflow-hidden h-8">
    <div class="h-full bg-green-500 transition-all duration-1000" :style="{ width: progress + '%' }"></div>
    <span class="absolute inset-0 flex items-center justify-center">Czas na zapoznanie się z pytaniem {{ remainingTime }} s</span>
  </div>
</template> -->

<script setup>
import { computed, defineProps } from "vue";
const props = defineProps({
  timer: Number,
  maxTime: Number,
});

const progress = computed(() => (props.timer / props.maxTime) * 100);
</script>

<style scoped></style>
