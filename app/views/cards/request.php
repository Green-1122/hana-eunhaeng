<?php $title = 'Request Card - Hana-Eunhaeng'; ?>

<div class="container-sm mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-credit-card text-primary" style="font-size: 3rem;"></i>
                        <h2 class="mt-3 fw-bold">Request a Card</h2>
                        <p class="text-muted">Get a new debit or credit card</p>
                    </div>

                    <form method="POST" action="/cards/requestSubmit">
                        <input type="hidden" name="csrf_token" value="<?php echo \App\Helpers\SecurityHelper::generateCsrfToken(); ?>">

                        <div class="mb-3">
                            <label for="account_id" class="form-label">Select Account</label>
                            <select class="form-select" id="account_id" name="account_id" required>
                                <option value="">Choose an account...</option>
                                <?php foreach ($accounts as $account): ?>
                                    <option value="<?php echo $account['id']; ?>">
                                        <?php echo htmlspecialchars($account['account_name']); ?> - $<?php echo number_format($account['balance'], 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="card_type" class="form-label">Card Type</label>
                            <select class="form-select" id="card_type" name="card_type" required>
                                <option value="debit">Debit Card</option>
                                <option value="credit">Credit Card</option>
                                <option value="prepaid">Prepaid Card</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="holder_name" class="form-label">Card Holder Name</label>
                            <input type="text" class="form-control" id="holder_name" name="holder_name" required>
                            <small class="text-muted">As it should appear on the card</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the card <a href="#" class="text-decoration-none">terms and conditions</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            <i class="fas fa-paper-plane me-2"></i>Submit Request
                        </button>
                    </form>

                    <div class="mt-4 p-3 bg-info bg-opacity-10 rounded-3">
                        <p class="text-muted small mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Your card will arrive in 7-10 business days.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
