<template>
  <button
    @click="$emit('click', $event)"
    class="p-1.5 w-full bg-gray-200 text-slate-800 font-semibold text-sm rounded-sm hover:bg-opacity-80 transition-colors shadow-sm">
    <slot />
  </button>
</template>

<script setup>
defineEmits(["click"]);
</script>

<style scoped></style>
