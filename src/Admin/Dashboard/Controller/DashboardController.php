<?php

namespace Kms\Admin\Dashboard\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Kms\Admin\Content\Controller\CategoryCrudController;
use Kms\Admin\Content\Controller\PageCrudController;
use Kms\Admin\Content\Controller\PostCrudController;
use Kms\Admin\Media\Controller\MediaCrudController;
use Kms\Admin\Media\Entity\Media;
use Kms\Admin\Menu\Controller\MenuCrudController;
use Kms\Admin\Menu\Entity\Menu;
use Kms\Admin\Security\Controller\ApiTokenCrudController;
use Kms\Admin\Setting\General as GeneralSetting;
use Kms\Admin\User\Controller\UserCrudController;
use Kms\Admin\User\Entity\User;
use Kms\Core\Content\Entity\Category;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Entity\Post;
use Kms\Core\Security\Entity\ApiToken;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private readonly GeneralSetting $generalSetting)
    {
    }

    public function index(): Response
    {
        return $this->render('@Kms/admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        $title = $this->generalSetting->get('site_name');
        $title = null !== $title && '' !== $title ? $title : 'KMS';

        return Dashboard::new()
            ->setTitle($title);
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('CMS'),
            MenuItem::linkToCrud('Pages', 'fas fa-file', Page::class)
                ->setController(PageCrudController::class),
            MenuItem::subMenu('Blog', 'fa fa-newspaper-o')->setSubItems([
                MenuItem::linkToCrud('Articles', 'fa fa-file-text', Post::class)
                    ->setController(PostCrudController::class),
                MenuItem::linkToCrud('Categories', 'fa fa-tag', Category::class)
                    ->setController(CategoryCrudController::class),
            ]),
            MenuItem::linkToCrud('Medias', 'fas fa-images', Media::class)
                ->setController(MediaCrudController::class),
            MenuItem::linkToCrud('Menus', 'fas fa-bars', Menu::class)
                ->setController(MenuCrudController::class),
            MenuItem::section('Security'),
            MenuItem::linkToCrud('Users', 'fas fa-user', User::class)
                ->setController(UserCrudController::class),
            MenuItem::linkToCrud('API', 'fas fa-key', ApiToken::class)
                ->setController(ApiTokenCrudController::class),
            MenuItem::section('Settings'),
            MenuItem::linkToRoute('General', 'fas fa-cog', 'kms_setting'),
            MenuItem::linkToRoute('Cache', 'fas fa-database', 'kms_setting_cache'),
        ];
    }
}
