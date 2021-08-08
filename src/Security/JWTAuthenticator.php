<?php


namespace FreedomSex\AuthBundle\Security;

use FreedomSex\Services\JWTManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;


class JWTAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtManager;
    private $token;
    private $payload;

    public function __construct(
        JWTManager $jwtManager,
        JWTExtractor $extractor,
        LoggerInterface $logger
    ) {
        $this->jwtManager = $jwtManager;
        $this->extractor = $extractor;
        $this->logger = $logger;
    }

    public function supports(Request $request)
    {
        $jwt = $this->extractor->extract($request);
        $key = $request->get('api_key');

        $this->token = $jwt ?: $key;
        try {
            $this->payload = (array) $this->jwtManager->load($this->token);
        } catch (\Exception $e) {
            $this->logger->debug(sprintf('JWT load error: %s', $e->getMessage()));
            return false;
        }
        return true;
    }

    public function getCredentials(Request $request)
    {
        if (!$this->token or !$this->payload) {
            return null;
        }
        return [
            'payload' => $this->payload,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $uid = $credentials['payload']['uid'];
        $uuid = null;
        if (array_key_exists('uuid', $credentials['payload'])) {
            $uuid = $credentials['payload']['uuid'];
        }
        if (!$uid) {
            return null;
        }
        return new JWTUser($uid, $uuid, $credentials['payload']);
    }


    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response('', Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('', Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
