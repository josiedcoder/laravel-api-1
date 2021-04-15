<?php

namespace App\Observers;

use App\Models\ExchangeRate;
use App\Events\ExchangeRateEvent;

class ExchangeRateObserver
{
    /**
     * Handle the ExchangeRate "created" event.
     *
     * @param  \App\Models\Models\ExchangeRate  $exchangeRate
     * @return void
     */
    public function created(ExchangeRate $exchangeRate)
    {
        //
    }

    /**
     * Handle the ExchangeRate "updated" event.
     *
     * @param  \App\Models\Models\ExchangeRate  $exchangeRate
     * @return void
     */
    public function updated(ExchangeRate $exchangeRate)
    {
        //
    }

    /**
     * Handle the ExchangeRate "deleted" event.
     *
     * @param  \App\Models\Models\ExchangeRate  $exchangeRate
     * @return void
     */
    public function deleted(ExchangeRate $exchangeRate)
    {
        //
    }

    /**
     * Handle the ExchangeRate "restored" event.
     *
     * @param  \App\Models\Models\ExchangeRate  $exchangeRate
     * @return void
     */
    public function restored(ExchangeRate $exchangeRate)
    {
        //
    }

    /**
     * Handle the ExchangeRate "force deleted" event.
     *
     * @param  \App\Models\Models\ExchangeRate  $exchangeRate
     * @return void
     */
    public function forceDeleted(ExchangeRate $exchangeRate)
    {
        //
    }

    public function saved(ExchangeRate $exchangeRate)
    {
        broadcast(new ExchangeRateEvent($exchangeRate));
    }
}
