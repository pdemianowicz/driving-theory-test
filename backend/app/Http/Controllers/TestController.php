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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ItemNotFoundException;

class TestController extends Controller
{
    const MAX_POSSIBLE_SCORE = 74;
    const PASSING_SCORE      = 68;

    // Show all categories
    public function getCategories()
    {
        $locale = App::getLocale();
        // $locale     = 'pl';
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

        $test = DB::transaction(function () use ($category, $questions) {
            $test = Test::create([
                'user_id'            => Auth::id(),
                'category_id'        => $category->id,
                'started_at'         => now(),
                'total_questions'    => $questions->count(),
                'max_possible_score' => self::MAX_POSSIBLE_SCORE,
                'score'              => 0,
            ]);

            $this->createTestQuestions($test, $questions);

            return $test;
        });

        $testQuestions = $test->testQuestions()->with(['question.translations', 'question.answers.translations'])->orderBy('question_order')->get();

        return response()->json([
            'test_id'            => $test->id,
            // 'questions'          => TestQuestionResource::collection($testQuestions),
            'questions'          => TestQuestionResource::collection($questions),
            'category'           => new CategoryResource($category),
            'max_possible_score' => self::MAX_POSSIBLE_SCORE,
        ]);

    }

    private function selectQuestions(Category $category)
    {
        $queryBuilder = function ($type, $points) use ($category) {
            return Question::whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
                ->where('type', $type)
                ->where('points', $points)
                ->with(['translations', 'answers.translations']);
        };

        // --- (Basic) ---
        $basic3Points = $queryBuilder('basic', 3)->inRandomOrder()->limit(10)->get();
        if ($basic3Points->count() < 10) {
            throw new ItemNotFoundException("Brak wystarczającej liczby pytań podstawowych za 3 punkty.");
        }

        $basic2Points = $queryBuilder('basic', 2)->inRandomOrder()->limit(6)->get();
        if ($basic2Points->count() < 6) {
            throw new ItemNotFoundException("Brak wystarczającej liczby pytań podstawowych za 2 punkty.");
        }

        $basic1Point = $queryBuilder('basic', 1)->inRandomOrder()->limit(4)->get();
        if ($basic1Point->count() < 4) {
            throw new ItemNotFoundException("Brak wystarczającej liczby pytań podstawowych za 1 punkt.");
        }

        // --- (Specialist) ---
        $specialist3Points = $queryBuilder('specialist', 3)->inRandomOrder()->limit(6)->get();
        if ($specialist3Points->count() < 6) {
            throw new ItemNotFoundException("Brak wystarczającej liczby pytań specjalistycznych za 3 punkty.");
        }

        $specialist2Points = $queryBuilder('specialist', 2)->inRandomOrder()->limit(4)->get();
        if ($specialist2Points->count() < 4) {
            throw new ItemNotFoundException("Brak wystarczającej liczby pytań specjalistycznych za 2 punkty.");
        }

        $specialist1Point = $queryBuilder('specialist', 1)->inRandomOrder()->limit(2)->get();
        if ($specialist1Point->count() < 2) {
            throw new ItemNotFoundException("Brak wystarczającej liczby pytań specjalistycznych za 1 punkt.");
        }

        $shuffledBasicQuestions      = $basic3Points->concat($basic2Points)->concat($basic1Point)->shuffle();
        $shuffledSpecialistQuestions = $specialist3Points->concat($specialist2Points)->concat($specialist1Point)->shuffle();
        $finalOrderedQuestions       = $shuffledBasicQuestions->concat($shuffledSpecialistQuestions);

        return $finalOrderedQuestions->values();
    }

    private function createTestQuestions(Test $test, Collection $questions)
    {
        $questionOrder     = 1;
        $testQuestionsData = [];
        $now               = now();

        foreach ($questions as $question) {
            $testQuestionsData[] = [
                'test_id'        => $test->id,
                'question_id'    => $question->id,
                'question_order' => $questionOrder++,
                'points'         => $question->points,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }
        TestQuestion::insert($testQuestionsData);
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

        $score = $test->testQuestions()
            ->where('is_correct', true)
            ->sum('points');

        $test->update([
            'completed_at' => now(),
            'time_taken'   => $timeTaken,
            'score'        => $score,
        ]);

        return response()->json(['message' => 'Test zakończony!']);
    }

    // show results of test
    public function getTestResult(Test $test)
    {
        $test->refresh();

        if (! $test->completed_at) {
            return response()->json(['message' => 'Test nie został jeszcze zakończony.'], 403);
        }

        $test->load([
            'category.translations',
            'testQuestions.question.answers',
        ]);

        return new TestResultResource($test);
    }

}
