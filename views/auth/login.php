<div class="max-w-md mx-auto mt-12">
  <div class="glass p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">Sign in to Hana-Eunhaeng</h2>
    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="mb-4 text-red-600"><?=htmlspecialchars($_SESSION['flash_error'])?></div>
      <?php unset($_SESSION['flash_error']); endif; ?>

    <form method="POST" action="/login.post">
      <input type="hidden" name="_csrf" value="<?=htmlspecialchars($csrf)?>">
      <label class="block mb-2 text-sm">Email</label>
      <input required class="w-full p-2 border rounded mb-3" type="email" name="email">

      <label class="block mb-2 text-sm">Password</label>
      <input required class="w-full p-2 border rounded mb-3" type="password" name="password">

      <button class="w-full py-2 bg-sky-600 text-white rounded">Sign in</button>
    </form>

    <div class="mt-4 text-xs text-slate-500">
      This scaffold uses secure password hashing and CSRF tokens. For production, enable HTTPS, rate-limiting, and monitoring.
    </div>
  </div>
</div>
