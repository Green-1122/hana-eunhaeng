<?php
namespace App\Services;

use App\Core\Database;

class JobQueue
{
    protected \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function push(array $payload, int $delaySeconds = 0): int
    {
        $sql = 'INSERT INTO jobs (type, payload, attempts, run_at, created_at) VALUES (:type, :payload, 0, :run_at, NOW())';
        $stmt = $this->db->prepare($sql);
        $runAt = date('Y-m-d H:i:s', time() + $delaySeconds);
        $stmt->execute([
            'type' => $payload['type'] ?? 'job',
            'payload' => json_encode($payload),
            'run_at' => $runAt,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function reserve(): ?array
    {
        // Simple reservation: select a job ready to run
        $this->db->beginTransaction();
        try {
            $sql = "SELECT * FROM jobs WHERE completed_at IS NULL AND run_at <= NOW() ORDER BY created_at ASC LIMIT 1 FOR UPDATE";
            $stmt = $this->db->query($sql);
            $job = $stmt->fetch();
            if (!$job) {
                $this->db->commit();
                return null;
            }

            // mark attempts++
            $upd = $this->db->prepare('UPDATE jobs SET attempts = attempts + 1 WHERE id = :id');
            $upd->execute(['id' => $job['id']]);
            $this->db->commit();
            return $job;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return null;
        }
    }

    public function finish(int $jobId): bool
    {
        $stmt = $this->db->prepare('UPDATE jobs SET completed_at = NOW() WHERE id = :id');
        return $stmt->execute(['id' => $jobId]);
    }

    public function fail(int $jobId, int $delaySeconds = 60): bool
    {
        $runAt = date('Y-m-d H:i:s', time() + $delaySeconds);
        $stmt = $this->db->prepare('UPDATE jobs SET run_at = :run_at WHERE id = :id');
        return $stmt->execute(['run_at' => $runAt, 'id' => $jobId]);
    }
}
