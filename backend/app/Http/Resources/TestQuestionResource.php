<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'content'     => $this->content,
            'explanation' => $this->explanation,
            'type'        => $this->type,
            'media'       => $this->media,
            'points'      => $this->points,
            'answers'     => AnswerResource::collection($this->answers),
        ];

    }
}
