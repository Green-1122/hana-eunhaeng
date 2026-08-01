<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; font-size:12px; color:#222 }
    .header { text-align:center; margin-bottom:20px }
    .company { font-size:18px; font-weight:700; color:#0ea5e9 }
    table { width:100%; border-collapse:collapse }
    th, td { padding:6px 8px; border-bottom:1px solid #ddd; text-align:left }
    .amount { text-align:right }
    .summary { margin-top:12px }
  </style>
</head>
<body>
  <div class="header">
    <div class="company">Hana-Eunhaeng</div>
    <div class="muted">Account statement for account #<?=htmlspecialchars($accountForView['id'])?></div>
    <div class="muted">Period: <?=htmlspecialchars($period[0])?> — <?=htmlspecialchars($period[1])?></div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Description</th>
        <th class="amount">Amount</th>
        <th class="amount">Balance</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $running = (float)$accountForView['balance'];
        // We can't compute opening balance precisely without full history; show transactions list
        foreach ($transactionsForView as $t):
      ?>
        <tr>
          <td><?=htmlspecialchars($t['created_at'])?></td>
          <td><?=htmlspecialchars($t['description'] ?? '')?></td>
          <td class="amount"><?=($t['type'] === 'credit' ? '+' : '-')?>$<?=number_format($t['amount'],2)?></td>
          <td class="amount">$<?=number_format($running,2)?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="summary">
    <div>Generated: <?=date('Y-m-d H:i:s')?></div>
  </div>
</body>
</html>
