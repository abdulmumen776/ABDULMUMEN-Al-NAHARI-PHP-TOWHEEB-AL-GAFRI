<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class EncryptionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Decrypt request data if it contains encrypted fields
        $this->decryptRequestData($request);

        $response = $next($request);

        // Encrypt response data if needed
        $this->encryptResponseData($response);

        return $response;
    }

    /**
     * Decrypt encrypted fields in the request.
     */
    private function decryptRequestData(Request $request): void
    {
        try {
            // Check if request contains encrypted data
            if ($request->has('encrypted_data')) {
                $encryptedData = $request->input('encrypted_data');
                $decryptedData = Crypt::decrypt($encryptedData);
                
                // Merge decrypted data into request
                $request->merge($decryptedData);
                $request->remove('encrypted_data');
            }

            // Decrypt specific sensitive fields
            $sensitiveFields = ['contact_phone', 'secret_key', 'api_key', 'password'];
            
            foreach ($sensitiveFields as $field) {
                if ($request->has($field)) {
                    $encryptedValue = $request->input($field);
                    
                    if ($this->isEncrypted($encryptedValue)) {
                        $decryptedValue = Crypt::decryptString($encryptedValue);
                        $request->merge([$field => $decryptedValue]);
                    }
                }
            }

            // Decrypt nested encrypted data
            $this->decryptNestedData($request->all());

        } catch (\Exception $e) {
            Log::error('Failed to decrypt request data', [
                'error' => $e->getMessage(),
                'request_path' => $request->path(),
                'ip' => $request->ip(),
            ]);

            // Continue with original request if decryption fails
        }
    }

    /**
     * Encrypt sensitive data in the response.
     */
    private function encryptResponseData($response): void
    {
        if (!method_exists($response, 'getData')) {
            return;
        }

        try {
            $data = $response->getData(true);

            if (is_array($data)) {
                $this->encryptSensitiveFields($data);
                $response->setData($data);
            }

        } catch (\Exception $e) {
            Log::error('Failed to encrypt response data', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Encrypt sensitive fields in an array.
     */
    private function encryptSensitiveFields(array &$data): void
    {
        $sensitiveFields = [
            'contact_phone',
            'secret_key',
            'api_key',
            'password',
            'token',
            'private_key',
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $data[$field] = $this->encryptValue($data[$field]);
            }
        }

        // Encrypt nested data
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->encryptSensitiveFields($data[$key]);
            }
        }
    }

    /**
     * Decrypt nested data structures.
     */
    private function decryptNestedData(array &$data): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->decryptNestedData($data[$key]);
            } elseif (is_string($value) && $this->isEncrypted($value)) {
                try {
                    $data[$key] = Crypt::decryptString($value);
                } catch (\Exception $e) {
                    Log::warning('Failed to decrypt nested field', [
                        'field' => $key,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    /**
     * Check if a value is encrypted.
     */
    private function isEncrypted(string $value): bool
    {
        // Laravel encrypted strings typically start with a specific pattern
        return Str::startsWith($value, 'eyJ') && strlen($value) > 50;
    }

    /**
     * Encrypt a value.
     */
    private function encryptValue(string $value): string
    {
        try {
            return Crypt::encryptString($value);
        } catch (\Exception $e) {
            Log::error('Failed to encrypt value', [
                'error' => $e->getMessage(),
            ]);
            
            return $value; // Return original value if encryption fails
        }
    }

    /**
     * Validate that required fields are not encrypted when they shouldn't be.
     */
    private function validateDecryption(array $data): bool
    {
        $requiredFields = ['id', 'name', 'email', 'status'];
        
        foreach ($requiredFields as $field) {
            if (isset($data[$field]) && $this->isEncrypted($data[$field])) {
                return false;
            }
        }

        return true;
    }
}
