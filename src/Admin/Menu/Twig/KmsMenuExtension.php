<?php

namespace Kms\Admin\Menu\Twig;

use Kms\Admin\Menu\Entity\MenuItem;
use Kms\Admin\Menu\Menu as MenuService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class KmsMenuExtension extends AbstractExtension
{
    public function __construct(private readonly MenuService $menu)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('kms_menu', [$this, 'kmsMenu']),
        ];
    }

    /**
     * @return MenuItem[]
     */
    public function kmsMenu(string $name): array
    {
        return $this->menu->get($name);
    }
}
