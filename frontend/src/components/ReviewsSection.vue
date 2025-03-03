<template>
  <section class="mt-40">
    <h2 class="text-center text-4xl font-bold text-slate-800 dark:text-stone-300">Uwielbiana przez społeczność</h2>
    <h3 class="text-center text-gray-700 dark:text-stone-400 text-xl px-5 mt-2.5 max-w-[470px] m-auto">
      Nie wierz nam na słowo - słuchaj tego, co mają do powiedzenia członkowie społeczności.
    </h3>
    <div class="md:columns-2 lg:columns-3 gap-6 max-w-[1100px] mx-auto mt-10">
      <div
        v-for="(item, index) in testimonials"
        :key="index"
        class="p-6 border border-neutral-200 dark:border-none dark:bg-[#1d2021] rounded-xl break-inside-avoid mb-6 transition-border-bg duration-500">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full overflow-hidden">
            <img class="w-full h-full" :src="item.image" alt="User avatar" />
          </div>
          <div>
            <p class="font-medium">{{ item.name }}</p>
            <span class="text-sm text-gray-700 dark:text-stone-400 font-medium">{{ item.username }}</span>
          </div>
        </div>
        <p class="mt-3">{{ item.text }}</p>
      </div>
    </div>
  </section>
</template>

<script setup>
const testimonials = [
  {
    name: "Paulina Pogorzelska",
    username: "@PaulinaP",
    image: "https://i.pravatar.cc/100?img=1",
    text: "Bardzo dobra aplikacja do nauki. Uwielbiam się z niej uczyć. Jest bardzo przydatna do przyswajania wiedzy. Planuję prawo jazdy w przyszłym roku. Jednak już od wakacji korzystam z aplikacji. Jestem mega zadowolona z materiału i formy. Gdy mam tylko chwilę wolnego siadam i się uczę.",
  },
  {
    name: "Filipskyo",
    username: "@Filipskyo",
    image: "https://i.pravatar.cc/100?img=2",
    text: "Pakiet kupiony dzień przed egzaminem, o wiele więcej pytań niż w aplikacji od mojej szkoły jazdy. Teoria zaliczona na 100% polecam.",
  },
  {
    name: "Agata Klosek",
    username: "@AgataK",
    image: "https://i.pravatar.cc/100?img=3",
    text: "Super aplikacja.. wiele więcej pytań w porównaniu z innymi aplikacjami.. a jeśli włączysz opcję automatycznego przełączania nauka idzie wręcz błyskawicznie 👍.",
  },
  {
    name: "Paula Love live",
    username: "@PaulaLive",
    image: "https://i.pravatar.cc/100?img=4",
    text: "Korzystam z aplikacji już drugi raz (kat. A teraz D) i jeśli będzie konieczność skorzystania po raz trzeci to napewno z tą aplikacją.",
  },
  {
    name: "Szymon 258",
    username: "@Szymon258",
    image: "https://i.pravatar.cc/100?img=5",
    text: "Fajna aplikacja do nauki teorii, zrozumiałe uzasadnienia odpowiedzi. Polecam przerobić sobie najpierw pytania działami a potem robić testy. Serdecznie polecam.",
  },
  {
    name: "Karolina D",
    username: "@KarolinaD",
    image: "https://i.pravatar.cc/100?img=6",
    text: "Bardzo dobra apka, dzięki niej zdane za pierwszym razem 💪 baza pytań ta sama co w Wordzie, nauka idzie bezproblemowo 🙂.",
  },
  {
    name: "Michal03",
    username: "@Michal03",
    image: "https://i.pravatar.cc/100?img=7",
    text: "Aplikacja jest świetna! Dzięki niej mogłem uczyć się w każdej wolnej chwili i zdałem egzamin teoretyczny bez problemu. Polecam każdemu, kto chce się dobrze przygotować!",
  },
  {
    name: "ZuzannaK",
    username: "@ZuziaK",
    image: "https://i.pravatar.cc/100?img=8",
    text: "Bardzo pomocna aplikacja! Interfejs jest przejrzysty, a pytania dobrze wytłumaczone. Dzięki niej udało mi się ogarnąć teorię w krótkim czasie.",
  },
  {
    name: "Wójcik",
    username: "@Wojcik",
    image: "https://i.pravatar.cc/100?img=9",
    text: "Zdane 74/74 ",
  },
];
</script>

<style lang="scss" scoped></style>
