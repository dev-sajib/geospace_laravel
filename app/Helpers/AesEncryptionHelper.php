<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class AesEncryptionHelper
{
    private static $key = 'a7f9d3b2c6e1f8a4b5c7d9e2f3a6b8c1'; // 32 chars
    private static $iv = 'e4f1a9c3b7d2e6f8'; // 16 chars

    /**
     * Encrypt a string using AES encryption
     *
     * @param string $plainText
     * @return string
     */
    public static function encrypt(string $plainText): string
    {
        try {
            $encrypted = openssl_encrypt(
                $plainText,
                'AES-256-CBC',
                self::$key,
                OPENSSL_RAW_DATA,
                self::$iv
            );
            
            return base64_encode($encrypted);
        } catch (\Exception $e) {
            throw new \Exception('Encryption failed: ' . $e->getMessage());
        }
    }

    /**
     * Decrypt a string using AES decryption
     *
     * @param string $cipherText
     * @return string
     */
    public static function decrypt(string $cipherText): string
    {
        try {
            $data = base64_decode($cipherText);
            
            $decrypted = openssl_decrypt(
                $data,
                'AES-256-CBC',
                self::$key,
                OPENSSL_RAW_DATA,
                self::$iv
            );
            
            if ($decrypted === false) {
                throw new \Exception('Decryption failed');
            }
            
            return $decrypted;
        } catch (\Exception $e) {
            throw new \Exception('Decryption failed: ' . $e->getMessage());
        }
    }

    /**
     * Laravel's built-in encryption (alternative method)
     *
     * @param string $plainText
     * @return string
     */
    public static function encryptLaravel(string $plainText): string
    {
        return Crypt::encryptString($plainText);
    }

    /**
     * Laravel's built-in decryption (alternative method)
     *
     * @param string $cipherText
     * @return string
     */
    public static function decryptLaravel(string $cipherText): string
    {
        return Crypt::decryptString($cipherText);
    }
}
