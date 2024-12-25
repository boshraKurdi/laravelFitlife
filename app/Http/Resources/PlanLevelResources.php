<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanLevelResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'goal_id' => $this->goal_id,
            'plan_level_id' => $this->plan_level_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'target' => $this->target,
            'data' => $this->target->users->date,
            'plan_levels' => $this->pll,
            'goals' => $this->goals,
            'check' => $this->check,
        ];
    }
}
