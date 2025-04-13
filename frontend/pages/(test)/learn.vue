<template>
  <!-- Stan Ładowania - Skeleton -->
  <div v-if="pending" class="animate-pulse">
    <!-- Szkielet Nagłówka -->
    <div class="flex justify-between items-center mb-4 sm:mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
      <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-1/3"></div>
      <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-1/4"></div>
    </div>

    <div class="grid lg:grid-cols-12 lg:grid-rows-[auto] gap-4">
      <!-- Szkielet Lewej Kolumny (Media) -->
      <div class="col-span-12 lg:col-span-9">
        <div class="aspect-video bg-gray-300 dark:bg-gray-700 rounded"></div>
        <!-- Placeholder dla mediów -->
      </div>

      <!-- Szkielet Prawej Kolumny -->
      <div class="hidden lg:flex flex-col lg:col-span-3">
        <div class="h-8 bg-gray-300 dark:bg-gray-700 rounded mb-4"></div>
        <!-- Placeholder dla przycisku Przerwij -->
        <div class="mt-auto space-y-2">
          <div class="h-8 bg-gray-300 dark:bg-gray-700 rounded"></div>
          <!-- Placeholder dla Oznacz -->
          <div class="h-8 bg-gray-300 dark:bg-gray-700 rounded"></div>
          <!-- Placeholder dla Wyjaśnienie -->
          <div class="h-11 bg-gray-400 dark:bg-gray-600 rounded mt-2"></div>
          <!-- Placeholder dla Dalej -->
        </div>
      </div>

      <!-- Szkielet Pytania -->
      <div class="col-span-12">
        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-full mb-2"></div>
        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-full"></div>
      </div>

      <!-- Szkielet Odpowiedzi -->
      <div class="col-span-12 space-y-2 mb-5">
        <div class="h-10 bg-gray-300 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-10 bg-gray-300 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-10 bg-gray-300 dark:bg-gray-700 rounded w-full"></div>
      </div>

      <!-- Szkielet Przycisku Dalej (Mobile) -->
      <div class="lg:hidden col-span-12 h-14 bg-gray-400 dark:bg-gray-600 rounded mt-4"></div>
    </div>
  </div>

  <div v-else-if="error || !learnData" class="text-center py-20 text-red-500 dark:text-red-400">
    <p>Nie udało się załadować pytań dla tej kategorii.</p>
    <p class="text-sm mt-2">{{ error?.message || "Brak danych." }}</p>
    <TestButton @click="goBack" class="mt-4 max-w-xs mx-auto !bg-gray-300 dark:!bg-gray-600">Wróć do wyboru</TestButton>
  </div>

  <div v-else-if="currentQuestion">
    <div class="flex items-center justify-between font-semibold text-sm text-gray-500 dark:text-[#c0bab2] mb-2">
      <span class="">
        Kategoria {{ categoryName }}
        <span class="ml-1 text-xs">({{ currentQuestion.points }} pkt.)</span>
      </span>
      <span class="">Pytanie {{ currentQuestionIndex + 1 }} / {{ totalQuestions }}</span>
    </div>

    <div class="grid lg:grid-cols-12 lg:grid-rows-[auto] gap-4">
      <div class="col-span-12 lg:col-span-9">
        <TestMediaDisplay :data="currentQuestion.media" />
      </div>

      <div class="hidden lg:flex flex-col lg:col-span-3">
        <TestButton class="hidden lg:block">Przerwij naukę</TestButton>

        <div class="flex flex-col gap-2 mt-auto">
          <!-- <TestButton @click="">Dodaj do ulubionych</TestButton> -->
          <button
            @click="toggleFlag"
            class="mt-3 text-sm flex items-center gap-1.5 text-gray-500 hover:text-blue-600 dark:text-stone-400 dark:hover:text-blue-400 transition-colors">
            <svg
              class="w-4 h-4"
              :class="{ 'text-blue-600 dark:text-blue-500 fill-current': isCurrentFlagged }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-14a2 2 0 012-2h11a2 2 0 012 2v14l-7-3.5L3 21z" />
            </svg>
            {{ isCurrentFlagged ? "Oznaczono" : "Oznacz do powtórki" }}
          </button>
          <TestButton @click="">Wyjaśnienie</TestButton>

          <TestButton
            @click="nextQuestion"
            :disabled="!isAnswered || isLastQuestion"
            class="w-full !bg-blue-500 !text-neutral-50 !text-base h-11 mt-2">
            {{ isLastQuestion ? "Koniec Nauki" : "Następne Pytanie" }}
          </TestButton>
        </div>
      </div>

      <TestQuestion :data="currentQuestion.content" class="col-span-12" />

      <!-- Odpowiedzi, Wyjaśnienie, Nawigacja -->
      <div class="col-span-12">
        <div :class="['flex w-full gap-2 mb-5', currentQuestion.type === 'basic' ? 'flex-row' : 'flex-col']">
          <button
            v-for="answer in currentQuestion.answers"
            :key="answer.id"
            @click="selectAnswer(answer.id)"
            :disabled="isAnswered"
            :class="[
              'w-full p-3 text-sm rounded-lg border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:ring-blue-500 disabled:cursor-not-allowed',
              getAnswerButtonClass(answer),
              currentQuestion.type === 'basic' ? 'text-center' : 'text-left',
            ]">
            {{ answer.content }}
          </button>
        </div>

        <!-- Sekcja Wyjaśnienia (widoczna po odpowiedzi) -->
        <div v-if="isAnswered && currentQuestion.explanation" class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700 mb-5">
          <h3 class="text-md font-semibold mb-2 text-slate-800 dark:text-stone-300">Wyjaśnienie:</h3>
          <p class="text-sm text-gray-700 dark:text-stone-400">{{ currentQuestion.explanation }}</p>
        </div>
        <div v-else-if="isAnswered && !currentQuestion.explanation" class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700 mb-5">
          <p class="text-sm text-gray-500 dark:text-stone-500 italic">Brak wyjaśnienia dla tego pytania.</p>
        </div>
      </div>

      <!-- Przycisk Następne Pytanie - Wersja Mobile -->
      <TestButton
        @click="nextQuestion"
        :disabled="!isAnswered || isLastQuestion"
        class="col-span-12 lg:hidden w-full !bg-blue-500 !text-neutral-50 !text-lg h-14 mt-4 disabled:!bg-gray-300 dark:disabled:!bg-gray-600">
        {{ isLastQuestion ? "Koniec Nauki" : "Następne Pytanie" }}
      </TestButton>
    </div>
  </div>

  <!-- 4. Widok Końca Nauki -->
  <div v-else-if="!pending && learnData && currentQuestionIndex >= totalQuestions" class="text-center py-16">
    <h2 class="text-2xl font-semibold mb-4 text-slate-800 dark:text-stone-300">Zakończono sesję nauki!</h2>
    <p class="text-gray-700 dark:text-stone-400 mb-6">
      Przejrzałeś wszystkie {{ totalQuestions }} pytań dla kategorii {{ categoryName }}.
      <span v-if="flaggedQuestions.size > 0">Oznaczono {{ flaggedQuestions.size }} pytań do powtórki.</span>
    </p>
    <div class="flex flex-col sm:flex-row justify-center gap-4">
      <TestButton @click="restartLearning" class="!bg-green-500 !text-white">Rozpocznij od nowa</TestButton>
      <TestButton @click="goBack" class="!bg-gray-300 dark:!bg-gray-600">Wróć do wyboru kategorii</TestButton>
      <!-- Można dodać przycisk "Powtórz oflagowane" -->
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  ssr: false,
  layout: "test",
});

const route = useRoute();
const router = useRouter();
const config = useRuntimeConfig();

// ID kategorii z URL
const categoryId = computed(() => route.query.id);

// Stan komponentu
const learnData = ref(null); // Przechowa wszystkie pytania i nazwę kategorii
const pending = ref(true); // Zamiast useFetch pending, zarządzamy sami
const error = ref(null);
const currentQuestionIndex = ref(0);
const selectedAnswerId = ref(null); // ID odpowiedzi wybranej przez użytkownika
const isAnswered = ref(false); // Czy użytkownik odpowiedział na bieżące pytanie?
const shuffledQuestions = ref([]); // Przetasowana lista pytań
const flaggedQuestions = ref(new Set()); // Zbiór ID oflagowanych pytań (tylko w tej sesji)

// Pobieranie danych
async function fetchLearnData() {
  if (!categoryId.value) {
    error.value = new Error("Brak ID kategorii w adresie URL.");
    pending.value = false;
    return;
  }
  pending.value = true;
  error.value = null;
  try {
    // UWAGA: Dostosuj URL endpointu API!
    const data = await $fetch(`${config.public.apiBase}/api/categories/${categoryId.value}/learn-questions`);
    if (!data || !data.questions || !data.category) {
      throw new Error("Otrzymano niekompletne dane z API.");
    }
    learnData.value = data; // Zawiera np. { category: { name: 'B' }, questions: [...] }
    shuffleQuestions(); // Tasuj pytania po pobraniu
  } catch (err) {
    console.error("Błąd podczas pobierania pytań do nauki:", err);
    error.value = err;
    learnData.value = null;
  } finally {
    pending.value = false;
  }
}

// Tasowanie pytań (prosty algorytm Fisher-Yates)
function shuffleQuestions() {
  if (!learnData.value || !learnData.value.questions) return;
  let array = [...learnData.value.questions]; // Kopia tablicy
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]]; // Zamiana miejscami
  }
  shuffledQuestions.value = array;
  currentQuestionIndex.value = 0; // Zresetuj indeks po tasowaniu
  resetQuestionState(); // Zresetuj stan odpowiedzi
}

// === Computed Properties ===
const categoryName = computed(() => learnData.value?.category?.name || "");
const totalQuestions = computed(() => shuffledQuestions.value.length);
const currentQuestion = computed(() => {
  if (shuffledQuestions.value.length > 0 && currentQuestionIndex.value < totalQuestions.value) {
    return shuffledQuestions.value[currentQuestionIndex.value];
  }
  return null;
});
const isLastQuestion = computed(() => currentQuestionIndex.value >= totalQuestions.value - 1);

const isCurrentFlagged = computed(() => {
  return currentQuestion.value && flaggedQuestions.value.has(currentQuestion.value.id);
});

// === Metody Akcji ===
function selectAnswer(answerId) {
  if (isAnswered.value) return; // Nie pozwól zmienić odpowiedzi
  selectedAnswerId.value = answerId;
  isAnswered.value = true;
  // Feedback jest obsługiwany przez klasy CSS
}

function nextQuestion() {
  if (!isAnswered.value || isLastQuestion.value) return; // Przejdź dalej tylko po odpowiedzi i jeśli to nie ostatnie
  currentQuestionIndex.value++;
  resetQuestionState();
}

function resetQuestionState() {
  selectedAnswerId.value = null;
  isAnswered.value = false;
}

function restartLearning() {
  shuffleQuestions(); // Ponownie tasuj i zacznij od początku
}

function goBack() {
  router.push("/testy-teoretyczne"); // Wróć do strony wyboru
}

function toggleFlag() {
  if (!currentQuestion.value) return;
  const questionId = currentQuestion.value.id;
  if (flaggedQuestions.value.has(questionId)) {
    flaggedQuestions.value.delete(questionId);
  } else {
    flaggedQuestions.value.add(questionId);
  }
}

// === Metody Pomocnicze (Stylizacja) ===
function getAnswerButtonClass(answer) {
  const baseClasses =
    "border text-left text-sm rounded-lg p-3 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:ring-blue-500";
  const disabledClasses = "disabled:cursor-not-allowed";
  const defaultClasses =
    "bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700";
  const correctAnswerClasses =
    "bg-green-100 dark:bg-green-900/30 border-green-500 dark:border-green-600 text-green-800 dark:text-green-200 font-medium";
  const incorrectAnswerClasses = "bg-red-100 dark:bg-red-900/30 border-red-500 dark:border-red-600 text-red-800 dark:text-red-200";
  const neutralAnsweredClasses = "bg-gray-100 dark:bg-gray-700/50 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 opacity-75"; // Dla nie-wybranych po odpowiedzi

  if (!isAnswered.value) {
    return `${baseClasses} ${defaultClasses}`;
  } else {
    // Po odpowiedzi
    if (answer.is_correct) {
      // Zaznacz poprawną zawsze na zielono
      return `${baseClasses} ${correctAnswerClasses} ${disabledClasses}`;
    } else if (answer.id === selectedAnswerId.value) {
      // Zaznacz wybraną błędną na czerwono
      return `${baseClasses} ${incorrectAnswerClasses} ${disabledClasses}`;
    } else {
      // Pozostałe błędne odpowiedzi są neutralne/przygaszone
      return `${baseClasses} ${neutralAnsweredClasses} ${disabledClasses}`;
    }
  }
}

// === Cykl Życia ===
onMounted(() => {
  fetchLearnData();
});

// Obserwuj zmianę ID kategorii w URL (jeśli użytkownik zmieni przez link bez przeładowania)
watch(categoryId, (newId, oldId) => {
  if (newId !== oldId) {
    fetchLearnData();
  }
});
</script>

<style scoped>
.loader {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  position: relative;
  animation: rotate 1s linear infinite;
}
.loader::before {
  content: "";
  box-sizing: border-box;
  position: absolute;
  inset: 0px;
  border-radius: 50%;
  border: 5px solid #fff;
  animation: prixClipFix 2s linear infinite;
}

@keyframes rotate {
  100% {
    transform: rotate(360deg);
  }
}

@keyframes prixClipFix {
  0% {
    clip-path: polygon(50% 50%, 0 0, 0 0, 0 0, 0 0, 0 0);
  }
  25% {
    clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 0, 100% 0, 100% 0);
  }
  50% {
    clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 100% 100%, 100% 100%);
  }
  75% {
    clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 100%);
  }
  100% {
    clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 0);
  }
}
</style>
