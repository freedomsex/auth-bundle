<?php


namespace FreedomSex\AuthBundle\Security;

use App\Security\Users\JWTUser;
use FreedomSex\Services\JWTManager;
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

    public function __construct(JWTManager $jwtManager, JWTExtractor $extractor)
    {
        $this->jwtManager = $jwtManager;
        $this->extractor = $extractor;
    }

    public function supports(Request $request)
    {
        return $this->extractor->extract($request);
    }

    public function getCredentials(Request $request)
    {
        $token = $this->extractor->extract($request);
        try {
            $payload = (array) $this->jwtManager->load($token);
        } catch (\Exception $e) {
            throw new BadRequestHttpException();
        }
        return [
            'payload' => $payload,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $uid = $credentials['payload']['uid'];
        if (!$uid) {
            return null;
        }
        return new JWTUser($uid, $credentials['payload']);
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
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new Response('', 401);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
