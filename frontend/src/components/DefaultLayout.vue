<template>
  <Header />
  <main class="flex-grow h-[calc(100%-56px)] pt-14 text-[#171717] dark:bg-[#181a1b] dark:text-[#d9d6d1] transition-colors duration-500">
    <RouterView class="container max-w-screen-xl mx-auto px-4 md:px-12 py-8 dark:text-white transition-border-bg duration-500" />
  </main>
  <Footer />
</template>

<script setup>
import Header from "./Header.vue";
import Footer from "./Footer.vue";
</script>

<style lang="scss" scoped></style>
