<template>
  <div v-if="learnMode.pending.value" class="animate-pulse">
    <div class="flex justify-between items-center mb-4 sm:mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
      <div class="h-5 bg-gray-300 dark:bg-gray-700 rounded w-1/3"></div>
      <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-1/4"></div>
    </div>
    <div class="grid lg:grid-cols-12 lg:grid-rows-[auto] gap-4">
      <div class="col-span-12 lg:col-span-9"><div class="aspect-video bg-gray-300 dark:bg-gray-700 rounded"></div></div>
      <div class="hidden lg:flex flex-col lg:col-span-3">
        <div class="h-8 bg-gray-300 dark:bg-gray-700 rounded mb-4"></div>
        <div class="mt-auto space-y-2">
          <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded w-3/4 mb-3"></div>
          <div class="h-8 bg-gray-300 dark:bg-gray-700 rounded"></div>
          <div class="h-11 bg-gray-400 dark:bg-gray-600 rounded mt-2"></div>
        </div>
      </div>
      <div class="col-span-12 mt-4">
        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-full mb-2"></div>
        <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-4/5"></div>
      </div>
      <div class="col-span-12 space-y-2 my-5">
        <div class="h-10 bg-gray-300 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-10 bg-gray-300 dark:bg-gray-700 rounded w-full"></div>
        <div class="h-10 bg-gray-300 dark:bg-gray-700 rounded w-full"></div>
      </div>
      <div class="lg:hidden col-span-12 h-14 bg-gray-400 dark:bg-gray-600 rounded mt-4"></div>
    </div>
  </div>

  <div
    v-else-if="learnMode.error.value || (!learnMode.pending.value && !learnMode.currentQuestion.value && !learnMode.isSessionFinished.value)"
    class="text-center py-20 text-red-500 dark:text-red-400">
    <p>Nie udało się załadować pytań dla tej kategorii.</p>
    <p class="text-sm mt-2">{{ learnMode.error.value?.message || "Brak pytań lub wystąpił problem." }}</p>
    <TestButton @click="learnMode.goBack" class="mt-4 max-w-xs mx-auto !bg-gray-300 dark:!bg-gray-600">Wróć do wyboru</TestButton>
  </div>

  <div v-else-if="learnMode.currentQuestion.value">
    <div class="flex items-center justify-between font-semibold text-sm text-gray-500 dark:text-[#c0bab2] mb-2">
      <span class="">
        Kategoria {{ learnMode.categoryName.value }}
        <span class="ml-1 text-xs">({{ learnMode.currentQuestion.value.points || "?" }} pkt.)</span>
      </span>
      <span class="">Pytanie {{ learnMode.currentQuestionDisplayIndex.value + 1 }} / {{ learnMode.totalQuestions.value }}</span>
    </div>

    <div class="grid lg:grid-cols-12 lg:grid-rows-[auto] gap-4">
      <div class="col-span-12 lg:col-span-9">
        <TestMediaDisplay :data="learnMode.currentQuestion.value.media" />
      </div>

      <div class="hidden lg:flex flex-col lg:col-span-3">
        <TestButton @click="learnMode.goBack" class="hidden lg:block !bg-gray-300 dark:!bg-gray-600">Przerwij naukę</TestButton>
        <TestButton class="hidden lg:block !bg-gray-300 dark:!bg-gray-600 mt-2">Zresetuj postęp</TestButton>

        <div class="flex flex-col gap-2 mt-auto">
          <button
            @click="learnMode.toggleFlag"
            class="mt-3 text-sm flex items-center gap-1.5 text-gray-500 hover:text-blue-600 dark:text-stone-400 dark:hover:text-blue-400 transition-colors">
            <svg
              class="w-4 h-4"
              :class="{ 'text-blue-600 dark:text-blue-500 fill-current': learnMode.isCurrentFlagged.value }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-14a2 2 0 012-2h11a2 2 0 012 2v14l-7-3.5L3 21z" />
            </svg>
            {{ learnMode.isCurrentFlagged.value ? "Oznaczono" : "Oznacz do powtórki" }}
          </button>
          <TestButton @click="learnMode.showExplanation" :disabled="!learnMode.isAnswered.value">Wyjaśnienie</TestButton>

          <TestButton
            @click="learnMode.nextQuestion"
            :disabled="!learnMode.isAnswered.value"
            class="w-full !bg-blue-500 !text-neutral-50 !text-base h-11 mt-2 disabled:!bg-gray-400 dark:disabled:!bg-gray-500">
            {{ learnMode.isLastQuestion.value ? "Zakończ Naukę" : "Następne Pytanie" }}
          </TestButton>
        </div>
      </div>

      <TestQuestion :data="learnMode.currentQuestion.value.content" class="col-span-12" />

      <div class="col-span-12">
        <div :class="['flex w-full gap-2 mb-5', learnMode.currentQuestion.value.type === 'basic' ? 'flex-row' : 'flex-col']">
          <button
            v-for="answer in learnMode.currentQuestion.value.answers"
            :key="answer.id"
            @click="learnMode.selectAnswer(answer.id)"
            :disabled="learnMode.isAnswered.value"
            :class="[
              'w-full p-3 text-sm rounded-lg border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:ring-blue-500 disabled:cursor-not-allowed',
              getAnswerButtonClass(answer), // Funkcja z sekcji script
              learnMode.currentQuestion.value.type === 'basic' ? 'h-14 md:h-16 text-center' : 'text-left',
            ]">
            {{ answer.content }}
          </button>
        </div>

        <div
          v-if="learnMode.isAnswered.value && learnMode.shouldShowExplanation.value"
          class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700 mb-5">
          <h3 class="text-md font-semibold mb-2 text-slate-800 dark:text-stone-300">Wyjaśnienie:</h3>
          <p v-if="learnMode.currentQuestion.value.explanation" class="text-sm text-gray-700 dark:text-stone-400">
            {{ learnMode.currentQuestion.value.explanation }}
          </p>
          <p v-else class="text-sm text-gray-500 dark:text-stone-500 italic">Brak wyjaśnienia dla tego pytania.</p>
        </div>
      </div>

      <TestButton
        @click="learnMode.nextQuestion"
        :disabled="!learnMode.isAnswered.value"
        class="col-span-12 lg:hidden w-full !bg-blue-500 !text-neutral-50 !text-lg h-14 mt-4 disabled:!bg-gray-400 dark:disabled:!bg-gray-500">
        {{ learnMode.isLastQuestion.value ? "Zakończ Naukę" : "Następne Pytanie" }}
      </TestButton>
    </div>
  </div>

  <div v-else-if="learnMode.isSessionFinished.value" class="text-center py-16">
    <h2 class="text-2xl font-semibold mb-4 text-slate-800 dark:text-stone-300">Zakończono sesję nauki!</h2>
    <p class="text-gray-700 dark:text-stone-400 mb-1">
      Przejrzałeś wszystkie {{ learnMode.totalQuestions.value }} pytań dla kategorii {{ learnMode.categoryName.value }}.
      <span v-if="learnMode.flaggedQuestions.value.size > 0">Oznaczono {{ learnMode.flaggedQuestions.value.size }} pytań do powtórki.</span>
    </p>
    <p class="text-xs text-gray-500 dark:text-stone-500 mb-6">(Twój postęp dla tej kategorii został wyczyszczony)</p>
    <div class="flex flex-col sm:flex-row justify-center gap-4">
      <TestButton @click="learnMode.restartLearning" class="!bg-green-500 !text-white">Rozpocznij od nowa</TestButton>
      <TestButton @click="learnMode.goBack" class="!bg-gray-300 dark:!bg-gray-600">Wróć do wyboru kategorii</TestButton>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  ssr: false,
  layout: "test",
  meta: [{ name: "robots", content: "noindex" }],
});

const learnMode = useLearnMode();

function getAnswerButtonClass(answer) {
  const base =
    "border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 focus:ring-blue-500";
  const textStyle = learnMode.currentQuestion.value?.type === "basic" ? "text-center" : "text-left";
  const sizeStyle = learnMode.currentQuestion.value?.type === "basic" ? "h-14 md:h-16" : ""; // Wyższe dla Tak/Nie
  const disabled = "disabled:cursor-not-allowed";
  const defaultState =
    "bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700";
  const correct = "bg-green-100 dark:bg-green-900/30 border-green-500 dark:border-green-600 text-green-800 dark:text-green-200 font-semibold";
  const incorrect = "bg-red-100 dark:bg-red-900/30 border-red-500 dark:border-red-600 text-red-800 dark:text-red-200";
  const neutral = "bg-gray-100 dark:bg-gray-700/50 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 opacity-75";

  let classes = `${base} ${textStyle} ${sizeStyle}`;

  if (!learnMode.isAnswered.value) {
    classes += ` ${defaultState}`;
  } else {
    classes += ` ${disabled}`; // Zawsze disabled po odpowiedzi
    if (answer.is_correct) {
      classes += ` ${correct}`;
    } else if (answer.id === learnMode.selectedAnswerId.value) {
      // Porównaj z ID ze stanu hooka
      classes += ` ${incorrect}`;
    } else {
      classes += ` ${neutral}`;
    }
  }
  return classes;
}
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
