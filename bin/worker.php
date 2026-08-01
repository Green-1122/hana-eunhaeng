<?php
// Simple worker to process jobs (run with: php bin/worker.php)
require __DIR__ . '/../vendor/autoload.php';

use App\Services\JobQueue;
use App\Services\PdfService;
use App\Services\MailService;
use App\Models\Statement;

$env = __DIR__ . '/../.env';
if (file_exists($env)) {
    Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
}

$queue = new JobQueue();
$ps = new PdfService();
$mail = new MailService();
$statementModel = new Statement();

echo "Worker started\n";
while (true) {
    $job = $queue->reserve();
    if (!$job) {
        // Sleep briefly
        sleep(3);
        continue;
    }

    $payload = json_decode($job['payload'], true);
    $jobId = (int)$job['id'];
    try {
        if ($payload['type'] === 'generate_statement') {
            $sid = (int)$payload['statement_id'];
            echo "Processing statement: $sid\n";
            $filename = $ps->renderStatementPdf($sid);
            $filePath = $filename;
            $fileHash = hash_file('sha256', __DIR__ . '/../public/uploads/statements/' . $filename);
            $expiresAt = date('Y-m-d H:i:s', time() + 60*60*24*7); // 7 days
            $statementModel->updateFile($sid, $filePath, $fileHash, $expiresAt);

            // Optionally email
            if (!empty($payload['email'])) {
                $stmt = $statementModel->findById($sid);
                $mail->sendStatementEmail((int)$stmt['user_id'], $stmt);
            }
        }

        $queue->finish($jobId);
    } catch (\Exception $e) {
        // retry later
        $queue->fail($jobId, 60);
        // In production log $e
    }
}
