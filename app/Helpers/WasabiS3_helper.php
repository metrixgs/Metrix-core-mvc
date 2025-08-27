<?php

use Aws\S3\S3Client;
use Aws\CommandPool;
use Aws\Exception\AwsException;

if (!function_exists('get_wasabi_s3_client')) {
    /**
     * Inicializa y devuelve una instancia del cliente S3 para Wasabi.
     *
     * @return S3Client
     */
    function get_wasabi_s3_client(): S3Client
    {
        // Credenciales y configuración de Wasabi S3
        $config = [
            'version' => 'latest',
            'region' => 'us-central-1', // La región proporcionada por el usuario
            'endpoint' => 'https://s3.us-central-1.wasabisys.com', // El endpoint proporcionado por el usuario
            'credentials' => [
                'key' => 'E6HDB33BYOIRC46OZPJ9', // La API Key proporcionada por el usuario
                'secret' => 'uy4i8rLle2RiSIzQzwNWn0iZvpSa5a6ZgooiZooB', // La Secret Key proporcionada por el usuario
            ],
            'use_path_style_endpoint' => true, // Necesario para Wasabi
        ];

        return new S3Client($config);
    }
}

if (!function_exists('get_signed_wasabi_s3_url')) {
    /**
     * Genera una URL pre-firmada para un objeto en Wasabi S3.
     *
     * @param string $bucketName El nombre del bucket de Wasabi S3.
     * @param string $objectKey La clave del objeto (ruta del archivo) en el bucket.
     * @param string $expiration La duración de la validez de la URL (ej. '+10 minutes', '+1 hour').
     * @return string|null La URL pre-firmada o null en caso de error.
     */
    function get_signed_wasabi_s3_url(string $bucketName, string $objectKey, string $expiration = '+10 minutes'): ?string
    {
        try {
            $s3Client = get_wasabi_s3_client();

            $command = $s3Client->getCommand('GetObject', [
                'Bucket' => $bucketName,
                'Key' => $objectKey,
            ]);

            $request = $s3Client->createPresignedRequest($command, $expiration);

            return (string) $request->getUri();
        } catch (AwsException $e) {
            // Log the error or handle it as appropriate for your application
            error_log("Error al generar URL pre-firmada de Wasabi S3: " . $e->getMessage());
            return null;
        }
    }
}