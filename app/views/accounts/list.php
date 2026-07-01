<?php $title = 'Accounts - Hana-Eunhaeng'; ?>

<div class="container-lg">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2">Your Accounts</h1>
            <p class="text-muted">Manage and monitor all your bank accounts in one place.</p>
        </div>
        <div class="col-lg-4 text-end">
            <a href="/accounts/create" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Create New Account
            </a>
        </div>
    </div>

    <?php if (empty($accounts)): ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="fas fa-inbox fa-4x text-muted opacity-50 mb-3 d-block"></i>
                <h4 class="fw-bold mb-2">No Accounts Found</h4>
                <p class="text-muted mb-4">You don't have any accounts yet. Create one to get started.</p>
                <a href="/accounts/create" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Account
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($accounts as $account): ?>
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 transition-all hover:shadow-lg">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($account['account_name']); ?></h5>
                                    <p class="text-muted small mb-0"><?php echo $account['account_number']; ?></p>
                                </div>
                                <span class="badge bg-<?php echo $account['status'] === 'active' ? 'success' : 'warning'; ?>"><?php echo ucfirst($account['status']); ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-muted small mb-1">Account Balance</p>
                                <h3 class="fw-bold">$<?php echo number_format($account['balance'], 2); ?></h3>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="text-muted small mb-1">Account Type</p>
                                    <p class="fw-bold"><?php echo ucfirst($account['account_type']); ?></p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-1">Interest Rate</p>
                                    <p class="fw-bold"><?php echo $account['interest_rate']; ?>%</p>
                                </div>
                            </div>

                            <a href="/accounts/view?id=<?php echo $account['id']; ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
