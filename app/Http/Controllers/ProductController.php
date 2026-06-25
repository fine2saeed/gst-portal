<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private function clientId(): ?int { return Auth::user()->client_id; }

    public function index()
    {
        $products = Product::where('client_id', $this->clientId())
            ->orderBy('name')->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'hs_code'     => 'nullable|string|max:20',
            'price'       => 'required|numeric|min:0',
            'gst_rate'    => 'required|numeric|min:0|max:100',
            'tax_type'    => 'required|in:standard,zero_rated,exempt',
            'unit'        => 'required|string|max:30',
        ]);

        $data['client_id'] = $this->clientId();
        if ($data['tax_type'] !== 'standard') {
            $data['gst_rate'] = 0;
        }
        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        abort_if($product->client_id !== $this->clientId(), 403);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->client_id !== $this->clientId(), 403);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'hs_code'     => 'nullable|string|max:20',
            'price'       => 'required|numeric|min:0',
            'gst_rate'    => 'required|numeric|min:0|max:100',
            'tax_type'    => 'required|in:standard,zero_rated,exempt',
            'unit'        => 'required|string|max:30',
        ]);

        if ($data['tax_type'] !== 'standard') {
            $data['gst_rate'] = 0;
        }
        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        abort_if($product->client_id !== $this->clientId(), 403);
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }
}
