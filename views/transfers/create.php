<div class="max-w-md mx-auto">
  <div class="glass p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Create a Transfer</h2>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="mb-4 text-red-600"><?=htmlspecialchars($_SESSION['flash_error'])?></div>
      <?php unset($_SESSION['flash_error']); endif; ?>

    <form method="POST" action="/transfers/create.post">
      <input type="hidden" name="_csrf" value="<?=htmlspecialchars($csrf)?>">

      <label class="block text-sm mt-2">From account</label>
      <select name="from_account" class="w-full p-2 border rounded">
        <?php foreach ($accounts as $a): ?>
          <option value="<?=$a['id']?>">#<?=$a['id']?> — <?=htmlspecialchars(ucfirst($a['account_type']))?> — $<?=number_format($a['balance'],2)?></option>
        <?php endforeach; ?>
      </select>

      <label class="block text-sm mt-2">To account (ID)</label>
      <input type="number" name="to_account" class="w-full p-2 border rounded" placeholder="Enter destination account ID">

      <label class="block text-sm mt-2">Amount</label>
      <input step="0.01" required type="number" name="amount" class="w-full p-2 border rounded" placeholder="0.00">

      <label class="block text-sm mt-2">Description (optional)</label>
      <input type="text" name="description" class="w-full p-2 border rounded" placeholder="For rent, transfer to savings, etc.">

      <button class="mt-4 w-full py-2 bg-sky-600 text-white rounded">Send Transfer</button>
    </form>

    <div class="mt-3 text-xs text-slate-500">Transfers between accounts are processed immediately. This demo uses DB transactions and row locking to ensure consistency.</div>
  </div>
</div>
