import { computed } from "vue";
import { parseISO, differenceInSeconds } from "date-fns";

export function useTimeFormatter(resultsData) {
  const formattedTime = computed(() => {
    const completedAt = parseISO(resultsData.value.completed_at);
    const createdAt = parseISO(resultsData.value.created_at);
    const totalSeconds = differenceInSeconds(completedAt, createdAt);
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    return `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
  });

  return {
    formattedTime,
  };
}
