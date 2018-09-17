<?php

namespace App\Http\Controllers;

use App\Sprint;
use App\User;
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
        return Sprint::with('project')->get();
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
                ? \Carbon\Carbon::parse($input['started_at'])->tz('UTC'):
                null;

        $sprint = User::find($input['user_id'])->sprints()->create([
            // 'user_id' => $request->input('user_id'),
            'project_id' => $request->input('project'),
            'rate' => $request->input('rate'),
            'currency' => $request->input('currency'),
            'rate_type' => $request->input('rate_type', null),
            'worked_time' => $request->input('worked_time', 0),
            'payment_status' => $request->input('payment_status', null),
            'notes' => $request->input('notes', null),
            'started_at' => $dateStart,
            'closed_at' => $request->input('closed_at', null),
            
        ]);

        return $sprint->load('user');

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
    public function update(Request $request, Sprint $sprint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sprint  $sprint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sprint $sprint)
    {
        //
    }
}
