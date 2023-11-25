<?php

namespace Jdd;

class Checksum
{
    private const MOD_ADLER = 0xfff1;

    public static function get_email_checksum($data): int
    {
        $checksum = self::email_checksum(1, $data);
        $checksum = (($checksum ^ -1418120497) + pow(2, 32)) & 0xFFFFFFFF;
        if ($checksum > pow(2, 31) - 1) {
            $checksum -= pow(2, 32);
        }
        return $checksum;
    }

    public static function email_checksum($key, $data): int
    {
        if ($data === null) {
            return 0;
        }
        $bytesData = mb_convert_encoding($data, 'gbk', 'utf8');
        $length = self::get_length($data);
        $a = $key & 0xFFFF;
        $b = ($key >> 16) & 0xFFFF;
        for ($i = 0; $i < $length; $i++) {
            $valueAtIndex = ord($bytesData[$i]);
            $a = ($a + $valueAtIndex) % self::MOD_ADLER;
            $b = ($b + $a) % self::MOD_ADLER;
            $a &= 0xFFFFFFFF;
            $b &= 0xFFFFFFFF;
        }
        return ((($b << 16) | $a) & 0xFFFFFFFF) + pow(2, 32) % pow(2, 32) & 0xFFFFFFFF;
    }

    public static function get_length($int): int
    {
        $length = 0;
        $totalChars = mb_strlen($int, 'utf8');
        for ($i = 0; $i < $totalChars; $i++) {
            $char = mb_substr($int, $i, 1, 'utf8');
            $length += mb_check_encoding($char, 'ascii') ? 1 : 2;
        }
        return $length;
    }
}