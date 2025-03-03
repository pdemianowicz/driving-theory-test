<template>
  <div>
    <div class="text-container overflow-hidden relative h-20 md:h-24">
      <transition name="slide-text" mode="out-in">
        <h2 :key="displayText" class="current-text absolute top-0 left-0 w-full text-center text-4xl md:text-6xl font-bold tracking-tighter">
          {{ displayText }}
        </h2>
      </transition>
    </div>
    <p class="text-center text-lg md:text-xl mb-4">
      Najlepsza aplikacja z oficjalnymi pytaniami zatwierdzonymi przez Ministerstwo Infrastruktury na prawo jazdy.
    </p>
    <div class="flex items-center justify-center gap-2">
      <button class="p-2 bg-blue-500 rounded text-white font-semibold text-lg">Egzamin</button>
      <button class="p-2 border-2 border-blue-500 rounded font-semibold text-lg">Nauka</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const textOptions = ["Zdaj teorię za pierwszym razem ✅", "Oficjalna baza pytań WORD ✅", "Twoja droga zaczyna się tutaj ✅"];
const displayText = ref(textOptions[0]);
let textIndex = 0;
let intervalId = null;

const changeText = () => {
  textIndex = (textIndex + 1) % textOptions.length;
  displayText.value = textOptions[textIndex];
};

onMounted(() => {
  intervalId = setInterval(changeText, 3000);
});

onUnmounted(() => {
  clearInterval(intervalId);
});
</script>

<style scoped>
.slide-text-enter-active,
.slide-text-leave-active {
  transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
  position: absolute;
}

.slide-text-enter-from {
  opacity: 0;
  transform: translateY(-100%);
}

.slide-text-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.slide-text-leave-from {
  opacity: 1;
  transform: translateY(0);
}

.slide-text-leave-to {
  opacity: 0;
  transform: translateY(100%);
}
</style>
