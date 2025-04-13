<template>
  <div v-if="testStore.isLoading">
    <p>Ładowanie pytań...</p>
  </div>

  <div v-else class="overflow-hidden">
    <TestButton @click="testStore.finishTest()" class="lg:hidden mb-4">Zakończ egzamin</TestButton>
    <TestHeader
      :currentQuestionIndex="testStore.currentQuestionIndex"
      :categoryName="testStore.categoryName"
      :mainTimeLeft="testStore.mainTimeLeft"
      :questionPoints="testStore.currentQuestion.points" />
    <div class="grid lg:grid-cols-12 lg:grid-rows-[auto] gap-4 relative">
      <div class="col-span-12 lg:col-span-9">
        <TestMediaDisplay
          :data="testStore.currentQuestion.media"
          @video-playing="testStore.isVideoPlaying = true"
          @video-ended="testStore.isVideoPlaying = false" />
      </div>
      <div class="flex flex-col col-span-12 lg:col-span-3 lg:relative">
        <TestButton @click="testStore.finishTest()" class="hidden lg:block">Zakończ egzamin</TestButton>

        <TestQuestionInfo :basicQuestionCount="testStore.basicQuestionCount" :specialistQuestionCount="testStore.specialistQuestionCount" />

        <div class="flex lg:flex-col lg:mt-4">
          <span class="hidden lg:block text-sm font-semibold">Czas na udzielenie odpowiedzi {{ testStore.questionTimeLeft }}s</span>

          <TestProgressBar class="mt-0.5" :timer="testStore.questionTimeLeft" :maxTime="testStore.currentAnsweringTime" />
        </div>

        <div class="hidden mt-auto lg:flex flex-col gap-2">
          <!-- <TestButton @click="">Dodaj do ulubionych</TestButton>
          <TestButton @click="">Wyjaśnienie</TestButton> -->
          <TestButton @click="testStore.handleNextOrFinish()" class="!bg-blue-500 !text-neutral-50 !text-base h-10 hover:!bg-blue-500/90">
            Następne pytanie
          </TestButton>
        </div>
      </div>

      <TestQuestion class="col-span-12" :data="testStore.currentQuestion.content" />

      <TestAnswers
        class="col-span-12"
        v-model="testStore.selectedAnswer"
        :data="testStore.currentQuestion"
        @update:modelValue="testStore.answerQuestion" />

      <TestButton
        @click="handleNextOrFinish()"
        class="lg:hidden col-span-12 !bg-blue-500 !text-neutral-50 !text-lg !rounded-lg h-14 md:h-16 hover:!bg-blue-500/90">
        Następne pytanie
      </TestButton>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  ssr: false,
  layout: "test",
});

const testStore = useTestStore();

const route = useRoute();
const router = useRouter();

onMounted(async () => {
  const categoryId = route.query.id;
  if (!categoryId) return router.push("/");
  await testStore.initTest(categoryId);
});

const handleNextOrFinish = () => {
  testStore.handleNextOrFinish();
};
</script>
