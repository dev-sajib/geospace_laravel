<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Helpers\AesEncryptionHelper;
use Symfony\Component\HttpFoundation\Response;

class ResponseEncryptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if response needs encryption
        if ($request->hasHeader('X-Encrypt-Response') && $request->header('X-Encrypt-Response') === 'true') {
            try {
                if ($response instanceof JsonResponse) {
                    $originalData = $response->getData(true);
                    $jsonData = json_encode($originalData);
                    
                    $encryptedData = AesEncryptionHelper::encrypt($jsonData);
                    
                    $response->setData([
                        'encrypted' => true,
                        'data' => $encryptedData
                    ]);
                    
                    $response->header('X-Encrypted', 'true');
                }
            } catch (\Exception $e) {
                return response()->json([
                    'StatusCode' => 500,
                    'Message' => 'Failed to encrypt response data: ' . $e->getMessage(),
                    'Success' => false
                ], 500);
            }
        }

        return $response;
    }
}
