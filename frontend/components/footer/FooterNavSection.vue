<template>
  <div>
    <p class="font-semibold text-slate-900 dark:text-slate-50">{{ title }}</p>
    <nav :aria-label="`Footer Navigation - ${title}`" class="mt-6">
      <ul class="space-y-4 text-sm">
        <li v-for="navItem in navItems" :key="navItem.name">
          <NuxtLink :to="navItem.route" class="transition text-gray-500 hover:opacity-75">
            {{ navItem.name }}
          </NuxtLink>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  navItems: {
    type: Array,
    required: true,
  },
});
</script>

<style lang="scss" scoped></style>
