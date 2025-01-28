<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\TestAnswer;
use App\Models\TestSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $category                  = Category::findOrFail($categoryId);
        $testSession               = new TestSession();
        $testSession->category_id  = $category->id;
        $testSession->uuid         = Str::uuid();
        $testSession->is_completed = false;
        $testSession->save();

        return response()->json($testSession->uuid);
    }

    public function questions(string $uuid)
    {
        $testSession = TestSession::where('uuid', $uuid)->firstOrFail();
        $categoryId  = $testSession->category_id;
        $category    = Category::findOrFail($categoryId);

        $basicQuestions = Question::where('category_id', $categoryId)
            ->where('question_type', 'basic')
            ->inRandomOrder()
            ->with('answers')
            ->limit(20)
            ->get();

        $specialistQuestions = Question::where('category_id', $categoryId)
            ->where('question_type', 'specialist')
            ->inRandomOrder()
            ->with('answers')
            ->limit(12)
            ->get();

        $questions = $basicQuestions->concat($specialistQuestions)->values();

        return response()->json([
            'category_name' => $category->name,
            'questions'     => $questions,
        ]);
    }

    public function submitAnswer(Request $request, string $uuid)
    {
        $session = TestSession::where('uuid', $uuid)->firstOrFail();
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer'      => 'nullable|exists:answers,id',
        ]);
        try {
            TestAnswer::create([
                'test_session_id' => $session->id,
                'question_id'     => $request->question_id,
                'user_answer_id'  => $request->answer_id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Wystąpił błąd podczas zapisu odpowiedzi.'], 500);
        }

    }

    public function finishTest(Request $request, string $uuid)
    {
        try {
            $session = TestSession::where('uuid', $uuid)->firstOrFail();
            $session->update([
                'completed_at' => now(),
                'is_completed' => true,
            ]);

            return response()->json(['message' => 'Test zakończony.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Wystąpił błąd podczas kończenia testu.'], 500);
        }

    }

    public function resultsTest(string $uuid)
    {

        $session = TestSession::where('uuid', $uuid)->firstOrFail();

        $userAnswersWithAllOptions = [];

        foreach ($session->testAnswers as $testAnswer) {
            $question                    = $testAnswer->question;
            $userAnswersWithAllOptions[] = [
                'question'        => $question,
                'given_answer_id' => $testAnswer->user_answer_id,
                'answers'         => $question->answers,
            ];
        }

        // return response()->json($userAnswersWithAllOptions);

        return response()->json([
            'category'     => $session->category->name,
            'completed_at' => $session->completed_at,
            'created_at'   => Carbon::parse($session->created_at)->format('Y-m-d H:i:s'),
            'answers'      => $userAnswersWithAllOptions,
        ]);

    }

}
