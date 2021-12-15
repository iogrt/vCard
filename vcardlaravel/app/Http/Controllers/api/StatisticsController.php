<?php

namespace App\Http\Controllers\api;

use App\Models\Transaction;
use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function currentCountActiveVcards(){
        return count(Vcard::withoutTrashed()->where('blocked',0)->get());
    }

    public function transactionsAmountByPaymentTypeByDay($date){
        $res = Transaction::select('payment_type',DB::raw('count(*) as count'),DB::raw('sum(value) as total'))
            ->groupBy('payment_type')
            ->whereRaw("DATE(date) =  '$date'")
            ->get();

        return $res->map(fn($x) => [
            $x->payment_type => [
                'count' => $x->count,
                'total' => $x->total,
            ]
        ]);
    }

    public function getAllBalancesDay($date){
        $balances = Transaction::select('vcard',DB::raw('max(datetime) as datetime'))
                    ->groupBy('vcard')
                    ->whereRaw("DATE(date) = '$date'")
                    ->get()
                    ->map(fn($x) => Transaction::select(DB::raw('new_balance as balance'))
                                        ->where('vcard',$x->vcard)
                                        ->where('datetime',$x->datetime)
                                        ->first());
        return [
            'totalBalance' => $balances->reduce(fn($acc,$x) => $x->balance + $acc,0),
            'avgBalance' => $balances->reduce(fn($acc,$x) => $x->balance + $acc,0) / count($balances),
        ];
    }

    public function moneyMovedCountTransactionsByDay(){
        return Transaction::select('date',DB::raw('sum(value) as total'),DB::raw('count(*) as count'))
                    ->groupBy('date')
                    ->get()
                    ->map(fn($x) => [
                        $x->date => array_merge($this->getAllBalancesDay($x->date),[
                            'totalAmountMoved' => $x->total,
                            'countTransactions' => $x->count,
                            'transactions' => $this->transactionsAmountByPaymentTypeByDay($x->date)
                        ])
                    ]);
    }

    public function statisticsResponse(){
        return [
            'activeCards' => $this->currentCountActiveVcards(),
            'dayStats' => $this->moneyMovedCountTransactionsByDay()
        ];
    }
}
