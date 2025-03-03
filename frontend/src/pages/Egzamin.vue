<template>
  <component :is="currentComponent" />
</template>

<script setup>
import TestOld from "./TestOld.vue";
import TestNew from "./TestNew.vue";
import { ref, computed } from "vue";

const currentView = ref("old");

const currentComponent = computed(() => {
  return currentView.value === "old" ? TestOld : TestNew;
});
</script>
