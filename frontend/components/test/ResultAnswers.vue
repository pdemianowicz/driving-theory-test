<template>
  <div class="flex text-lg uppercase">
    <div v-if="data.type === 'basic'" :class="data.type === 'basic' ? 'flex flex-row w-full gap-3' : 'flex flex-col w-full gap-2'">
      <button
        v-for="answer in data.answers"
        :key="answer.id"
        :class="getAnswerClasses(answer)"
        class="py-2 px-4 w-full h-14 md:h-16 text-base rounded-lg transition-border-bg duration-500 cursor-default">
        {{ answer.content }}
      </button>
      <!--    :class="data.user_answer_id == answer.id ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021]'" -->
    </div>
    <div v-else class="flex flex-col w-full gap-2">
      <button
        v-for="answer in data.answers"
        :key="answer.id"
        :class="getAnswerClasses(answer)"
        class="py-2 px-4 w-full h-14 md:h-16 text-left text-base rounded-lg transition-border-bg duration-500 cursor-default">
        {{ answer.content }}
      </button>
      <!--    :class="data.user_answer_id == answer.id ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-[#1d2021]'" -->
    </div>

    <div class="text-sm normal-case mt-2 text-gray-700 dark:text-gray-300">
      <template v-if="userAnswerContent">
        Twoja odpowiedź:
        <span class="font-semibold">{{ userAnswerContent }}</span>
        <span v-if="isUserAnswerCorrect" class="text-green-600 font-semibold">(Poprawnie ✔️)</span>
        <span v-else class="text-red-600 font-semibold">(Niepoprawnie ❌)</span>
      </template>
      <template v-else>
        <span class="font-semibold text-orange-600">Nie udzielono odpowiedzi</span>
      </template>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
});

function getAnswerClasses(answer) {
  const isCorrect = answer.is_correct;

  if (isCorrect) {
    return "bg-green-100 dark:bg-green-900/50 border-2 border-green-500 text-green-800 dark:text-green-200";
  } else {
    return "bg-gray-200 dark:bg-[#1d2021]/80 border-2 border-transparent text-gray-800 dark:text-gray-300 opacity-80"; // Lekko przygaszone
  }
}

const userAnswerContent = computed(() => {
  if (props.data.user_answer_id === null) {
    return null;
  }
  const userAnswer = props.data.answers.find((ans) => ans.id === props.data.user_answer_id);
  return userAnswer ? userAnswer.content : "Nieznana odpowiedź";
});

const isUserAnswerCorrect = computed(() => {
  if (props.data.user_answer_id === null) {
    return false;
  }
  const userAnswer = props.data.answers.find((ans) => ans.id === props.data.user_answer_id);
  return userAnswer ? userAnswer.is_correct : false;
});
</script>

<style scoped></style>
