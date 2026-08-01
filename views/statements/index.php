<div class="max-w-3xl mx-auto">
  <div class="glass p-6 rounded shadow mt-4">
    <h2 class="text-xl font-semibold mb-4">Request Account Statement</h2>

    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?=htmlspecialchars($_SESSION['flash_success'])?></div>
      <?php unset($_SESSION['flash_success']); endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="mb-4 p-3 bg-red-100 text-red-800 rounded"><?=htmlspecialchars($_SESSION['flash_error'])?></div>
      <?php unset($_SESSION['flash_error']); endif; ?>

    <form method="POST" action="/statements/generate.post">
      <input type="hidden" name="_csrf" value="<?=htmlspecialchars($csrf)?>">

      <label class="block text-sm mt-2">Account</label>
      <select name="account_id" class="w-full p-2 border rounded">
        <?php foreach ($accounts as $a): ?>
          <option value="<?=$a['id']?>">#<?=$a['id']?> — <?=htmlspecialchars(ucfirst($a['account_type']))?> — $<?=number_format($a['balance'],2)?></option>
        <?php endforeach; ?>
      </select>

      <div class="grid grid-cols-2 gap-3 mt-3">
        <div>
          <label class="block text-sm">From</label>
          <input type="date" name="from_date" class="w-full p-2 border rounded" required>
        </div>
        <div>
          <label class="block text-sm">To</label>
          <input type="date" name="to_date" class="w-full p-2 border rounded" required>
        </div>
      </div>

      <label class="block text-sm mt-3"><input type="checkbox" name="email_me"> Email me when ready</label>

      <button class="mt-4 w-full py-2 bg-sky-600 text-white rounded">Request Statement</button>
    </form>

    <div class="mt-6">
      <h3 class="text-lg font-medium">Recent Statements</h3>
      <?php
        $statementModel = new App\Models\Statement();
        $list = $statementModel->findByUser((int)$_SESSION['user_id']);
      ?>
      <?php if (empty($list)): ?>
        <div class="text-slate-500 mt-3">No statements yet.</div>
      <?php else: ?>
        <ul class="mt-3 divide-y">
          <?php foreach ($list as $s): ?>
            <li class="py-3 flex items-center justify-between">
              <div>
                <div class="text-sm">Statement #<?=htmlspecialchars($s['id'])?> — <?=htmlspecialchars($s['period_from'])?> to <?=htmlspecialchars($s['period_to'])?></div>
                <div class="text-xs text-slate-400">Status: <?=htmlspecialchars($s['status'])?> — <?=htmlspecialchars($s['created_at'])?></div>
              </div>
              <div>
                <?php if ($s['status'] === 'ready'): ?>
                  <?php $token = hash_hmac('sha256', $s['id'] . '|' . $s['expires_at'], getenv('APP_KEY') ?: ''); ?>
                  <a class="text-sky-600 mr-3" href="/statements/download?id=<?=$s['id']?>&t=<?=$token?>">Download</a>
                  <form method="POST" action="/statements/email.post" style="display:inline">
                    <input type="hidden" name="_csrf" value="<?=htmlspecialchars($csrf)?>">
                    <input type="hidden" name="statement_id" value="<?=$s['id']?>">
                    <button class="text-sm text-slate-600">Email</button>
                  </form>
                <?php else: ?>
                  <span class="text-sm text-slate-500">Queued</span>
                <?php endif; ?>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
