/**
 * Template Name: iPortfolio - v3.7.0
 * Template URL: https://bootstrapmade.com/iportfolio-bootstrap-portfolio-websites-template/
 * Author: BootstrapMade.com
 * License: https://bootstrapmade.com/license/
 */
(function() {
    "use strict";

    /**
     * Easy selector helper function
     */
    const select = (el, all = false) => {
        el = el.trim()
        if (all) {
            return [...document.querySelectorAll(el)]
        } else {
            return document.querySelector(el)
        }
    }

    /**
     * Easy event listener function
     */
    const on = (type, el, listener, all = false) => {
        let selectEl = select(el, all)
        if (selectEl) {
            if (all) {
                selectEl.forEach(e => e.addEventListener(type, listener))
            } else {
                selectEl.addEventListener(type, listener)
            }
        }
    }

    /**
     * Easy on scroll event listener 
     */
    const onscroll = (el, listener) => {
        el.addEventListener('scroll', listener)
    }

    /**
     * Navbar links active state on scroll
     */
    let navbarlinks = select('#navbar .scrollto', true)
    const navbarlinksActive = () => {
        let position = window.scrollY + 200
        navbarlinks.forEach(navbarlink => {
            if (!navbarlink.hash) return
            let section = select(navbarlink.hash)
            if (!section) return
            if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
                navbarlink.classList.add('active')
            } else {
                navbarlink.classList.remove('active')
            }
        })
    }
    window.addEventListener('load', navbarlinksActive)
    onscroll(document, navbarlinksActive)

    /**
     * Scrolls to an element with header offset
     */
    const scrollto = (el) => {
        let elementPos = select(el).offsetTop
        window.scrollTo({
            top: elementPos,
            behavior: 'smooth'
        })
    }

    /**
     * Back to top button
     */
    let backtotop = select('.back-to-top')
    if (backtotop) {
        const toggleBacktotop = () => {
            if (window.scrollY > 100) {
                backtotop.classList.add('active')
            } else {
                backtotop.classList.remove('active')
            }
        }
        window.addEventListener('load', toggleBacktotop)
        onscroll(document, toggleBacktotop)
    }

    /**
     * Mobile nav toggle
     */
    on('click', '.mobile-nav-toggle', function(e) {
        select('body').classList.toggle('mobile-nav-active')
        this.classList.toggle('bi-list')
        this.classList.toggle('bi-x')
    })

    /**
     * Scrool with ofset on links with a class name .scrollto
     */
    on('click', '.scrollto', function(e) {
        if (select(this.hash)) {
            e.preventDefault()

            let body = select('body')
            if (body.classList.contains('mobile-nav-active')) {
                body.classList.remove('mobile-nav-active')
                let navbarToggle = select('.mobile-nav-toggle')
                navbarToggle.classList.toggle('bi-list')
                navbarToggle.classList.toggle('bi-x')
            }
            scrollto(this.hash)
        }
    }, true)

    /**
     * Scroll with ofset on page load with hash links in the url
     */
    window.addEventListener('load', () => {
        if (window.location.hash) {
            if (select(window.location.hash)) {
                scrollto(window.location.hash)
            }
        }
    });

    /**
     * Hero type effect
     */
    const typed = select('.typed')
    if (typed) {
        let typed_strings = typed.getAttribute('data-typed-items')
        typed_strings = typed_strings.split(',')
        new Typed('.typed', {
            strings: typed_strings,
            loop: true,
            typeSpeed: 100,
            backSpeed: 50,
            backDelay: 2000
        });
    }

    /**
     * Skills animation
     */
    let skilsContent = select('.skills-content');
    if (skilsContent) {
        new Waypoint({
            element: skilsContent,
            offset: '80%',
            handler: function(direction) {
                let progress = select('.progress .progress-bar', true);
                progress.forEach((el) => {
                    el.style.width = el.getAttribute('aria-valuenow') + '%'
                });
            }
        })
    }

    /**
     * Porfolio isotope and filter
     */
    window.addEventListener('load', () => {
        let portfolioContainer = select('.portfolio-container');
        if (portfolioContainer) {
            let portfolioIsotope = new Isotope(portfolioContainer, {
                itemSelector: '.portfolio-item'
            });

            let portfolioFilters = select('#portfolio-flters li', true);

            on('click', '#portfolio-flters li', function(e) {
                e.preventDefault();
                portfolioFilters.forEach(function(el) {
                    el.classList.remove('filter-active');
                });
                this.classList.add('filter-active');

                portfolioIsotope.arrange({
                    filter: this.getAttribute('data-filter')
                });
                portfolioIsotope.on('arrangeComplete', function() {
                    AOS.refresh()
                });
            }, true);
        }

    });

    /**
     * Initiate portfolio lightbox 
     */
    const portfolioLightbox = GLightbox({
        selector: '.portfolio-lightbox'
    });

    /**
     * Portfolio details slider
     */
    new Swiper('.portfolio-details-slider', {
        speed: 400,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true
        }
    });

    /**
     * Testimonials slider
     */
    new Swiper('.testimonials-slider', {
        speed: 600,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        slidesPerView: 'auto',
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 20
            },

            1200: {
                slidesPerView: 3,
                spaceBetween: 20
            }
        }
    });

    /**
     * Animation on scroll
     */
    window.addEventListener('load', () => {
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        })
    });

})()

/**
 * Dark Mode Toggle
 */
document.addEventListener('DOMContentLoaded', function() {
  const themeToggle = document.getElementById('theme-toggle');
  const themeIcon = document.getElementById('theme-icon');
  const body = document.body;

  // Check for saved theme preference or default to light mode
  const currentTheme = localStorage.getItem('theme') || 'light';
  body.setAttribute('data-theme', currentTheme);
  
  // Update icon based on current theme
  updateThemeIcon(currentTheme);

  if (themeToggle) {
    themeToggle.addEventListener('click', function() {
      const currentTheme = body.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      body.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      updateThemeIcon(newTheme);
    });
  }

  function updateThemeIcon(theme) {
    if (themeIcon) {
      if (theme === 'dark') {
        themeIcon.className = 'bx bx-sun';
      } else {
        themeIcon.className = 'bx bx-moon';
      }
    }
  }
});

/**
 * Enhanced Interactive Animations
 */

// Particle animation for hero section
function createParticles() {
  const hero = document.querySelector('#hero');
  if (!hero) return;

  for (let i = 0; i < 50; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.cssText = `
      position: absolute;
      width: ${Math.random() * 4 + 1}px;
      height: ${Math.random() * 4 + 1}px;
      background: rgba(20, 157, 221, ${Math.random() * 0.5 + 0.2});
      border-radius: 50%;
      left: ${Math.random() * 100}%;
      top: ${Math.random() * 100}%;
      animation: float ${Math.random() * 6 + 4}s ease-in-out infinite;
      animation-delay: ${Math.random() * 2}s;
    `;
    hero.appendChild(particle);
  }
}

// Typing animation enhancement
function enhancedTypingEffect() {
  const typed = document.querySelector('.typed');
  if (typed) {
    typed.addEventListener('DOMSubtreeModified', function() {
      this.style.borderRight = '2px solid #149ddd';
      this.style.animation = 'blink 1s infinite';
    });
  }
}

// Skill bars animation on scroll
function animateSkillBars() {
  const skillBars = document.querySelectorAll('.progress-bar');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const bar = entry.target;
        const width = bar.getAttribute('aria-valuenow') + '%';
        bar.style.width = '0%';
        setTimeout(() => {
          bar.style.width = width;
          bar.style.transition = 'width 2s ease-in-out';
        }, 200);
      }
    });
  });

  skillBars.forEach(bar => observer.observe(bar));
}

// Interactive card hover effects
function addCardInteractions() {
  const cards = document.querySelectorAll('.portfolio-wrap, .resume-item, .stats-card');
  
  cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-10px) scale(1.02)';
      this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
      this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    });

    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0) scale(1)';
      this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
    });
  });
}

// Smooth scroll with easing
function smoothScrolling() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
}

// Counter animation for stats
function animateCounters() {
  const counters = document.querySelectorAll('.counter');
  
  counters.forEach(counter => {
    const target = parseInt(counter.getAttribute('data-target'));
    const increment = target / 100;
    let current = 0;
    
    const updateCounter = () => {
      if (current < target) {
        current += increment;
        counter.textContent = Math.ceil(current);
        requestAnimationFrame(updateCounter);
      } else {
        counter.textContent = target;
      }
    };
    
    const observer = new IntersectionObserver((entries) => {
      if (entries[0].isIntersecting) {
        updateCounter();
        observer.disconnect();
      }
    });
    
    observer.observe(counter);
  });
}

// Parallax scrolling effects
window.addEventListener('scroll', function() {
  const scrolled = window.pageYOffset;
  const parallax = document.querySelector('#hero');
  
  if (parallax) {
    const speed = scrolled * 0.3;
    parallax.style.transform = `translateY(${speed}px)`;
  }

  // Navbar background on scroll
  const navbar = document.querySelector('#header');
  if (navbar) {
    if (scrolled > 100) {
      navbar.style.background = 'rgba(4, 11, 20, 0.95)';
      navbar.style.backdropFilter = 'blur(10px)';
    } else {
      navbar.style.background = '#040b14';
      navbar.style.backdropFilter = 'none';
    }
  }
});

// Image placeholder interactions
function addImagePlaceholderEffects() {
  const placeholders = document.querySelectorAll('.img-placeholder');
  
  placeholders.forEach(placeholder => {
    placeholder.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-5px) scale(1.02)';
      this.style.borderColor = '#149ddd';
    });
    
    placeholder.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0) scale(1)';
      this.style.borderColor = '#ccc';
    });
    
    // Add click animation
    placeholder.addEventListener('click', function() {
      this.style.animation = 'bounce 0.6s ease';
      setTimeout(() => {
        this.style.animation = '';
      }, 600);
    });
  });
}

// Enhanced navigation animations
function enhanceNavigation() {
  const navLinks = document.querySelectorAll('#navbar a');
  
  navLinks.forEach(link => {
    link.addEventListener('mouseenter', function() {
      this.style.transform = 'translateX(10px)';
      this.style.color = '#149ddd';
    });
    
    link.addEventListener('mouseleave', function() {
      this.style.transform = 'translateX(0)';
      this.style.color = '';
    });
  });
}

// Button ripple effects
function addRippleEffects() {
  const buttons = document.querySelectorAll('.btn, .portfolio-link, .social-links a');
  
  buttons.forEach(button => {
    button.addEventListener('click', function(e) {
      const ripple = document.createElement('span');
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const x = e.clientX - rect.left - size / 2;
      const y = e.clientY - rect.top - size / 2;
      
      ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
      `;
      
      this.style.position = 'relative';
      this.style.overflow = 'hidden';
      this.appendChild(ripple);
      
      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  });
}

// Scroll reveal animations
function addScrollRevealAnimations() {
  const elements = document.querySelectorAll('.section-title, .portfolio-item, .resume-item');
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        entry.target.style.transition = 'all 0.6s ease';
      }
    });
  }, { threshold: 0.1 });
  
  elements.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    observer.observe(el);
  });
}

// Crazy Submit Button Logic
function initializeCrazySubmitButton() {
  const form = document.getElementById('contact-form');
  const submitBtn = document.getElementById('submit-btn');
  const validationStatus = document.querySelector('.form-validation-status');
  const inputs = form.querySelectorAll('input[required], textarea[required]');
  
  let isFormValid = false;
  let mouseX = 0;
  let mouseY = 0;
  
  // Track mouse position
  document.addEventListener('mousemove', function(e) {
    mouseX = e.clientX;
    mouseY = e.clientY;
  });
  
  // Validation function
  function validateForm() {
    let allValid = true;
    
    inputs.forEach(input => {
      const value = input.value.trim();
      const minLength = input.getAttribute('minlength') || 0;
      
      if (!value || value.length < minLength) {
        allValid = false;
      }
      
      if (input.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
          allValid = false;
        }
      }
    });
    
    isFormValid = allValid;
    
    if (allValid) {
      submitBtn.disabled = false;
      submitBtn.querySelector('.btn-text').innerHTML = '<i class="fas fa-rocket"></i> Send Message';
      validationStatus.classList.add('hidden');
      validationStatus.querySelector('.validation-text').textContent = 'Ready to send!';
      validationStatus.querySelector('.validation-text').style.color = '#27ae60';
    } else {
      submitBtn.disabled = true;
      validationStatus.classList.remove('hidden');
      validationStatus.querySelector('.validation-text').textContent = 'Complete all fields to unlock the button!';
      validationStatus.querySelector('.validation-text').style.color = '#e74c3c';
    }
  }
  
  // Button escape logic
  function makeButtonRunAway() {
    if (isFormValid) return;
    
    const btnRect = submitBtn.getBoundingClientRect();
    const btnCenterX = btnRect.left + btnRect.width / 2;
    const btnCenterY = btnRect.top + btnRect.height / 2;
    
    const distance = Math.sqrt(
      Math.pow(mouseX - btnCenterX, 2) + Math.pow(mouseY - btnCenterY, 2)
    );
    
    // If mouse is too close and form is invalid, move button
    if (distance < 100 && !isFormValid) {
      const container = submitBtn.parentElement;
      const containerRect = container.getBoundingClientRect();
      
      // Calculate escape direction (opposite to mouse)
      const escapeX = btnCenterX - mouseX;
      const escapeY = btnCenterY - mouseY;
      
      // Normalize and multiply by escape distance
      const magnitude = Math.sqrt(escapeX * escapeX + escapeY * escapeY);
      const normalizedX = (escapeX / magnitude) * 80;
      const normalizedY = (escapeY / magnitude) * 40;
      
      // Apply bounds to keep button in container
      const maxX = containerRect.width - btnRect.width;
      const maxY = containerRect.height - btnRect.height;
      
      let newX = Math.max(0, Math.min(maxX, btnRect.left - containerRect.left + normalizedX));
      let newY = Math.max(0, Math.min(maxY, btnRect.top - containerRect.top + normalizedY));
      
      submitBtn.style.position = 'absolute';
      submitBtn.style.left = newX + 'px';
      submitBtn.style.top = newY + 'px';
      submitBtn.style.transform = `rotate(${Math.random() * 20 - 10}deg) scale(0.9)`;
      
      // Add shake effect
      submitBtn.style.animation = 'button-run-away 0.8s ease-out';
      
      setTimeout(() => {
        submitBtn.style.animation = '';
      }, 800);
    }
  }
  
  // Reset button position when form becomes valid
  function resetButtonPosition() {
    if (isFormValid) {
      submitBtn.style.position = 'relative';
      submitBtn.style.left = 'auto';
      submitBtn.style.top = 'auto';
      submitBtn.style.transform = 'none';
    }
  }
  
  // Add event listeners
  inputs.forEach(input => {
    input.addEventListener('input', validateForm);
    input.addEventListener('blur', validateForm);
  });
  
  // Mouse move listener for button escape
  document.addEventListener('mousemove', makeButtonRunAway);
  
  // Form validation on change
  form.addEventListener('input', () => {
    validateForm();
    resetButtonPosition();
  });
  
  // Initial validation
  validateForm();
}

// Enhanced form input animations
function enhanceFormInputs() {
  const inputs = document.querySelectorAll('.enhanced-input');
  
  inputs.forEach(input => {
    // Add floating label effect
    input.addEventListener('focus', function() {
      this.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
      if (!this.value) {
        this.parentElement.classList.remove('focused');
      }
    });
    
    // Add typing sound effect (visual)
    input.addEventListener('input', function() {
      this.style.boxShadow = '0 0 20px rgba(20, 157, 221, 0.3)';
      setTimeout(() => {
        this.style.boxShadow = '';
      }, 200);
    });
  });
}

// Initialize all animations
document.addEventListener('DOMContentLoaded', function() {
  createParticles();
  enhancedTypingEffect();
  animateSkillBars();
  addCardInteractions();
  smoothScrolling();
  animateCounters();
  addImagePlaceholderEffects();
  enhanceNavigation();
  addRippleEffects();
  addScrollRevealAnimations();
  initializeCrazySubmitButton();
  enhanceFormInputs();
});

/**
 * Contact Form Handler
 */
document.addEventListener('DOMContentLoaded', function() {
  const contactForm = document.getElementById('contact-form');
  const submitBtn = document.getElementById('submit-btn');
  const loadingDiv = document.querySelector('.loading');
  const errorDiv = document.getElementById('error-message');
  const successDiv = document.getElementById('sent-message');

  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Reset messages
      hideMessages();
      
      // Show loading
      loadingDiv.style.display = 'block';
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending...';
      
      // Prepare form data
      const formData = new FormData(contactForm);
      
      // Send AJAX request
      fetch('forms/contact.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        loadingDiv.style.display = 'none';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Message';
        
        if (data.success) {
          successDiv.textContent = data.message;
          successDiv.style.display = 'block';
          contactForm.reset();
          
          // Show success alert
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'success',
              title: 'Message Sent!',
              text: data.message,
              confirmButtonColor: '#149ddd'
            });
          } else {
            alert('✅ ' + data.message);
          }
        } else {
          errorDiv.textContent = data.message;
          errorDiv.style.display = 'block';
          
          // Show error alert
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: data.message,
              confirmButtonColor: '#149ddd'
            });
          } else {
            alert('❌ ' + data.message);
          }
        }
      })
      .catch(error => {
        console.error('Error:', error);
        loadingDiv.style.display = 'none';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Message';
        
        const errorMessage = 'An error occurred while sending your message. Please try again.';
        errorDiv.textContent = errorMessage;
        errorDiv.style.display = 'block';
        
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Connection Error!',
            text: errorMessage,
            confirmButtonColor: '#149ddd'
          });
        } else {
          alert('❌ ' + errorMessage);
        }
      });
    });
  }
  
  function hideMessages() {
    loadingDiv.style.display = 'none';
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';
  }
});