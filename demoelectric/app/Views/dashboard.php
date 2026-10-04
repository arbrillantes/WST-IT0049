<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<section class="section-padding bg-light-custom">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <span class="badge text-bg-primary mb-2">Admin dashboard</span>
                <h1 class="display-6 fw-bold text-primary-custom mb-1">Customer Accounts</h1>
                <p class="text-muted mb-0">Welcome, <?= esc(session()->get('userName')) ?>.</p>
            </div>
            <a href="<?= site_url('account/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2" aria-hidden="true"></i>Add customer
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="dashboard-stat bg-primary"><strong><?= esc($totalAccounts) ?></strong><span>Total accounts</span></div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="dashboard-stat bg-success"><strong><?= esc($activeAccounts) ?></strong><span>Active</span></div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="dashboard-stat bg-secondary"><strong><?= esc($inactiveAccounts) ?></strong><span>Inactive</span></div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="dashboard-stat bg-warning text-dark"><strong><?= esc($suspendedAccounts) ?></strong><span>Suspended</span></div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form action="<?= site_url('admin/dashboard') ?>" method="get" class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <label for="search" class="form-label">Search</label>
                        <input type="search" name="search" id="search" class="form-control"
                            placeholder="Name, account, email, or phone" value="<?= esc($filters['search']) ?>">
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All statuses</option>
                            <?php foreach (['active', 'inactive', 'suspended'] as $status): ?>
                                <option value="<?= $status ?>" <?= $filters['status'] === $status ? 'selected' : '' ?>>
                                    <?= ucfirst($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All types</option>
                            <?php foreach (['residential', 'commercial', 'industrial'] as $type): ?>
                                <option value="<?= $type ?>" <?= $filters['type'] === $type ? 'selected' : '' ?>>
                                    <?= ucfirst($type) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-search me-2"></i>Filter</button>
                        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Account</th>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th><span class="visually-hidden">Action</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($accounts === []): ?>
                                <tr><td colspan="6" class="text-center text-muted py-5">No customer accounts found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($accounts as $account): ?>
                                    <tr>
                                        <td class="fw-semibold"><?= esc($account['account_number']) ?></td>
                                        <td><?= esc($account['customer_name']) ?></td>
                                        <td>
                                            <div><?= esc($account['email'] ?: '—') ?></div>
                                            <small class="text-muted"><?= esc($account['phone'] ?: '—') ?></small>
                                        </td>
                                        <td><span class="badge text-bg-info"><?= esc(ucfirst($account['connection_type'])) ?></span></td>
                                        <td><span class="badge account-status-<?= esc($account['status']) ?>"><?= esc(ucfirst($account['status'])) ?></span></td>
                                        <td class="text-end text-nowrap">
                                            <a href="<?= site_url('account/' . $account['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="<?= site_url('account/' . $account['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <a href="<?= site_url('account/' . $account['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <p class="text-muted mt-3 mb-0"><?= esc($pager->getTotal('accounts')) ?> matching account(s)</p>
        <?php if ($pager->getPageCount('accounts') > 1): ?>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-2">
                <span class="text-muted">Page <?= esc($currentPage) ?> of <?= esc($pager->getPageCount('accounts')) ?></span>
                <?= $pager->only(['search', 'status', 'type'])->links('accounts', 'bootstrap') ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
