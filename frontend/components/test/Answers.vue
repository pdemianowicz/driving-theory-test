<template>
  <div class="flex text-lg uppercase">
    <div v-if="data.type === 'basic'" class="flex flex-row w-full gap-3">
      <!-- <button
        key="true"
        @click="selectAnswer(true)"
        :class="selectedAnswer === true ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021] hover:bg-gray-300 dark:hover:bg-opacity-50'"
        class="py-2 px-4 w-full h-14 md:h-20 text-lg md:text-xl rounded-lg transition-border-bg duration-500">
        Tak
      </button>
      <button
        key="false"
        @click="selectAnswer(false)"
        :class="selectedAnswer === false ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021] hover:bg-gray-300 dark:hover:bg-opacity-50'"
        class="py-2 px-4 w-full h-14 md:h-20 text-lg md:text-xl rounded-lg transition-border-bg duration-500">
        Nie
      </button> -->
      <!-- <button
        v-for="answer in data.answers"
        :key="answer.id"
        @click="selectAnswer(answer.id)"
        :class="isSelectedAnswer(answer.id) ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021] hover:bg-gray-300 dark:hover:bg-opacity-50'"
        class="py-2 px-4 w-full h-14 md:h-20 text-lg md:text-xl rounded-lg transition-border-bg duration-500">
        {{ answer.content }}
      </button> -->
      <button
        v-for="answer in data.answers"
        :key="answer.id"
        @click="selectAnswer(answer.id)"
        :class="isSelectedAnswer(answer.id) ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021] hover:bg-gray-300 dark:hover:bg-opacity-50'"
        class="py-2 px-4 w-full h-14 md:h-16 text-base rounded-lg transition-border-bg duration-500">
        {{ answer.content }}
      </button>
    </div>
    <div v-else class="flex flex-col w-full gap-2">
      <button
        v-for="answer in data.answers"
        :key="answer.id"
        @click="selectAnswer(answer.id)"
        :class="isSelectedAnswer(answer.id) ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021] hover:bg-gray-300 dark:hover:bg-opacity-50'"
        class="py-2 px-4 w-full h-14 md:h-16 text-left text-base rounded-lg transition-border-bg duration-500">
        {{ answer.content }}
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
  modelValue: {
    type: [Number, Boolean, null],
    default: null,
  },
});

const emit = defineEmits(["update:modelValue"]);

const selectedAnswer = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const selectAnswer = (answerValue) => {
  selectedAnswer.value = answerValue;
};

const isSelectedAnswer = (answerId) => {
  return selectedAnswer.value == answerId;
};

watch(
  () => props.data.id,
  () => {
    selectedAnswer.value = null;
  }
);
</script>

<style scoped></style>
