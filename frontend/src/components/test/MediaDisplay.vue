<template>
  <div class="relative w-full aspect-video rounded overflow-hidden">
    <img
      v-if="media && media.endsWith('.jpg')"
      class="w-full h-full"
      :src="`http://127.0.0.1:8000/storage/media/${media.replace('.jpg', '.webp')}`"
      alt="Obrazek" />

    <div v-else-if="media && media.endsWith('.wmv')" :key="media" class="relative w-full h-full">
      <video @ended="handleVideoEnded" ref="videoPlayer" class="w-full h-full">
        <source :src="`http://127.0.0.1:8000/storage/media/${media.replace('.wmv', '.mp4')}`" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div
        v-show="!isVideoPlaying"
        @click="playVideo"
        class="absolute inset-0 flex items-center justify-center bg-black/50 text-white text-2xl cursor-pointer">
        <span>
          <svg class="w-16 h-16 text-white/60" viewBox="0 0 24 24">
            <path
              fill="currentColor"
              d="m9.5 16.5l7-4.5l-7-4.5zM12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22" />
          </svg>
        </span>
      </div>
    </div>
    <img v-else src="../../assets/brak-obrazu.jpg" alt="Nie ma obrazka!" />
  </div>
</template>

<script setup>
import { ref, computed, defineProps, watch, defineEmits, onMounted } from "vue";
import { useSettingsStore } from "@/store/settings";

const props = defineProps({
  data: String,
});

const emit = defineEmits(["video-playing", "video-ended"]);

const media = computed(() => props.data);
const isVideoPlaying = ref(false);
const videoPlayer = ref(null);

const videoStore = useSettingsStore();
const isAutoPlayEnabled = ref(videoStore.autoPlay);
// const playVideos = () => {
//   if (videoStore.autoPlay && videoPlayer.value) {
//     videoPlayer.value.play();
//   }
// };
function playVideo() {
  isVideoPlaying.value = true;
  videoPlayer.value.play();
  emit("video-playing");
}

function handleVideoEnded() {
  emit("video-ended");
}

watch(
  () => props.data,
  () => {
    // Resetowanie stanu odtwarzania przy zmianie danych multimediów
    isVideoPlaying.value = false;
    if (videoPlayer.value) {
      videoPlayer.value.load(); // Ładowanie nowego wideo po zmianie
      if (videoStore.autoPlay) {
        setTimeout(() => {
          playVideo(); // Upewnij się, że odtworzenie wideo następuje po załadowaniu
        }, 1000); // Odczekaj chwilę, aby wideo miało czas na załadowanie
      }
    }
  }
);

watch(
  () => videoStore.autoPlay,
  (newAutoPlayState) => {
    isAutoPlayEnabled.value = newAutoPlayState;
    if (newAutoPlayState && !isVideoPlaying.value) {
      playVideo();
    }
  }
);
</script>

<style scoped></style>
