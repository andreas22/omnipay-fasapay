<?php

namespace Omnipay\Fasapay\Helpers;

class Security
{

    /**
     * Get sha256 hash
     *
     * @param $data
     * @return string
     */
    public static function getHash($data)
    {
        $string = implode(':', $data);
        return hash('sha256', $string);
    }
}
