<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'test_id'         => $this->id,
            'category_name'   => $this->category->name,
            'completed_at'    => $this->completed_at,
            'score'           => $this->score,
            'total_questions' => $this->total_questions,
            'time_taken'      => $this->time_taken,
            'questions'       => TestQuestionResultResource::collection($this->testQuestions),
        ];

    }
}
