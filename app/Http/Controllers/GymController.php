<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Http\Requests\StoreGymRequest;
use App\Http\Requests\UpdateGymRequest;
use App\Services\Distance;

class GymController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $index = Gym::query()->with('media')->get();
        $newIndex = $index->map(function ($i) {
            $result = Distance::haversineGreatCircleDistance($i->lat, $i->lon, auth()->user()->lat, auth()->user()->lon);
            $i->distance = $result;
            return $i;
        })->sortBy('distance');
        return response()->json(['data' => $newIndex->values()]);
    }
    public function getIndex()
    {
        $index = Gym::query()->with('media', 'section')->get();
        return response()->json(['data' => $index]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGymRequest $request)
    {
        $store = Gym::query()->create([
            'name' => $request->name,
            'location_id' => 1,
            'description_ar' => $request->description_ar,
            'description' => $request->description,
            'type' => $request->type,
            'open' => $request->open,
            'price' => $request->price,
            'close' => $request->close
        ]);
        if ($request->media) {
            $store->addMediaFromRequest('media')->toMediaCollection('gyms');
        }
        if ($request->section) {
            $store->section()->attach($request->section);
        }
        return response()->json($store);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gym $gym)
    {
        $show = $gym->load(['media', 'section', 'section.media']);

        $result = Distance::haversineGreatCircleDistance($show->lat, $show->lon, auth()->user()->lat, auth()->user()->lon);
        $show->distance = $result;
        return response()->json($show);
    }

    public function showGym(Gym $gym)
    {
        $show = $gym->load(['media', 'section', 'section.media']);
        return response()->json(['data' => $show]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGymRequest $request, Gym $gym)
    {
        $gym->update([
            'name' => $request->name,
            'location_id' => 1,
            'description_ar' => $request->description_ar,
            'description' => $request->description,
            'address' => $request->address,
            'type' => $request->type,
            'open' => $request->open,
            'price' => $request->price,
            'close' => $request->close
        ]);
        if ($request->media) {
            $gym->addMediaFromRequest('media')->toMediaCollection('gyms');
        }
        if ($request->section) {
            $gym->section()->sync($request->section);
        }
        return response()->json(['data' => 'update gym successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gym $gym)
    {
        $gym->delete();
        return response()->json('gym been deleted successfully');
    }
}
