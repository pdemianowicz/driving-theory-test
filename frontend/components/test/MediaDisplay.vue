<template>
  <div class="relative w-full aspect-video rounded overflow-hidden">
    <img v-if="filename && filename.endsWith('.webp')" class="w-full h-full" :src="mediaUrl" alt="Ilustracja do pytania" />

    <div v-else-if="filename && filename.endsWith('.mp4')" :key="filename" class="relative w-full h-full">
      <video @ended="handleVideoEnded" ref="videoPlayer" class="w-full h-full">
        <source :src="mediaUrl" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div
        v-show="!isVideoPlaying"
        @click="playVideo()"
        class="absolute inset-0 flex items-center justify-center bg-[#354354]/60 text-white text-2xl cursor-pointer"
        aria-label="Odtwórz wideo"
        role="button">
        <span>
          <svg class="w-16 h-16 text-white/80" viewBox="0 0 24 24">
            <path
              fill="currentColor"
              d="m9.5 16.5l7-4.5l-7-4.5zM12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22" />
          </svg>
        </span>
      </div>
    </div>
    <img v-else class="w-full h-full" :src="fallbackImageUrl" alt="Brak dostępnego obrazu lub wideo" />
  </div>
</template>

<script setup>
const props = defineProps({
  data: String,
});

const testStore = useTestStore();

const filename = computed(() => props.data || "");
const fallbackImageUrl = "http://127.0.0.1:8000/brak-obrazu.jpg";
const mediaUrl = computed(() => {
  if (!filename.value) return fallbackImageUrl;
  return `http://127.0.0.1:8000/media/${filename.value}`;
});

const videoPlayer = ref(null);
const isVideoPlaying = ref(false);

function playVideo() {
  if (videoPlayer.value && !isVideoPlaying.value) {
    isVideoPlaying.value = true;
    videoPlayer.value.play();
    testStore.setVideoPlaying(true);
  }
}

function handleVideoEnded() {
  testStore.setVideoPlaying(false);
}

watch(
  () => props.data,
  () => {
    isVideoPlaying.value = false;
    if (videoPlayer.value) {
      videoPlayer.value.load();
    }
  },
  { immediate: true }
);
</script>

<style scoped></style>
