<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessProfileController extends Controller
{
    public function edit()
    {
        $client    = Auth::user()->client;
        $provinces = \App\Models\Client::provinces();
        return view('profile.business', compact('client', 'provinces'));
    }

    public function update(Request $request)
    {
        $client = Auth::user()->client;

        $data = $request->validate([
            'business_name'    => 'required|string|max:255',
            'ntn'              => 'required|string|max:50',
            'strn'             => 'nullable|string|max:50',
            'province'         => 'required|string|in:FBR,SRB,PRA,KPRA,BRA',
            'default_gst_rate' => 'required|numeric|min:0|max:100',
            'address'          => 'required|string',
            'city'             => 'required|string|max:100',
            'phone'            => 'nullable|string|max:30',
            'email'            => 'nullable|email|max:255',
            'invoice_prefix'   => 'required|string|max:10|alpha_num',
            'logo'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($client->logo) {
                Storage::delete($client->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $data['profile_complete'] = true;
        $client->update($data);

        return redirect()->route('dashboard')->with('success', 'Business profile updated successfully.');
    }
}
