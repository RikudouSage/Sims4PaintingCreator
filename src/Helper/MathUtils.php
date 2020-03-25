<?php

namespace Rikudou\Sims4\Paintings\Helper;

final class MathUtils
{
    public static function FNV1(string $content): string
    {
        $content = strtolower($content);

        $prime = gmp_init('0x00000100000001B3');
        $offset = gmp_init('0xCBF29CE484222325');
        $size = gmp_pow(2, 64);

        $hash = $offset;
        for ($i = 0; $i < strlen($content); ++$i) {
            $hash = $hash * $prime % $size;
            $hash ^= ord($content[$i]);
        }

        return (string) $hash;
    }

    public static function decToHex(string $decimal): string
    {
        $hex = '';
        do {
            $last = gmp_mod($decimal, 16);
            $hex = dechex($last) . $hex;
            $decimal = gmp_div(gmp_sub($decimal, $last), 16);
        } while ($decimal > 0);

        return $hex;
    }

    public static function hexToDec(string $hex): string
    {
        return (string) gmp_init($hex, 16);
    }
}
