<template>
  <div class="flex text-lg uppercase">
    <div v-if="data.question_type === 'basic'" class="flex flex-row w-full gap-3">
      <button
        v-for="answer in data.answers"
        :key="answer.id"
        @click="selectAnswer(answer.id)"
        :class="selectedAnswer === answer.id ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021] hover:bg-gray-300 dark:hover:bg-opacity-50'"
        class="py-2 px-4 w-full h-14 md:h-20 text-lg md:text-xl rounded-lg transition-border-bg duration-500">
        {{ answer.answer_content }}
      </button>
    </div>
    <div v-else class="flex flex-col w-full gap-2">
      <button
        v-for="answer in data.answers"
        :key="answer.id"
        @click="selectAnswer(answer.id)"
        :class="selectedAnswer === answer.id ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021] hover:bg-gray-300 dark:hover:bg-opacity-50'"
        class="py-2 px-4 w-full h-14 md:h-16 text-left text-base rounded-lg transition-border-bg duration-500">
        {{ answer.answer_content }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed, toRefs } from "vue";

const props = defineProps({
  data: Object,
  modelValue: Number,
});

const { data } = toRefs(props);
const emit = defineEmits(["update:modelValue"]);

const selectedAnswer = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const selectAnswer = (answerId) => {
  selectedAnswer.value = answerId;
};
</script>

<style scoped></style>
