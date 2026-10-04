<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<section class="section-padding bg-light-custom min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" role="alert"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary mb-4">
                    <i class="fas fa-arrow-left me-2"></i>Back to dashboard
                </a>
                <a href="<?= site_url('account/' . $account['id'] . '/edit') ?>" class="btn btn-outline-primary mb-4">Edit</a>
                <a href="<?= site_url('account/' . $account['id'] . '/delete') ?>" class="btn btn-outline-danger mb-4">Delete</a>
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white p-4">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <div>
                                <small>Customer account</small>
                                <h1 class="h3 mb-0"><?= esc($account['account_number']) ?></h1>
                            </div>
                            <span class="badge account-status-<?= esc($account['status']) ?> fs-6">
                                <?= esc(ucfirst($account['status'])) ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row account-details mb-0">
                            <dt class="col-sm-4">Customer name</dt><dd class="col-sm-8"><?= esc($account['customer_name']) ?></dd>
                            <dt class="col-sm-4">Address</dt><dd class="col-sm-8"><?= esc($account['address']) ?></dd>
                            <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><?= esc($account['email'] ?: '—') ?></dd>
                            <dt class="col-sm-4">Phone</dt><dd class="col-sm-8"><?= esc($account['phone'] ?: '—') ?></dd>
                            <dt class="col-sm-4">Meter number</dt><dd class="col-sm-8"><?= esc($account['meter_number'] ?: '—') ?></dd>
                            <dt class="col-sm-4">Connection type</dt><dd class="col-sm-8"><?= esc(ucfirst($account['connection_type'])) ?></dd>
                            <dt class="col-sm-4">Created</dt><dd class="col-sm-8"><?= esc(date('M j, Y g:i A', strtotime($account['created_at']))) ?></dd>
                            <dt class="col-sm-4">Last updated</dt><dd class="col-sm-8"><?= esc(date('M j, Y g:i A', strtotime($account['updated_at']))) ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
