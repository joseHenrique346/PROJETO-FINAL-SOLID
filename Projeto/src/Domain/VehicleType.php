<?php

namespace App\Domain;

enum VehicleType: string
{
    case CARRO = 'carro';
    case MOTO = 'moto';
    case CAMINHAO = 'caminhao';

    public function label(): string {
        
        return match ($this) {
            self::CARRO => 'Carro',
            self::MOTO => 'Moto',
            self::CAMINHAO => 'Caminhão',
        };
    }
}
