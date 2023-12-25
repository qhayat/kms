<?php

namespace Kms\Api\EventListener;

use Kms\Core\Content\Enum\Type;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: 'kernel.request', method: 'listen')]
final class RequestListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function listen(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!str_starts_with($request->attributes->get('_route'), 'kms_api_')) {
            return;
        }

        $type = Type::fromApiRouteName($request->attributes->get('_route'));

        if (!$this->security->isGranted(sprintf('API:%s:%s', strtoupper($type->value), 'READ'))) {
            $event->setResponse(new JsonResponse(['message' => sprintf('You are not allowed to %s %s!', 'read', $type->value)], Response::HTTP_FORBIDDEN));
        }
    }
}
