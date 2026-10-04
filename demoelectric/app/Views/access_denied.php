<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<section class="section-padding">
    <div class="container text-center">
        <h1>Access denied</h1>
        <p>Your account does not have access to this page.</p>
        <a class="btn btn-primary" href="<?= site_url('dashboard') ?>">Return to your dashboard</a>
    </div>
</section>
<?= $this->endSection() ?>
