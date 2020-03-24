<?php

namespace Rikudou\Sims4\Paintings\Helper;

final class BinaryDataWriter
{
    public static function createUnsignedLong(int $number): string
    {
        return pack('V', $number);
    }

    public static function createUnsignedShort(int $number): string
    {
        return pack('v', $number);
    }

    public static function createSignedShort(int $number): string
    {
        return pack('s', $number);
    }
}
