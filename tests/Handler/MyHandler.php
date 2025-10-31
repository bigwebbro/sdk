<?php

declare(strict_types=1);

namespace Tests\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;

class MyHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'DateTimeImmutable',
                'method' => 'serializeDateTimeToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'DateTimeImmutable',
                'method' => 'deserializeDateTimeToJson',
            ],
        ];
    }

    public function serializeDateTimeToJson(JsonSerializationVisitor $visitor, \DateTimeImmutable $date, array $type, Context $context)
    {
        return $date->format('Y-m-d H:i:s.uP');
    }

    public function deserializeDateTimeToJson(JsonDeserializationVisitor $visitor, $dateAsString, array $type, Context $context)
    {
        return \DateTimeImmutable::createFromFormat('Y-m-d H:i:s.uP', $dateAsString);
    }
}
