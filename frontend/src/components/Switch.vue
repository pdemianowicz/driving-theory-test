<template>
  <label
    class="flex items-center justify-between cursor-pointer bg-gray-100 dark:bg-neutral-800 hover:opacity-85 py-3 px-4 mb-4 rounded-lg transition-border-bg duration-500">
    <span v-if="label" class="font-medium text-sm">{{ label }}</span>
    <input type="checkbox" class="sr-only" v-model="isChecked" />
    <span
      class="slider flex h-[22px] w-10 items-center rounded-full p-1 bg-gray-200 dark:bg-neutral-700 duration-300"
      :class="{ '!bg-blue-500': isChecked }">
      <span class="size-4 rounded-full bg-white dark:bg-gray-900 duration-300" :class="{ 'translate-x-full': isChecked }"></span>
    </span>
  </label>
</template>

<script setup>
import { defineProps, defineEmits, computed } from "vue";

const props = defineProps({
  modelValue: Boolean,
  label: String,
});

const emit = defineEmits(["update:modelValue"]);

const isChecked = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});
</script>

<style scoped></style>
