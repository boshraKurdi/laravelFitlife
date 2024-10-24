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
        $index = Gym::query()->with('location', 'media')->get();
        $newIndex = $index->map(function ($i) {
            $result = Distance::haversineGreatCircleDistance($i->location->lat, $i->location->lon, auth()->user()->lat, auth()->user()->lon);
            $i->distance = $result;
            return $i;
        })->sortBy('distance');
        return response()->json($newIndex->values());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGymRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Gym $gym)
    {
        $show = $gym->load(['media', 'location']);

        $result = Distance::haversineGreatCircleDistance($show->location->lat, $show->location->lon, auth()->user()->lat, auth()->user()->lon);
        $show->distance = $result;
        return response()->json($show);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGymRequest $request, Gym $gym)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gym $gym)
    {
        //
    }
}
