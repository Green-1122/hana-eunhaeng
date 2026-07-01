<?php $title = 'Transactions - Hana-Eunhaeng'; ?>

<div class="container-lg">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2">Transaction History</h1>
            <p class="text-muted">Review all your past and pending transactions.</p>
        </div>
        <div class="col-lg-4 text-end">
            <a href="/transactions/transfer" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane me-2"></i>New Transfer
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" class="form-control" placeholder="Search transactions..." id="searchInput">
                </div>
                <div class="col-md-3 mb-2">
                    <select class="form-select" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="deposit">Deposits</option>
                        <option value="withdrawal">Withdrawals</option>
                        <option value="transfer">Transfers</option>
                        <option value="payment">Payments</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button class="btn btn-outline-secondary w-100"><i class="fas fa-download me-2"></i>Export</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <?php if (empty($transactions)): ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="fas fa-inbox fa-4x text-muted opacity-50 mb-3 d-block"></i>
                <h4 class="fw-bold mb-2">No Transactions</h4>
                <p class="text-muted mb-0">You haven't made any transactions yet. Start by making a transfer.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($transaction['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark"><?php echo ucfirst($transaction['transaction_type']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($transaction['description'] ?? '', 0, 30)); ?></td>
                                    <td class="fw-bold">
                                        <span class="<?php echo $transaction['amount'] > 0 ? 'text-danger' : 'text-success'; ?>">
                                            <?php echo ($transaction['amount'] > 0 ? '-' : '+'); ?>$<?php echo number_format(abs($transaction['amount']), 2); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $transaction['status'] === 'completed' ? 'success' : ($transaction['status'] === 'pending' ? 'warning' : 'danger'); ?>">
                                            <?php echo ucfirst($transaction['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/transactions/view?id=<?php echo $transaction['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
