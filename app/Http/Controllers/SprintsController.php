<?php

namespace App\Http\Controllers;

use App\Sprint;
use App\User;
use App\ExchangeRate;
use App\Currency;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SprintsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sprint::whereNull('closed_at')->with('project', 'currency')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'project' => 'required',
            'rate' => 'required|integer|min:0'
        ]);


        $input = $request->all();

        $dateStart = $input['started_at']
                ? \Carbon\Carbon::parse($input['started_at'])->tz('UTC')->format('Y-m-d H:i:s') :
                \Carbon\Carbon::now()->tz('UTC')->format('Y-m-d H:i:s');
                // dd($sprint->worked_time);

        $dateClose = $input['closed_date']
                ? \Carbon\Carbon::parse($input['closed_date'])->tz('UTC')->format('Y-m-d H:i:s'):
                null;

        if($request->input('payment_status') == 2) {
            $paymentDate = $dateClose ? \Carbon\Carbon::parse($input['closed_date'])->tz('UTC')
                : \Carbon\Carbon::now()->tz('UTC');
            $currency = Currency::find($request->input('currency'));
            //dd($paymentDate->toDateString());
            if($currency->name !== 'USD') {
                $exchangeRate = ExchangeRate::where('fsym',$currency->name)
                    ->whereDate('created_at', '=', $paymentDate->toDateString())
                    ->get()
                    ->first();
                //dd($exchangeRate->toArray());
                if(!$exchangeRate) {
                    $guzzleClient = new \GuzzleHttp\Client();
                    $currencies = Currency::where('name', '<>', 'USD')->get();
                    $minApiCryptocompare = 'https://min-api.cryptocompare.com/data/histoday?fsym={{fsym}}&tsym=USD&limit=1';
                    foreach ($currencies as $currency) {
                        $fsym = $currency->name;
                        $apiUrl = str_replace('{{fsym}}', $fsym, $minApiCryptocompare).'&toTs='.$paymentDate->timestamp;
                        //dd($apiUrl);
                        $responseBody = $guzzleClient
                            ->request('GET', $apiUrl)
                            ->getBody();
                        $res = json_decode($responseBody, true);
                        if($res['Data'] && $res['Data'][0]) {
                            $exchangeRate = new ExchangeRate;
                            $exchangeRate->fsym = $fsym;
                            $exchangeRate->tsym = 'USD';
                            $exchangeRate->rate = $res['Data'][0]['close'];
                            $exchangeRate->created_at = $paymentDate->format('Y-m-d H:i:s');
                            $exchangeRate->save();
                        }
                        //dd($res);
                        // $res = $res['coins'];
                        //$data = [];
                    }
                }
            }

            // $table->string('fsym')->nullable();
            // $table->string('tsym')->default('USD');
        }

        $sprint = User::find($input['user_id'])->sprints()->create([
            // 'user_id' => $request->input('user_id'),
            'project_id' => $request->input('project'),
            'rate' => $request->input('rate'),
            'currency_id' => $request->input('currency'),
            'rate_type' => $request->input('rate_type', null),
            'worked_time' => $request->input('worked_time', 0),
            'payment_status' => $request->input('payment_status', null),
            'notes' => $request->input('notes', null),
            'started_at' => $dateStart,
            'closed_at' => $dateClose,

        ]);

        return $sprint->load('user', 'project','project.owner', 'currency');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sprint  $sprint
     * @return \Illuminate\Http\Response
     */
    public function show(Sprint $sprint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sprint  $sprint
     * @return \Illuminate\Http\Response
     */
    public function edit(Sprint $sprint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sprint  $sprint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'project' => 'required',
            'rate' => 'required|integer|min:0'
        ]);


        $input = $request->all();

        //if payment - get currency cource
        // if($sprint->payment_status) {
        //     $currency = $request->input('currency');
        // }

        $dateClose = $input['closed_date']
                ? \Carbon\Carbon::parse($input['closed_date'])->tz('UTC')->format('Y-m-d H:i:s'):
                \Carbon\Carbon::now()->tz('UTC')->format('Y-m-d H:i:s');

        $sprint = Sprint::findOrFail($input['sprint_id']);

        $dateStart = $input['started_at']
            ? \Carbon\Carbon::parse($input['started_at'])->tz('UTC')->format('Y-m-d H:i:s') :
            $sprint->started_at;

        $sprint->rate = $request->input('rate');
        $sprint->currency_id = $request->input('currency');
        $sprint->rate_type = $request->input('rate_type', 'hourly');
        $sprint->worked_time = $request->input('worked_time', 0);
        $sprint->payment_status =  $request->input('payment_status', false);
        $sprint->notes = $request->input('notes', null);
        $sprint->closed_at = $dateClose;
                //  dd($sprint->worked_time);
        $sprint->save();

        return $sprint->load('user', 'project','project.owner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sprint  $sprint
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get customer
        $sprint = Sprint::findOrFail($id);

        if($sprint->delete()){
            return $sprint;
        }
    }
}
