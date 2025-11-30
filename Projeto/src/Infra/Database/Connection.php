<?php

namespace App\Infra\Database;

use PDO;

final class Connection
{
    private ?PDO $pdo = null;

    public function getPdo(): PDO {
        
        if ($this->pdo) {
            return $this->pdo;
        }

        $dir = __DIR__ . '/../../../storage';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir . '/parking.sqlite';

        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
        return $this->pdo;
    }
}

?>