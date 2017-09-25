<?php

namespace App\Libraries\Famous\Aws\Object;

use Aws\Rekognition\RekognitionClient;
use Session;

class Detection
{
    protected $_instance = null;

    public function __construct(RekognitionClient $instance)
    {
        $this->_instance =  $instance;

        return $this;
    }

    public function detect( $photo) {

        try {
            $result = $this->_instance->detectLabels([
                'Image'                 => [
                                                'S3Object'  => [
                                                    'Bucket'    => env('S3_BUCKET'),
                                                    'Name'      => $photo
                                                ],
                                            ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error detect object : ' . $e->getMessage());
            $result = null;
        }

        return $result;
    }
}