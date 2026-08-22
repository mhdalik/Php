<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
    use ResponseTrait;

    public function login()
    {
        $json = $this->request->getJSON(true);

        if (empty($json)) {
            return $this->failValidationErrors(['error' => 'No JSON data provided']);
        }

        $rules = [
            'email'    => 'required',
            'password' => 'required'
        ];

        if (!$this->validateData($json, $rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $email = $json['email'];
        $password = $json['password'];

        // check hardcoded credentials (admin/admin123 or admin@example.com/admin123)
        if (($email === 'admin' || $email === 'admin@example.com') && $password === 'admin123') {
            $key = getenv('JWT_SECRET') ?: 'default_jwt_secret_key_123456';
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600; // 1 hour validity
            
            $payload = [
                'iss' => base_url(),
                'aud' => base_url(),
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'uid' => 1,
                'email' => $email
            ];

            $token = JWT::encode($payload, $key, 'HS256');

            return $this->respond([
                'status' => 'success',
                'token' => $token,
                'expires_at' => date('Y-m-d H:i:s', $expirationTime)
            ], 200);
        }

        return $this->failUnauthorized('Invalid email or password');
    }
}
