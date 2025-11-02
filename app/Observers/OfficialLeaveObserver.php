<?php

namespace App\Observers;

use App\Models\OfficialLeave;
use Illuminate\Support\Facades\Auth;

class OfficialLeaveObserver
{
    /**
     * Handle the OfficialLeave "created" event.
     */
    public function created(OfficialLeave $officialLeave): void
    {
        //
    }

    /**
     * Handle the OfficialLeave "updated" event.
     */
    public function updating(OfficialLeave $officialLeave): void
    {
        if ($officialLeave->isDirty('status')) {

            if ($officialLeave->status === 'approved') {
                $officialLeave->approved_by = Auth::id();
            } else {
                $officialLeave->approved_by = null;
            }
        }
    }


    /**
     * Handle the OfficialLeave "deleted" event.
     */
    public function deleted(OfficialLeave $officialLeave): void
    {
        //
    }

    /**
     * Handle the OfficialLeave "restored" event.
     */
    public function restored(OfficialLeave $officialLeave): void
    {
        //
    }

    /**
     * Handle the OfficialLeave "force deleted" event.
     */
    public function forceDeleted(OfficialLeave $officialLeave): void
    {
        //
    }
}
