<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Http\Requests\StoreStepRequest;
use App\Http\Requests\UpdateStepRequest;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStepRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Step $step)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStepRequest $request, Step $step)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Step $step)
    {
        //
    }
}
