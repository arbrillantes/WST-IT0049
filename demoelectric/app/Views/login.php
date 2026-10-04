<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<?php
$adminLogin = $adminLogin ?? false;
$field = $adminLogin ? 'username' : 'email';
?>
<section class="section-padding bg-light-custom min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                            <h1 class="h2 text-primary-custom"><?= $adminLogin ? 'Admin Login' : 'Customer Login' ?></h1>
                            <p class="text-muted"><?= $adminLogin ? 'Sign in to manage customer accounts.' : 'Sign in to view your profile.' ?></p>
                        </div>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success" role="alert">
                                <?= esc(session()->getFlashdata('success')) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= esc(session()->getFlashdata('error')) ?>
                            </div>
                        <?php endif; ?>

                        <?php $validation = session()->getFlashdata('validation') ?? []; ?>
                        <form action="<?= site_url($adminLogin ? 'admin/login' : 'login') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="<?= $field ?>" class="form-label fw-semibold"><?= $adminLogin ? 'Username' : 'Email address' ?></label>
                                <input type="<?= $adminLogin ? 'text' : 'email' ?>" name="<?= $field ?>" id="<?= $field ?>"
                                    class="form-control form-control-lg <?= isset($validation[$field]) ? 'is-invalid' : '' ?>"
                                    value="<?= old($field) ?>" autocomplete="username" maxlength="<?= $adminLogin ? 50 : 100 ?>" required autofocus>
                                <?php if (isset($validation[$field])): ?>
                                    <div class="invalid-feedback"><?= esc($validation[$field]) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" id="password"
                                    class="form-control form-control-lg <?= isset($validation['password']) ? 'is-invalid' : '' ?>"
                                    autocomplete="current-password" required>
                                <?php if (isset($validation['password'])): ?>
                                    <div class="invalid-feedback"><?= esc($validation['password']) ?></div>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-right-to-bracket me-2"></i>Log in
                            </button>
                        </form>

                        <?php if ($adminLogin): ?>
                            <p class="text-center mt-4 mb-0"><a href="<?= site_url('login') ?>">Go to customer login</a></p>
                        <?php else: ?>
                            <p class="text-center text-muted mt-4 mb-0">No account? <a href="<?= site_url('register') ?>">Register as a customer</a>.</p>
                            <p class="text-center mt-2 mb-0"><a href="<?= site_url('admin/login') ?>">Admin login</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
