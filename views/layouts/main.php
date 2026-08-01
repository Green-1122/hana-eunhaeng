<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Hana-Eunhaeng</title>
  <meta name="description" content="Hana-Eunhaeng fintech platform">
  <!-- Tailwind CDN for quick prototyping -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="" crossorigin="anonymous">
  <style>
    /* Basic glassmorphism card */
    .glass { background: rgba(255,255,255,0.6); backdrop-filter: blur(8px); }
    [data-theme="dark"] .glass { background: rgba(0,0,0,0.5); }
  </style>
</head>
<body class="antialiased bg-slate-50" data-theme="light">
  <div class="min-h-screen flex flex-col">
    <header class="bg-white/60 shadow-sm py-4 px-6 glass">
      <div class="max-w-6xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="text-2xl font-semibold text-sky-700">Hana-Eunhaeng</div>
          <div class="text-sm text-slate-600">Modern digital banking</div>
        </div>
        <div>
          <?php if (!empty($_SESSION['user_email'])): ?>
            <span class="mr-4 text-sm text-slate-600">Hello, <?=htmlspecialchars($_SESSION['user_email'])?></span>
            <a class="text-sky-600" href="/logout">Logout</a>
          <?php else: ?>
            <a class="text-sky-600" href="/login">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </header>

    <main class="flex-1 p-6 max-w-6xl mx-auto">
      <?php include $viewFile; ?>
    </main>

    <footer class="py-6 text-center text-sm text-slate-500">
      &copy; <?=date('Y')?> Hana-Eunhaeng — Educational fintech scaffold
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
