<?php

namespace App\Service\Helper;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class SerializeService
 * @package App\Service\Helpers
 */
class SerializeService
{
    private $serializer;

    public function __construct()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * Serialize object in order to send by api
     * @param $entity
     * @return string
     */
    public function serialize($entity): string
    {
        return $this->serializer->serialize($entity, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        //return $serializer->serialize($jsonObject, 'json');
    }

    /**
     * Deserialize object
     * @param $data
     * @param $entity
     * @param $type
     * @return array|object
     */
    public function deserialize($data, $entity, $type)
    {
        return $this->serializer->deserialize($data, $entity, $type);
    }
}