<template>
  <div
    ref="menuRef"
    id="testMenu"
    :class="[
      ' absolute inset-0 left-full p-3 ml-0.5 -mt-0.5 bg-white rounded-lg border border-neutral-200 dark:border-none dark:bg-[#1d2021] text-slate-800 dark:text-stone-300 transition duration-500',
      menuIsActive ? '-translate-x-full' : 'translate-x-6',
      'w-[calc(100%+4px)]',
      'h-[calc(100%+4px)]',
    ]">
    <div class="flex flex-col h-full pt-0.5">
      <ToggleSwitch v-model="newAppInterface" label="Nowy interfejs aplikacji" />
      <ToggleSwitch v-model="autoPlay" label="Autoodtwarzanie filmów" />

      <span class="text-sm font-medium py-3 border-b dark:border-neutral-800 hover:text-blue-400 cursor-pointer transition-colors">Statystyki</span>
      <span class="text-sm font-medium py-3 border-b dark:border-neutral-800 hover:text-blue-400 cursor-pointer transition-colors">Mój profil</span>
      <span class="text-sm font-medium py-3 border-b dark:border-neutral-800 hover:text-blue-400 cursor-pointer transition-colors">Pomoc</span>
      <span class="text-sm font-medium py-3 border-b dark:border-neutral-800 hover:text-blue-400 cursor-pointer transition-colors">FAQ</span>
      <span class="text-sm font-medium py-3 border-b dark:border-neutral-800 hover:text-blue-400 cursor-pointer transition-colors">Zgłoś błąd!</span>

      <Button @click="" class="!bg-blue-500 !text-neutral-50 !text-base hover:!bg-blue-500/90 mt-auto">Zapisz</Button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { onClickOutside } from "@vueuse/core";
import Button from "../../components/test/Button.vue";
import ToggleSwitch from "@/components/Switch.vue";
import { useSettingsStore } from "@/store/settings";

const videoStore = useSettingsStore();
const autoPlay = ref(videoStore.autoPlay);
watch(autoPlay, (newVal) => {
  videoStore.autoPlay = newVal;
});

const newAppInterface = ref(true);

const props = defineProps({
  menuIsActive: Boolean,
});

const emit = defineEmits(["update:menuIsActive"]);

const menuRef = ref(null);

onClickOutside(menuRef, () => {
  if (props.menuIsActive) {
    emit("update:menuIsActive", false);
  }
});
</script>

<style scoped></style>
