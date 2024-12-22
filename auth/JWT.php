<?php

    // Function to encode data in base 64 URL

    function base64UrlEncode($data) {

        $base64 = base64_encode($data);
        
        $base64Url = rtrim(strtr($base64, '+/', '-_'), '=');

        return $base64Url;
    }

    // Function to decode data

    function base64UrlDecode($data) {
        $data = strtr($data, '-_', '+/');

        $padding = strlen($data) % 4;
        if ($padding) {
            $data .= str_repeat('=', 4 - $padding);
        }
        return base64_decode($data);
    }

    // Check if token has expired

    function isTokenExpired($payload) {

        if (time() > $payload -> exp) {
            return true;
        }
        return false;
    }

    // Check if payload has the necessary claims

    function isPayloadValid($payload) {
        if (!isset($payload -> exp, $payload -> email, $payload -> iat, $payload -> id, $payload -> role, $payload -> first_name, $payload -> last_name)) {
            return false;
        }
        return true;
    }

    // Create a Json Web Token (JWT)
    
    function createJWT($id, $email, $role, $first_name, $last_name, $image) {
        
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
            'role' => $role,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'image_url' => $image
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

    function validateJWT($jwt_cookie) {

        $jwt = json_decode($jwt_cookie) -> jwt;
        
        $jwt_parts = explode('.', $jwt);
        
        if (count($jwt_parts) !== 3) {
            return false;
        }

        // Make a new signature base on the retrieved header and payload
        
        $secretKey = "anass@BT";

        $headerAndPayload = $jwt_parts[0] . '.' . $jwt_parts[1];
        $signatureProvided = $jwt_parts[2];
        
        $signatureExpected = hash_hmac('sha256', $headerAndPayload, $secretKey, true);
        $signatureExpected = base64UrlEncode($signatureExpected);
        
        // Find the payload

        $payload = json_decode(base64UrlDecode($jwt_parts[1]));

        // Verify Token validity

        if ($signatureProvided !== $signatureExpected || !isPayloadValid($payload) || isTokenExpired($payload)) {
            return false;
        } else {
            return $payload;
        }
    }

?>