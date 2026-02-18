<?php
class SMSService {
    private $api_url = "https://smsgateway.me/api/v3/messages/send";
    private $device_id = "123456"; // You'll need to register at SMS Gateway to get a device ID
    
    public function sendSMS($phone, $message) {
        // Format phone number (remove any non-digit characters)
        $phone = preg_replace('/\D/', '', $phone);
        
        // Prepare the data
        $data = [
            "phone_number" => $phone,
            "message" => $message,
            "device_id" => $this->device_id
        ];
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Execute the request
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
}
?>