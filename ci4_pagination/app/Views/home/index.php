<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electric Company - Customer Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px auto;
        }
        .header-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .header-section h1 {
            color: #667eea;
            font-weight: bold;
        }
        .stats-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
        }
        .stats-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }
        .stats-card p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        .card-total { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-active { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .card-inactive { background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%); }
        .card-suspended { background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%); }
        .search-filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .table-container {
            overflow-x: auto;
        }
        .badge-active { background-color: #28a745; }
        .badge-inactive { background-color: #dc3545; }
        .badge-suspended { background-color: #ffc107; color: #000; }
        .pagination {
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container">
            <!-- Header -->
            <div class="header-section">
                <h1><i class="bi bi-lightning-charge-fill text-warning"></i> Puihaha Electric Company</h1>
                <p class="text-muted">Customer Account Management System</p>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card card-total">
                        <h3><?= $total_accounts ?></h3>
                        <p>Total Accounts</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card card-active">
                        <h3><?= $active_accounts ?></h3>
                        <p>Active Accounts</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card card-inactive">
                        <h3><?= $inactive_accounts ?></h3>
                        <p>Inactive Accounts</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card card-suspended">
                        <h3><?= $suspended_accounts ?></h3>
                        <p>Suspended Accounts</p>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="search-filter-section">
                <form method="GET" action="<?= base_url() ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" placeholder="Search by name, account, email, phone..." value="<?= esc($search_keyword ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="active" <?= ($filter_status ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= ($filter_status ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="suspended" <?= ($filter_status ?? '') == 'suspended' ? 'selected' : '' ?>>Suspended</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="type">
                                <option value="">All Types</option>
                                <option value="residential" <?= ($filter_type ?? '') == 'residential' ? 'selected' : '' ?>>Residential</option>
                                <option value="commercial" <?= ($filter_type ?? '') == 'commercial' ? 'selected' : '' ?>>Commercial</option>
                                <option value="industrial" <?= ($filter_type ?? '') == 'industrial' ? 'selected' : '' ?>>Industrial</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button>
                        </div>
                    </div>
                </form>
                <?php if ($search_keyword || $filter_status || $filter_type): ?>
                    <div class="mt-2">
                        <a href="<?= base_url() ?>" class="btn btn-sm btn-secondary"><i class="bi bi-x-circle"></i> Clear Filters</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Customer Accounts Table -->
            <div class="table-container">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Account Number</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Connection Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($accounts)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No accounts found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($accounts as $account): ?>
                                <tr>
                                    <td><strong><?= esc($account['account_number']) ?></strong></td>
                                    <td><?= esc($account['customer_name']) ?></td>
                                    <td><?= esc($account['email']) ?></td>
                                    <td><?= esc($account['phone']) ?></td>
                                    <td><span class="badge bg-info"><?= ucfirst(esc($account['connection_type'])) ?></span></td>
                                    <td>
                                        <?php
                                        $badgeClass = 'badge-' . $account['status'];
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst(esc($account['status'])) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('account/' . $account['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pager): ?>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing page <?= $current_page ?> of <?= $pager->getPageCount() ?>
                    </div>
                    <div>
                        <?= $pager->links() ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>