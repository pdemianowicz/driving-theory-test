<template>
  <div>
    <div id="test-container" class="test grid">
      <div class="mainTimer max-lg:bg-blue-500 max-lg:text-white max-lg:text-center lg:flex lg:gap-6">
        <div class="hidden lg:block">
          Wartość punktowa
          <span class="text-white bg-blue-500 px-2.5 py-1 rounded-sm">3</span>
        </div>
        <div class="hidden lg:block">
          Aktualna kategoria
          <span class="text-white bg-blue-500 px-2.5 py-1 rounded-sm">{{ categoryName }}</span>
        </div>
        <div>
          Czas do końca egzaminu
          <time id="timer" class="md:text-white lg:bg-blue-500 md:px-2.5 md:py-1 md:rounded-sm" aria-label="Pozostały czas">
            {{ formatTime(mainTimeLeft) }}
          </time>
        </div>
      </div>
      <button
        @click="finishTest"
        type="submit"
        class="quitExam w-full text-center text-xl p-1.5 text-white bg-amber-400 rounded-sm hover:bg-opacity-80">
        Przerwij egzamin
      </button>

      <div class="media mt-4">
        <img class="w-full h-full" src="../assets/x.jpg" alt="Obrazek" />
        <!-- <img class="w-full h-full" src="http://127.0.0.1:8000/storage/media/3B350.webp" alt="Obrazek" /> -->
      </div>

      <ProgressBar class="progressBar mt-5 lg:-mt-20" :timer="questionTimeLeft" />

      <div class="question p-1 text-2xl font-semibold py-4">
        <p>{{ currentQuestion.content }}</p>
      </div>

      <div class="answer flex text-center text-lg uppercase mt-2">
        <div v-if="currentQuestion.question_type === 'basic'" class="flex w-full">
          <button
            v-for="answer in currentQuestion.answers"
            :key="answer.id"
            @click="selectAnswer(answer.id)"
            :class="{ 'bg-blue-500 text-white': selectedAnswer === answer.id }"
            class="border p-3 w-1/2 rounded transition-all duration-200 border-gray-200 text-gray-700 mt-2">
            {{ answer.answer_content }}
          </button>
        </div>
        <div v-else class="flex flex-col w-full">
          <button
            v-for="answer in currentQuestion.answers"
            :key="answer.id"
            @click="selectAnswer(answer.id)"
            :class="{ 'bg-blue-500 text-white': selectedAnswer === answer.id }"
            class="text-left border p-3 w-full rounded transition-all duration-200 border-gray-200 text-gray-700 mt-2">
            {{ answer.answer_content }}
          </button>
        </div>
      </div>

      <button
        @click="handleNextOrFinish"
        type="submit"
        class="nextQuestion mt-auto text-xl p-1.5 text-white bg-amber-400 rounded-sm hover:bg-opacity-80">
        {{ isLastQuestion ? "Zakończ egzamin" : "Następne pytanie" }}
      </button>
      <!-- <button
        type="submit"
        class="nextQuestion mt-auto flex w-full items-center justify-center rounded-md border border-transparent bg-blue-500 px-8 py-3 text-base font-medium text-white hover:bg-blue-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden">
        Następne pytanie
      </button> -->

      <!-- <button
        type="submit"
        id="submit"
        name="send"
        class="nextQuestion mt-auto text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-2.5 shadow-md focus:outline-none transition-all duration-300">
        Następne pytanie
      </button> -->

      <!-- <button
        class="nextQuestion mt-auto text-center cursor-pointer text-zinc-200 bg-blue-600 px-4 py-2 rounded-lg font-medium text-sm hover:bg-blue-700 transition-all ease-in duration-200">
        Następne pytanie
      </button> -->

      <div class="questionNumber hidden lg:grid grid-cols-2 gap-8 lg:mt-5">
        <div class="flex flex-col">
          <p>Pytania podstawowe</p>
          <span class="text-white bg-blue-400 px-6 rounded-sm text-center">{{ basicQuestionCount }} z 20</span>
        </div>
        <div class="flex flex-col">
          <p>Pytania specjialistyczne</p>
          <span class="text-white bg-blue-400 px-6 rounded-sm text-center">{{ specialistQuestionCount }} z 12</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import axiosClient from "../axios.js";
import ProgressBar from "../components/ProgressBar.vue";

const route = useRoute();
const uuid = route.params.uuid;
const router = useRouter();

const questions = ref([]);
const currentQuestionIndex = ref(0);
const categoryName = ref("");
const mainTimeLeft = ref(30 * 60);
const questionTimeLeft = ref(30);
const selectedAnswer = ref(null);
const isTestFinished = ref(false);

const fetchQuestions = async () => {
  try {
    const response = await axiosClient.get(`/api/test/${uuid}/questions`);
    const data = response.data;
    categoryName.value = data.category_name;
    questions.value.push(...data.questions);
    console.log("Fetched questions:", questions.value);
  } catch (error) {
    if (error.response?.status === 302) {
      console.warn("No access to test!");
      router.push({ name: "Home" });
    } else {
      console.error("Error fetching questions:", error);
    }
  }
};

const currentQuestion = computed(() => {
  return questions.value[currentQuestionIndex.value] || { content: "", media: "", answers: [] };
});

const isLastQuestion = computed(() => {
  return currentQuestionIndex.value === questions.value.length - 1;
});

const nextQuestion = () => {
  if (currentQuestionIndex.value < questions.value.length - 1) {
    submitAnswer();
    currentQuestionIndex.value++;
    questionTimeLeft.value = 30;
  }
};

const selectAnswer = (answer) => {
  selectedAnswer.value = answer;
};

const handleNextOrFinish = () => {
  if (isLastQuestion.value) {
    finishTest();
  } else {
    nextQuestion();
  }
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

const formatTime = (seconds) => {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  const formattedMinutes = String(minutes).padStart(2, "0");
  const formattedSeconds = String(remainingSeconds).padStart(2, "0");
  return `${formattedMinutes}:${formattedSeconds}`;
};

const basicQuestionCount = computed(() => {
  return Math.min(currentQuestionIndex.value + 1, 20);
});

const specialistQuestionCount = computed(() => {
  return Math.max(0, currentQuestionIndex.value - 19);
});

const submitAnswer = async () => {
  try {
    await axiosClient.post(`/api/test/${uuid}/answer`, {
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

  try {
    submitAnswer();
    const response = await axiosClient.post(`/api/test/${uuid}/finish`);
    if (response.status !== 200) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    console.log(response.data);
    router.replace({ name: "Results", params: { uuid: uuid } });
  } catch (error) {
    console.error("Error finishing the test:", error);
    alert("Wystąpił błąd podczas kończenia testu.");
  }
};

onMounted(() => {
  fetchQuestions();
  startTimers();
});
</script>

<style scoped>
.test {
  grid-template-rows: auto;
  grid-template-areas:
    "quitExam"
    "mainTimer"
    "question"
    "media"
    "progressBar"
    "answer"
    "nextQuestion";
  @media (min-width: 1024px) {
    grid-template-columns: 5fr 0.4fr 2fr;
    grid-template-areas:
      "mainTimer . quitExam"
      "media . questionNumber"
      "media . progressBar"
      "media . nextQuestion"
      "question . ."
      "answer . .";
  }
}

.mainTimer {
  grid-area: mainTimer;
}

.quitExam {
  grid-area: quitExam;
}

.media {
  grid-area: media;
}

.progressBar {
  grid-area: progressBar;
}

.question {
  grid-area: question;
}

.answer {
  grid-area: answer;
}

.nextQuestion {
  grid-area: nextQuestion;
}

.questionNumber {
  grid-area: questionNumber;
}
</style>
