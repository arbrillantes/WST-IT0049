<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Puihaha Electric') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet">
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #f59e0b;
            --accent-color: #10b981;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--secondary-color) !important;
        }

        .navbar-nav .nav-link.active {
            color: var(--primary-color) !important;
            font-weight: 600;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            color: white;
            padding: 100px 0;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #d97706;
            border-color: #d97706;
            transform: translateY(-2px);
        }

        .btn-outline-light {
            border-width: 2px;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            transform: translateY(-2px);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 50px 0 20px;
        }

        .footer h5 {
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        .footer a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--secondary-color);
        }

        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .section-padding {
            padding: 80px 0;
        }

        .text-primary-custom {
            color: var(--primary-color) !important;
        }

        .text-secondary-custom {
            color: var(--secondary-color) !important;
        }

        .bg-light-custom {
            background-color: var(--light-color) !important;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-bolt text-warning me-2"></i>Puihaha Electric
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($page) && $page == 'home') ? 'active' : '' ?>" href="<?=
                                                                                                            base_url() ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($page) && $page == 'about') ? 'active' : '' ?>" href="<?=
                                                                                                            base_url('about') ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($page) && $page == 'services') ? 'active' : '' ?>" href="<?=
                                                                                                                base_url('services') ?>">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($page) && $page == 'contact') ? 'active' : '' ?>" href="<?=
                                                                                                                base_url('contact') ?>">Contact</a>
                    </li>
                    <?php if (session()->get('isLoggedIn') === true): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= in_array($page ?? '', ['dashboard', 'customer'], true) ? 'active' : '' ?>"
                                href="<?= site_url('dashboard') ?>"><?= session()->get('userType') === 'admin' ? 'Admin dashboard' : 'My account' ?></a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <form action="<?= base_url('logout') ?>" method="post" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-link nav-link border-0">Logout</button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') === 'register' ? 'active' : '' ?>"
                                href="<?= base_url('register') ?>">Customer Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($page ?? '') === 'login' ? 'active' : '' ?>"
                                href="<?= base_url('login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link admin-login-link <?= ($page ?? '') === 'admin_login' ? 'active' : '' ?>"
                                href="<?= site_url('admin/login') ?>">ADMIN LOGIN</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-bolt text-warning me-2"></i>Puihaha Electric</h5>
                    <p class="mb-3">Providing reliable and sustainable electrical solutions for over 25 years.
                        Your trusted partner for all electrical needs.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url() ?>">Home</a></li>
                        <li><a href="<?= base_url('about') ?>">About</a></li>
                        <li><a href="<?= base_url('services') ?>">Services</a></li>
                        <li><a href="<?= base_url('contact') ?>">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Residential Wiring</a></li>
                        <li><a href="#">Commercial Installation</a></li>
                        <li><a href="#">Emergency Repairs</a></li>
                        <li><a href="#">Solar Solutions</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i>123 Electric Avenue, Power City, PC
                            12345</li>
                        <li><i class="fas fa-phone me-2"></i>(555) 123-4567</li>
                        <li><i class="fas fa-envelope me-2"></i>info@Puihahaelectric.com</li>
                        <li><i class="fas fa-clock me-2"></i>24/7 Emergency Service</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> Puihaha Electric. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Licensed & Insured | License #EL123456</p>
                </div>
            </div>
        </div>
    </footer>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>

</html>
