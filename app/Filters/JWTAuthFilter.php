<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');

        if (!$authHeader) {
            $response = Services::response();
            return $response->setJSON([
                'status' => 401,
                'error' => 401,
                'messages' => [
                    'error' => 'Authorization header is missing'
                ]
            ])->setStatusCode(401);
        }

        $arr = explode(" ", $authHeader);
        $token = null;

        if (count($arr) === 2 && strcasecmp($arr[0], 'Bearer') === 0) {
            $token = $arr[1];
        }

        if (!$token) {
            $response = Services::response();
            return $response->setJSON([
                'status' => 401,
                'error' => 401,
                'messages' => [
                    'error' => 'Bearer token is missing'
                ]
            ])->setStatusCode(401);
        }

        try {
            $key = getenv('JWT_SECRET') ?: 'default_jwt_secret_key_123456';
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            
            // store user details in the request to access in controller
            $request->user = $decoded;
        } catch (Exception $e) {
            $response = Services::response();
            return $response->setJSON([
                'status' => 401,
                'error' => 401,
                'messages' => [
                    'error' => 'Invalid or expired token: ' . $e->getMessage()
                ]
            ])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // do nothing
    }
}
