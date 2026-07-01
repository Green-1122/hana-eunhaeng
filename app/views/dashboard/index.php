<?php $title = 'Dashboard - Hana-Eunhaeng'; ?>

<div class="container-lg">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2">Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
            <p class="text-muted">Here's what's happening with your money today.</p>
        </div>
        <div class="col-lg-4 text-end">
            <a href="/accounts/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Account
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-opacity-75 mb-1">Total Balance</p>
                            <h3 class="fw-bold mb-0">$<?php echo number_format($totalBalance, 2); ?></h3>
                        </div>
                        <i class="fas fa-wallet fa-2x text-opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Accounts</p>
                            <h3 class="fw-bold mb-0"><?php echo $accountCount; ?></h3>
                        </div>
                        <i class="fas fa-credit-card fa-2x text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Spent Today</p>
                            <h3 class="fw-bold mb-0">$<?php echo number_format($totalSpent, 2); ?></h3>
                        </div>
                        <i class="fas fa-arrow-up fa-2x text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Member Since</p>
                            <h3 class="fw-bold mb-0"><?php echo date('M Y', strtotime($user['created_at'])); ?></h3>
                        </div>
                        <i class="fas fa-calendar fa-2x text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounts Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2 text-primary"></i>Your Accounts</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($accounts)): ?>
                        <p class="text-muted text-center py-4">
                            <i class="fas fa-inbox fa-2x opacity-50 d-block mb-2"></i>
                            No accounts yet. <a href="/accounts/create" class="text-decoration-none">Create one</a>
                        </p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($accounts as $account): ?>
                                <a href="/accounts/view?id=<?php echo $account['id']; ?>" class="list-group-item list-group-item-action px-0 py-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($account['account_name']); ?></h6>
                                            <p class="text-muted small mb-0"><?php echo $account['account_number']; ?> • <?php echo ucfirst($account['account_type']); ?></p>
                                        </div>
                                        <div class="text-end">
                                            <h6 class="mb-1 fw-bold">$<?php echo number_format($account['balance'], 2); ?></h6>
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="/transactions/transfer" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-exchange-alt me-2"></i>Transfer Money
                    </a>
                    <a href="/accounts/create" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-plus-circle me-2"></i>Add Account
                    </a>
                    <a href="/transactions/history" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-history me-2"></i>View History
                    </a>
                    <a href="/dashboard/profile" class="btn btn-outline-primary w-100">
                        <i class="fas fa-user me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-info"></i>Recent Transactions</h5>
            <a href="/transactions/history" class="text-decoration-none">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentTransactions)): ?>
                <p class="text-muted text-center py-4">No transactions yet</p>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach (array_slice($recentTransactions, 0, 5) as $transaction): ?>
                        <div class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-light p-2 me-3">
                                        <i class="fas fa-<?php echo $transaction['transaction_type'] === 'transfer' ? 'exchange-alt' : ($transaction['transaction_type'] === 'deposit' ? 'arrow-down' : 'arrow-up'); ?> text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($transaction['description'] ?? ucfirst($transaction['transaction_type'])); ?></h6>
                                        <p class="text-muted small mb-0"><?php echo date('M d, Y', strtotime($transaction['created_at'])); ?></p>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-1 fw-bold <?php echo $transaction['amount'] > 0 ? 'text-danger' : 'text-success'; ?>">$<?php echo number_format(abs($transaction['amount']), 2); ?></h6>
                                    <span class="badge bg-<?php echo $transaction['status'] === 'completed' ? 'success' : 'warning'; ?>"><?php echo ucfirst($transaction['status']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
