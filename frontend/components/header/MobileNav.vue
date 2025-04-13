<template>
  <div class="z-20">
    <button
      @click="emitToggle()"
      aria-label="Otwórz/zamknij menu"
      aria-controls="primaryNav"
      :aria-expanded="isOpen.toString()"
      class="relative inline-flex items-center cursor-pointer justify-center text-slate-800 dark:text-gray-300 w-[40px] h-[40px]">
      <svg
        v-if="!isOpen"
        class="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2">
        <path d="M4 12h16M4 18h16M4 6h16"></path>
      </svg>
      <svg
        v-else
        class="h-5 w-5"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2">
        <line x1="18" y1="6" x2="6" y2="18" />
        <line x1="6" y1="6" x2="18" y2="18" />
      </svg>
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["toggle-menu"]);

function emitToggle() {
  emit("toggle-menu");
}
</script>

<style lang="scss" scoped></style>
