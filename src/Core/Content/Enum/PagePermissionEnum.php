<?php

namespace Kms\Core\Content\Enum;

enum PagePermissionEnum: string
{
    case CREATE = 'page.create';
    case READ = 'page.read';
    case UPDATE = 'page.update';
    case DELETE = 'page.delete';
}
