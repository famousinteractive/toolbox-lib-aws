<?php
namespace App\Libraries\Aws\Object;

use Aws\Rekognition\RekognitionClient;
use Session;

/**
 * Class Detection
 * @package App\Libraries\Famous\Aws\Object
 */
class Detection
{
    protected $_instance = null;

    /**
     * Detection constructor.
     * @param RekognitionClient $instance
     */
    public function __construct(RekognitionClient $instance)
    {
        $this->_instance =  $instance;

        return $this;
    }

    /**
     * Detect all the label in a picture
     * @param $photo : path to the photo on the S3 Bucket
     * @return \Aws\Result|null
     */
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