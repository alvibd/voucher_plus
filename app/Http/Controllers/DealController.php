<?php

namespace App\Http\Controllers;

use App\Deal;
use App\deals;
use App\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Vendor $vendor)
    {
        if (!auth()->user()->hasRoleAndOwns('owner', $vendor)) {
            abort(403);
        }

        if($vendor->whereHas('deals', function(Builder $query){
            $query->where('expiration_date', '>=', today()->toDateString());
        })->exists())
        {
            abort(405, 'Cannot create another deal before the current one expires.');
        }

        $this->validate($request, [
            'campaign_name' => 'required|string|min:10',
            'campaign_description' => 'required|string|min:25',
            'terms_and_conditions' => 'required|string|min:25',
            'launching_date' => 'required|date|after_or_equal:'.today()->toDateString(),
            'expiration_date' => 'required|date|after:launching_date',
            'final_redemption_date' => 'required|date|after:expiration_date'
        ]);

        $deal = new Deal();

        $deal->campaign_name = $request->campaign_name;
        $deal->campaign_description = $request->campaign_description;
        $deal->terms_and_conditions = $request->terms_and_conditions;
        $deal->launching_date = $request->launching_date;
        $deal->expiration_date = $request->expiration_date;
        $deal->final_redemption_date = $request->final_redemption_date;

        $deal->vendor()->associate($vendor);

        $deal->saveOrFail();

        return response()->json(['message' => 'Succesfully created your campaign']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\deals  $deals
     * @return \Illuminate\Http\Response
     */
    public function show(deals $deals)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\deals  $deals
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, deals $deals)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\deals  $deals
     * @return \Illuminate\Http\Response
     */
    public function destroy(deals $deals)
    {
        //
    }
}
