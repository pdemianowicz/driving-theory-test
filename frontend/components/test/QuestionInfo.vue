<template>
  <div class="hidden lg:grid grid-cols-2 gap-4 mt-4 rounded-lg text-sm font-semibold text-gray-800 dark:text-stone-300">
    <div class="flex flex-col">
      <p>
        Pytania
        <br />
        podstawowe
      </p>
      <span class="text-base text-slate-800 bg-gray-200 px-6 py-0.5 rounded-sm text-center">{{ basicQuestionCount }} z 20</span>
    </div>
    <div class="flex flex-col">
      <p>Pytania specjialistyczne</p>
      <span class="text-base text-slate-800 bg-gray-200 px-6 py-0.5 rounded-sm text-center">{{ specialistQuestionCount }} z 12</span>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  basicQuestionCount: Number,
  specialistQuestionCount: Number,
});
</script>

<style scoped></style>
