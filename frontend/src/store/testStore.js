import { defineStore } from "pinia";
import { ref, computed } from "vue";
import axiosClient from "../axios.js";
import { useRouter } from "vue-router";

export const useTestStore = defineStore("test", () => {
  const router = useRouter();

  const questions = ref([]);
  const currentQuestionIndex = ref(0);
  const categoryName = ref("");
  const mainTimeLeft = ref(30 * 60);
  const questionTimeLeft = ref(30);
  const selectedAnswer = ref(null);
  const isTestFinished = ref(false);
  const testUuid = ref(null); // Dodajemy testUuid do store

  const currentQuestion = computed(() => {
    return questions.value[currentQuestionIndex.value] || { content: "", media: "", answers: [] };
  });

  const isLastQuestion = computed(() => {
    return currentQuestionIndex.value === questions.value.length - 1;
  });

  const basicQuestionCount = computed(() => {
    return Math.min(currentQuestionIndex.value + 1, 20);
  });

  const specialistQuestionCount = computed(() => {
    return Math.max(0, currentQuestionIndex.value - 19);
  });

  const fetchQuestions = async (uuid) => {
    testUuid.value = uuid; // Zapisujemy UUID w store
    try {
      const response = await axiosClient.get(`/api/test/${uuid}/questions`);
      const data = response.data;
      categoryName.value = data.category_name;
      questions.value.push(...data.questions);
      currentQuestionIndex.value = data.current_question_index || 0;

      localStorage.setItem("test_uuid", uuid);
      localStorage.setItem("current_question_index", currentQuestionIndex.value.toString());
    } catch (error) {
      if (error.response?.status === 302) {
        console.warn("No access to test!");
        router.push({ name: "Home" });
      } else {
        console.error("Error fetching questions:", error);
      }
    }
  };

  const nextQuestion = () => {
    if (currentQuestionIndex.value < questions.value.length - 1) {
      submitAnswer();
      currentQuestionIndex.value++;
      questionTimeLeft.value = 30;
      localStorage.setItem("current_question_index", currentQuestionIndex.value.toString());
    }
  };

  const handleNextOrFinish = () => {
    isLastQuestion.value ? finishTest() : nextQuestion();
  };

  const startTimers = () => {
    setInterval(() => {
      if (mainTimeLeft.value > 0) {
        mainTimeLeft.value--;
      } else {
        finishTest();
      }
    }, 1000);

    setInterval(() => {
      if (questionTimeLeft.value > 0) {
        questionTimeLeft.value--;
      } else {
        if (isLastQuestion.value) {
          finishTest();
        } else {
          nextQuestion();
          questionTimeLeft.value = 30;
        }
      }
    }, 1000);
  };

  const submitAnswer = async () => {
    try {
      await axiosClient.post(`/api/test/${testUuid.value}/answer`, {
        // Używamy testUuid.value ze store
        question_id: currentQuestion.value.id,
        answer_id: selectedAnswer.value,
      });
    } catch (error) {
      console.error("Error submitting answer:", error);
    }
  };

  const finishTest = async () => {
    if (isTestFinished.value) return;
    isTestFinished.value = true;
    localStorage.removeItem("test_uuid");
    localStorage.removeItem("current_question_index");

    try {
      submitAnswer();
      const response = await axiosClient.post(`/api/test/${testUuid.value}/finish`); // Używamy testUuid.value ze store
      if (response.status !== 200) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      console.log(response.data);
      router.replace({ name: "Results", params: { uuid: testUuid.value } }); // Używamy testUuid.value ze store
    } catch (error) {
      console.error("Error finishing the test:", error);
      alert("Wystąpił błąd podczas kończenia testu.");
    }
  };

  return {
    questions,
    currentQuestionIndex,
    categoryName,
    mainTimeLeft,
    questionTimeLeft,
    selectedAnswer,
    isTestFinished,
    testUuid, // Eksportujemy testUuid
    currentQuestion,
    isLastQuestion,
    basicQuestionCount,
    specialistQuestionCount,
    fetchQuestions,
    nextQuestion,
    handleNextOrFinish,
    startTimers,
    submitAnswer,
    finishTest,
  };
});
