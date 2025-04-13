<template>
  <div class="bg-gray-100 dark:bg-[#1d2021] p-2 md:p-4 lg:p-6 rounded-lg shadow transition-border-bg duration-500">
    <p class="text-base font-semibold text-gray-800 dark:text-stone-300">{{ data }}</p>
  </div>
</template>

<script setup>
import { toRefs } from "vue";

const props = defineProps({
  data: String,
});

const { data } = toRefs(props);
</script>

<style scoped></style>
