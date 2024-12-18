<?php

    // Function to encode data in base 64 URL

    function base64UrlEncode($data) {

        $base64 = base64_encode($data);
        
        $base64Url = rtrim(strtr($base64, '+/', '-_'), '=');

        return $base64Url;
    }

    // Create a Json Web Token (JWT)
    
    function createJWT($id, $email, $role) {
        
        // Create the header

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        // Create the payload

        $payload = [
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24),
            'id' => $id,
            'email' => $email,
            'role' => $role
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

        $jwt = $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;

        return json_encode(['jwt' => $jwt]);
    }

    // Validate the JWT

    function validateJWT($jwt) {
        
        $jwt_parts = explode('.', $jwt);

        if (count($jwt_parts) !== 3) {
            return false;
        }

        $headerAndPayload = $jwt_parts[0] . '.' . $jwt_parts[1];
        $signatureProvided = $jwt_parts[2];

        echo '<pre>';
        print_r($headerAndPayload);
        print_r($signatureProvided);
        echo '</pre>';

        $secretKey = "anass@BT";

        $signatureExpected = hash_hmac('sha256', $headerAndPayload, $secretKey, true);
        $signatureExpected = base64UrlEncode($signatureExpected);

        if ($signatureProvided === $signatureExpected) {
            return true;
        } else {
            return false;
        }
    }

?>