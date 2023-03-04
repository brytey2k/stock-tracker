<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiFailureResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\StockHistory;
use App\Notifications\StockQuoteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StockTrackerController extends Controller
{
    public function getStock(Request $request) {
        $stockTicker = $request->query('q');

        if($stockTicker === '' || is_null($stockTicker)) {
            return new ApiFailureResponse('Stock ticker is required', 400);
        }

        $response = Http::get("https://stooq.com/q/l/?s=$stockTicker&f=sd2t2ohlcvn&h&e=json");

        if($response->successful()) {
            $data = collect($response->collect('symbols')->first())->only([
                'name', 'symbol', 'open', 'high', 'low', 'close',
            ]);

            // queue email to be sent
            $request->user()->notify(new StockQuoteNotification($data));

            // save history
            StockHistory::create([
                'user_id' => $request->user()->id,
                'data' => $data->toJson(),
            ]);

            return response()->json($data);
        } else {
            return new ApiFailureResponse('Failed to get stock data.', 500);
        }
    }

    public function history(Request $request) {
        $history = StockHistory::where('user_id', $request->user()->id)->latest('created_at')->cursor();
        $histories = [];
        $history->each(function($item) use (&$histories) {
            $data = json_decode($item->data, true);
            $data['date'] = $item->created_at;

            $histories[] = $data;
        });
        return response()->json($histories);
    }

}
