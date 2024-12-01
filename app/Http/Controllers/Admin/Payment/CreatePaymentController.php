<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreatePaymentController extends Controller
{
    public function create(Request $request)
    {
        // Get data from the request
        $amountValue = $request->input('amount_value');
        $currency = $request->input('currency');
        $description = $request->input('description');
        $redirectUrl = $request->input('redirect_url');
        $orderId = $request->input('order_id');

        // Prepare the data for the payment request
        $data = [
            'amount' => [
                'value' => $amountValue,
                'currency' => $currency
            ],
            'description' => $description,
            'redirectUrl' => $redirectUrl,
            'metadata' => [
                'order_id' => $orderId
            ]
        ];

        // Convert the data to JSON format
        $payload = json_encode($data);

        // Set the headers for the request
        $headers = [
            'Content-Type: application/json',
            // 'Accept: application/json',
            'Authorization: Bearer sk_test_283d7f01cfbc807dc3e30d8d4763be909ef3' // Replace with your actual access token
        ];
                // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, 'https://api.dibsy.one/v2/payments');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Set cURL options to address SSL certificate problem
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, 'E:\cacert-2024-03-11.pem'); // Replace with the actual path to your CA certificates bundle

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            // Handle the error
            // For example, return an error response
            return response()->json(['error' => $error_message], 500);
        }

        // Close cURL session
        curl_close($ch);

        // Process the response
        $decoded_response = json_decode($response, true);

        // Return the response
        return response()->json($decoded_response);
    }
}
