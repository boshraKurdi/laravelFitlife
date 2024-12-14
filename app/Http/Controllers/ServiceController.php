<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = Service::get();
        return response()->json(['data' => $index]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $store = Service::create([
            'service' => $request->service,
            'price' => $request->price,
            'duration' => $request->duration
        ]);
        return response()->json($store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return response()->json(['data' => $service]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update([
            'service' => $request->service,
            'price' => $request->price,
            'duration' => $request->duration
        ]);
        return response()->json(['data' => 'update service successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['data' => 'delete service successfully!']);
    }
}
