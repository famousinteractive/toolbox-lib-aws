<?php
namespace App\Libraries\Aws;

use App\Libraries\Aws\Face\Comparison;
use App\Libraries\Aws\Face\Recognition;
use App\Libraries\Aws\Object\Detection;
use Aws\Rekognition\RekognitionClient;

/**
 * Class Aws
 * @package App\Libraries\Famous\Aws
 */
class Aws
{
    protected $_instance = null;

    /**
     * Aws constructor.
     */
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

    /**
     * @return Recognition
     */
    public function getFaceDetection() {
        return new Recognition($this->_instance);
    }

    /**
     * @return Comparison
     */
    public function getFaceComparison() {
        return new Comparison($this->_instance);
    }

    /**
     * @return Detection
     */
    public function getObjectDetection() {
        return new Detection($this->_instance);
    }
}