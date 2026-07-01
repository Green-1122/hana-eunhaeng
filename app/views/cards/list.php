<?php $title = 'Cards - Hana-Eunhaeng'; ?>

<div class="container-lg">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2">Your Cards</h1>
            <p class="text-muted">Manage all your debit, credit, and prepaid cards.</p>
        </div>
        <div class="col-lg-4 text-end">
            <a href="/cards/request" class="btn btn-primary btn-lg">
                <i class="fas fa-credit-card me-2"></i>Request New Card
            </a>
        </div>
    </div>

    <?php if (empty($cards)): ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="fas fa-credit-card fa-4x text-muted opacity-50 mb-3 d-block"></i>
                <h4 class="fw-bold mb-2">No Cards Found</h4>
                <p class="text-muted mb-4">You don't have any cards yet. Request one to get started.</p>
                <a href="/cards/request" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Request Your First Card
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($cards as $card): ?>
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-gradient-primary text-white" style="min-height: 250px;">
                        <div class="card-body d-flex flex-column justify-content-between" style="height: 100%;">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div>
                                        <p class="text-opacity-75 small mb-1">Card Number</p>
                                        <h5 class="fw-bold">•••• •••• •••• <?php echo substr($card['card_number'], -4); ?></h5>
                                    </div>
                                    <i class="fas fa-<?php echo strtolower($card['card_brand']); ?> fa-2x text-opacity-50"></i>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-opacity-75 small mb-1">Card Holder</p>
                                        <p class="small mb-0"><?php echo htmlspecialchars($card['holder_name']); ?></p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="text-opacity-75 small mb-1">Expires</p>
                                        <p class="small mb-0"><?php echo str_pad($card['expiry_month'], 2, '0', STR_PAD_LEFT) . '/' . substr($card['expiry_year'], -2); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="/cards/view?id=<?php echo $card['id']; ?>" class="btn btn-sm btn-light text-primary flex-grow-1">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <?php if ($card['status'] === 'active'): ?>
                                    <button class="btn btn-sm btn-outline-light" onclick="lockCard(<?php echo $card['id']; ?>)">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-light" onclick="unlockCard(<?php echo $card['id']; ?>)">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function lockCard(cardId) {
    if (confirm('Lock this card?')) {
        // Send AJAX request
        fetch('/cards/lock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'card_id=' + cardId
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              }
          });
    }
}

function unlockCard(cardId) {
    if (confirm('Unlock this card?')) {
        fetch('/cards/unlock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'card_id=' + cardId
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  location.reload();
              }
          });
    }
}
</script>
