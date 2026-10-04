<?= $this->extend('layout') ?> 
 
<?= $this->section('content') ?> 
 
<!-- Page Header --> 
<section class="hero-section"> 
    <div class="container"> 
        <div class="row text-center"> 
            <div class="col-lg-8 mx-auto"> 
                <h1 class="display-4 fw-bold mb-4">Contact Puihaha Electric</h1> 
                <p class="lead">Get in touch with our expert team for all your electrical needs. We're here 
to help 24/7.</p> 
            </div> 
        </div> 
    </div> 
</section> 
 
<!-- Contact Information --> 
<section class="section-padding"> 
    <div class="container"> 
        <div class="row g-4 mb-5"> 
            <div class="col-lg-3 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-map-marker-alt"></i> 
                    </div> 
                    <h4 class="text-primary-custom mb-3">Visit Our Office</h4> 
                    <p class="text-muted mb-0">123 Electric Avenue<br>Power City, PC 12345<br>United 
States</p> 
                </div> 
            </div> 
            <div class="col-lg-3 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-phone"></i> 
                    </div> 
                    <h4 class="text-primary-custom mb-3">Call Us</h4> 
                    <p class="text-muted mb-2"><strong>Main:</strong> (555) 123-4567</p> 
                    <p class="text-muted mb-0"><strong>Emergency:</strong> (555) 123-4567</p> 
                </div> 
            </div> 
            <div class="col-lg-3 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-envelope"></i> 
                    </div> 
                    <h4 class="text-primary-custom mb-3">Email Us</h4> 
<p class="text-muted mb-
2"><strong>General:</strong><br>info@Puihahaelectric.com</p> 
                    <p class="text-muted mb-
0"><strong>Emergency:</strong><br>emergency@Puihahaelectric.com</p> 
                </div> 
            </div> 
            <div class="col-lg-3 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-clock"></i> 
                    </div> 
                    <h4 class="text-primary-custom mb-3">Business Hours</h4> 
                    <p class="text-muted mb-2"><strong>Mon-Fri:</strong> 7:00 AM - 6:00 PM</p> 
                    <p class="text-muted mb-2"><strong>Saturday:</strong> 8:00 AM - 4:00 PM</p> 
                    <p class="text-muted mb-0"><strong>Emergency:</strong> 24/7</p> 
                </div> 
            </div> 
        </div> 
    </div> 
</section> 
 
<!-- Contact Form Section --> 
<section class="section-padding bg-light-custom"> 
    <div class="container"> 
        <div class="row"> 
            <div class="col-lg-8 mx-auto"> 
                <div class="card shadow-lg border-0"> 
                    <div class="card-body p-5"> 
                        <div class="text-center mb-4"> 
<h2 class="display-6 fw-bold text-primary-custom mb-3">Get Your Free Quote</h2> 
                            <p class="lead text-muted">Fill out the form below and we'll get back to you within 24 
hours with a detailed quote for your project.</p> 
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
                         
                        <form method="POST" action="<?= base_url('contact') ?>" id="contactForm"> 
                            <?= csrf_field() ?> 
                             
                            <div class="row g-3"> 
                                <div class="col-md-6"> 
                                    <label for="name" class="form-label fw-semibold">Full Name *</label> 
                                    <input type="text" class="form-control form-control-lg <?= 
isset($validation['name']) ? 'is-invalid' : '' ?>"  
                                           id="name" name="name" value="<?= old('name') ?>" required> 
                                    <?php if (isset($validation['name'])): ?> 
<div class="invalid-feedback"><?= esc($validation['name']) ?></div> 
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
                                <div class="col-md-6"> 
                                    <label for="service_type" class="form-label fw-semibold">Service Type *</label> 
                                    <select class="form-select form-select-lg <?= isset($validation['service_type']) ? 
'is-invalid' : '' ?>"  
                                            id="service_type" name="service_type" required> 
                                        <option value="">Select a service...</option> 
                                        <option value="residential_wiring" <?= old('service_type') == 'residential_wiring' 
? 'selected' : '' ?>>Residential Wiring</option> 
<option value="commercial_electrical" <?= old('service_type') == 
'commercial_electrical' ? 'selected' : '' ?>>Commercial Electrical</option> 
                                        <option value="panel_upgrade" <?= old('service_type') == 'panel_upgrade' ? 
'selected' : '' ?>>Panel Upgrade</option> 
                                        <option value="lighting_installation" <?= old('service_type') == 
'lighting_installation' ? 'selected' : '' ?>>Lighting Installation</option> 
                                        <option value="smart_home" <?= old('service_type') == 'smart_home' ? 
'selected' : '' ?>>Smart Home Automation</option> 
                                        <option value="solar_installation" <?= old('service_type') == 'solar_installation' ? 
'selected' : '' ?>>Solar Installation</option> 
                                        <option value="ev_charging" <?= old('service_type') == 'ev_charging' ? 'selected' 
: '' ?>>EV Charging Station</option> 
                                        <option value="emergency_repair" <?= old('service_type') == 'emergency_repair' 
? 'selected' : '' ?>>Emergency Repair</option> 
                                        <option value="maintenance" <?= old('service_type') == 'maintenance' ? 
'selected' : '' ?>>Maintenance Service</option> 
                                        <option value="other" <?= old('service_type') == 'other' ? 'selected' : '' 
?>>Other</option> 
                                    </select> 
                                    <?php if (isset($validation['service_type'])): ?> 
                                        <div class="invalid-feedback"><?= esc($validation['service_type']) ?></div> 
                                    <?php endif; ?> 
                                </div> 
                                <div class="col-12"> 
                                    <label for="message" class="form-label fw-semibold">Project Details *</label> 
                                    <textarea class="form-control <?= isset($validation['message']) ? 'is-invalid' : '' ?>"  
                                              id="message" name="message" rows="5"  
                                              placeholder="Please describe your electrical project, including location, 
timeline, and any specific requirements... " required><?= old('message') ?></textarea> 
                                    <?php if (isset($validation['message'])): ?> 
                                        <div class="invalid-feedback"><?= esc($validation['message']) ?></div> 
                                    <?php endif; ?> 
</div> 
                                <div class="col-12"> 
                                    <div class="form-check"> 
                                        <input class="form-check-input" type="checkbox" id="consent" required> 
                                        <label class="form-check-label text-muted" for="consent"> 
                                            I agree to be contacted by Puihaha Electric regarding my inquiry and 
understand that my information will be kept confidential. 
                                        </label> 
                                    </div> 
                                </div> 
                                <div class="col-12 text-center"> 
                                    <button type="submit" class="btn btn-primary btn-lg px-5"> 
                                        <i class="fas fa-paper-plane me-2"></i>Send Message 
                                    </button> 
                                </div> 
                            </div> 
                        </form> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</section> 
 
<!-- Emergency Contact Section --> 
<section class="section-padding bg-danger text-white"> 
    <div class="container"> 
        <div class="row text-center"> 
            <div class="col-lg-8 mx-auto"> 
<h2 class="display-5 fw-bold mb-4"> 
                    <i class="fas fa-exclamation-triangle text-warning me-3"></i>Electrical Emergency? 
                </h2> 
                <p class="lead mb-4">Don't wait! Electrical emergencies can be dangerous. Call our 24/7 
emergency hotline for immediate assistance.</p> 
                <div class="d-flex flex-wrap justify-content-center gap-3"> 
                    <a href="tel:5551234567" class="btn btn-warning btn-lg"> 
                        <i class="fas fa-phone me-2"></i>Emergency: (555) 123-4567 
                    </a> 
                    <a href="mailto:emergency@Puihahaelectric.com" class="btn btn-outline-light btn-lg"> 
                        <i class="fas fa-envelope me-2"></i>Emergency Email 
                    </a> 
                </div> 
                <div class="mt-4"> 
                    <p class="mb-0"><small>Available 24 hours a day, 7 days a week for electrical 
emergencies</small></p> 
                </div> 
            </div> 
        </div> 
    </div> 
</section> 
 
<!-- Service Areas --> 
<section class="section-padding"> 
    <div class="container"> 
        <div class="row text-center mb-5"> 
            <div class="col-lg-8 mx-auto"> 
                <h2 class="display-5 fw-bold text-primary-custom mb-3">Service Areas</h2> 
                <p class="lead text-muted">We proudly serve the following areas with professional 
electrical services</p> 
</div> 
        </div> 
        <div class="row g-4"> 
            <div class="col-lg-4 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-city"></i> 
                    </div> 
                    <h4 class="text-primary-custom mb-3">Metropolitan Area</h4> 
                    <ul class="list-unstyled text-muted"> 
                        <li>Power City</li> 
                        <li>Electric Heights</li> 
                        <li>Voltage Valley</li> 
                        <li>Current Creek</li> 
                    </ul> 
                </div> 
            </div> 
            <div class="col-lg-4 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-home"></i> 
                    </div> 
                    <h4 class="text-primary-custom mb-3">Suburban Communities</h4> 
                    <ul class="list-unstyled text-muted"> 
                        <li>Residential Ridge</li> 
                        <li>Family Fields</li> 
                        <li>Suburban Springs</li> 
                        <li>Neighborhood Nook</li> 
                    </ul> 
</div> 
            </div> 
            <div class="col-lg-4 col-md-6"> 
                <div class="card h-100 text-center p-4 feature-item"> 
                    <div class="feature-icon"> 
                        <i class="fas fa-industry"></i> 
                    </div> 
                    <h4 class="text-primary-custom mb-3">Industrial Zones</h4> 
                    <ul class="list-unstyled text-muted"> 
                        <li>Manufacturing Mile</li> 
                        <li>Industrial Park</li> 
                        <li>Business Boulevard</li> 
                        <li>Commerce Center</li> 
                    </ul> 
                </div> 
            </div> 
        </div> 
        <div class="text-center mt-5"> 
            <p class="text-muted">Don't see your area listed? <a href="#contactForm" class="text-
primary-custom fw-semibold">Contact us</a> to check if we service your location.</p> 
        </div> 
    </div> 
</section> 
 
<!-- Map Section (Placeholder) --> 
<section class="section-padding bg-light-custom"> 
    <div class="container"> 
        <div class="row text-center mb-4"> 
            <div class="col-lg-8 mx-auto"> 
<h2 class="display-5 fw-bold text-primary-custom mb-3">Find Us</h2> 
                <p class="lead text-muted">Visit our office or call to schedule an on-site consultation</p> 
            </div> 
        </div> 
        <div class="row"> 
            <div class="col-12"> 
                <div class="card"> 
                    <div class="card-body p-0"> 
                        <!-- In a real application, you would embed Google Maps or another map service --> 
                        <div class="bg-secondary d-flex align-items-center justify-content-center text-white" 
style="height: 400px;"> 
                            <div class="text-center"> 
                                <i class="fas fa-map-marked-alt mb-3" style="font-size: 4rem;"></i> 
                                <h4>Interactive Map</h4> 
                                <p class="mb-0">123 Electric Avenue, Power City, PC 12345</p> 
                                <a href="https://maps.google.com" target="_blank" class="btn btn-primary mt-3"> 
                                    <i class="fas fa-directions me-2"></i>Get Directions 
                                </a> 
                            </div> 
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
    const form = document.getElementById('contactForm'); 
    const submitBtn = form.querySelector('button[type="submit"]'); 
     
    form.addEventListener('submit', function(e) { 
        // Add loading state 
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending... '; 
        submitBtn.disabled = true; 
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
}); 
</script> 
 
<?= $this->endSection() ?>
