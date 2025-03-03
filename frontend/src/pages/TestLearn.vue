<template>
  <div class="relative">
    <div class="max-w-screen-xl mx-auto">
      <div class="py-1 flex items-center justify-between border-b border-gray-200 dark:border-neutral-700 transition-border-bg duration-500">
        <!-- <span class="text-sm font-semibold text-gray-500">Kategoria B</span> -->
        <span class="text-sm font-semibold text-gray-500 dark:text-[#c0bab2]">Pytanie {{ currentQuestionIndex + 1 }}/32</span>
        <div class="text-sm font-semibold text-gray-500 dark:text-[#c0bab2] flex items-center gap-1">
          <span class="w-5 h-5">
            <svg class="w-full h-full" viewBox="0 0 24 24" aria-hidden="true">
              <path
                fill="currentColor"
                d="m15.87 15.25l-3.37-2V8.72c0-.4-.32-.72-.72-.72h-.06c-.4 0-.72.32-.72.72v4.72c0 .35.18.68.49.86l3.65 2.19c.34.2.78.1.98-.24c.21-.35.1-.8-.25-1m5.31-10.24L18.1 2.45c-.42-.35-1.05-.3-1.41.13c-.35.42-.29 1.05.13 1.41l3.07 2.56c.42.35 1.05.3 1.41-.13a1 1 0 0 0-.12-1.41M4.1 6.55l3.07-2.56c.43-.36.49-.99.13-1.41a.988.988 0 0 0-1.4-.13L2.82 5.01a1 1 0 0 0-.12 1.41c.35.43.98.48 1.4.13M12 4a9 9 0 1 0 .001 18.001A9 9 0 0 0 12 4m0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7s7 3.14 7 7s-3.14 7-7 7" />
            </svg>
          </span>
          <span>{{ formatTime(mainTimeLeft) }}</span>
        </div>
      </div>

      <div class="absolute top-0 right-0 p-2 cursor-pointer">
        <svg class="w-5 h-5 text-gray-500 dark:text-[#c0bab2]" viewBox="0 0 24 24">
          <path
            fill="currentColor"
            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m1 17h-2v-2h2zm2.07-7.75l-.9.92c-.5.51-.86.97-1.04 1.69c-.08.32-.13.68-.13 1.14h-2v-.5a3.997 3.997 0 0 1 1.17-2.83l1.24-1.26c.46-.44.68-1.1.55-1.8a1.99 1.99 0 0 0-1.39-1.53c-1.11-.31-2.14.32-2.47 1.27c-.12.37-.43.65-.82.65h-.3C8.4 9 8 8.44 8.16 7.88a4.008 4.008 0 0 1 3.23-2.83c1.52-.24 2.97.55 3.87 1.8c1.18 1.63.83 3.38-.19 4.4" />
        </svg>
      </div>

      <div class="py-2 md:py-6 text-lg md:text-3xl font-bold md:text-center">
        <p>{{ currentQuestion.content }}</p>
      </div>

      <div class="lg:grid grid-cols-3 gap-5">
        <div class="col-span-2">
          <div class="max-h-[450px] aspect-video mx-auto rounded overflow-hidden">
            <img class="w-full h-full" src="../assets/x.jpg" alt="Obrazek" />
          </div>
        </div>
        <div class="flex flex-col">
          <ProgressBar class="my-4 lg:mb-6 lg:my-0" :timer="questionTimeLeft" />

          <div class="flex text-lg uppercase mt-2 lg:mt-0">
            <div v-if="currentQuestion.question_type === 'basic'" class="flex flex-col w-full gap-3">
              <button
                v-for="answer in currentQuestion.answers"
                :key="answer.id"
                @click="selectAnswer(answer.id)"
                :class="{ 'bg-blue-500 text-white': selectedAnswer === answer.id }"
                class="py-2 px-4 w-full h-20 text-lg md:text-xl bg-gray-200 hover:bg-gray-300 dark:bg-neutral-800 dark:hover:bg-neutral-700 rounded-lg transition-border-bg duration-200">
                {{ answer.answer_content }}
              </button>
            </div>
            <div v-else class="flex flex-col w-full gap-3">
              <button
                v-for="answer in currentQuestion.answers"
                :key="answer.id"
                @click="selectAnswer(answer.id)"
                :class="{ 'bg-blue-500 text-white': selectedAnswer === answer.id }"
                class="py-2 px-4 w-full min-h-16 text-left text-base bg-gray-200 hover:bg-gray-300 dark:bg-neutral-800 dark:hover:bg-neutral-700 rounded-lg transition-border-bg duration-200">
                {{ answer.answer_content }}
              </button>
            </div>
          </div>

          <button @click="handleNextOrFinish" type="submit" class="mt-auto text-xl p-1.5 text-white bg-blue-500 rounded-sm hover:bg-opacity-80">
            {{ isLastQuestion ? "Zakończ egzamin" : "Następne pytanie" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup></script>

<style lang="scss" scoped></style>
