<?php

namespace Kms\Admin\Setting;

use Doctrine\ORM\EntityManagerInterface;
use Kms\Admin\Setting\Entity\Setting;
use Kms\Admin\Setting\Repository\SettingRepository;
use Kms\Admin\User\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class Upsert
{
    public function __construct(
        private readonly SettingRepository $settingRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
    ) {
    }

    public function process(string $key, ?string $value = null): void
    {
        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        if (null === $setting = $this->settingRepository->findOneBy(['key' => $key])) {
            $setting = (new Setting())
                ->setKey($key)
                ->setAuthor($user)
            ;
        }

        $setting->setValue($value ?? '');
        $this->entityManager->persist($setting);
        $this->entityManager->flush();
    }
}
