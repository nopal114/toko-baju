<?php 

namespace App\Helpers;

class AESCBC
{
    public static function encrypt($plaintext, $key, $iv)
    {
        return base64_encode(openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }

    public static function decrypt($ciphertext, $key, $iv)
    {
        return openssl_decrypt(base64_decode($ciphertext), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}
