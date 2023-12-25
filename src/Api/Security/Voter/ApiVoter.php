<?php

namespace Kms\Api\Security\Voter;

use Kms\Core\Security\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ApiVoter extends Voter
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ApiTokenRepository $apiTokenRepository,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return str_starts_with($attribute, 'API:');
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        $apiKey = $request->headers->get('apiKey');
        if (null === $apiKey) {
            return false;
        }

        $apiToken = $this->apiTokenRepository->findOneBy(['token' => $apiKey]);
        if (null === $apiToken || false === $apiToken->getEnabled()) {
            return false;
        }

        $permissionKey = str_replace('API:', '', $attribute);

        return $apiToken->hasPermission($permissionKey);
    }
}
