<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $modules = $this['modules'];
        $resource_modules = [];

        foreach($modules as $module)
        {
            $resource_modules[$module["modname"]."s"][] = $module;
        }
        return [
            "id" => $this['id'],
            "name" => $this['name'],
            "section" => $this['section'],
            "summary" => $this['summary'],
            "modules" => $resource_modules
        ];
    }
}
