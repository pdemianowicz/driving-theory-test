<template>
  <div class="max-w-[1200px] mx-auto py-10">
    <h1 class="text-3xl md:text-4xl font-bold text-center mb-4 text-slate-800 dark:text-stone-200">Kategorie Prawa Jazdy</h1>
    <p class="text-center text-lg text-gray-600 dark:text-gray-400 mb-10">
      Wybierz kategorię, która Cię interesuje, aby dowiedzieć się więcej i rozpocząć naukę.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <!--  :to="`/kategorie-prawa-jazdy/${category.slug}`" -->
      <NuxtLink
        v-for="category in categories"
        :key="category.slug"
        class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-300 ease-in-out hover:-translate-y-1 group"
        :aria-label="`Dowiedz się więcej o ${category.name}`">
        <!-- Miejsce na ikonę (opcjonalnie) -->
        <div class="mb-3 text-center">
          <!-- Przykład użycia prostej ikony SVG lub komponentu ikony -->
          <!-- <Icon :name="category.iconName || 'ph:car-simple-duotone'" size="48" class="mx-auto text-blue-500 group-hover:text-blue-600 transition-colors" /> -->
          <span class="text-5xl">{{ category.emoji || "🚗" }}</span>
        </div>

        <h2
          class="text-xl font-semibold text-center text-slate-800 dark:text-stone-200 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
          {{ category.name }}
        </h2>
        <p v-if="category.description" class="text-sm text-center text-gray-600 dark:text-gray-400">
          {{ category.description }}
        </p>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup>
useHead({
  title: "Kategorie Prawa Jazdy - SuperPrawko",
  meta: [
    {
      name: "description",
      content:
        "Przeglądaj wszystkie kategorie prawa jazdy dostępne w Polsce. Dowiedz się więcej o wymaganiach, kursach i egzaminach dla każdej kategorii.",
    },
  ],
});

const categories = ref([
  {
    name: "Kategoria AM",
    slug: "kategoria-am",
    description: "Motorower, czterokołowiec lekki.",
    // iconName: 'mdi:moped', // przykład dla nuxt-icon
    emoji: "🛵",
  },
  {
    name: "Kategoria A1",
    slug: "kategoria-a1",
    description: "Motocykl do 125 cm³ i 11 kW.",
    emoji: "🏍️",
  },
  {
    name: "Kategoria A2",
    slug: "kategoria-a2",
    description: "Motocykl do 35 kW.",
    emoji: "🏍️",
  },
  {
    name: "Kategoria A",
    slug: "kategoria-a",
    description: "Wszystkie motocykle.",
    emoji: "🏍️💨",
  },
  {
    name: "Kategoria B1",
    slug: "kategoria-b1",
    description: "Czterokołowiec.",
    emoji: "🏎️",
  },
  {
    name: "Kategoria B",
    slug: "kategoria-b",
    description: "Samochód osobowy do 3.5t.",
    emoji: "🚗",
  },

  {
    name: "Kategoria C1",
    slug: "kategoria-c1",
    description: "Lekka ciężarówka (3.5t - 7.5t).",
    emoji: "🚚",
  },
  {
    name: "Kategoria C",
    slug: "kategoria-c",
    description: "Samochód ciężarowy > 3.5t.",
    emoji: "🚛",
  },

  {
    name: "Kategoria D1",
    slug: "kategoria-d1",
    description: "Mały autobus (do 17 osób).",
    emoji: "🚌",
  },
  {
    name: "Kategoria D",
    slug: "kategoria-d",
    description: "Autobus.",
    emoji: "🚌",
  },
  {
    name: "Kategoria T",
    slug: "kategoria-t",
    description: "Ciągnik rolniczy.",
    emoji: "🚜",
  },
  {
    name: "Kategoria PT",
    slug: "kategoria-pt",
    description: "Tramwaj.",
    emoji: "🚋",
  },
]);
</script>

<style scoped></style>
