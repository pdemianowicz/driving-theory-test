<template>
  <div class="relative w-full bg-gray-200 overflow-hidden h-8">
    <div class="h-full bg-green-500 transition-all duration-1000" :style="{ width: progress + '%' }"></div>
    <span class="absolute inset-0 flex items-center justify-center">Czas na zapoznanie się z pytaniem {{ remainingTime }} s</span>
  </div>
</template>

<script setup>
import { computed, defineProps } from "vue";
const props = defineProps({
  timer: Number,
});

const progress = computed(() => (props.timer / 30) * 100);
const remainingTime = computed(() => props.timer);
</script>

<style scoped></style>
