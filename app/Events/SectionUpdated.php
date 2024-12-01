<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Section;

class SectionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $section;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }
}
