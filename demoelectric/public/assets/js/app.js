/** 
 * PowerFlow Electric - Custom JavaScript 
 * Enhanced interactions and animations 
 */ 
 
document.addEventListener('DOMContentLoaded', function() { 
     
    // Initialize all components 
    initScrollAnimations(); 
    initFormEnhancements(); 
    initNavigationEffects(); 

initCounterAnimations(); 
    initParallaxEffects(); 
     
    /** 
     * Scroll-triggered animations 
     */ 
    function initScrollAnimations() { 
        const observerOptions = { 
            threshold: 0.1, 
            rootMargin: '0px 0px -50px 0px' 
        }; 
         
        const observer = new IntersectionObserver(function(entries) { 
            entries.forEach(entry => { 
                if (entry.isIntersecting) { 
                    entry.target.classList.add('animate-in'); 
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                     
                    // Stagger animation for multiple items 
                    const siblings = entry.target.parentElement.children; 
                    Array.from(siblings).forEach((sibling, index) => { 
                        if (sibling.classList.contains('feature-item')) { 
                            setTimeout(() => { 
                                sibling.style.opacity = '1'; 
                                sibling.style.transform = 'translateY(0)'; 
                            }, index * 100); 
                        } 
                    }); 
                } 
            }); 
}, observerOptions); 
         
        // Observe all animated elements 
        document.querySelectorAll(' .feature-item, .card, .timeline-item').forEach(el => { 
            el.style.opacity = '0'; 
            el.style.transform = 'translateY(20px)'; 
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease'; 
            observer.observe(el); 
        }); 
    } 
     
    /** 
     * Form enhancements 
     */ 
    function initFormEnhancements() { 
        // Floating labels effect 
        const formInputs = document.querySelectorAll(' .form-control, .form-select'); 
        formInputs.forEach(input => { 
            input.addEventListener('focus', function() { 
                this.parentElement.classList.add('focused'); 
            }); 
             
            input.addEventListener('blur', function() { 
                if (!this.value) { 
                    this.parentElement.classList.remove('focused'); 
                } 
            }); 
        }); 
         
// Real-time validation feedback 
        const emailInputs = document.querySelectorAll('input[type="email"]'); 
        emailInputs.forEach(input => { 
            input.addEventListener('input', function() { 
                const isValid = this.checkValidity(); 
                this.classList.toggle('is-valid', isValid && this.value.length > 0); 
                this.classList.toggle('is-invalid', !isValid && this.value.length > 0); 
            }); 
        }); 
         
        // Password strength indicator 
        const passwordInputs = document.querySelectorAll('input[type="password"]'); 
        passwordInputs.forEach(input => { 
            if (input.name === 'password' && input.closest('#registerForm')) { 
                input.addEventListener('input', function() { 
                    const strength = calculatePasswordStrength(this.value); 
                    updatePasswordStrengthIndicator(this, strength); 
                }); 
            } 
        }); 
    } 
     
    /** 
     * Navigation effects 
     */ 
    function initNavigationEffects() { 
        const navbar = document.querySelector(' .navbar'); 
        let lastScrollTop = 0; 
         
window.addEventListener('scroll', function() { 
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop; 
             
            // Add/remove scrolled class 
            if (scrollTop > 50) { 
                navbar.classList.add('scrolled'); 
            } else { 
                navbar.classList.remove('scrolled'); 
            } 
             
            // Hide/show navbar on scroll 
            if (scrollTop > lastScrollTop && scrollTop > 100) { 
                navbar.style.transform = 'translateY(-100%)'; 
            } else { 
                navbar.style.transform = 'translateY(0)'; 
            } 
             
            lastScrollTop = scrollTop; 
        }); 
         
        // Smooth scroll for anchor links 
        document.querySelectorAll('a[href^="#"]').forEach(anchor => { 
            anchor.addEventListener('click', function(e) { 
                e.preventDefault(); 
                const targetId = this.getAttribute('href').slice(1);
                const target = targetId ? document.getElementById(targetId) : null;
                if (target) { 
                    target.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
}); 
                } 
            }); 
        }); 
    } 
     
    /** 
     * Counter animations for statistics 
     */ 
    function initCounterAnimations() { 
        const counters = document.querySelectorAll(' .stat-item h2'); 
         
        const counterObserver = new IntersectionObserver(function(entries) { 
            entries.forEach(entry => { 
                if (entry.isIntersecting) { 
                    const counter = entry.target; 
                    const target = parseInt(counter.textContent.replace(/\D/g, '')); 
                    const suffix = counter.textContent.replace(/\d/g, ''); 
                     
                    animateCounter(counter, 0, target, suffix, 2000); 
                    counterObserver.unobserve(counter); 
                } 
            }); 
        }); 
         
        counters.forEach(counter => { 
            counterObserver.observe(counter); 
        }); 
    } 
/** 
     * Parallax effects 
     */ 
    function initParallaxEffects() { 
        const parallaxElements = document.querySelectorAll(' .hero-section'); 
         
        window.addEventListener('scroll', function() { 
            const scrolled = window.pageYOffset; 
            const rate = scrolled * -0.5; 
             
            parallaxElements.forEach(element => { 
                element.style.transform = `translateY(${rate}px)`; 
            }); 
        }); 
    } 
     
    /** 
     * Utility functions 
     */ 
    function calculatePasswordStrength(password) { 
        let strength = 0; 
         
        if (password.length >= 8) strength++; 
        if (/[a-z]/.test(password)) strength++; 
        if (/[A-Z]/.test(password)) strength++; 
        if (/[0-9]/.test(password)) strength++; 
        if (/[^A-Za-z0-9]/.test(password)) strength++; 
         
return strength; 
    } 
     
    function updatePasswordStrengthIndicator(input, strength) { 
        let indicator = input.parentElement.querySelector(' .password-strength'); 
         
        if (!indicator) { 
            indicator = document.createElement('div'); 
            indicator.className = 'password-strength mt-1'; 
            input.parentElement.appendChild(indicator); 
        } 
         
        const strengthLevels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong']; 
        const strengthColors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#28a745']; 
         
        if (input.value.length === 0) { 
            indicator.style.display = 'none'; 
            return; 
        } 
         
        indicator.style.display = 'block'; 
        indicator.innerHTML = ` 
            <div class="progress" style="height: 4px;"> 
                <div class="progress-bar" style="width: ${(strength / 5) * 100}%; background-color: 
${strengthColors[strength - 1] || strengthColors[0]}"></div> 
            </div> 
            <small class="text-muted">Password strength: ${strengthLevels[strength - 1] || 
strengthLevels[0]}</small> 
        `; 
} 
     
    function animateCounter(element, start, end, suffix, duration) { 
        const startTime = performance.now(); 
         
        function updateCounter(currentTime) { 
            const elapsed = currentTime - startTime; 
            const progress = Math.min(elapsed / duration, 1); 
             
            const current = Math.floor(start + (end - start) * easeOutQuart(progress)); 
            element.textContent = current + suffix; 
             
            if (progress < 1) { 
                requestAnimationFrame(updateCounter); 
            } 
        } 
         
        requestAnimationFrame(updateCounter); 
    } 
     
    function easeOutQuart(t) { 
        return 1 - (--t) * t * t * t; 
    } 
     
    // Loading states for forms 
    document.querySelectorAll('form').forEach(form => { 
        form.addEventListener('submit', function() { 
            const submitBtn = this.querySelector('button[type="submit"]'); 
            if (submitBtn && !submitBtn.disabled) { 
const originalText = submitBtn.innerHTML; 
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing... '; 
                submitBtn.disabled = true; 
                 
                // Re-enable after 5 seconds as fallback 
                setTimeout(() => { 
                    submitBtn.innerHTML = originalText; 
                    submitBtn.disabled = false; 
                }, 5000); 
            } 
        }); 
    }); 
     
    // Enhanced tooltips 
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')); 
    tooltipTriggerList.map(function(tooltipTriggerEl) { 
        return new bootstrap.Tooltip(tooltipTriggerEl); 
    }); 
     
    // Back to top button 
    const backToTopBtn = document.createElement('button'); 
    backToTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>'; 
    backToTopBtn.className = 'btn btn-primary position-fixed'; 
    backToTopBtn.style.cssText = ` 
        bottom: 20px; 
        right: 20px; 
        z-index: 1000; 
        border-radius: 50%; 
        width: 50px; 
height: 50px; 
        display: none; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.2); 
    `; 
     
    document.body.appendChild(backToTopBtn); 
     
    window.addEventListener('scroll', function() { 
        if (window.pageYOffset > 300) { 
            backToTopBtn.style.display = 'block'; 
        } else { 
            backToTopBtn.style.display = 'none'; 
        } 
    }); 
     
    backToTopBtn.addEventListener('click', function() { 
        window.scrollTo({ 
            top: 0, 
            behavior: 'smooth' 
        }); 
    }); 
     
    // Console welcome message 
    console.log('%cPowerFlow Electric', 'color: #1e40af; font-size: 24px; font-weight: bold;'); 
    console.log('%cWebsite powered by CodeIgniter 4', 'color: #f59e0b; font-size: 14px;'); 
     
}); 
 
// Service Worker registration (for future PWA features) 
if ('serviceWorker' in navigator) { 
    window.addEventListener('load', function() { 
        // navigator.serviceWorker.register('/sw.js'); 
    }); 
}
