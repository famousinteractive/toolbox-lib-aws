<?php

namespace App\Libraries\Aws\Face;

use Aws\Rekognition\RekognitionClient;

/**
 * Class Recognition
 * @package App\Libraries\Famous\Aws\Face
 */
class Recognition
{
    protected $_instance = null;

    /**
     * Recognition constructor.
     * @param RekognitionClient $instance
     */
    public function __construct(RekognitionClient $instance)
    {
        $this->_instance =  $instance;

        return $this;
    }

    /**
     * Return all faces detail on a picture
     * @param string $photoName Path of the picture on the S3 bucket
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