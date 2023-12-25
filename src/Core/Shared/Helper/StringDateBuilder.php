<?php

namespace Kms\Core\Shared\Helper;

class StringDateBuilder
{
    public const LONG_WEEKDAY_NAME = [
        'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi',
    ];
    public const SHORT_WEEKDAY_NAME = [
        'Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam',
    ];
    public const LONG_MONTH_NAME = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre',
    ];
    public const SHORT_MONTH_NAME = [
        'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Jui', 'Août', 'Sept', 'Oct', 'Nov', 'Déc',
    ];

    private bool $shortWeekDayName = false;
    private bool $shortMonthName = false;
    private bool $withTime = false;

    public function __construct(
        protected readonly \DateTimeInterface $dateTime,
    ) {
    }

    public function shortWeekDayName(): self
    {
        $this->shortWeekDayName = true;

        return $this;
    }

    public function shortMonthName(): self
    {
        $this->shortMonthName = true;

        return $this;
    }

    public function withTime(): self
    {
        $this->withTime = true;

        return $this;
    }

    public function build(): string
    {
        $weekDayName = $this->shortWeekDayName ? self::SHORT_WEEKDAY_NAME : self::LONG_WEEKDAY_NAME;
        $monthName = $this->shortMonthName ? self::SHORT_MONTH_NAME : self::LONG_MONTH_NAME;

        $date = sprintf('Le %s %s %s %s',
            $weekDayName[$this->dateTime->format('w')],
            $this->dateTime->format('j'),
            $monthName[$this->dateTime->format('n') - 1],
            $this->dateTime->format('Y')
        );

        if ($this->withTime) {
            $date .= sprintf(' à %s:%s',
                $this->dateTime->format('H'),
                $this->dateTime->format('i')
            );
        }

        return $date;
    }
}
