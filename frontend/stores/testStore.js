export const useTestStore = defineStore("test", () => {
  const route = useRoute();
  const router = useRouter();
  const config = useRuntimeConfig();
  const nuxtApp = useNuxtApp();

  const isLoading = ref(true);
  const testId = ref(null);
  const questions = ref([]);
  const currentQuestionIndex = ref(0);
  const categoryName = ref("");
  const mainTimeLeft = ref(25 * 60);
  const selectedAnswer = ref(null);
  const isTestFinished = ref(false);
  const isVideoPlaying = ref(false);
  const questionTimeLeft = ref(0);
  const menuIsActive = ref(false);

  const answeringTimeBasic = 35;
  const answeringTimeSpecialist = 50;

  let mainTimer = null;
  let questionTimer = null;

  const currentQuestion = computed(() => questions.value[currentQuestionIndex.value] || null);
  const isLastQuestion = computed(() => currentQuestionIndex.value === questions.value.length - 1);
  const currentAnsweringTime = computed(() => {
    if (!currentQuestion.value) return answeringTimeBasic;
    return currentQuestion.value?.type === "basic" ? answeringTimeBasic : answeringTimeSpecialist;
  });
  const basicQuestionCount = computed(() => Math.min(currentQuestionIndex.value + 1, 20));
  const specialistQuestionCount = computed(() => Math.max(0, currentQuestionIndex.value - 19));

  // --- Funkcje timera ---
  function stopQuestionTimer() {
    if (questionTimer) {
      clearInterval(questionTimer);
      questionTimer = null;
    }
  }

  function startQuestionTimer() {
    stopQuestionTimer();
    if (isTestFinished.value || !currentQuestion.value) return;

    questionTimeLeft.value = currentAnsweringTime.value;

    questionTimer = setInterval(() => {
      if (isTestFinished.value) {
        stopQuestionTimer();
        return;
      }

      if (!isVideoPlaying.value && questionTimeLeft.value > 0) {
        questionTimeLeft.value--;
      } else if (questionTimeLeft.value <= 0) {
        stopQuestionTimer();
        handleNextOrFinish();
      }
    }, 1000);
  }

  function startMainTimer() {
    if (mainTimer) clearInterval(mainTimer);
    mainTimer = setInterval(() => {
      if (mainTimeLeft.value > 0) {
        mainTimeLeft.value--;
      } else {
        clearTimers();
        finishTest();
      }
    }, 1000);
  }

  function clearTimers() {
    if (mainTimer) {
      clearInterval(mainTimer);
      mainTimer = null;
    }
    stopQuestionTimer();
  }

  async function initTest(categoryIdentifier) {
    isLoading.value = true;
    isTestFinished.value = false;
    clearTimers();
    currentQuestionIndex.value = 0;
    selectedAnswer.value = null;
    mainTimeLeft.value = 25 * 60;
    questions.value = [];
    testId.value = null;
    categoryName.value = "";

    try {
      const csrfToken = nuxtApp.$csrfToken;
      const headers = {
        "X-XSRF-TOKEN": csrfToken,
        "X-Locale": "pl",
        // "X-Locale": route.params.locale || "pl_PL",
      };

      const bodyPayload = {};
      bodyPayload.category_slug = categoryIdentifier;

      const data = await $fetch(`${config.public.apiBase}/api/initTest`, {
        method: "POST",
        credentials: "include",
        headers: headers,
        body: bodyPayload,
      });

      testId.value = data.test_id;
      questions.value = data.questions;
      categoryName.value = data.category ? data.category.name : "Nieznana Kategoria";
      console.log("API Response Data (initTest):", data);

      if (!questions.value || questions.value.length === 0) {
        console.warn("API returned no questions for this category (slug: " + categoryIdentifier + ").");
        isLoading.value = false;
        return;
      }

      startMainTimer();
      startQuestionTimer();
    } catch (err) {
      console.error("Error initializing test (slug: " + categoryIdentifier + "):", err);
      clearTimers();
      router.push("/");
    } finally {
      isLoading.value = false;
    }
  }

  async function saveCurrentAnswer() {
    if (isTestFinished.value || !currentQuestion.value) return;

    try {
      const csrfToken = nuxtApp.$csrfToken;
      const timeTakenForQuestion =
        questionTimeLeft.value >= 0 ? Math.max(0, currentAnsweringTime.value - questionTimeLeft.value) : currentAnsweringTime.value;
      const bodyData = {
        answer_id: selectedAnswer.value,
        answer_time_taken: timeTakenForQuestion,
      };

      await $fetch(`${config.public.apiBase}/api/${testId.value}/${currentQuestionIndex.value + 1}/answer`, {
        method: "POST",
        credentials: "include",
        headers: { "X-XSRF-TOKEN": csrfToken },
        body: bodyData,
      });
    } catch (err) {
      console.error("Error saving answer:", err);
    }
  }

  async function finishTest() {
    if (isTestFinished.value) return;
    isTestFinished.value = true;
    clearTimers();
    try {
      // await saveCurrentAnswer();
      const csrfToken = nuxtApp.$csrfToken;
      const timeTaken = 25 * 60 - mainTimeLeft.value;
      await $fetch(`${config.public.apiBase}/api/${testId.value}/finish`, {
        method: "POST",
        credentials: "include",
        headers: { "X-XSRF-TOKEN": csrfToken },
        body: { time_taken: timeTaken },
      });
      router.push({ name: "results" });
    } catch (err) {
      console.error("Błąd przy kończeniu testu:", err);
    }
  }

  async function handleNextOrFinish() {
    if (isTestFinished.value) return;

    stopQuestionTimer();
    await saveCurrentAnswer();

    if (!isLastQuestion.value) {
      isVideoPlaying.value = false;
      currentQuestionIndex.value++;
      selectedAnswer.value = null;
      startQuestionTimer();
    } else {
      await finishTest();
    }
  }

  function answerQuestion(answerId) {
    selectedAnswer.value = answerId;
  }

  function setVideoPlaying(playing) {
    isVideoPlaying.value = playing;
  }

  return {
    isLoading,
    testId,
    questions,
    currentQuestionIndex,
    categoryName,
    mainTimeLeft,
    selectedAnswer,
    isTestFinished,
    isVideoPlaying,
    setVideoPlaying,
    questionTimeLeft,
    menuIsActive,
    currentQuestion,
    isLastQuestion,
    currentAnsweringTime,
    basicQuestionCount,
    specialistQuestionCount,
    initTest,
    // nextQuestion,
    finishTest,
    // startTimers,
    clearTimers,
    handleNextOrFinish,
    answerQuestion,
    answeringTimeBasic,
    answeringTimeSpecialist,
  };
});
