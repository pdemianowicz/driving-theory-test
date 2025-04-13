<template>
  <div class="relative group">
    <button
      id="options-menu-button"
      type="button"
      @click="toggleDropdown()"
      class="p-2 md:p-3 flex items-center justify-center text-gray-700 dark:text-[#dfdfd6] group-hover:text-[#98989f] transition-colors duration-300"
      aria-haspopup="true"
      :aria-label="ariaLabel"
      :aria-expanded="isOpen.toString()"
      aria-controls="dropdown-menu-container">
      <slot name="button">Dropdown</slot>
      <span>
        <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': isOpen }" viewBox="0 0 21 21" fill="currentColor">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="m14.5 8.5l-4 4l-4-4" />
        </svg>
      </span>
    </button>

    <div
      id="dropdown-menu-container"
      :class="[
        'md:bg-white md:dark:bg-[#1d2021] rounded-md ', // Wspólne style wyglądu
        'transition-all duration-300 ease-in-out', // Wspólne style przejścia
        // --- Style Domyślne (Mobile) ---
        'w-full mt-1 overflow-hidden', // Pełna szerokość, margines, ukrycie przepełnienia
        isOpen ? 'max-h-[500px] opacity-100' : 'max-h-0 opacity-0', // Widoczność na mobile przez klik (animacja max-height)
        // --- Style Desktop (md: i większe) ---
        'md:absolute md:-right-1/2 md:min-w-32 md:w-auto md:mt-0 md:shadow-lg md:ring-1 ring-black ring-opacity-5', // Przywrócenie absolute, rozmiarów i marginesu
        'md:opacity-0 md:pointer-events-none md:group-hover:opacity-100 md:group-hover:pointer-events-auto', // Przywrócenie widoczności na hover
        'md:max-h-none', // Usunięcie ograniczenia max-height na desktopie
      ]"
      role="menu"
      aria-labelledby="options-menu-button"
      aria-orientation="vertical">
      <div class="py-1" role="none">
        <template v-for="(item, index) in menuItems" :key="index">
          <a
            :href="item.href"
            @click="closeDropdown()"
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-stone-300 dark:hover:bg-opacity-5"
            role="menuitem">
            {{ item.label }}
          </a>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  menuItems: {
    type: Array,
    default: () => [],
  },
  ariaLabel: {
    type: String,
    default: "Toggle dropdown",
  },
});

const isOpen = ref(false);

function toggleDropdown() {
  isOpen.value = !isOpen.value;
}

function closeDropdown() {
  isOpen.value = false;
}
</script>

<style scoped></style>
