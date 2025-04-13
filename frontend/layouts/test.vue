<template>
  <Header />

  <div class="flex flex-col min-h-screen">
    <main class="flex-grow pt-14 text-[#171717] dark:bg-[#181a1b] dark:text-[#d9d6d1] transition-colors duration-500">
      <div class="px-4 sm:px-6 lg:px-8 my-10 max-w-screen-lg mx-auto dark:text-white">
        <slot />
      </div>
    </main>
  </div>

  <Footer />
</template>

<script setup></script>
<style lang="scss" scoped></style>
