<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\AesEncryptionHelper;
use Symfony\Component\HttpFoundation\Response;

class RequestDecryptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if request needs decryption (you can add logic here)
        // For now, we'll skip decryption but keep the middleware structure
        
        if ($request->hasHeader('X-Encrypted') && $request->header('X-Encrypted') === 'true') {
            try {
                $encryptedData = $request->getContent();
                
                if (!empty($encryptedData)) {
                    $decryptedData = AesEncryptionHelper::decrypt($encryptedData);
                    $decodedData = json_decode($decryptedData, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // Replace request data with decrypted data
                        $request->merge($decodedData);
                    }
                }
            } catch (\Exception $e) {
                return response()->json([
                    'StatusCode' => 400,
                    'Message' => 'Failed to decrypt request data: ' . $e->getMessage(),
                    'Success' => false
                ], 400);
            }
        }

        return $next($request);
    }
}
