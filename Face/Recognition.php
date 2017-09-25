<?php

namespace App\Libraries\Famous\Aws\Face;

use Aws\Rekognition\RekognitionClient;

class Recognition
{
    protected $_instance = null;

    public function __construct(RekognitionClient $instance)
    {
        $this->_instance =  $instance;

        return $this;
    }

    /**
     * @param string $photoName
     * @return \Aws\Result|null
     */
    public function detectFaces( $photoName) {

        try {
            $faceDetect = $this->_instance->detectFaces([
                'Attributes' => ["ALL"],
                'Image' => [
                    'S3Object' => [
                        'Bucket' => env('S3_BUCKET'),
                        'Name' => $photoName
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error face reko : ' . $e->getMessage());
            $faceDetect = null;
        }

        return $faceDetect;
    }

}