<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\createTransactionRequest;
use App\Http\Requests\updateTransactionRequest;
use App\Jobs\UpdateTransactionStatus;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    public function store(createTransactionRequest $request)
    {
        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
            'status' => 'pending'
        ]);

        return response()->json($transaction);
    }


    public function update(updateTransactionRequest $request, Transaction $transaction)
    {
        UpdateTransactionStatus::dispatch($transaction, $request->status);

        return response()->json(['message' => 'Update is being processed'], 200);
    }

    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->user()->id)
            ->paginate(10);

        return response()->json($transactions);
    }

    public function transactionSummary()
    {
        $userId = auth()->user()->id;

        // Cache key to identify the user's transaction summary cache
        $cacheKey = 'transaction_summary_' . $userId;

        // Attempt to retrieve from cache, if not found, execute the query and cache the result
        $summary = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($userId) {
            return [
                'total_transactions' => Transaction::where('user_id', $userId)->count(),
                'average_amount' => Transaction::where('user_id', $userId)->average('amount'),
                'highest_transaction' => Transaction::where('user_id', $userId)->orderBy('amount', 'desc')->first(),
                'lowest_transaction' => Transaction::where('user_id', $userId)->orderBy('amount', 'asc')->first(),
                'longest_name_transaction' => Transaction::where('user_id', $userId)->with('user')->get()->sortByDesc(function ($transaction) {
                    return strlen($transaction->user->name);
                })->first(),
                'status_distribution' => [
                    'pending' => Transaction::where('user_id', $userId)->where('status', 'pending')->count(),
                    'completed' => Transaction::where('user_id', $userId)->where('status', 'completed')->count(),
                    'failed' => Transaction::where('user_id', $userId)->where('status', 'failed')->count(),
                ],
            ];
        });

        return response()->json($summary);
    }
}
