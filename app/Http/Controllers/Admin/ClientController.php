<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount(['invoices', 'customers'])->latest()->paginate(20);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        $provinces = Client::provinces();
        return view('admin.clients.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name'    => 'required|string|max:255',
            'ntn'              => 'nullable|string|max:50',
            'strn'             => 'nullable|string|max:50',
            'province'         => 'required|in:FBR,SRB,PRA,KPRA,BRA',
            'default_gst_rate' => 'required|numeric|min:0|max:100',
            'address'          => 'nullable|string',
            'city'             => 'nullable|string|max:100',
            'phone'            => 'nullable|string|max:30',
            'invoice_prefix'   => 'required|string|max:10|alpha_num',
            // Admin user for this client
            'admin_name'       => 'required|string|max:255',
            'admin_email'      => 'required|email|unique:users,email',
            'admin_password'   => 'required|min:8|confirmed',
        ]);

        $client = Client::create([
            'business_name'    => $data['business_name'],
            'ntn'              => $data['ntn'],
            'strn'             => $data['strn'],
            'province'         => $data['province'],
            'default_gst_rate' => $data['default_gst_rate'],
            'address'          => $data['address'],
            'city'             => $data['city'],
            'phone'            => $data['phone'],
            'invoice_prefix'   => strtoupper($data['invoice_prefix']),
        ]);

        User::create([
            'name'      => $data['admin_name'],
            'email'     => $data['admin_email'],
            'password'  => Hash::make($data['admin_password']),
            'role'      => 'client_admin',
            'client_id' => $client->id,
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', "Client '{$client->business_name}' created with admin login.");
    }

    public function show(Client $client)
    {
        $client->load('users', 'invoices');
        $stats = [
            'invoices' => $client->invoices()->count(),
            'total_gst'=> $client->invoices()->where('status', 'final')->sum('gst_amount'),
            'total_rev'=> $client->invoices()->where('status', 'final')->sum('total'),
        ];
        return view('admin.clients.show', compact('client', 'stats'));
    }

    public function edit(Client $client)
    {
        $provinces = Client::provinces();
        return view('admin.clients.edit', compact('client', 'provinces'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'business_name'    => 'required|string|max:255',
            'ntn'              => 'nullable|string|max:50',
            'strn'             => 'nullable|string|max:50',
            'province'         => 'required|in:FBR,SRB,PRA,KPRA,BRA',
            'default_gst_rate' => 'required|numeric|min:0|max:100',
            'address'          => 'nullable|string',
            'city'             => 'nullable|string|max:100',
            'phone'            => 'nullable|string|max:30',
            'invoice_prefix'   => 'nullable|string|max:10',
            'is_active'        => 'nullable|in:0,1',
        ]);

        if (isset($data['invoice_prefix'])) {
            $data['invoice_prefix'] = strtoupper($data['invoice_prefix']);
        }
        $data['is_active'] = ($request->input('is_active', '1') === '1');

        $client->update($data);
        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client removed.');
    }
}
