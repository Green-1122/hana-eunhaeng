<?php $title = 'Transfer Money - Hana-Eunhaeng'; ?>

<div class="container-sm mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-exchange-alt text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3 fw-bold">Transfer Money</h2>
                        <p class="text-muted">Send money to another account</p>
                    </div>

                    <form method="POST" action="/transactions/processTransfer">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Helpers\SecurityHelper::generateCsrfToken(); ?>">

                        <div class="mb-3">
                            <label for="from_account" class="form-label">From Account</label>
                            <select class="form-select" id="from_account" name="from_account" required>
                                <option value="">Choose source account...</option>
                                <?php foreach ($accounts as $account): ?>
                                    <option value="<?php echo $account['id']; ?>">
                                        <?php echo htmlspecialchars($account['account_name']); ?> - $<?php echo number_format($account['balance'], 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="to_account" class="form-label">To Account</label>
                            <select class="form-select" id="to_account" name="to_account" required>
                                <option value="">Choose destination account...</option>
                                <?php foreach ($accounts as $account): ?>
                                    <option value="<?php echo $account['id']; ?>">
                                        <?php echo htmlspecialchars($account['account_name']); ?> - $<?php echo number_format($account['balance'], 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="2" placeholder="Add a note..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            <i class="fas fa-paper-plane me-2"></i>Transfer Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
