<template>
  <div class="horizontal-fade-mask">
    <div class="grid grid-cols-10 gap-6">
      <div
        v-for="i in 50"
        :key="i"
        class="w-24 h-24 rounded-xl bg-neutral-100 dark:bg-[#1d2021] border dark:border-none flex items-center justify-center"></div>
    </div>
  </div>
</template>

<script setup>
const categories = ref(["A", "B", "C", "D", "T", "AM", "A1", "A2", "B1", "C1", "D1", "PT"]);
</script>

<style scoped>
.horizontal-fade-mask {
  mask-image: linear-gradient(90deg, transparent 0%, black 300px, black calc(100% - 300px), transparent 100%);
  -webkit-mask-image: linear-gradient(90deg, transparent 0%, black 300px, black calc(100% - 300px), transparent 100%);
}
</style>
