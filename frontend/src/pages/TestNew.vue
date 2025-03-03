<template>
  <!-- "max-w-[1220px] mx-auto px-2 md:px-6 pt-2 pb-4 my-6 shadow rounded-md bg-white dark:bg-[#181a1b] dark:text-white transition-border-bg duration-500"> -->
  <div>
    <!-- <div>
      <h1 class="text-2xl md:text-4xl font-extrabold mb-2 md:text-center">Kalkulator zapotrzebowania kalorycznego</h1>
      <p class="md:text-center">
        W tym miejscu możesz w prosty sposób obliczyć swoje zapotrzebowanie kaloryczne. Wystarczy, że uzupełnisz poniższy formularz.
      </p>
    </div> -->
    <div class="overflow-hidden !max-w-[1220px] !px-2 !md:px-6 mt-2">
      <TestHeader v-model="menuIsActive" :currentQuestionIndex="currentQuestionIndex" :categoryName="categoryName" :mainTimeLeft="mainTimeLeft" />
      <div class="grid lg:grid-cols-12 lg:grid-rows-[auto] gap-4 relative">
        <div class="col-span-12 lg:col-span-9">
          <MediaDisplay :data="currentQuestion.media" @video-playing="isVideoPlaying = true" @video-ended="isVideoPlaying = false" />
        </div>
        <div class="flex flex-col col-span-12 lg:col-span-3 lg:relative">
          <Button @click="finishTest" class="hidden lg:block">Zakończ egzamin</Button>

          <QuestionInfo :basicQuestionCount="basicQuestionCount" :specialistQuestionCount="specialistQuestionCount" />

          <div class="flex lg:flex-col lg:mt-4">
            <span class="hidden lg:block text-sm font-semibold">Czas na udzielenie odpowiedzi {{ questionTimeLeft }}s</span>
            <ProgressBar class="mt-0.5" :timer="questionTimeLeft" :maxTime="currentAnsweringTime" />
          </div>

          <div class="hidden mt-auto lg:flex flex-col gap-2">
            <Button @click="">Dodaj do ulubionych</Button>
            <Button @click="">Wyjaśnienie</Button>
            <Button @click="handleNextOrFinish" class="!bg-blue-500 !text-neutral-50 !text-base h-10 hover:!bg-blue-500/90">Następne pytanie</Button>
          </div>

          <Menu v-model:menuIsActive="menuIsActive" />
        </div>

        <Question class="col-span-12" :data="currentQuestion.content" />

        <Answers class="col-span-12" v-model="selectedAnswer" :data="currentQuestion" />

        <Button
          @click="handleNextOrFinish"
          class="lg:hidden col-span-12 !bg-blue-500 !text-neutral-50 !text-lg !rounded-lg h-14 md:h-16 hover:!bg-blue-500/90">
          Następne pytanie
        </Button>
      </div>
    </div>

    <div id="info" class="max-w-[700px] my-8" data-v-c3e6e4ee="">
      <h2 class="font-bold text-3xl mt-8" data-v-c3e6e4ee="">Objaśnienie</h2>
      <h3 class="font-bold text-xl mt-8" data-v-c3e6e4ee="">Metabolizm podstawowy</h3>
      <p class="mt-4" data-v-c3e6e4ee="">
        Podstawowy metabolizm (BMR) to energia, jaką organizm musi wyprodukować, aby w stanie spoczynku, czyli bez jakiejkolwiek dziennej aktywności,
        utrzymywać wszystkie funkcje życiowe.
      </p>
      <h3 class="font-bold text-xl mt-8" data-v-c3e6e4ee="">Wskaźnik BMI</h3>
      <p class="mt-4" data-v-c3e6e4ee="">
        Wskaźnik masy ciała, zwykle oznaczany skrótem BMI (z angielskiego body mass index) jest liczbą stosowaną do określania niedowagi, normalnej
        masy ciała, nadwagi i otyłości.
      </p>
      <h3 class="font-bold text-xl mt-8" data-v-c3e6e4ee="">Makroelementy</h3>
      <p class="mt-4" data-v-c3e6e4ee="">
        Główne składniki odżywcze, które otrzymujemy z naszej diety i od których zależy funkcjonowanie organizmu. Są to białka, węglowodany i
        tłuszcze.
      </p>
      <h3 class="font-bold text-xl mt-8" data-v-c3e6e4ee="">Zalecane dzienne spożycie kalorii*</h3>
      <p class="mt-4" data-v-c3e6e4ee="">
        Są to orientacyjne wyliczenia, które mogą różnić się u poszczególnych osób ze względu na indywidualne cechy i parametry dodatkowe.
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";

import axiosClient from "../axios.js";
import TestHeader from "../components/test/TestHeader.vue";
import ProgressBar from "../components/ProgressBar.vue";
import MediaDisplay from "../components/test/MediaDisplay.vue";
import Question from "../components/test/Question.vue";
import Answers from "../components/test/Answers.vue";
import Button from "../components/test/Button.vue";
import QuestionInfo from "../components/test/QuestionInfo.vue";
import Menu from "../components/test/Menu.vue";

const route = useRoute();
const uuid = route.params.uuid;
const router = useRouter();

const questions = ref([]);
const currentQuestionIndex = ref(0);
const categoryName = ref("");
const mainTimeLeft = ref(25 * 60);

const answeringTimeBasic = 35;
const answeringTimeSpecialist = 50;

const questionTimeLeft = ref(0);

const selectedAnswer = ref(null);
const userAnswers = ref([]);

const menuIsActive = ref(false);
const isTestFinished = ref(false);
const isVideoPlaying = ref(false);

const fetchQuestions = async () => {
  try {
    const response = await axiosClient.get(`/api/test/${uuid}/questions`);
    const data = response.data;
    categoryName.value = data.category_name;
    questions.value.push(...data.questions);
    if (data.current_question_index) {
      currentQuestionIndex.value = data.current_question_index;
    }
    console.log("Fetched questions:", questions.value);
    if (questions.value.length > 0) {
      questionTimeLeft.value = currentAnsweringTime.value;
    }
  } catch (error) {
    if (error.response?.status === 302) {
      console.warn("No access to test!");
      router.push({ name: "Home" });
    } else {
      console.error("Error fetching questions:", error);
    }
  }
};

const currentAnsweringTime = computed(() => (currentQuestion.value.question_type === "basic" ? answeringTimeBasic : answeringTimeSpecialist));

const currentQuestion = computed(() => {
  return questions.value[currentQuestionIndex.value] || { content: "", media: "", answers: [] };
});

const isLastQuestion = computed(() => {
  return currentQuestionIndex.value === questions.value.length - 1;
});

const nextQuestion = () => {
  if (currentQuestionIndex.value < questions.value.length - 1) {
    userAnswers.value.push({
      question_id: currentQuestion.value.id,
      answer_id: selectedAnswer.value,
      question_index: currentQuestionIndex.value,
    });
    currentQuestionIndex.value++;
    questionTimeLeft.value = currentAnsweringTime.value;
    isVideoPlaying.value = false;
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
    if (isVideoPlaying.value) return;
    if (questionTimeLeft.value > 0) {
      questionTimeLeft.value--;
    } else {
      if (isLastQuestion.value) {
        finishTest();
      } else {
        userAnswers.value.push({
          question_id: currentQuestion.value.id,
          answer_id: selectedAnswer.value,
          question_index: currentQuestionIndex.value,
        });
        currentQuestionIndex.value++;
        questionTimeLeft.value = currentAnsweringTime.value;
      }
    }
  }, 1000);
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
      current_question_index: currentQuestionIndex.value,
    });
  } catch (error) {
    console.error("Error submitting answer:", error);
  }
};

const finishTest = async () => {
  const startTime = performance.now();
  if (isTestFinished.value) return;
  isTestFinished.value = true;

  if (currentQuestionIndex.value === questions.value.length - 1) {
    userAnswers.value.push({
      question_id: currentQuestion.value.id,
      answer_id: selectedAnswer.value,
      question_index: currentQuestionIndex.value,
    });
  }

  try {
    router.replace({ name: "Results", params: { uuid: uuid } });

    // submitAnswer();
    const response = await axiosClient.post(`/api/test/${uuid}/finish`, {
      answers: userAnswers.value,
    });
    if (response.status !== 200) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    console.log(response.data);
  } catch (error) {
    console.error("Error finishing the test:", error);
    alert("Wystąpił błąd podczas kończenia testu.");
  }

  const endTime = performance.now();
  console.log(`Czas ładowania wyników: ${(endTime - startTime).toFixed(2)} ms`);
};

onMounted(() => {
  fetchQuestions();
  startTimers();
});
</script>
