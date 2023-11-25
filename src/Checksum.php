<?php

namespace Jdd;


class Checksum
{

    public static function get_data_checksum($data): int
    {
        $a = 1 & 65535;
        $b = (1 >> 16) & 65535;
        $data = iconv('utf-8', 'gb2312//IGNORE', $data);
        $arr = str_split(bin2hex($data), 2);
        foreach ($arr as $i) {
            $a = ($a + hexdec($i)) % 65521;
            $b = ($b + $a) % 65521;
        }
        $c = ($b << 16) | $a;
        $int = $c ^ hexdec('AB7932CF');
        return self::int32($int);
    }

    public static function int32($num): float|int
    {
        $num = $num & 0xffffffff;
        $i = $num >> 31;
        if ($i == 1) {
            $num = $num - 1;
            $num = ~$num;
            $num = $num & 0xffffffff;
            return $num * -1;
        } else {
            return $num;
        }
    }

    public static function get_account_checksum($account, $pwd, $jurisdiction, $gold, $silver): array
    {
        $pwd = strtoupper(md5($pwd));
        $pwd = strtoupper(md5($account . $pwd . '20070201'));
        $accountStr = $account;
        $pwdStr = $pwd;
        $jurisdiction = sprintf('%08X', $jurisdiction);
        $goldCoinStr = sprintf('%08X', $gold);
        $silverCoinStr = sprintf('%08X', $silver);
        $checksum = strtoupper(md5($accountStr . $pwdStr . $jurisdiction . $goldCoinStr . $silverCoinStr . 'ABCDEF'));
        return array('pwd' => $pwd, 'checksum' => $checksum);
    }

}