<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'organization_name' => 'required|string|max:200|unique_translation:vendors,name',
            'contact_no' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'required|string',
            'tin_no' => 'nullable|alpha_num',
            'organization_type' => ['required', Rule::in(['sole proprietorship', 'partnership', 'corporation', 'limited liability company'])]
        ]);

        $user = auth()->user();

        $vendor = new Vendor();

        $vendor->name = $request->organization_name;
        $vendor->contact_no = $request->contact_no;
        $vendor->address = $request->address;
        $vendor->postal_code = $request->postal_code;
        $vendor->tin_no = $request->tin_no;
        $vendor->organization_type = $request->organization_type;

        $vendor->user()->associate($user);

        $vendor->saveOrFail();

        if(!$user->isAn('owner')){
            $user->attachRole('owner');
        }

        return response()->json(['message' => 'Successfully created your business.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        // dd(auth()->id());
        // dd($vendor);
        if(!auth()->user()->hasRoleAndOwns('owner', $vendor))
        {
            abort(403);
        }

        $this->validate($request, [
            'contact_no' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'required|string',
            'tin_no' => 'nullable|alpha_num',
        ]);

        $vendor->contact_no = $request->contact_no;
        $vendor->address = $request->address;
        $vendor->postal_code = $request->postal_code;
        $vendor->tin_no = $request->tin_no;

        $vendor->save();

        return response()->json(['message' => 'Successfully saved your changes.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        //
    }
}
