<?php

namespace App\enum;




enum EventState: string
{
    case EN_CREATION = 'En création';
    case OUVERTE = 'Ouverte';
    case CLOTURE = 'Clôturée';
    case EN_COURS = 'En cours';
    case TERMINEE = 'Terminée';
    case ANNULEE = 'Annulée';
    case HISTORISEE = 'Historisée';

    public static function getAsArray(): array
    {
        return array_reduce(
            self::cases(),
            static fn (array $choices, EventState $type) => $choices + [$type->name => $type->value],
            [],
        );
    }
}