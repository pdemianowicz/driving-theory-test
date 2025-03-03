<template>
  <footer class="bg-white dark:bg-[#181a1b] p-8 border-t border-gray-200 dark:border-neutral-800 transition-border-bg duration-500 mt-auto">
    <div class="max-w-screen-xl mx-auto flex items-center justify-between text-gray-600 dark:text-gray-400 text-sm font-medium">
      <p>Copyright © {{ currentYear }} e-prawko.pl</p>

      <a href="https://github.com/pdemianowicz" target="_blank" rel="noreferrer" class="flex items-center gap-1">
        <!-- <svg class="w-4 h-4 animate-pulse text-[#d7443e]" viewBox="0 0 24 24">
          <path
            fill="currentColor"
            d="M12 20.325q-.35 0-.712-.125t-.638-.4l-1.725-1.575q-2.65-2.425-4.788-4.812T2 8.15Q2 5.8 3.575 4.225T7.5 2.65q1.325 0 2.5.562t2 1.538q.825-.975 2-1.537t2.5-.563q2.35 0 3.925 1.575T22 8.15q0 2.875-2.125 5.275T15.05 18.25l-1.7 1.55q-.275.275-.637.4t-.713.125" />
        </svg> -->
        <span class="">Made with ❤️ for a better web.</span>
      </a>
    </div>
  </footer>
</template>

<script setup>
const currentYear = new Date().getFullYear();
</script>

<style lang="scss" scoped></style>
