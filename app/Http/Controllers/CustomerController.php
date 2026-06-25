<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    private function clientId(): ?int { return Auth::user()->client_id; }

    public function index()
    {
        $customers = Customer::where('client_id', $this->clientId())
            ->orderBy('name')->paginate(20);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $provinces = \App\Models\Client::provinces();
        return view('customers.create', compact('provinces'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'ntn'      => 'nullable|string|max:50',
            'cnic'     => 'nullable|string|max:20',
            'strn'     => 'nullable|string|max:50',
            'address'  => 'nullable|string',
            'city'     => 'nullable|string|max:100',
            'province' => 'nullable|string|max:10',
            'phone'    => 'nullable|string|max:30',
            'email'    => 'nullable|email|max:255',
        ]);

        $data['client_id'] = $this->clientId();
        Customer::create($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer added successfully.');
    }

    public function edit(Customer $customer)
    {
        abort_if($customer->client_id !== $this->clientId(), 403);
        $provinces = \App\Models\Client::provinces();
        return view('customers.edit', compact('customer', 'provinces'));
    }

    public function update(Request $request, Customer $customer)
    {
        abort_if($customer->client_id !== $this->clientId(), 403);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'ntn'      => 'nullable|string|max:50',
            'cnic'     => 'nullable|string|max:20',
            'strn'     => 'nullable|string|max:50',
            'address'  => 'nullable|string',
            'city'     => 'nullable|string|max:100',
            'province' => 'nullable|string|max:10',
            'phone'    => 'nullable|string|max:30',
            'email'    => 'nullable|email|max:255',
        ]);

        $customer->update($data);
        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        abort_if($customer->client_id !== $this->clientId(), 403);
        $customer->delete();
        return back()->with('success', 'Customer deleted.');
    }
}
