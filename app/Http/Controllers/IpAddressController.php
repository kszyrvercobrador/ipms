<?php

namespace App\Http\Controllers;

use App\Models\IpAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\IpAddressResource;

class IpAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return IpAddressResource::collection(
            $request->user()->ipAddresses()->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'label' => 'required',
        ]);

        $ipAddresses = $request->user()->ipAddresses()->create([
            'ip_address' => $request->input('ip_address'),
            'label' => $request->input('label'),
        ]);

        return IpAddressResource::make($ipAddresses);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IpAddress  $ipAddress
     * @return \Illuminate\Http\Response
     */
    public function show(IpAddress $ipAddress)
    {
        return IpAddressResource::make($ipAddress);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IpAddress  $ipAddress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IpAddress $ipAddress)
    {
        Gate::authorize('update-ipaddress', $ipAddress);
        $request->validate(['label' => 'required']);

        $ipAddress->label = $request->input('label');
        $ipAddress->save();

        return IpAddressResource::make($ipAddress);
    }
}
