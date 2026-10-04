<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
$editing = isset($account['id']);
$validation = session()->getFlashdata('validation') ?? [];
$fields = [
    'account_number' => ['Account number', 50, true],
    'customer_name' => ['Customer name', 201, true],
    'email' => ['Email address', 100, false],
    'phone' => ['Phone number', 20, false],
    'meter_number' => ['Meter number', 50, false],
];
?>
<section class="section-padding bg-light-custom">
    <div class="container account-form-container">
        <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary mb-4">Back to dashboard</a>
        <div class="card"><div class="card-body p-4 p-md-5">
            <h1 class="h2 text-primary-custom mb-3"><?= $editing ? 'Edit customer account' : 'Add customer account' ?></h1>
            <p class="text-muted">Fields marked * are required.</p>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php if ($validation): ?>
                <div class="alert alert-danger" role="alert">Please check the highlighted fields.</div>
            <?php endif; ?>
            <form method="post" action="<?= site_url($editing ? 'account/' . $account['id'] . '/update' : 'account') ?>">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <?php foreach ($fields as $name => [$label, $max, $required]): ?>
                        <div class="col-md-6">
                            <label class="form-label" for="<?= $name ?>"><?= $label ?><?= $required ? ' *' : '' ?></label>
                            <input class="form-control <?= isset($validation[$name]) ? 'is-invalid' : '' ?>"
                                type="<?= $name === 'email' ? 'email' : ($name === 'phone' ? 'tel' : 'text') ?>"
                                id="<?= $name ?>" name="<?= $name ?>" maxlength="<?= $max ?>"
                                value="<?= old($name, $account[$name] ?? '') ?>" <?= $required ? 'required' : '' ?>
                                <?= isset($validation[$name]) ? 'aria-invalid="true" aria-describedby="' . $name . '-error"' : '' ?>>
                            <?php if (isset($validation[$name])): ?>
                                <div class="invalid-feedback" id="<?= $name ?>-error"><?= esc($validation[$name]) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-12">
                        <label class="form-label" for="address">Address *</label>
                        <textarea class="form-control <?= isset($validation['address']) ? 'is-invalid' : '' ?>" id="address" name="address"
                            rows="3" minlength="5" maxlength="2000" required><?= old('address', $account['address'] ?? '') ?></textarea>
                        <?php if (isset($validation['address'])): ?>
                            <div class="invalid-feedback"><?= esc($validation['address']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php foreach (['connection_type' => ['residential', 'commercial', 'industrial'], 'status' => ['active', 'inactive', 'suspended']] as $name => $options): ?>
                        <div class="col-md-6">
                            <label class="form-label" for="<?= $name ?>"><?= $name === 'status' ? 'Status' : 'Connection type' ?> *</label>
                            <select class="form-select <?= isset($validation[$name]) ? 'is-invalid' : '' ?>" id="<?= $name ?>" name="<?= $name ?>" required>
                                <?php foreach ($options as $option): ?>
                                    <option value="<?= $option ?>" <?= old($name, $account[$name] ?? $options[0]) === $option ? 'selected' : '' ?>><?= ucfirst($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($validation[$name])): ?>
                                <div class="invalid-feedback"><?= esc($validation[$name]) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary"><?= $editing ? 'Save changes' : 'Create account' ?></button>
                    <a href="<?= site_url($editing ? 'account/' . $account['id'] : 'dashboard') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div></div>
    </div>
</section>
<?= $this->endSection() ?>
