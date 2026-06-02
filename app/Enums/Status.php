<?php

namespace App\Enums;

enum Status: string
{
    case ACTIVO = 'activo';
    case INACTIVO = 'inactivo';
    case PROSPECTO = 'prospecto';

    public static function random(): string
    {
        $cases = self::cases();
        return $cases[array_rand($cases)]->value;
    }

    /**
     * Retorna una etiqueta legible y formateada, ideal para mostrar en la interfaz de Vue 3.
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVO => 'Activo',
            self::INACTIVO => 'Inactivo',
            self::PROSPECTO => 'Prospecto',
        };
    }

    /**
     * Retorna un array plano con todos los valores string del Enum.
     * Útil para seeders, factories y validaciones manuales.
     * * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Retorna los estilos para el list en la vista
     */
    public function style(): string
    {
        return match ($this) {
            self::ACTIVO => 'bg-success bg-opacity-10 text-success border-success',
            self::INACTIVO => 'bg-danger bg-opacity-10 text-danger border-danger',
            self::PROSPECTO => 'bg-info bg-opacity-10 text-info border-info',
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
            'label' => $status->label(),
            'style' => $status->style(),
        ], self::cases());
    }
}
