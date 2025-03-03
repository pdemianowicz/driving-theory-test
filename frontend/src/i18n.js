import { createI18n } from "vue-i18n";
import headerPl from "./locales/pl/headerPl.json";
import headerEn from "./locales/en/headerEn.json";

const messages = {
  pl: {
    header: headerPl,
  },
  en: {
    header: headerEn,
  },
};

const i18n = createI18n({
  legacy: false,
  locale: "pl",
  fallbackLocale: "en",
  messages,
});

export default i18n;
