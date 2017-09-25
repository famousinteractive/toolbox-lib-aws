<?php

namespace App\Libraries\Famous\Aws;

use App\Libraries\Famous\Aws\Face\Comparison;
use App\Libraries\Famous\Aws\Face\Recognition;
use App\Libraries\Famous\Aws\Object\Detection;
use Aws\Rekognition\RekognitionClient;

class Aws
{
    protected $_instance = null;

    public function __construct()
    {
        $this->_instance =  new RekognitionClient([
            'version'     => 'latest',
            'region'      => env('AWS_REGION'),
            'credentials' => [
                'key'    => env('AWS_KEY'),
                'secret' => env('AWS_SECRET')
            ]
        ]);

        return $this;
    }


    public function getFaceDetection() {
        return new Recognition($this->_instance);
    }


    public function getFaceComparison() {
        return new Comparison($this->_instance);
    }

    public function getObjectDetection() {
        return new Detection($this->_instance);
    }
}