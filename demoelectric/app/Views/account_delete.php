<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<section class="section-padding bg-light-custom">
    <div class="container account-form-container">
        <div class="card"><div class="card-body p-4 p-md-5">
            <h1 class="h2 text-danger">Delete customer account?</h1>
            <p>Delete <strong><?= esc($account['account_number']) ?></strong> for <strong><?= esc($account['customer_name']) ?></strong>?</p>
            <p class="text-muted">This permanently removes this customer record. This action cannot be undone.</p>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <form action="<?= site_url('account/' . $account['id'] . '/delete') ?>" method="post" class="d-flex gap-2">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger">Yes, delete account</button>
                <a href="<?= site_url('account/' . $account['id']) ?>" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div></div>
    </div>
</section>
<?= $this->endSection() ?>
