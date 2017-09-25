<?php

namespace App\Libraries\Famous\Aws\Face;

use Aws\Rekognition\RekognitionClient;

class Comparison
{
    protected $_instance = null;
    protected $collectionId = 'MemoriesFacesCollections';


    public function __construct(RekognitionClient $instance)
    {
        $this->_instance =  $instance;

        return $this;
    }

    protected function createCollection() {
        try {
            $result = $this->_instance->createCollection([
                'CollectionId'  => $this->collectionId
            ]);
        } catch (\Exception $e) {
            \Log::error('Error create collection : ' . $e->getMessage());
            $result = null;
        }

        return $result;
    }

    public function createCollectionIfNotExists() {

        try {
            $result = $this->_instance->listCollections([
            ]);
        } catch (\Exception $e) {
            \Log::error('Error create collection : ' . $e->getMessage());
            $result = null;
        }

        $collections = $result->get('CollectionIds');

        if(!in_array($this->collectionId,$collections)) {
            $this->createCollection();
        }
    }

    public function clearCollection() {
        try {
            $result = $this->_instance->deleteCollection([
                'CollectionId'  => $this->collectionId
            ]);
            $this->createCollection();
        } catch (\Exception $e) {
            \Log::error('Error delete collection : ' . $e->getMessage());
            $result = null;
        }

        return $result;
    }

    public function indexFaces( $path, $externalImageId) {

        try {
            $result = $this->_instance->indexFaces([
                'CollectionId'          => $this->collectionId,
                'DetectionAttributes'   => ["ALL"],
                'ExternalImageId'       => $externalImageId,
                'Image'                 => [
                                                'S3Object'  => [
                                                'Bucket'    => env('S3_BUCKET'),
                                                'Name'      => $path
                                            ],
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error create collection : ' . $e->getMessage());
            $result = null;
        }

        return $result;
    }

    public function searchFacesByImage( $photo) {

        try {
            $result = $this->_instance->searchFacesByImage([
                'CollectionId'  => $this->collectionId,
                'Image' => [
                    'S3Object' => [
                        'Bucket' => env('S3_BUCKET'),
                        'Name' => $this->photo
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error compare face : ' . $e->getMessage());
            $result = null;
        }

        return $result;
    }

    public function searchFaces( $faceId) {
        try {
            $result = $this->_instance->searchFaces([
                'CollectionId'          => $this->collectionId,
                'FaceId'                => $faceId,
                'FaceMatchThreshold'    => 50.

            ]);
        } catch (\Exception $e) {
            \Log::error('Error compare face : ' . $e->getMessage());
            $result = null;
        }

        return $result;
    }
}