<template>
  <div v-if="status === 'pending'" class="text-center py-10">
    <p>Ładowanie wyników...</p>
  </div>

  <div
    v-else-if="error"
    class="text-center bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 px-4 py-3 rounded relative"
    role="alert">
    <strong class="font-bold">Wystąpił błąd!</strong>
    <span class="block">Nie udało się załadować wyników. Spróbuj ponownie później.</span>
    <pre class="mt-2 text-xs text-left">{{ error }}</pre>
  </div>

  <div v-else-if="status === 'success' && resultsData">
    <div class="py-4 sm:py-6 text-center border-b border-gray-200 dark:border-gray-700">
      <h1 class="text-xl sm:text-2xl font-bold mb-2 text-gray-800 dark:text-gray-100">Podsumowanie Egzaminu</h1>
      <p v-if="didPass" class="text-2xl sm:text-3xl font-semibold text-green-600 dark:text-green-400">ZDAŁEŚ</p>
      <p v-else class="text-2xl sm:text-3xl font-semibold text-red-600 dark:text-red-400">NIE ZDAŁEŚ</p>
      <p class="text-lg mt-1 text-gray-700 dark:text-gray-300">Twój wynik: {{ totalScore }} / {{ maxScore }} pkt.</p>
      <p class="text-sm text-gray-600 dark:text-gray-400">Minimalny próg zaliczenia: {{ passingScore }} / {{ maxScore }} pkt.</p>
    </div>

    <div>
      <div v-if="!currentResult" class="py-4 sm:py-6">
        <p class="text-sm text-center text-gray-600 dark:text-gray-400 mb-3">Kliknij numer pytania poniżej, aby zobaczyć szczegóły.</p>

        <div class="flex flex-row flex-wrap gap-2 justify-center max-w-2xl mx-auto mb-6">
          <button
            v-for="(question, index) in resultsData.questions"
            :key="`grid-full-${question.id}`"
            @click="selectQuestion(index)"
            :aria-label="`Pokaż pytanie ${index + 1}`"
            class="w-10 h-8 flex items-center justify-center text-center font-semibold text-sm cursor-pointer rounded transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900"
            :class="getGridButtonClass(question, index)">
            {{ index + 1 }}
          </button>
        </div>

        <div class="mt-6 flex justify-center gap-4">
          <NuxtLink
            to="/testy-teoretyczne"
            class="px-4 py-2 bg-blue-500 text-white font-semibold text-sm rounded hover:bg-blue-600 transition-colors shadow-sm">
            Powrót do kategorii
          </NuxtLink>
        </div>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
        <div class="lg:col-span-8 order-2 lg:order-1">
          <div class="mb-4 rounded overflow-hidden mx-auto max-w-md lg:max-w-2xl py-4 sm:py-6">
            <TestMediaDisplay :data="currentResult.media" />
          </div>

          <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-stone-100">
            Pytanie {{ currentResultIndex + 1 }}:
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">({{ currentResult.points || "?" }} pkt.)</span>
          </h3>
          <p class="text-base mb-4 text-gray-800 dark:text-stone-200">
            {{ currentResult.content }}
          </p>

          <h4 class="text-md font-semibold mb-2 text-gray-900 dark:text-stone-100">Odpowiedzi:</h4>
          <TestSimpleResultAnswerDisplay class="mb-4" :data="currentResult" />

          <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
            <h4 class="text-md font-semibold mb-2 text-gray-900 dark:text-stone-100">Wyjaśnienie:</h4>
            <p v-if="currentResult.explanation" class="text-sm text-gray-700 dark:text-gray-300">
              {{ currentResult.explanation }}
            </p>
            <p v-else class="text-sm text-gray-500 dark:text-gray-400 italic">Brak dostępnego wyjaśnienia dla tego pytania.</p>
          </div>
        </div>

        <div class="lg:col-span-4 order-1 lg:order-2 lg:top-6 h-fit">
          <div class="border-b lg:border-b-0 border-gray-200 dark:border-gray-700 py-4">
            <button
              @click="hideDetails"
              class="w-full px-3 py-1.5 text-sm text-center text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded transition-colors font-medium"
              title="Ukryj szczegóły pytania i wróć do podsumowania">
              × Ukryj szczegóły
            </button>
            <h3 class="text-lg font-semibold mb-3 text-center text-gray-800 dark:text-gray-100">Nawigacja</h3>

            <div class="flex flex-row flex-wrap gap-1.5 justify-center mb-4">
              <button
                v-for="(question, index) in resultsData.questions"
                :key="`grid-side-${question.id}`"
                @click="selectQuestion(index)"
                :aria-label="`Pokaż pytanie ${index + 1}`"
                class="w-8 h-7 flex items-center justify-center text-center font-semibold text-xs cursor-pointer rounded transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900"
                :class="getGridButtonClass(question, index)">
                {{ index + 1 }}
              </button>
            </div>

            <div class="flex justify-between gap-2 mb-4">
              <button
                @click="previousQuestion"
                :disabled="currentResultIndex === 0"
                class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors w-full text-gray-800 dark:text-gray-200">
                < Poprzednie
              </button>
              <button
                @click="nextQuestion"
                :disabled="resultsData.questions && currentResultIndex === resultsData.questions.length - 1"
                class="px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors w-full text-gray-800 dark:text-gray-200">
                Następne >
              </button>
            </div>

            <div class="flex flex-col gap-2">
              <button
                @click="addToFavorites(currentResult.id)"
                class="px-3 py-1.5 text-sm bg-yellow-100 dark:bg-yellow-900/40 border border-yellow-400 dark:border-yellow-600 rounded hover:bg-yellow-200 dark:hover:bg-yellow-900/60 transition-colors text-yellow-800 dark:text-yellow-200 w-full">
                Dodaj do ulubionych
              </button>
              <NuxtLink
                to="/testy-teoretyczne"
                class="px-3 py-1.5 text-sm bg-blue-500 text-white text-center font-semibold rounded hover:bg-blue-600 transition-colors shadow-sm w-full">
                Powrót do kategorii
              </NuxtLink>
            </div>

            <div class="mt-4 text-center text-xs text-gray-600 dark:text-gray-400">
              Wynik: {{ totalScore }} / {{ maxScore }} pkt. ({{ didPass ? "Zaliczono" : "Nie zaliczono" }})
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  ssr: false,
  layout: "test",
});
const config = useRuntimeConfig();
const route = useRoute();
const router = useRouter();

const testId = ref(route.query.testId || 13);

const { data, status, error } = await useFetch(() => `${config.public.apiBase}/api/${testId.value}/results`, { key: `test-results-${testId.value}` });

const resultsData = computed(() => data.value);

const currentResultIndex = ref(null);

const currentResult = computed(() => {
  if (resultsData.value?.questions && currentResultIndex.value !== null) {
    return resultsData.value.questions[currentResultIndex.value];
  }
  return null;
});

const passingScore = computed(() => resultsData.value?.passing_threshold || 68);
const maxScore = computed(() => resultsData.value?.max_score || 74);
const totalScore = computed(() => {
  if (!resultsData.value?.questions) return 0;
  return resultsData.value.questions.reduce((sum, q) => {
    if (isCorrectAnswer(q)) {
      return sum + (q.points || 0);
    }
    return sum;
  }, 0);
});
const didPass = computed(() => totalScore.value >= passingScore.value);

const isCorrectAnswer = (question) => {
  return question && question.user_answer_id !== null && question.answers.some((a) => a.id === question.user_answer_id && a.is_correct);
};
const isIncorrectAnswer = (question) => {
  return question && question.user_answer_id !== null && question.answers.some((a) => a.id === question.user_answer_id && !a.is_correct);
};
const isUnanswered = (question) => {
  return !question || question.user_answer_id === null;
};
const getGridButtonClass = (question, index) => {
  const classes = [];
  if (!question) return "bg-gray-200 opacity-50";

  if (isCorrectAnswer(question)) {
    classes.push("bg-green-500/80 hover:bg-green-500/100 text-white");
  } else if (isIncorrectAnswer(question)) {
    classes.push("bg-red-500/80 hover:bg-red-500/100 text-white");
  } else {
    classes.push("bg-gray-300 dark:bg-gray-500 hover:bg-gray-400 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100");
  }
  if (currentResultIndex.value === index) {
    classes.push("ring-2 ring-offset-2 ring-blue-500 dark:ring-offset-gray-900");
  }
  return classes.join(" ");
};

const selectQuestion = (index) => {
  currentResultIndex.value = index;
};

const previousQuestion = () => {
  if (currentResultIndex.value > 0) {
    currentResultIndex.value--;
  }
};

const nextQuestion = () => {
  if (resultsData.value && currentResultIndex.value < resultsData.value.questions.length - 1) {
    currentResultIndex.value++;
  }
};

const addToFavorites = (questionId) => {
  console.log("Dodaj do ulubionych:", questionId);
  alert(`Pytanie ${questionId} dodane do ulubionych (implementacja w toku).`);
};

const shouldCenterQuestion = computed(() => {
  const contentLength = currentResult.value?.content?.length || 0;
  return contentLength < 100;
});

const hideDetails = () => {
  currentResultIndex.value = null;
  // Opcjonalnie: Przewiń stronę do góry sekcji podsumowania, jeśli jest potrzeba
  nextTick(() => {
    const summaryElement = document.querySelector(".summary-section-class");
    if (summaryElement) {
      summaryElement.scrollIntoView({ behavior: "smooth", block: "start" });
    } else {
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  });
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
