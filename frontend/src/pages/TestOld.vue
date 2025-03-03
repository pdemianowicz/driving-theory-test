<template>
  <div>
    <div class="grid grid-cols-12 grid-rows-[auto,auto,auto,auto] gap-2">
      <div class="col-span-9 row-span-1 flex items-center justify-between leading-tight">
        <div class="flex items-stretch gap-2">
          Wartość
          <br />
          punktowa
          <span class="flex items-center justify-center text-white bg-blue-700 text-xl font-bold uppercase px-3.5">3</span>
        </div>
        <div class="flex items-stretch gap-2">
          Aktualna
          <br />
          kategoria
          <span class="flex items-center justify-center text-white bg-blue-700 text-xl font-bold uppercase px-3.5">B</span>
        </div>
        <div class="flex items-stretch gap-2 text-center">
          Czas do końca
          <br />
          egzaminu
          <time
            id="timer"
            class="flex items-center justify-center text-white bg-blue-700 text-xl font-bold uppercase px-3.5"
            aria-label="Pozostały czas">
            {{ formatTime(mainTimeLeft) }}
          </time>
        </div>
      </div>

      <div class="col-span-9 row-span-1">
        <img class="w-full h-full" src="../assets/x.jpg" alt="Obrazek" />
      </div>

      <div class="flex flex-col col-span-3 row-span-1">
        <button @click="finishTest" type="submit" class="w-full text-lg font-bold tracking-wide p-3 text-white bg-yellow-500 hover:bg-opacity-80">
          Zakończ egzamin
        </button>

        <div class="grid grid-cols-2 gap-2 mt-4 leading-tight">
          <div class="flex flex-col gap-2">
            <span class="tracking-wide">Pytania podstawowe</span>
            <span class="flex items-center justify-center text-white bg-blue-700 text-xl font-bold py-1">{{ basicQuestionCount }} z 20</span>
          </div>
          <div class="flex flex-col gap-2">
            <span class="tracking-wide">Pytania specjialistyczne</span>
            <span class="flex items-center justify-center text-white bg-blue-700 text-xl font-bold py-1">{{ specialistQuestionCount }} z 12</span>
          </div>
        </div>

        <div class="mt-4">
          <span>Czas na udzielenie odpowiedzi</span>
          <div class="flex items-center gap-2 mt-2">
            <button type="submit" class="text-base font-semibold tracking-wide px-3.5 py-2 text-white bg-blue-700 hover:bg-opacity-80">Start</button>
            <ProgressBar class="progressBar" :timer="questionTimeLeft" />
          </div>
        </div>

        <button
          @click="handleNextOrFinish"
          type="submit"
          class="mt-auto w-full text-lg font-bold tracking-wide p-3 text-white bg-yellow-500 hover:bg-opacity-80">
          {{ isLastQuestion ? "Zakończ egzamin" : "Następne pytanie" }}
        </button>
      </div>

      <div class="col-span-9 row-span-1 text-base font-semibold pb-2">
        <p>Dlaczego układanie na boku osób nieprzytomnych i oddychających jest bezpieczne?</p>
      </div>

      <div class="col-span-9 row-span-1 flex items-center justify-evenly">
        <button class="text-white bg-blue-700 hover:bg-fuchsia-700 text-xl font-bold p-1.5 px-6">Tak</button>
        <button class="text-white bg-blue-700 hover:bg-fuchsia-700 text-xl font-bold p-1.5 px-6">Nie</button>
      </div>
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
