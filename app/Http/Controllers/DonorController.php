<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return'index';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return'create';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return'store';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $donor)
    {
        return'show' . $donor;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donor $donor)
    {
        return'edit';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donor $donor)
    {
//        return'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donor $donor)
    {
//        return'destroy';
    }
}
