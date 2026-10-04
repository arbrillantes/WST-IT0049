<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<section class="section-padding bg-light-custom">
    <div class="container account-form-container">
        <h1 class="h2 text-primary-custom">My Account</h1>
        <p class="text-muted">Welcome, <?= esc($customer['customer_name']) ?>.</p>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <div class="card mb-4"><div class="card-body p-4">
            <h2 class="h4 mb-3">Your profile</h2>
            <dl class="row account-details mb-0">
                <dt class="col-sm-4">Account number</dt><dd class="col-sm-8"><?= esc($customer['account_number']) ?></dd>
                <dt class="col-sm-4">Name</dt><dd class="col-sm-8"><?= esc($customer['customer_name']) ?></dd>
                <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><?= esc($customer['email']) ?></dd>
                <dt class="col-sm-4">Phone</dt><dd class="col-sm-8"><?= esc($customer['phone'] ?? '') ?></dd>
                <dt class="col-sm-4">Address</dt><dd class="col-sm-8"><?= esc(implode(', ', [$customer['address'], $customer['city'], $customer['state'], $customer['zip_code']])) ?></dd>
            </dl>
        </div></div>
        <p>Need help with your electricity account? Contact our team.</p>
        <a href="<?= site_url('contact') ?>" class="btn btn-primary">Contact us</a>
        <a href="<?= site_url('services') ?>" class="btn btn-outline-secondary">View services</a>
    </div>
</section>
<?= $this->endSection() ?>
