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
          <span class="text-white bg-blue-500 px-2.5 py-1 rounded-sm">{{ resultsData.category }}</span>
        </div>
        <div>
          Egzamin ukończony w
          <time id="timer" class="md:text-white lg:bg-blue-500 md:px-2.5 md:py-1 md:rounded-sm" aria-label="Pozostały czas">
            {{
              String(
                Math.max(0, Math.floor((new Date(resultsData.completed_at).getTime() - new Date(resultsData.created_at).getTime()) / 60000))
              ).padStart(2, "0")
            }}:{{
              String(
                Math.max(0, Math.floor(((new Date(resultsData.completed_at).getTime() - new Date(resultsData.created_at).getTime()) % 60000) / 1000))
              ).padStart(2, "0")
            }}
          </time>
        </div>
      </div>

      <div class="media mt-4">
        <img class="w-full h-full" src="../assets/x.jpg" alt="Obrazek" />
      </div>

      <div class="questionNumber mt-4 flex flex-wrap gap-2">
        <div
          v-for="(data, index) in resultsData.answers"
          :key="data.question.id"
          @click="currentResultIndex = index"
          class="px-2 bg-gray-400 text-black cursor-pointer"
          :class="{
            'bg-green-500': data.answers.some((answer) => answer.id === data.given_answer_id && answer.is_correct),
            'bg-red-500': data.answers.some((answer) => answer.id === data.given_answer_id && !answer.is_correct),
          }">
          <span>{{ index + 1 }}</span>
        </div>
      </div>

      <div class="question text-center p-1">
        <p>{{ currentResult.question.content }}</p>
      </div>

      <div class="answer flex text-center text-lg uppercase mt-2">
        <div v-if="currentResult.question.question_type === 'basic'" class="flex w-full">
          <button
            v-for="answer in currentResult.answers"
            :key="answer.id"
            :class="{ 'bg-blue-500 text-white': currentResult.given_answer_id === answer.id }"
            class="border p-3 w-1/2 rounded transition-all duration-200 border-gray-200 text-gray-700 mt-2">
            {{ answer.answer_content }}
          </button>
        </div>
        <div v-else class="flex flex-col w-full">
          <button
            v-for="answer in currentResult.answers"
            :key="answer.id"
            :class="{ 'bg-blue-500 text-white': currentResult.given_answer_id === answer.id }"
            class="text-left border p-3 w-full rounded transition-all duration-200 border-gray-200 text-gray-700 mt-2">
            {{ answer.answer_content }}
          </button>
        </div>

        <!-- <p>
          Twoja odpowiedź:
          {{ currentResult && currentResult.given_answer_id !== null ? currentResult.given_answer_id : "Nie udzielono odpowiedzi" }}
        </p> -->
      </div>

      <router-link
        :to="{ name: 'Home' }"
        class="nextQuestion mt-auto text-center text-lg font-semibold p-1.5 text-white border-2 bg-blue-500 hover:bg-transparent hover:text-blue-600 border-blue-500 rounded-md">
        Powrót do kategorii
      </router-link>
    </div>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const uuid = route.params.uuid;

const resultsData = ref({
  category: "",
  completed_at: "",
  created_at: "",
  answers: [],
});

const results = ref([]);
const currentResultIndex = ref(0);

const fetchResults = async () => {
  const url = `http://127.0.0.1:8000/api/test/${uuid}/results`;

  try {
    const response = await axios.get(url);
    resultsData.value = response.data;

    console.log("Fetched results:", resultsData.value);
  } catch (error) {
    console.error("Error fetching results:", error);
  }
};

const currentResult = computed(() => {
  return resultsData.value.answers[currentResultIndex.value] || { question: { content: "" }, media: "", answers: [] };
});

onMounted(() => {
  fetchResults();
});
</script>

<style scoped>
.test {
  grid-template-rows: auto;
  grid-template-areas:
    "quitExam"
    "mainTimer"
    "questionNumber"
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
