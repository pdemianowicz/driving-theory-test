// --- Helpery LocalStorage ---
const getLocalStorageKey = (catSlug) => (catSlug ? `learnState_v2_slug_${catSlug}` : null);

function saveMinimalStateToLocalStorage(categorySlug, state) {
  const key = getLocalStorageKey(categorySlug);
  if (!key || !state) return;
  try {
    const stateToSave = {
      currentQuestionIndex: state.currentQuestionIndex,
      shuffledQuestionIds: state.shuffledQuestionIds,
      flaggedQuestionIds: Array.from(state.flaggedQuestions), // Konwertujemy Set na Array
      timestamp: Date.now(),
    };
    localStorage.setItem(key, JSON.stringify(stateToSave));
  } catch (e) {
    console.error("LS Save Error:", e);
  }
}

function loadMinimalStateFromLocalStorage(categorySlug) {
  const key = getLocalStorageKey(categorySlug);
  if (!key) return null;
  const savedString = localStorage.getItem(key);
  if (!savedString) return null;
  try {
    const saved = JSON.parse(savedString);
    if (
      saved &&
      typeof saved.currentQuestionIndex === "number" &&
      Array.isArray(saved.shuffledQuestionIds) &&
      Array.isArray(saved.flaggedQuestionIds)
    ) {
      return saved;
    }
    console.warn("Invalid LS structure for slug:", categorySlug);
    clearStateFromLocalStorage(categorySlug);
    return null;
  } catch (e) {
    console.error("LS Parse Error for slug:", categorySlug, e);
    clearStateFromLocalStorage(categorySlug);
    return null;
  }
}

function clearStateFromLocalStorage(categorySlug) {
  const key = getLocalStorageKey(categorySlug);
  if (key) {
    localStorage.removeItem(key);
  }
}

// --- Helper Tasowania ---
function shuffleArray(array) {
  let currentIndex = array.length,
    randomIndex;
  while (currentIndex !== 0) {
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex--;
    [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
  }
  return array;
}

// --- Główny Composable ---
export function useLearnMode() {
  const route = useRoute();
  const router = useRouter();
  const config = useRuntimeConfig();
  const nuxtApp = useNuxtApp();

  // --- Stan ---
  const categorySlug = computed(() => {
    if (route.params.categorySlug && typeof route.params.categorySlug === "string") {
      return route.params.categorySlug;
    }
    return null;
  });
  const allQuestionsData = ref([]);
  const categoryName = ref("");
  const pending = ref(true);
  const error = ref(null); // Może być obiekt z polem 'message'

  const shuffledQuestions = ref([]);
  const currentQuestionIndex = ref(0);
  const selectedAnswerId = ref(null);
  const isAnswered = ref(false);
  const flaggedQuestions = ref(new Set());
  const shouldShowExplanation = ref(false);
  const isSessionFinished = ref(false);

  // --- Computed Properties ---
  const totalQuestions = computed(() => shuffledQuestions.value.length);
  const currentQuestion = computed(() => {
    if (shuffledQuestions.value.length > 0 && currentQuestionIndex.value < totalQuestions.value) {
      return shuffledQuestions.value[currentQuestionIndex.value];
    }
    return null;
  });
  const currentQuestionDisplayIndex = computed(() => currentQuestionIndex.value);
  const isLastQuestion = computed(() => currentQuestion.value && currentQuestionIndex.value >= totalQuestions.value - 1 && totalQuestions.value > 0);
  const isCurrentFlagged = computed(() => currentQuestion.value && flaggedQuestions.value.has(currentQuestion.value.id));
  // Poprawiona logika isSessionFinished - sesja jest zakończona, gdy nie ma pytań (i nie było błędu API) LUB gdy indeks wyszedł poza zakres
  isSessionFinished.value = computed(
    () =>
      !pending.value &&
      ((allQuestionsData.value.length === 0 && !error.value) || (currentQuestionIndex.value >= totalQuestions.value && totalQuestions.value > 0))
  );

  // --- Funkcje Wewnętrzne ---
  function _resetQuestionStateVisuals() {
    selectedAnswerId.value = null;
    isAnswered.value = false;
    shouldShowExplanation.value = false;
  }

  function _saveCurrentState() {
    if (!categorySlug.value || shuffledQuestions.value.length === 0) return;
    const stateToSave = {
      currentQuestionIndex: currentQuestionIndex.value,
      shuffledQuestionIds: shuffledQuestions.value.map((q) => q.id),
      flaggedQuestions: flaggedQuestions.value,
    };
    saveMinimalStateToLocalStorage(categorySlug.value, stateToSave);
  }

  async function _initialize() {
    if (!categorySlug.value) {
      error.value = { message: "Brak slugu kategorii w adresie URL." };
      pending.value = false;
      return;
    }
    pending.value = true;
    error.value = null;
    allQuestionsData.value = [];
    shuffledQuestions.value = [];
    categoryName.value = "";
    currentQuestionIndex.value = 0;
    flaggedQuestions.value = new Set();
    isSessionFinished.value = false; // Resetuj stan zakończenia
    _resetQuestionStateVisuals();

    try {
      const headers = {
        // "X-Locale": nuxtApp.$i18n?.locale?.value || "pl_PL",
        "X-Locale": "pl",
      };

      const data = await $fetch(`${config.public.apiBase}/api/learn-questions/${categorySlug.value}`, { headers });

      if (!data || !data.questions || !data.category || !Array.isArray(data.questions)) {
        throw new Error("Otrzymano niekompletne lub nieprawidłowe dane z API.");
      }
      allQuestionsData.value = data.questions;
      categoryName.value = data.category.name;

      const savedState = loadMinimalStateFromLocalStorage(categorySlug.value);
      let stateLoaded = false;

      if (savedState && allQuestionsData.value.length > 0) {
        const questionsMap = new Map(allQuestionsData.value.map((q) => [q.id, q]));
        const reconstructedShuffled = savedState.shuffledQuestionIds.map((id) => questionsMap.get(id)).filter((q) => q !== undefined);

        if (reconstructedShuffled.length === savedState.shuffledQuestionIds.length) {
          shuffledQuestions.value = reconstructedShuffled;
          currentQuestionIndex.value = savedState.currentQuestionIndex;
          // Walidacja indeksu
          if (currentQuestionIndex.value >= shuffledQuestions.value.length && shuffledQuestions.value.length > 0) {
            currentQuestionIndex.value = shuffledQuestions.value.length - 1;
          } else if (currentQuestionIndex.value >= shuffledQuestions.value.length && shuffledQuestions.value.length === 0) {
            currentQuestionIndex.value = 0;
          }

          flaggedQuestions.value = new Set(savedState.flaggedQuestionIds);
          _resetQuestionStateVisuals();
          stateLoaded = true;
        } else {
          console.warn("Mismatch between saved question IDs and fetched data for slug:", categorySlug.value, ". Discarding saved state.");
          clearStateFromLocalStorage(categorySlug.value);
        }
      }

      if (!stateLoaded) {
        if (allQuestionsData.value.length > 0) {
          shuffledQuestions.value = shuffleArray([...allQuestionsData.value]);
        } else {
          shuffledQuestions.value = [];
          if (!error.value) error.value = { message: "Brak pytań dla tej kategorii." };
        }
        currentQuestionIndex.value = 0;
        flaggedQuestions.value = new Set();
        _resetQuestionStateVisuals();
        if (shuffledQuestions.value.length > 0) _saveCurrentState(); // Zapisz stan początkowy, jeśli są pytania
      }
    } catch (err) {
      console.error("Błąd podczas inicjalizacji sesji nauki:", err);
      error.value = { message: err.data?.message || err.message || "Nie udało się załadować danych." };
      allQuestionsData.value = [];
      shuffledQuestions.value = [];
    } finally {
      pending.value = false;
    }
  }

  // --- Metody Akcji (Eksportowane) ---
  function selectAnswer(answerId) {
    if (isAnswered.value) return;
    selectedAnswerId.value = answerId;
    isAnswered.value = true;
    shouldShowExplanation.value = true;
  }

  function nextQuestion() {
    if (!isAnswered.value && currentQuestion.value) {
      // Możesz dodać ostrzeżenie lub po prostu pozwolić przejść dalej bez zapisu
      console.log("Przechodzenie do następnego pytania bez udzielenia odpowiedzi.");
    } else if (isAnswered.value) {
      _saveCurrentState();
    }

    if (isLastQuestion.value) {
      currentQuestionIndex.value++; // Przesuń poza zakres, aby `isSessionFinished` stało się true
      // Nie czyść LS tutaj, zrób to przy 'restart' lub jeśli użytkownik świadomie zakończy
    } else if (currentQuestionIndex.value < totalQuestions.value - 1) {
      currentQuestionIndex.value++;
      _resetQuestionStateVisuals();
    }
  }

  function prevQuestion() {
    if (currentQuestionIndex.value > 0) {
      // Zapisz stan BIEŻĄCEGO pytania ZANIM cofniesz, jeśli odpowiedziano
      if (isAnswered.value) {
        _saveCurrentState();
      }
      currentQuestionIndex.value--;
      // Wczytaj stan poprzedniego pytania lub po prostu zresetuj widok
      // Dla uproszczenia, zresetujmy widok. Można by to rozbudować o wczytanie poprzedniej odpowiedzi.
      _resetQuestionStateVisuals();
    }
  }

  function restartLearning() {
    clearStateFromLocalStorage(categorySlug.value);
    _initialize(); // Ponownie inicjalizuje (w tym tasuje i resetuje stany)
  }

  function goBack() {
    _saveCurrentState();
    router.push("/testy-teoretyczne/");
  }

  function toggleFlag() {
    if (!currentQuestion.value) return;
    const questionId = currentQuestion.value.id;
    if (flaggedQuestions.value.has(questionId)) {
      flaggedQuestions.value.delete(questionId);
    } else {
      flaggedQuestions.value.add(questionId);
    }
    _saveCurrentState();
  }

  function showExplanation() {
    if (isAnswered.value) {
      shouldShowExplanation.value = !shouldShowExplanation.value;
    }
  }

  function saveBeforeUnload() {
    _saveCurrentState();
  }

  // --- Cykl Życia ---
  onMounted(() => {
    _initialize();
    window.addEventListener("beforeunload", saveBeforeUnload);
  });

  onBeforeUnmount(() => {
    saveBeforeUnload();
    window.removeEventListener("beforeunload", saveBeforeUnload);
  });

  watch(categorySlug, (newSlug, oldSlug) => {
    if (newSlug && newSlug !== oldSlug) {
      // Zapisz stan dla starego slugu (jeśli istniał) przed przełączeniem
      // Używamy closure, aby `oldSlugValue` było dostępne w momencie zapisu
      const oldSlugValue = oldSlug;
      if (oldSlugValue) {
        // Trzeba by mieć dostęp do `shuffledQuestions` i `currentQuestionIndex` dla `oldSlugValue`
        // To jest skomplikowane, jeśli stany nie są izolowane per slug.
        // Najprościej: _saveCurrentState() zapisze dla aktualnie aktywnego (starego) slugu.
        _saveCurrentState();
      }
      _initialize(); // Rozpocznie dla nowego slugu (newSlug)
    } else if (!newSlug && oldSlug) {
      // Jeśli slug zniknął (np. użytkownik wyszedł ze strony kategorii)
      clearStateFromLocalStorage(oldSlug);
      allQuestionsData.value = [];
      shuffledQuestions.value = [];
      // ... inne resety
    }
  });

  // --- Zwracany Interfejs ---
  return {
    pending,
    error,
    categoryName,
    currentQuestion,
    totalQuestions,
    currentQuestionDisplayIndex,
    isAnswered,
    selectedAnswerId,
    isLastQuestion,
    isCurrentFlagged,
    shouldShowExplanation,
    isSessionFinished,
    selectAnswer,
    nextQuestion,
    prevQuestion, // Dodano
    restartLearning,
    goBack,
    toggleFlag,
    showExplanation,
    saveBeforeUnload,
  };
}
