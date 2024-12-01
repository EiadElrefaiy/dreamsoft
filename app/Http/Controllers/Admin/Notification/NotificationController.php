<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        // FCM Token of the target device
        $fcmToken = 'DEVICE_TOKEN';

        // FCM Server Key
        $serverKey = 'AAAA5rjr2Ak:APA91bEQtypkbkyZ6HwyGlj96xgbydhm58fmtYDQoNWGrsG_fJwlluxg7W43n-UO1c0MU9NWSoWN4UH3da5F1le736EyyDATgjtl3oDE7qglsOVwqaFU2TgVlmDGVUEAjZIn2z-yuFvR';

        // Message payload
        $message = [
            'to' => $fcmToken,
            'notification' => [
                'title' => 'Test Notification',
                'body' => 'This is a test notification sent from Laravel controller',
            ],
            'data' => [
                'extra_information' => 'Some extra information',
            ],
        ];

        // Encode message as JSON
        $jsonData = json_encode($message);

        // Execute cURL request
        $ch = curl_init('https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json',
        ]);

        // Set cURL options to address SSL certificate problem
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, 'E:\cacert-2024-03-11.pem'); // Replace with the actual path to your CA certificates bundle

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Check for cURL errors
        if ($error) {
            return response()->json(['message' => 'cURL Error: ' . $error], 500);
        }
        
        // Handle the response
        if ($statusCode == 200) {
            return response()->json(['message' => 'Notification sent successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to send notification', 'response' => $response], $statusCode);
        }
    }
}
