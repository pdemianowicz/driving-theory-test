import { defineStore } from "pinia";

export const useSettingsStore = defineStore("video", {
  state: () => ({
    autoPlay: false,
  }),
  actions: {
    toggleAutoPlay() {
      this.autoPlay = !this.autoPlay;
    },
  },
});
