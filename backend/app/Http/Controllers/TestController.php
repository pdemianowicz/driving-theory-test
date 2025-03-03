<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\TestAnswer;
use App\Models\TestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name', 'description')->get();
        return response()->json($categories);
    }

    public function start(string $categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $testSession = TestSession::create([
            'category_id'            => $category->id,
            'uuid'                   => Str::uuid(),
            'is_completed'           => false,
            'current_question_index' => 0,
            'selected_answers'       => [],
            'user_id'                => Auth::check() ? Auth::id() : null,
        ]);

        return response()->json($testSession->uuid);
    }

    public function questions(string $uuid)
    {
        $testSession = TestSession::where('uuid', $uuid)->firstOrFail();
        $categoryId  = $testSession->category_id;
        $category    = Category::findOrFail($categoryId);

        $categoryFilter = function ($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        };

        $basicQuestions = Question::whereHas('categories', $categoryFilter)
            ->where('question_type', 'basic')
            ->with('answers')
            ->inRandomOrder()
            ->limit(20)
            ->get();

        $specialistQuestions = Question::whereHas('categories', $categoryFilter)
            ->where('question_type', 'specialist')
            ->with('answers')
            ->inRandomOrder()
            ->limit(12)
            ->get();

        $questions = $basicQuestions->concat($specialistQuestions)->values();

        return response()->json([
            'category_name' => "$category->name",
            'questions'     => $questions,
        ]);
    }

    public function submitAnswer(Request $request, string $uuid)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_id'   => 'nullable|exists:answers,id',
        ]);

        $session = TestSession::where('uuid', $uuid)->firstOrFail();

        try {
            $testAnswer = TestAnswer::updateOrCreate(
                [
                    'test_session_id' => $session->id,
                    'question_id'     => $request->question_id,
                ],
                [
                    'user_answer_id' => $request->answer_id,
                ]
            );

            // Zapisanie aktualnego indeksu pytania
            $session->current_question_index = $request->current_question_index;
            $session->save();

            return response()->json([
                'message' => 'Odpowiedź zapisana.',
                'answer'  => $testAnswer,
            ]);

        } catch (\Exception $e) {
            Log::error('Błąd zapisu odpowiedzi: ' . $e->getMessage());
            return response()->json(['message' => 'Wystąpił błąd podczas zapisu odpowiedzi.'], 500);
        }
    }

    // public function finishTest(Request $request, string $uuid)
    // {
    //     try {
    //         $session = TestSession::where('uuid', $uuid)->firstOrFail();
    //         $session->update([
    //             'completed_at' => now(),
    //             'is_completed' => true,
    //         ]);

    //         return response()->json(['message' => 'Test zakończony.']);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Wystąpił błąd podczas kończenia testu.'], 500);
    //     }

    // }

    public function finishTest(Request $request, string $uuid)
    {
        // Walidacja danych wejściowych
        $request->validate([
            'answers'               => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_id'   => 'nullable|exists:answers,id',
        ]);

        try {
            $session = TestSession::where('uuid', $uuid)->firstOrFail();
            // Aktualizacja sesji testu – oznaczenie zakończenia testu
            $session->update([
                'completed_at' => now(),
                'is_completed' => true,
            ]);

            // Przetwarzamy przesłany zbiór odpowiedzi
            foreach ($request->answers as $answerData) {
                TestAnswer::updateOrCreate(
                    [
                        'test_session_id' => $session->id,
                        'question_id'     => $answerData['question_id'],
                    ],
                    [
                        'user_answer_id' => $answerData['answer_id'],
                    ]
                );
            }

            return response()->json(['message' => 'Test zakończony.']);
        } catch (\Exception $e) {
            // Możesz logować błąd:
            Log::error('Błąd kończenia testu: ' . $e->getMessage());
            return response()->json(['message' => 'Wystąpił błąd podczas kończenia testu.'], 500);
        }
    }

    public function resultsTest(string $uuid)
    {
        $session = TestSession::with(['testAnswers.question.answers', 'category'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $answers = $session->testAnswers->map(fn($testAnswer) => [
            'question'        => $testAnswer->question,
            'given_answer_id' => $testAnswer->user_answer_id,
            'answers'         => $testAnswer->question->answers,
        ]);

        return response()->json([
            'category'     => $session->category->name,
            'completed_at' => $session->completed_at,
            'created_at'   => $session->created_at->format('Y-m-d H:i:s'),
            'answers'      => $answers,
        ]);
    }

    public function userStats()
    {
        $userId       = Auth::id();
        $testSessions = TestSession::where('user_id', $userId)->get();

        return response()->json($testSessions);
    }
}
