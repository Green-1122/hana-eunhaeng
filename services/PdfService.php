<?php
namespace App\Services;

use TCPDF;
use App\Models\Statement;
use App\Models\Transaction;
use App\Models\Account;

class PdfService
{
    protected string $storagePath;

    public function __construct()
    {
        $this->storagePath = __DIR__ . '/../../public/uploads/statements';
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    public function renderStatementPdf(int $statementId): string
    {
        $statementModel = new Statement();
        $stmt = $statementModel->findById($statementId);
        if (!$stmt) {
            throw new \Exception('Statement not found');
        }

        $accountModel = new Account();
        $account = $accountModel->find((int)$stmt['account_id']);

        // Gather transactions for the period
        $txnModel = new Transaction();
        $from = $stmt['period_from'];
        $to = $stmt['period_to'];

        // Simple fetch: all transactions for account within date range
        $pdo = \App\Core\Database::getInstance();
        $q = $pdo->prepare('SELECT * FROM transactions WHERE account_id = :aid AND created_at BETWEEN :from AND :to ORDER BY created_at ASC');
        $q->execute(['aid' => $account['id'], 'from' => $from . ' 00:00:00', 'to' => $to . ' 23:59:59']);
        $txns = $q->fetchAll();

        // Render HTML template
        ob_start();
        $pdfTemplate = __DIR__ . '/../../views/statements/pdf_template.php';
        $accountForView = $account;
        $transactionsForView = $txns;
        $period = [$from, $to];
        include $pdfTemplate;
        $html = ob_get_clean();

        // Create PDF with TCPDF
        $pdf = new \TCPDF();
        $pdf->SetCreator('Hana-Eunhaeng');
        $pdf->SetAuthor('Hana-Eunhaeng');
        $pdf->SetTitle('Statement ' . $statementId);
        $pdf->SetMargins(10, 10, 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = 'statement-' . $statementId . '-' . time() . '.pdf';
        $filePath = $this->storagePath . '/' . $filename;
        $pdf->Output($filePath, 'F');

        return $filename;
    }
}
