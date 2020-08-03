<?php
declare(strict_types=1);

namespace App\Lib\Domain\Serializer;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class Serializer implements ArrayTransformerInterface, SerializerInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ArrayTransformerInterface
     */
    private $arrayTransformer;

    public function __construct(ArrayTransformerInterface $arrayTransformer, SerializerInterface $serializer)
    {
        $this->arrayTransformer = $arrayTransformer;
        $this->serializer = $serializer;
    }

    public function toArray($data, ?SerializationContext $context = null, ?string $type = null): array
    {
        return $this->arrayTransformer->toArray($data, $context ?? $this->getDefaultSerializationContext(), $type);
    }

    public function fromArray(array $data, string $type, ?DeserializationContext $context = null)
    {
        return $this->arrayTransformer->fromArray($data, $type, $context);
    }

    private function getDefaultSerializationContext(): SerializationContext
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $context;
    }

    public function serialize(
        $data,
        string $format,
        ?SerializationContext $context = null,
        ?string $type = null
    ): string {
        return $this->serializer->serialize($data, $format, $context, $type);
    }

    public function deserialize(string $data, string $type, string $format, ?DeserializationContext $context = null)
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
