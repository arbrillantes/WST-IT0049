<?= $this->extend('layout') ?> 
 
<?= $this->section('content') ?> 
 
<!-- Page Header --> 
<section class="hero-section"> 
    <div class="container"> 
        <div class="row text-center"> 
            <div class="col-lg-8 mx-auto"> 
                <h1 class="display-4 fw-bold mb-4">Join Puihaha Electric</h1> 
                <p class="lead">Register to access exclusive customer benefits, service history, and 
priority scheduling</p> 
            </div> 
        </div> 
    </div> 
</section> 
 
<!-- Registration Benefits --> 
<section class="section-padding"> 
    <div class="container"> 
        <div class="row text-center mb-5"> 
            <div class="col-lg-8 mx-auto"> 
                <h2 class="display-5 fw-bold text-primary-custom mb-3">Customer Benefits</h2> 
                <p class="lead text-muted">Join thousands of satisfied customers and enjoy exclusive 
benefits</p> 
            </div> 
        </div> 
        <div class="row g-4 mb-5"> 
            <div class="col-lg-3 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
<i class="fas fa-calendar-check"></i> 
                    </div> 
                    <h5 class="text-primary-custom mb-2">Priority Scheduling</h5> 
                    <p class="text-muted small">Get priority booking for all electrical services and 
maintenance</p> 
                </div> 
            </div> 
            <div class="col-lg-3 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-percentage"></i> 
                    </div> 
                    <h5 class="text-primary-custom mb-2">Exclusive Discounts</h5> 
                    <p class="text-muted small">Access to member-only discounts and seasonal 
promotions</p> 
                </div> 
            </div> 
            <div class="col-lg-3 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-history"></i> 
                    </div> 
                    <h5 class="text-primary-custom mb-2">Service History</h5> 
                    <p class="text-muted small">Track all your electrical services and maintenance 
records</p> 
                </div> 
            </div> 
            <div class="col-lg-3 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
<i class="fas fa-headset"></i> 
                    </div> 
                    <h5 class="text-primary-custom mb-2">24/7 Support</h5> 
                    <p class="text-muted small">Dedicated customer support and emergency 
assistance</p> 
                </div> 
            </div> 
        </div> 
    </div> 
</section> 
 
<!-- Registration Form --> 
<section class="section-padding bg-light-custom"> 
    <div class="container"> 
        <div class="row"> 
            <div class="col-lg-8 mx-auto"> 
                <div class="card shadow-lg border-0"> 
                    <div class="card-body p-5"> 
                        <div class="text-center mb-4"> 
                            <h2 class="display-6 fw-bold text-primary-custom mb-3">Create Your Account</h2> 
                            <p class="lead text-muted">Join the Puihaha Electric family today</p> 
                        </div> 
                         
                        <?php if (isset($success) && $success): ?> 
                            <div class="alert alert-success alert-dismissible fade show" role="alert"> 
                                <i class="fas fa-check-circle me-2"></i><?= esc($success) ?> 
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button> 
                            </div> 
                        <?php endif; ?> 
<?php if (isset($error) && $error): ?> 
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                                <i class="fas fa-exclamation-circle me-2"></i><?= esc($error) ?> 
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button> 
                            </div> 
                        <?php endif; ?> 
                         
                        <form method="POST" action="<?= base_url('register') ?>" id="registerForm"> 
                            <?= csrf_field() ?> 
                             
                            <!-- Personal Information --> 
                            <div class="mb-4"> 
                                <h4 class="text-primary-custom mb-3"> 
                                    <i class="fas fa-user me-2"></i>Personal Information 
                                </h4> 
                                <div class="row g-3"> 
                                    <div class="col-md-6"> 
                                        <label for="first_name" class="form-label fw-semibold">First Name *</label> 
                                        <input type="text" class="form-control form-control-lg <?= 
isset($validation['first_name']) ? 'is-invalid' : '' ?>"  
                                               id="first_name" name="first_name" value="<?= old('first_name') ?>" 
required> 
                                        <?php if (isset($validation['first_name'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['first_name']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                    <div class="col-md-6"> 
                                        <label for="last_name" class="form-label fw-semibold">Last Name *</label> 
<input type="text" class="form-control form-control-lg <?= 
isset($validation['last_name']) ? 'is-invalid' : '' ?>"  
                                               id="last_name" name="last_name" value="<?= old('last_name') ?>" 
required> 
                                        <?php if (isset($validation['last_name'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['last_name']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                    <div class="col-md-6"> 
                                        <label for="email" class="form-label fw-semibold">Email Address *</label> 
                                        <input type="email" class="form-control form-control-lg <?= 
isset($validation['email']) ? 'is-invalid' : '' ?>"  
                                               id="email" name="email" value="<?= old('email') ?>" required> 
                                        <?php if (isset($validation['email'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['email']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                    <div class="col-md-6"> 
                                        <label for="phone" class="form-label fw-semibold">Phone Number *</label> 
                                        <input type="tel" class="form-control form-control-lg <?= 
isset($validation['phone']) ? 'is-invalid' : '' ?>"  
                                               id="phone" name="phone" value="<?= old('phone') ?>" required> 
                                        <?php if (isset($validation['phone'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['phone']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                </div> 
                            </div> 
                             
                            <!-- Address Information --> 
<div class="mb-4"> 
                                <h4 class="text-primary-custom mb-3"> 
                                    <i class="fas fa-map-marker-alt me-2"></i>Address Information 
                                </h4> 
                                <div class="row g-3"> 
                                    <div class="col-12"> 
                                        <label for="address" class="form-label fw-semibold">Street Address *</label> 
                                        <input type="text" class="form-control form-control-lg <?= 
isset($validation['address']) ? 'is-invalid' : '' ?>"  
                                               id="address" name="address" value="<?= old('address') ?>"  
                                               placeholder="123 Main Street" required> 
                                        <?php if (isset($validation['address'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['address']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                    <div class="col-md-4"> 
                                        <label for="city" class="form-label fw-semibold">City *</label> 
                                        <input type="text" class="form-control form-control-lg <?= 
isset($validation['city']) ? 'is-invalid' : '' ?>"  
                                               id="city" name="city" value="<?= old('city') ?>" required> 
                                        <?php if (isset($validation['city'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['city']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                    <div class="col-md-4"> 
                                        <label for="state" class="form-label fw-semibold">Province / State *</label>
                                        <input type="text" class="form-control form-control-lg <?= isset($validation['state']) ? 'is-invalid' : '' ?>"
                                            id="state" name="state" value="<?= old('state') ?>" minlength="2" maxlength="50" required> 
                                        <?php if (isset($validation['state'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['state']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                    <div class="col-md-4"> 
                                        <label for="zip_code" class="form-label fw-semibold">ZIP Code *</label> 
                                        <input type="text" class="form-control form-control-lg <?= 
isset($validation['zip_code']) ? 'is-invalid' : '' ?>"  
                                               id="zip_code" name="zip_code" value="<?= old('zip_code') ?>"  
                                               placeholder="12345" required> 
                                        <?php if (isset($validation['zip_code'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['zip_code']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
</div> 
                            </div> 
                             
                            <!-- Account Security --> 
                            <div class="mb-4"> 
                                <h4 class="text-primary-custom mb-3"> 
                                    <i class="fas fa-lock me-2"></i>Account Security 
                                </h4> 
                                <div class="row g-3"> 
                                    <div class="col-md-6"> 
                                        <label for="password" class="form-label fw-semibold">Password *</label> 
                                        <input type="password" class="form-control form-control-lg <?= 
isset($validation['password']) ? 'is-invalid' : '' ?>"  
                                               id="password" name="password" minlength="8" maxlength="72" autocomplete="new-password" required> 
                                        <div class="form-text">Password must be at least 8 characters long</div> 
                                        <?php if (isset($validation['password'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['password']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                    <div class="col-md-6"> 
                                        <label for="confirm_password" class="form-label fw-semibold">Confirm 
Password *</label> 
                                        <input type="password" class="form-control form-control-lg <?= 
isset($validation['confirm_password']) ? 'is-invalid' : '' ?>"  
                                               id="confirm_password" name="confirm_password" maxlength="72" autocomplete="new-password" required> 
                                        <?php if (isset($validation['confirm_password'])): ?> 
                                            <div class="invalid-feedback"><?= esc($validation['confirm_password']) ?></div> 
                                        <?php endif; ?> 
                                    </div> 
                                </div> 
</div> 
                             
                            <!-- Terms and Conditions --> 
                            <div class="mb-4"> 
                                <div class="form-check"> 
                                    <input class="form-check-input <?= isset($validation['terms']) ? 'is-invalid' : '' ?>"  
                                           type="checkbox" id="terms" name="terms" required> 
                                    <label class="form-check-label" for="terms"> 
                                        I agree to the <a href="#" class="text-primary-custom">Terms of Service</a> 
and  
                                        <a href="#" class="text-primary-custom">Privacy Policy</a> * 
                                    </label> 
                                    <?php if (isset($validation['terms'])): ?> 
                                        <div class="invalid-feedback d-block"><?= esc($validation['terms']) ?></div> 
                                    <?php endif; ?> 
                                </div> 
                                <div class="form-check mt-2"> 
                                    <input class="form-check-input" type="checkbox" id="newsletter" 
name="newsletter"> 
                                    <label class="form-check-label" for="newsletter"> 
                                        Subscribe to our newsletter for electrical tips and special offers 
                                    </label> 
                                </div> 
                            </div> 
                             
                            <div class="text-center"> 
                                <button type="submit" class="btn btn-primary btn-lg px-5"> 
                                    <i class="fas fa-user-plus me-2"></i>Create Account 
                                </button> 
</div> 
                        </form> 
                         
                        <div class="text-center mt-4"> 
                            <p class="text-muted">Already have an account? <a href="<?= base_url('login') ?>"
class="text-primary-custom fw-semibold">Log in</a>.</p>
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</section> 
 
<!-- Security & Privacy --> 
<section class="section-padding"> 
    <div class="container"> 
        <div class="row text-center"> 
            <div class="col-lg-8 mx-auto"> 
                <h2 class="display-5 fw-bold text-primary-custom mb-4">Your Privacy & Security</h2> 
                <p class="lead text-muted mb-4">We take your privacy seriously and use industry-standard 
security measures to protect your personal information.</p> 
                <div class="row g-4"> 
                    <div class="col-md-4"> 
                        <div class="security-item"> 
                            <i class="fas fa-shield-alt text-success mb-3" style="font-size: 3rem;"></i> 
                            <h5 class="text-primary-custom">SSL Encryption</h5> 
                            <p class="text-muted small">All data is encrypted using 256-bit SSL security</p> 
                        </div> 
</div> 
                    <div class="col-md-4"> 
                        <div class="security-item"> 
                            <i class="fas fa-user-shield text-success mb-3" style="font-size: 3rem;"></i> 
                            <h5 class="text-primary-custom">Privacy Protected</h5> 
                            <p class="text-muted small">We never share your information with third parties</p> 
                        </div> 
                    </div> 
                    <div class="col-md-4"> 
                        <div class="security-item"> 
                            <i class="fas fa-lock text-success mb-3" style="font-size: 3rem;"></i> 
                            <h5 class="text-primary-custom">Secure Storage</h5> 
                            <p class="text-muted small">Your data is stored in secure, encrypted databases</p> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</section> 
 
<script> 
document.addEventListener('DOMContentLoaded', function() { 
    // Form validation 
    const form = document.getElementById('registerForm'); 
    const submitBtn = form.querySelector('button[type="submit"]'); 
    const password = document.getElementById('password'); 
    const confirmPassword = document.getElementById('confirm_password'); 
     
// Password matching validation 
    confirmPassword.addEventListener('input', function() { 
        if (password.value !== confirmPassword.value) { 
            confirmPassword.setCustomValidity('Passwords do not match'); 
        } else { 
            confirmPassword.setCustomValidity(''); 
        } 
    }); 
     
    // Phone number formatting 
    const phoneInput = document.getElementById('phone'); 
    phoneInput.addEventListener('input', function(e) { 
        let value = e.target.value.replace(/\D/g, ''); 
        if (value.length >= 6) { 
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3'); 
        } else if (value.length >= 3) { 
            value = value.replace(/(\d{3})(\d{0,3})/, '($1) $2'); 
        } 
        e.target.value = value; 
    }); 
     
    // ZIP code formatting 
    const zipInput = document.getElementById('zip_code'); 
    zipInput.addEventListener('input', function(e) { 
        let value = e.target.value.replace(/\D/g, ''); 
        if (value.length > 5) { 
            value = value.replace(/(\d{5})(\d{0,4})/, '$1-$2'); 
        } 
        e.target.value = value; 
}); 
     
    // Form submission 
    form.addEventListener('submit', function(e) { 
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account... '; 
        submitBtn.disabled = true; 
    }); 
}); 
</script> 
 
<?= $this->endSection() ?>
