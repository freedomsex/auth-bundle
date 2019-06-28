<?php


namespace FreedomSex\AuthBundle\Security;


use Symfony\Component\HttpFoundation\Request;

class JWTExtractor
{
    const AUTH_HEADER = 'Authorization';
    const AUTH_PREFIX = 'Bearer';

    public function extract(Request $request)
    {
        if (!$request->headers->has(self::AUTH_HEADER)) {
            return false;
        }

        $header = $request->headers->get(self::AUTH_HEADER);
        $data = explode(' ', $header);

        if (count($data) == 2 and strcasecmp($data[0], self::AUTH_PREFIX) === 0) {
            return $data[1];
        }
        return false;
    }
}
