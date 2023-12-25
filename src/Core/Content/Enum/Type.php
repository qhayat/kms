<?php

namespace Kms\Core\Content\Enum;

enum Type: string
{
    case PAGE = 'page';
    case POST = 'post';

    public function equals(self $type): bool
    {
        return $this->value === $type->value;
    }

    public static function fromRouteName(string $routeName): self
    {
        $type = str_replace('kms_', '', $routeName);

        if ('home' === $type) {
            return self::PAGE;
        }

        return self::byCaseValue($type);
    }

    public static function fromApiRouteName(string $routeName): self
    {
        $type = str_replace('kms_api_', '', $routeName);
        $type = explode('_', $type)[0];

        return self::byCaseValue($type);
    }

    public static function byCaseValue(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        return self::PAGE;
    }
}
