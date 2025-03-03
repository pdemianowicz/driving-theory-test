import { createApp } from "vue";
import { createPinia } from "pinia";
import { createI18n } from "vue-i18n";
import "./style.css";
import App from "./App.vue";
import router from "./router";
import i18n from "./i18n";

const pinia = createPinia();

createApp(App).use(router).use(pinia).use(i18n).mount("#app");
