<template>
  <div class="flex items-center lg:grid grid-cols-12 lg:gap-4 mb-2">
    <span class="col-span-3 lg:hidden text-sm font-semibold text-gray-500 dark:text-[#c0bab2]">Pytanie {{ currentQuestionIndex + 1 }}/32</span>
    <span class="col-span-3 hidden lg:block text-sm font-semibold text-gray-500">Kategoria {{ categoryName }}</span>
    <div class="col-span-6 flex items-center justify-end gap-4 max-lg:ml-auto">
      <div class="text-sm font-semibold text-gray-500 dark:text-[#c0bab2] flex items-center gap-1">
        <span class="w-5 h-5">
          <svg class="w-full h-full" viewBox="0 0 24 24" aria-hidden="true">
            <path
              fill="currentColor"
              d="m15.87 15.25l-3.37-2V8.72c0-.4-.32-.72-.72-.72h-.06c-.4 0-.72.32-.72.72v4.72c0 .35.18.68.49.86l3.65 2.19c.34.2.78.1.98-.24c.21-.35.1-.8-.25-1m5.31-10.24L18.1 2.45c-.42-.35-1.05-.3-1.41.13c-.35.42-.29 1.05.13 1.41l3.07 2.56c.42.35 1.05.3 1.41-.13a1 1 0 0 0-.12-1.41M4.1 6.55l3.07-2.56c.43-.36.49-.99.13-1.41a.988.988 0 0 0-1.4-.13L2.82 5.01a1 1 0 0 0-.12 1.41c.35.43.98.48 1.4.13M12 4a9 9 0 1 0 .001 18.001A9 9 0 0 0 12 4m0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7s7 3.14 7 7s-3.14 7-7 7" />
          </svg>
        </span>
        <span>{{ formattedTime }}</span>
      </div>
    </div>
    <button
      @click="toggleMenu()"
      class="col-start-12 flex items-center justify-end max-lg:ml-4 text-gray-400 dark:text-[#c0bab2] group hover:text-slate-800 transition-colors duration-200"
      aria-expanded="false"
      aria-controls="testMenu"
      aria-label="Test menu">
      <svg v-show="!menuIsActiveComputed" class="size-4 group-hover:text-slate-800" viewBox="0 0 24 24">
        <path fill="currentColor" d="M1.5 4.5v3h21v-3zm0 6v3h21v-3zm0 6v3h21v-3z" />
      </svg>

      <svg v-show="menuIsActiveComputed" class="size-5 -ml-0.5 -mr-0.5 group-hover:text-slate-800" viewBox="0 0 24 24">
        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m7 7l10 10M7 17L17 7" />
      </svg>

      <span class="font-medium text-sm ml-1.5 text-gray-500 dark:text-[#c0bab2] group-hover:text-slate-800">Menu</span>
    </button>
  </div>
</template>

<script setup>
import { ref, computed, defineEmits } from "vue";
import { useDateFormat } from "@vueuse/core";

const props = defineProps({
  currentQuestionIndex: Number,
  categoryName: String,
  mainTimeLeft: Number,
  modelValue: Boolean,
});

const emit = defineEmits(["update:modelValue"]);

const menuIsActiveComputed = computed({
  get() {
    return props.modelValue;
  },
  set(value) {
    emit("update:modelValue", value);
  },
});

const formattedTime = useDateFormat(() => props.mainTimeLeft * 1000, "mm:ss");

const toggleMenu = () => {
  menuIsActiveComputed.value = !menuIsActiveComputed.value;
};
</script>

<style scoped></style>
