<?php

namespace App\Libraries\Famous\Aws\Face;

use Aws\Rekognition\RekognitionClient;

/**
 * Class Comparison
 * @package App\Libraries\Famous\Aws\Face
 */
class Comparison
{
    protected $_instance = null;
    protected $collectionId = 'FacesCollections';


    /**
     * Comparison constructor.
     * @param RekognitionClient $instance
     */
    public function __construct(RekognitionClient $instance)
    {
        $this->_instance =  $instance;

        return $this;
    }

    /**
     * Create a collections of faces.  Use setCollectionName( $name ) to set a custom name
     */
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

    /**
     * @return \Aws\Result|null
     */
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

    /**
     * Remove all the faces from a collection
     * @return \Aws\Result|null
     */
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

    /**
     * Index faces in an image
     * @param $path to the image on the S3 bucket
     * @param $externalImageId Id to indentify the image later
     * @return \Aws\Result|null
     */
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

    /**
     * Return matching faces by sending a path to a image on the S3 bucket
     * @param $path
     * @return \Aws\Result|null
     */
    public function searchFacesByImage( $path ) {

        try {
            $result = $this->_instance->searchFacesByImage([
                'CollectionId'  => $this->collectionId,
                'Image' => [
                    'S3Object' => [
                        'Bucket' => env('S3_BUCKET'),
                        'Name' => $path
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error compare face : ' . $e->getMessage());
            $result = null;
        }

        return $result;
    }

    /**
     * Return matching faces by sending a face ID (returned during the index operation)
     * @param $faceId
     * @return \Aws\Result|null
     */
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

    /**
     * @param $name
     */
    public function setCollectionName( $name ) {
        $this->collectionId = $name;
    }
}