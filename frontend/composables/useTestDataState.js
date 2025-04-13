export const useTestDataState = () => {
  const testSessionId = useState("testSessionId", () => null);
  const category = useState("category", () => null);
  const questions = useState("questions", () => []);

  return {
    testSessionId,
    category,
    questions,
  };
};
