<?php


namespace FreedomSex\AuthBundle\Services;


use Symfony\Component\HttpFoundation\RequestStack;

class ClientIdentifier
{
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function request()
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function ip()
    {
        return $this->request()->getClientIp();
    }

    public function ipin()
    {
        return sprintf("%u", ip2long($this->ip())); // Числовой
    }

    public function agent()
    {
        return $this->request()->headers->get('User-Agent');
    }
}
