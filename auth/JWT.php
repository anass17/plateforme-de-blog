<?php

    // Function to encode data in base 64 URL

    function base64UrlEncode($data) {

        $base64 = base64_encode($data);
        
        $base64Url = rtrim(strtr($base64, '+/', '-_'), '=');

        return $base64Url;
    }

    // Create a Json Web Token (JWT)
    
    function createJWT($email) {
        
        // Create the header

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        // Create the payload

        $payload = [
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24),
            'email' => $email
        ];


        // Encode the header and payload in base 64 URL

        $encodedHeader = base64UrlEncode(json_encode($header));
        $encodedPayload = base64UrlEncode(json_encode($payload));

        // Create the Signature

        $secretKey = 'anass@BT';
        $dataToSign = $encodedHeader . '.' . $encodedPayload;
        $signature = hash_hmac('sha256', $dataToSign, $secretKey, true);

        // Encode the signature

        $encodedSignature = base64UrlEncode($signature);

        // The final JWT

        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }

?>