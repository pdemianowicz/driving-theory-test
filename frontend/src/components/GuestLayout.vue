<template>
  <div class="flex min-h-full flex-1 flex-col justify-center sm:px-6 py-12 lg:px-8 dark:bg-[#1d2021] -mt-[56px]">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="text-center text-2xl/9 font-bold tracking-tight text-gray-900 dark:text-stone-300">
        <slot name="header"></slot>
      </h2>
    </div>

    <div v-if="errorMessage" class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px] text-center py-2 px-3 rounded text-white bg-red-400">
      {{ errorMessage }}
    </div>

    <div class="mx-auto mt-10 w-full">
      <div class="sm:mx-auto sm:w-full sm:max-w-[480px] bg-white dark:bg-[#181a1b] px-6 py-12 rounded-lg shadow">
        <form @submit.prevent="submit" class="space-y-6" action="#" method="POST">
          <slot></slot>
        </form>
      </div>
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
  submit: Function,
  errorMessage: String,
});
</script>

<style lang="scss" scoped></style>
