<?php

namespace App\Infra\Repository;

use App\Domain\Entity\ParkingSession;
use App\Domain\Repository\ParkingSessionRepositoryInterface;
use App\Domain\VehicleType;
use App\Infra\Database\Connection;
use PDO;

final class SQLiteParkingSessionRepository implements ParkingSessionRepositoryInterface
{
    public function __construct(private readonly Connection $connection)
    {
    }

    private function pdo(): PDO
    {
        $pdo = $this->connection->getPdo();
        $pdo->exec('CREATE TABLE IF NOT EXISTS parking_sessions (
            id TEXT PRIMARY KEY,
            plate TEXT NOT NULL,
            vehicle_type TEXT NOT NULL,
            entry_at TEXT NOT NULL,
            exit_at TEXT NULL,
            total_hours INTEGER NULL,
            amount REAL NULL
        )');
        $pdo->exec('CREATE INDEX IF NOT EXISTS idx_active_plate ON parking_sessions(plate, exit_at)');
        return $pdo;
    }

    public function start(ParkingSession $session): void
    {
        $stmt = $this->pdo()->prepare('INSERT INTO parking_sessions (id, plate, vehicle_type, entry_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $session->id,
            $session->plate,
            $session->vehicleType->value,
            $session->entryAt->format(DATE_ATOM),
        ]);
    }

    public function finish(ParkingSession $session): void
    {
        $stmt = $this->pdo()->prepare('UPDATE parking_sessions SET exit_at = ?, total_hours = ?, amount = ? WHERE id = ?');
        $stmt->execute([
            $session->exitAt?->format(DATE_ATOM),
            $session->totalHours,
            $session->amount,
            $session->id,
        ]);
    }

    public function findActiveByPlate(string $plate): ?ParkingSession
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM parking_sessions WHERE plate = ? AND exit_at IS NULL ORDER BY entry_at DESC LIMIT 1');
        $stmt->execute([$plate]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new ParkingSession(
            id: $row['id'],
            plate: $row['plate'],
            vehicleType: VehicleType::from($row['vehicle_type']),
            entryAt: new \DateTimeImmutable($row['entry_at'])
        );
    }

    public function reportTotals(): array
    {
        $types = [VehicleType::CARRO->value, VehicleType::MOTO->value, VehicleType::CAMINHAO->value];
        $result = [];
        foreach ($types as $t) {
            $stmt = $this->pdo()->prepare('SELECT COUNT(*) as count, COALESCE(SUM(amount),0) as amount, COALESCE(SUM(total_hours),0) as hours FROM parking_sessions WHERE vehicle_type = ? AND exit_at IS NOT NULL');
            $stmt->execute([$t]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $result[$t] = [
                'count' => (int) $row['count'],
                'amount' => (float) $row['amount'],
                'hours' => (int) $row['hours'],
            ];
        }
        return $result;
    }
}

?>