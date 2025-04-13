// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: "2024-11-01",
  devtools: { enabled: true },
  modules: ["@nuxtjs/tailwindcss", "@vueuse/nuxt", "@nuxtjs/color-mode", "@pinia/nuxt", "@nuxt/image"],

  // colorMode: {
  //   preference: "system",
  //   fallback: "light",
  //   classSuffix: "",
  //   // storage: "cookie",
  //   storageKey: "nuxt-color-mode",
  // },

  colorMode: {
    preference: "system",
    fallback: "light",
    classSuffix: "",
    storageKey: "nuxt-color-mode",
  },

  tailwindcss: {
    exposeConfig: true,
    viewer: true,
    // and more...
  },

  runtimeConfig: {
    public: {
      apiBase: "http://localhost:8000",
    },
  },
});
