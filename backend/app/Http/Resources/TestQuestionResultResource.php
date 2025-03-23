<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestQuestionResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->question->id,
            'content'        => $this->question->content,
            'type'           => $this->question->type,
            'media'          => $this->question->media,
            'points'         => $this->question->points,
            'answers'        => AnswerResource::collection($this->question->answers),
            'user_answer_id' => $this->answer_id,
        ];
    }
}
