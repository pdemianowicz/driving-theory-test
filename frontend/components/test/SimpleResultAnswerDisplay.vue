<template>
  <div class="space-y-2">
    <div
      v-for="answer in data.answers"
      :key="answer.id"
      class="p-3 rounded border flex items-center text-sm transition-colors duration-200"
      :class="getAnswerRowClasses(answer)">
      <!-- Ikony wskazujące status -->
      <div class="mr-3 flex-shrink-0 w-5 text-center pt-0.5">
        <span v-if="isUserSelected(answer)" title="Twoja odpowiedź" class="font-bold text-lg text-blue-600 dark:text-blue-400">➔</span>
        <span v-else-if="answer.is_correct" title="Poprawna odpowiedź" class="font-bold text-lg text-green-600 dark:text-green-400">✓</span>
        <!-- Można dodać '✗' dla błędnych, ale może być za dużo informacji wizualnej -->
      </div>
      <!-- Treść odpowiedzi -->
      <span class="flex-grow">{{ answer.content }}</span>
    </div>

    <!-- Podsumowanie poniżej listy -->
    <div class="text-sm normal-case mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
      <template v-if="userAnswer">
        <p>
          <span class="font-semibold">Twoja odpowiedź:</span>
          {{ userAnswer.content }}
          <span v-if="isUserAnswerCorrect" class="ml-1 font-semibold text-green-600 dark:text-green-400">(Poprawnie ✔️)</span>
          <span v-else class="ml-1 font-semibold text-red-600 dark:text-red-400">(Niepoprawnie ❌)</span>
        </p>
        <!-- Pokaż poprawną tylko jeśli użytkownik odpowiedział źle -->
        <p v-if="!isUserAnswerCorrect && correctAnswer" class="mt-1 text-xs text-gray-600 dark:text-gray-400">
          <span class="font-semibold">Poprawna:</span>
          {{ correctAnswer.content }}
        </p>
      </template>
      <template v-else>
        <p class="font-semibold text-orange-600 dark:text-orange-400">Nie udzielono odpowiedzi</p>
        <!-- Pokaż poprawną jeśli użytkownik nie odpowiedział -->
        <p v-if="correctAnswer" class="mt-1 text-xs text-gray-600 dark:text-gray-400">
          <span class="font-semibold">Poprawna:</span>
          {{ correctAnswer.content }}
        </p>
      </template>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  data: {
    // Oczekuje obiektu pytania z `answers` i `user_answer_id`
    type: Object,
    required: true,
  },
});

// Znajdź odpowiedź użytkownika
const userAnswer = computed(() => {
  if (props.data.user_answer_id === null) return null;
  return props.data.answers.find((a) => a.id === props.data.user_answer_id);
});

// Znajdź poprawną odpowiedź
const correctAnswer = computed(() => {
  return props.data.answers.find((a) => a.is_correct);
});

// Czy odpowiedź użytkownika jest poprawna?
const isUserAnswerCorrect = computed(() => {
  return userAnswer.value ? userAnswer.value.is_correct : false;
});

// Sprawdza, czy dana odpowiedź w pętli była wybrana przez użytkownika
const isUserSelected = (answer) => {
  return answer.id === props.data.user_answer_id;
};

// Funkcja do stylowania wierszy odpowiedzi
function getAnswerRowClasses(answer) {
  const classes = [];

  if (answer.is_correct) {
    // Poprawna odpowiedź zawsze lekko podświetlona na zielono
    classes.push("border-green-400 dark:border-green-600 bg-green-50 dark:bg-green-900/20");
  } else if (isUserSelected(answer)) {
    // Błędna odpowiedź użytkownika podświetlona na czerwono
    classes.push("border-red-400 dark:border-red-600 bg-red-50 dark:bg-red-900/20");
  } else {
    // Inne (niepoprawne i niewybrane) - neutralne tło i lekko przygaszone
    classes.push("border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 opacity-75");
  }

  return classes.join(" ");
}
</script>

<style scoped>
/* Można dodać dodatkowe style, jeśli potrzeba */
</style>
