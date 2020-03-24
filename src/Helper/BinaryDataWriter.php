<?php

namespace Rikudou\Sims4\Paintings\Helper;

final class BinaryDataWriter
{
    /**
     * Converts a number to an unsigned long that can be written to binary data
     *
     * @param int $number
     *
     * @return string
     */
    public static function createUnsignedLong(int $number): string
    {
        return pack('V', $number);
    }

    /**
     * Converts a number to an unsigned short that can be written to binary data
     *
     * @param int $number
     *
     * @return string
     */
    public static function createUnsignedShort(int $number): string
    {
        return pack('v', $number);
    }

    /**
     * Converts a number to an signed short that can be written to binary data
     *
     * @param int $number
     *
     * @return string
     */
    public static function createSignedShort(int $number): string
    {
        return pack('s', $number);
    }
}
