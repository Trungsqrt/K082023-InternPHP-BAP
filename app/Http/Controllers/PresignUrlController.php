<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\PresignUrlRequest;
use Aws\S3\Exception\S3Exception;

class PresignUrlController extends Controller
{
    /**
     * Generates a presigned URL for uploading a file to an AWS S3 bucket and returns the presigned URL along with the public URL of the uploaded file.
     *
     * @param PresignUrlRequest $request The request object containing the validated request data.
     * @throws S3Exception If there is an error interacting with the AWS S3 service.
     * @return Response The response object containing the presigned URL and public URL of the uploaded file.
     */
    public function getPresignUrl(PresignUrlRequest $request)
    {
        $validatedRequest = $request->validated();
        $fileName = $validatedRequest['fileName'];
        $folderPath = $validatedRequest['folderPath'];
        $contentType = $validatedRequest['contentType'];

        try {
            $s3Client = new S3Client([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            $command = $s3Client->getCommand('PutObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $folderPath . "/" . $fileName,
                'ContentType' => $contentType,
            ]);

            $presignedUrl = (string)$s3Client->createPresignedRequest($command, '+15 minutes')->getUri();
            $publicUrl = $s3Client->getObjectUrl(env('AWS_BUCKET'), $folderPath . "/" . $fileName);

            return $this->respondSuccess([
                'presigned_url' => $presignedUrl,
                'public_url' => $publicUrl
            ]);
        } catch (S3Exception $e) {
            return $this->respondError($e->getMessage());
        }
    }
}
