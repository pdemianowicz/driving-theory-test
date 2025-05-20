<template>
  <div class="flex items-center lg:grid grid-cols-12 lg:gap-4 mb-2">
    <div class="col-span-12 lg:col-span-5 flex items-center font-semibold text-sm text-gray-500 dark:text-[#c0bab2]">
      <span class="lg:hidden">Pytanie {{ currentQuestionIndex + 1 }}/32</span>
      <span class="hidden lg:block">{{ categoryName }}</span>
      <span class="ml-1 text-xs">({{ questionPoints }} pkt.)</span>
    </div>

    <div class="col-span-12 lg:col-span-7 flex items-center justify-end gap-4 max-lg:ml-auto">
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
  </div>
</template>

<script setup>
const props = defineProps({
  currentQuestionIndex: {
    type: Number,
    required: true,
  },
  categoryName: {
    type: String,
    required: true,
  },
  mainTimeLeft: {
    type: Number,
    required: true,
  },
  questionPoints: {
    type: Number,
    required: true,
  },
});

const formattedTime = useDateFormat(() => props.mainTimeLeft * 1000, "mm:ss");
</script>

<style scoped></style>
