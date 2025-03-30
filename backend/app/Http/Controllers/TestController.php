<?php
namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\TestQuestionResource;
use App\Http\Resources\TestResultResource;
use App\Models\Category;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestQuestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    // Show all categories
    public function getCategories()
    {
        $locale     = App::getLocale();
        $categories = Cache::rememberForever('categories:' . $locale, function () {
            return Category::with('translations')->get();
        });

        return CategoryResource::collection($categories);
    }

    // Start a new test for a given category
    public function initTest(Request $request)
    {

        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $headerLocale = $request->header('X-Locale');
        App::setLocale($headerLocale);

        $category = Category::with('translations')->findOrFail($request->category_id);

        $questions = $this->selectQuestions($category);

        $test = Test::create([
            'user_id'         => Auth::id(),
            'category_id'     => $category->id,
            'started_at'      => now(),
            'total_questions' => $questions->count(),
        ]);

        $this->createTestQuestions($test, $questions);

        return response()->json([
            'test_id'   => $test->id,
            'questions' => TestQuestionResource::collection($questions),
            'category'  => new CategoryResource($category),
        ]);

    }

    private function selectQuestions(Category $category)
    {
        $basicQuestions = Question::whereHas('categories', function ($query) use ($category) {
            $query->where('categories.id', $category->id);
        })
            ->where('type', 'basic')
            ->inRandomOrder()
            ->limit(20)
            ->with(['translations', 'answers.translations'])
            ->get();

        $specialistQuestions = Question::whereHas('categories', function ($query) use ($category) {
            $query->where('categories.id', $category->id);
        })
            ->where('type', 'specialist')
            ->inRandomOrder()
            ->limit(12)
            ->with(['translations', 'answers.translations'])
            ->get();

        return $basicQuestions->concat($specialistQuestions)->values();
    }

    private function createTestQuestions(Test $test, Collection $questions)
    {
        $questionOrder = 1;
        foreach ($questions as $question) {
            TestQuestion::create([
                'test_id'        => $test->id,
                'question_id'    => $question->id,
                'question_order' => $questionOrder++,
            ]);
        }
    }

    // Save answers
    public function answerQuestion(Request $request, Test $test, int $questionOrder)
    {
        $request->validate([
            'answer_id'         => 'nullable|exists:answers,id',
            'answer_time_taken' => 'nullable|integer|min:0',
        ]);

        $answerId        = $request->input('answer_id');
        $answerTimeTaken = $request->input('answer_time_taken');

        $testQuestion = TestQuestion::where('test_id', $test->id)->where('question_order', $questionOrder)->firstOrFail();

        $isCorrect = null;

        if ($answerId !== null) {
            $correctAnswer = $testQuestion->question->answers->firstWhere('is_correct', true);
            $isCorrect     = $correctAnswer && $correctAnswer->id == $answerId;
        }

        $testQuestion->update([
            'answer_id'         => $answerId,
            'is_correct'        => $isCorrect,
            'answer_time_taken' => $answerTimeTaken,
        ]);

        return response()->json(['message' => 'Odpowiedź zapisana!']);
    }

    // finish test
    public function finishTest(Request $request, Test $test)
    {
        $request->validate([
            'time_taken' => 'nullable|integer|min:0',
        ]);

        $timeTaken = $request->input('time_taken');

        $test->update([
            'completed_at' => now(),
            'time_taken'   => $timeTaken,
        ]);

        return response()->json(['message' => 'Test zakończony!']);
    }

    // show results of test
    public function getTestResult(Test $test)
    {
        $test->load([
            'category',
            'testQuestions.question.answers',
        ]);

        return new TestResultResource($test);
    }

}
