<template>
  <li
    @click="emit('select', category.id)"
    class="sm:max-w-sm p-6 rounded-lg shadow hover:bg-gray-200 cursor-pointer bg-neutral-100 dark:bg-[#1d2021] transition-border-bg duration-500">
    <a class="text-2xl font-bold flex items-center justify-center">{{ category.name }}</a>
    <p>{{ category.description }}</p>
  </li>
</template>

<script setup>
defineProps({
  category: Object,
});
const emit = defineEmits(["select"]);
</script>

<style lang="scss" scoped></style>
