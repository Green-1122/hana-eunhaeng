<div class="max-w-3xl mx-auto">
  <a class="text-sm text-slate-600" href="/accounts">&larr; Back to accounts</a>
  <div class="glass p-6 rounded shadow mt-4">
    <h2 class="text-xl font-semibold">Account #<?=htmlspecialchars($account['id'])?> — <?=htmlspecialchars(ucfirst($account['account_type']))?></h2>
    <div class="mt-2 text-slate-600">Balance: <span class="font-medium text-sky-700">$<?=number_format($account['balance'],2)?></span></div>

    <div class="mt-6">
      <h3 class="text-lg font-medium">Recent Transactions</h3>
      <?php if (empty($transactions)): ?>
        <div class="text-slate-500 mt-3">No transactions found.</div>
      <?php else: ?>
        <ul class="mt-3 divide-y">
          <?php foreach ($transactions as $t): ?>
            <li class="py-3 flex items-start justify-between">
              <div>
                <div class="text-sm text-slate-600"><?=htmlspecialchars($t['description'] ?? '')?></div>
                <div class="text-xs text-slate-400"><?=htmlspecialchars($t['created_at'])?></div>
              </div>
              <div class="text-sm <?= $t['type'] === 'credit' ? 'text-green-600' : 'text-red-600' ?>"><?= $t['type'] === 'credit' ? '+' : '-' ?>$<?=number_format($t['amount'],2)?></div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
