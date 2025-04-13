export default defineNuxtPlugin(async (nuxtApp) => {
  try {
    await $fetch(`${useRuntimeConfig().public.apiBase}/sanctum/csrf-cookie`, {
      credentials: "include",
    });

    const csrfToken = useCookie("XSRF-TOKEN").value;

    nuxtApp.provide("csrfToken", csrfToken);
  } catch (error) {
    console.error("Błąd podczas pobierania tokenu CSRF w pluginie:", error);
  }
});
