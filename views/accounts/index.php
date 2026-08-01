<div class="max-w-4xl mx-auto">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Your Accounts</h1>
    <a class="px-3 py-2 bg-sky-600 text-white rounded" href="/transfers/create">New Transfer</a>
  </div>

  <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?=htmlspecialchars($_SESSION['flash_success'])?></div>
    <?php unset($_SESSION['flash_success']); endif; ?>
  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?=htmlspecialchars($_SESSION['flash_error'])?></div>
    <?php unset($_SESSION['flash_error']); endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <?php foreach ($accounts as $a): ?>
      <div class="glass p-4 rounded shadow">
        <div class="text-sm text-slate-500">Account #<?=htmlspecialchars($a['id'])?></div>
        <div class="text-lg font-semibold mt-2"><?=htmlspecialchars(ucfirst($a['account_type']))?></div>
        <div class="text-2xl text-sky-700 mt-4">$<?=number_format($a['balance'],2)?></div>
        <div class="mt-4">
          <a class="text-sky-600 text-sm" href="/accounts/show?id=<?=$a['id']?>>">View</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
