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

    /**
     * Retorna una etiqueta legible y formateada.
     */
    public function label(): string
    {
        return match ($this) {
            self::SISTEMAS => 'Gerente de Sistemas',
            self::TI => 'Director de TI',
            self::COMPRAS => 'Coordinador de Compras',
            self::DESARROLLO => 'Analista de Desarrollo',
            self::RRHH => 'Director de Gestión Humana',
            self::CONTABILIDAD => 'Contador Senior',
            self::COMERCIAL => 'Líder Comercial',
            self::ADMINISTRACION => 'Administrador de Sistemas'
        };
    }

    /**
     * Retorna un mapa completo de valores y etiquetas.
     * Excelente para exponer mediante un API endpoint y llenar los selects del frontend.
     * * @return array<array{value: string, label: string}>
     */
    public static function choices(): array
    {
        return array_map(fn($status) => [
            'value' => $status->value,
            'label' => $status->label()
        ], self::cases());
    }
}
