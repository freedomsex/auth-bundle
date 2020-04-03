<?php


namespace FreedomSex\AuthBundle\Services;


class PasswordManager
{

    public function randomChar($length)
    {
        $array = array('a','b','c','d','e','f','g','h','j','k','m','n','p','r','s','t','u','v','x','y','z');
        $data = [];
        while (count($data) < $length) {
            $char = $array[mt_rand(0, count($array)-1)];
            $data[] = random_int(0, 1) ? mb_strtoupper($char) : $char;
        }
        return $data;
    }

    public function randomDigit($length)
    {
        $array = array('1','2','3','4','5','6','7','8','9','8');
        $data = [];
        while (count($data) < $length) {
            $data[] = $array[mt_rand(0, count($array)-1)];
        }
        return $data;
    }

    public function generate($charCount, $digitCount)
    {
        $data = array_merge(
            $this->randomChar($charCount),
            $this->randomDigit($digitCount)
        );
        shuffle($data);
        return join('', $data);
    }

    public function random($length)
    {
        $charCount = round($length/2);
        $digitCount = $length - $charCount;
        return $this->generate($charCount, $digitCount);
    }

}
