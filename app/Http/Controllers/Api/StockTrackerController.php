<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiFailureResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StockTrackerController extends Controller
{
    public function getStock(Request $request) {
        $stockTicker = $request->query('q');

        if($stockTicker === '') {
            return new ApiFailureResponse('Stock ticker is required');
        }

        $response = Http::get("https://stooq.com/q/l/?s=$stockTicker&f=sd2t2ohlcvn&h&e=json");

        if($response->successful()) {
            $data = collect($response->collect('symbols')->first())->only([
                'name', 'symbol', 'open', 'high', 'low', 'close',
            ]);

            // todo: send email

            // save history
            StockHistory::create([
                'user_id' => $request->user()->id,
                'data' => json_encode($data),
            ]);

            return response()->json($data);
        } else {
            return new ApiFailureResponse('Failed to get stock data.');
        }
    }

    public function history(Request $request) {
        $history = StockHistory::where('user_id', $request->user()->id)->cursor();
        $histories = [];
        $history->each(function($item) use (&$histories) {
            $data = json_decode($item->data, true);
            $data['date'] = $item->created_at;

            $histories[] = $data;
        });
        return response()->json($histories);
    }

}
