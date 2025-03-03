<template>
  <div>
    <div v-if="loading" class="flex items-center justify-center h-full py-10">
      <svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
      </svg>
      <span class="ml-2 text-gray-500">Ładowanie wyników...</span>
    </div>
    <div v-if="showResultsModal" class="fixed inset-0 z-10 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h2 class="text-xl font-bold">Test zaliczony jest po zdobyciu co najmniej 68 punktów.</h2>
        <p class="mt-2">Kolorem zielonym oznaczone są pytania, na które została udzielona prawidłowa odpowiedź</p>
        <p class="mt-2">Kolorem pomarańczowym oznaczone są pytania, na które nie została udzielona odpowiedź</p>
        <p class="mt-2">Kolorem czerwonym oznaczone są pytania, na które została udzielona nieprawidłowa odpowiedź</p>
        <p class="text-gray-600 mt-1">Minimalny próg zaliczenia to 68%</p>
        <button @click="showResultsModal = false" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Przejdź do wyników</button>
      </div>
    </div>

    <div v-else class="overflow-hidden">
      <TestHeader :currentQuestionIndex="currentResultIndex" :categoryName="resultsData.category" :mainTimeLeft="timeDifferenceInSeconds" />
      <!-- v-model="menuIsActive"   -->
      <div class="grid lg:grid-cols-12 lg:grid-rows-[auto] gap-4 relative">
        <div class="col-span-12 lg:col-span-9">
          <MediaDisplay :data="currentResult.question.media" />
        </div>
        <div class="flex flex-col col-span-12 lg:col-span-3 gap-2 lg:relative">
          <div class="grid grid-cols-8 lg:grid-cols-6 xl:grid-cols-8 gap-2 p-0.5">
            <div
              v-for="(data, index) in resultsData.answers"
              :key="data.question.id"
              @click="currentResultIndex = index"
              class="px-2 py-1 text-center bg-gray-300 text-slate-800 font-semibold text-sm cursor-pointer rounded-sm transition duration-200"
              :class="{
                'bg-green-400': data.answers.some((answer) => answer.id === data.given_answer_id && answer.is_correct),
                'bg-red-400': data.answers.some((answer) => answer.id === data.given_answer_id && !answer.is_correct),
                'outline outline-2 outline-blue-500': currentResultIndex === index,
              }">
              {{ index + 1 }}
            </div>
          </div>

          <Button @click="nextQuestion" class="hidden lg:block mt-auto">Następne pytanie</Button>
          <Button class="hidden lg:block col-span-12 !bg-blue-500 !text-neutral-50 hover:!bg-blue-500/90">Powrót do kategorii</Button>

          <!-- <Menu :menuIsActive="menuIsActive" /> -->
        </div>

        <Question class="col-span-12" :data="currentResult.question.content" />

        <Answers class="col-span-12" v-model="selectedAnswer" :data="currentResult.question" />

        <Button
          @click="nextQuestion"
          class="lg:hidden col-span-12 !bg-blue-500 !text-neutral-50 !text-lg !rounded-lg h-14 md:h-16 hover:!bg-blue-500/90">
          Następne pytanie
        </Button>
      </div>
    </div>
  </div>
</template>

<!-- 

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

        <p>
          Twoja odpowiedź:
          {{ currentResult && currentResult.given_answer_id !== null ? currentResult.given_answer_id : "Nie udzielono odpowiedzi" }}
        </p>
      </div>

      <router-link
        :to="{ name: 'Home' }"
        class="nextQuestion mt-auto text-center text-lg font-semibold p-1.5 text-white border-2 bg-blue-500 hover:bg-transparent hover:text-blue-600 border-blue-500 rounded-md">
        Powrót do kategorii
      </router-link>
    </div>
  </div>
</template> -->

<script setup>
import { ref, onMounted, computed, watchEffect } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios.js";

import TestHeader from "../components/test/TestHeader.vue";
import ProgressBar from "../components/ProgressBar.vue";
import MediaDisplay from "../components/test/MediaDisplay.vue";
import Question from "../components/test/Question.vue";
import Answers from "../components/test/Answers.vue";
import Button from "../components/test/Button.vue";
import QuestionInfo from "../components/test/QuestionInfo.vue";
import Menu from "../components/test/Menu.vue";

const showResultsModal = ref(false);
const isPassed = true;

const route = useRoute();
const uuid = route.params.uuid;
const loading = ref(false);

const resultsData = ref({
  category: "",
  completed_at: "",
  created_at: "",
  answers: [],
});

const currentResultIndex = ref(0);
const selectedAnswer = ref(null);

const results = ref([]);

const fetchResults = async () => {
  loading.value = true; // start spinnera
  try {
    const response = await axiosClient.get(`/api/test/${uuid}/results`);
    resultsData.value = response.data;
    updateSelectedAnswer();
    console.log("Fetched results:", resultsData.value);
  } catch (error) {
    console.error("Error fetching results:", error);
  } finally {
    loading.value = false; // zatrzymaj spinner
  }
};

const timeDifferenceInSeconds = computed(() => {
  const createdAt = new Date(resultsData.value.created_at).getTime();
  const completedAt = new Date(resultsData.value.completed_at).getTime();
  return Math.max(0, (completedAt - createdAt) / 1000);
});

const currentResult = computed(() => {
  return resultsData.value.answers[currentResultIndex.value] || { question: { content: "", media: "" }, answers: [] };
});

const updateSelectedAnswer = () => {
  if (currentResult.value && currentResult.value.given_answer_id !== null) {
    selectedAnswer.value = currentResult.value.given_answer_id;
    console.log(timeDifferenceInSeconds.value);
  } else {
    selectedAnswer.value = null;
  }
};

const nextQuestion = () => {
  if (currentResultIndex.value < resultsData.value.answers.length - 1) {
    currentResultIndex.value++;
    updateSelectedAnswer();
  }
};

watchEffect(() => {
  updateSelectedAnswer();
});

onMounted(() => {
  fetchResults();
});
</script>
