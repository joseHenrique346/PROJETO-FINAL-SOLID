<?php

use App\Application\ParkingService;
use App\Domain\Pricing\FixedRateStrategy;
use App\Domain\Pricing\PricingStrategyRegistry;
use App\Domain\VehicleType;
use App\Infra\Database\Connection;
use App\Infra\Repository\SQLiteParkingSessionRepository;

$root = dirname(__DIR__);
$autoload = $root . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
} else {
    spl_autoload_register(function ($class) use ($root) {
        $prefix = 'App\\';
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        $relativeClass = substr($class, $len);
        $candidatePaths = [
            $root . '/Projeto/src/' . str_replace('\\', '/', $relativeClass) . '.php',
            $root . '/src/' . str_replace('\\', '/', $relativeClass) . '.php',
        ];
        foreach ($candidatePaths as $file) {
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    });
}

$registry = new PricingStrategyRegistry();//
$registry->register(VehicleType::CARRO, new FixedRateStrategy(5));
$registry->register(VehicleType::MOTO, new FixedRateStrategy(3));
$registry->register(VehicleType::CAMINHAO, new FixedRateStrategy(10));

$connection = new Connection();
$connection->getPdo();

$service = new ParkingService(//
    new SQLiteParkingSessionRepository($connection),
    $registry);

return $service;

?>