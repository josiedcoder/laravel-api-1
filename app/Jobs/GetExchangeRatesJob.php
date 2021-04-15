<?php

namespace App\Jobs;

use App\Models\ExchangeRate;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use CoinMarketCap\Api as CoinMarketAPI;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GetExchangeRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $coinMarketData = new CoinMarketAPI(env('COINMARKET_API_KEY'));
        $exchangeRate = $coinMarketData->tools()->priceConversion(['amount' => 1, 'symbol' => 'BTC']);
        if ($exchangeRate) {
            ExchangeRate::updateOrCreate(
                ['currency_id' =>  $exchangeRate->data->id],
                [
                    'name' => $exchangeRate->data->name,
                    'symbol' => $exchangeRate->data->symbol,
                    'amount' => $exchangeRate->data->amount,
                    'price' => $exchangeRate->data->quote->USD->price
                ]
            );
        }
    }
}
