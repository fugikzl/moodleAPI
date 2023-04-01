<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MoodleCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'shortname' => $this['shortname'],
            'fullname' => $this['fullname'],
            'enrolledusercount' => ['enrolledusercount'],
            'idnumber' => $this['idnumber'],
            'category' => $this['category'],
            'completed' => $this['completed'],
            'startdate' => $this['startdate'],
            'enddate' => $this['enddate'],
            'lastaccess' => $this['lastaccess'],
            'isfavourite' => $this['isfavourite'],
        ];
    }
}
