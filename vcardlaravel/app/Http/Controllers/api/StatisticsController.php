<?php

namespace App\Http\Controllers\api;

use App\Models\Transaction;
use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    private $isAdmin = false;

    public function currentCountActiveVcards(){
        return count(Vcard::withoutTrashed()->where('blocked',0)->get());
    }

    public function transactionsAmountByPaymentType(){
        $res = $this->filter(Transaction::select('payment_type',DB::raw('count(*) as count'),DB::raw('sum(value) as total')))
            ->groupBy('payment_type')
            ->get();

        return $res->flatMap(fn($x) => [
            $x->payment_type => [
                'count' => $x->count,
                'total' => $x->total,
            ]
        ]);
    }

    public function getAllBalancesDay($date){
        $balances = $this->filter(Transaction::select('vcard',DB::raw('max(datetime) as datetime'))
                                  ->groupBy('vcard'))
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

    public function filter($queryBuilder) {
        if($this->isAdmin) {
            return $queryBuilder;
        }
        return $queryBuilder->where('vcard',Auth::user()->username);
    }
    public function moneyMovedCountTransactionsByDay(){

        return $this->filter(Transaction::select('date',DB::raw('sum(value) as total'),DB::raw('count(*) as count'))
                      ->groupBy('date'))
                    ->get()
                    ->flatMap(fn($x) => [
                        $x->date => array_merge($this->getAllBalancesDay($x->date),[
                            'totalAmountMoved' => $x->total,
                            'countTransactions' => $x->count,
                        ])
                    ]);
    }

    public function statisticsResponse(){
        return [
            'dayStats' => $this->moneyMovedCountTransactionsByDay(),
            'transactions' => $this->transactionsAmountByPaymentType()
        ];
    }

    public function adminStatisticsResponse(){
        $this->isAdmin = true;
        return [
            'activeCards' => $this->currentCountActiveVcards(),
            'dayStats' => $this->moneyMovedCountTransactionsByDay(),
            'transactions' => $this->transactionsAmountByPaymentType()
        ];
    }
}
