<?php

namespace App\Enums;

enum Position: string
{
    case SISTEMAS = 'Gerente de Sistemas';
    case TI = 'Director de TI';
    case COMPRAS = 'Coordinador de Compras';
    case DESARROLLO = 'Analista de Desarrollo';
    case RRHH = 'Director de Gestión Humana';
    case CONTABILIDAD = 'Contador Senior';
    case COMERCIAL = 'Líder Comercial';
    case ADMINISTRACION = 'Administrador de Sistemas';

    public static function random(): string
    {
        $cases = self::cases();
        return $cases[array_rand($cases)]->value;
    }
}
