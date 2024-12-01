<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use App\Events\SectionUpdated;

class ClearSectionCache
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\SectionUpdated  $event
     * @return void
     */
    public function handle(SectionUpdated $event)
    {
        // Invalidate all sections cache
        Cache::forget('all_sections');
    }
}
