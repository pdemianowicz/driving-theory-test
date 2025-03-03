<template>
  <div>
    <h1>Statystyki egzaminów</h1>
    <ul>
      <li v-for="session in testSessions" :key="session.id">
        Egzamin z kategorii: {{ session.category_id }} - Wynik: {{ session.is_completed ? "Zakończony" : "W toku" }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axiosClient from "../axios.js";

const testSessions = ref([]);

onMounted(async () => {
  try {
    const response = await axiosClient.get("/api/user/stats");
    testSessions.value = response.data;
  } catch (error) {
    console.error("Error fetching user stats:", error);
  }
});
</script>

<style scoped></style>
