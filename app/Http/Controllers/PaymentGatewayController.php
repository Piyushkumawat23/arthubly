<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::orderBy('sort_order')->get();
        return view('admin.payment-gateways.index', compact('gateways'));
    }

    public function edit($id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        return view('admin.payment-gateways.edit', compact('gateway'));
    }

    public function update(Request $request, $id)
    {
        $gateway = PaymentGateway::findOrFail($id);

        // Sirf is gateway ke defined fields hi save karo
        $fields = config('payment_gateways.' . $gateway->slug . '.fields', []);
        $credentials = [];
        foreach (array_keys($fields) as $field) {
            $credentials[$field] = $request->input('credentials.' . $field);
        }

        $gateway->update([
            'mode'         => $request->input('mode', 'test'),
            'instructions' => $request->input('instructions'),
            'credentials'  => $credentials,
        ]);

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', $gateway->name . ' settings updated successfully!');
    }

    // AJAX toggle (page reload nahi)
    public function toggleStatus($id)
    {
        $gateway = PaymentGateway::findOrFail($id);
        $gateway->is_active = ! $gateway->is_active;
        $gateway->save();

        return response()->json([
            'status'    => true,
            'is_active' => $gateway->is_active,
            'message'   => $gateway->name . ' is now ' . ($gateway->is_active ? 'Active' : 'Inactive'),
        ]);
    }
}