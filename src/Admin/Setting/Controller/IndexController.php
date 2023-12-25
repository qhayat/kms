<?php

namespace Kms\Admin\Setting\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Kms\Admin\Content\Service\Page\BlogPageSetter;
use Kms\Admin\Content\Service\Page\HomePageSetter;
use Kms\Admin\Media\Uploader\UploaderInterface;
use Kms\Admin\Setting\Form\SettingType;
use Kms\Admin\Setting\General as GeneralSetting;
use Kms\Admin\Setting\Repository\SettingRepository;
use Kms\Admin\Setting\Upsert;
use Kms\Core\Content\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly Upsert $upsertSetting,
        private readonly UploaderInterface $kmsUploader,
        private readonly BlogPageSetter $blogPageSetter,
        private readonly HomePageSetter $homePageSetter,
        private readonly EntityManagerInterface $entityManager,
        private readonly SettingRepository $settingRepository,
        private readonly GeneralSetting $generalSetting,
        private readonly TagAwareAdapterInterface $kmsCache,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $settings = $this->settingRepository->toArray();
        $form = $this->createForm(SettingType::class, /*$settings*/);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData() as $key => $value) {
                if ('site_logo' == $key && null == $value) {
                    continue;
                }

                if ($value instanceof UploadedFile) {
                    $media = $this->kmsUploader->upload($value);
                    $this->entityManager->persist($media);
                    $this->entityManager->flush();
                    $value = (string) $media->getId();
                }

                if ('blog_page' == $key && $value instanceof Page) {
                    $this->blogPageSetter->set($value);
                    $value = (string) $value->getId();
                }

                if ('home_page' == $key && $value instanceof Page) {
                    $this->homePageSetter->set($value);
                    $value = (string) $value->getId();
                }

                $this->upsertSetting->process($key, $value);
            }

            $this->kmsCache->invalidateTags(['setting']);
        }

        if (isset($settings['site_logo'])) {
            $settings['site_logo'] = $this->generalSetting->getLogoFinalUrl($settings['site_logo']);
        }

        return $this->render('@Kms/admin/setting/index.html.twig',
            [
                'form' => $form->createView(),
                'settings' => $settings,
            ]
        );
    }
}
