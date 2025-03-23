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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TestController extends Controller
{
    // Show all categories
    public function getCategories()
    {
        $categories = Cache::rememberForever('categories', function () {
            return Category::all();
        });

        return CategoryResource::collection($categories);
    }

    // Start a new test for a given category
    public function initTest(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);

        $category = Category::select('id', 'name')->findOrFail($request->category_id);

        $questions = $this->selectQuestions($category);
        $test      = $this->createTest($category, $questions);
        $this->createTestQuestions($test, $questions);

        return response()->json([
            'test_id'   => $test->id,
            'questions' => TestQuestionResource::collection($questions),
            'category'  => $category,
            'message'   => 'Sesja testowa rozpoczęta!',
        ]);

    }

    private function createTest(Category $category, $questions)
    {
        return Test::create([
            'user_id'         => Auth::id(),
            'category_id'     => $category->id,
            'started_at'      => now(),
            'total_questions' => count($questions),
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
            ->with(['answers'])
            ->get();

        $specialistQuestions = Question::whereHas('categories', function ($query) use ($category) {
            $query->where('categories.id', $category->id);
        })
            ->where('type', 'specialist')
            ->inRandomOrder()
            ->limit(12)
            ->with(['answers'])
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
            $correctAnswer = $testQuestion->question->answers()->where('is_correct', true)->first();
            $isCorrect     = ($correctAnswer && $correctAnswer->id == $answerId);
        }

        $testQuestion->answer_id         = $answerId;
        $testQuestion->is_correct        = $isCorrect;
        $testQuestion->answer_time_taken = $answerTimeTaken;
        $testQuestion->save();

        return response()->json(['message' => 'Odpowiedź zapisana!']);
    }

    public function finishTest(Test $test)
    {
        $test->completed_at = now();
        $test->save();

        return response()->json(['message' => 'Test zakończony!']);
    }

    public function getTestResult(Test $test)
    {
        $testQuestions = $test->testQuestions()->with('question.answers')->get();
        $test->load('category');

        return new TestResultResource($test, $testQuestions);
    }

}
