// JavaScript for the seamless review slider loop and hover pause
document.addEventListener("DOMContentLoaded", function () {
    const reviewsGrid = document.querySelector(".reviews-grid");
    if (reviewsGrid) {
        const originalCards = document.getElementById("original-cards");

        // Clone the original set of cards to create the seamless effect
        const clonedCards = originalCards.cloneNode(true);
        clonedCards.removeAttribute("id");
        reviewsGrid.appendChild(clonedCards);

        // Logic to pause animation ONLY on card hover
        const allCards = document.querySelectorAll(".testimonial-card");

        allCards.forEach((card) => {
          card.addEventListener("mouseenter", () => {
            reviewsGrid.style.animationPlayState = "paused";
          });
          card.addEventListener("mouseleave", () => {
            reviewsGrid.style.animationPlayState = "running";
          });
        });
    }

    // Active Link Highlighting on Scroll with Intersection Observer for better performance
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link");

    // Helper to remove active class from all links
    function removeActiveClasses() {
        navLinks.forEach((link) => {
            link.classList.remove("active");
        });
    }

    // Helper to add active class to a specific link
    function addActiveClass(id) {
        if (!id) return;
        const activeLink = document.querySelector(`.nav-link[href="#${id}"], .nav-link[href="index.html#${id}"]`);
        if (activeLink) {
            removeActiveClasses();
            activeLink.classList.add("active");
        }
    }

    // Use Intersection Observer for better scroll performance
    const observerOptions = {
        root: null,
        rootMargin: '-20% 0px -60% 0px',
        threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                if (id) {
                    addActiveClass(id);
                }
            }
        });
    }, observerOptions);

    sections.forEach(section => {
        if (section.id) {
            observer.observe(section);
        }
    });

    // Fallback scroll listener for edge cases
    let ticking = false;
    window.addEventListener("scroll", () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                let current = "";
                
                sections.forEach((section) => {
                    const sectionTop = section.offsetTop;
                    if (window.scrollY >= (sectionTop - 150)) {
                        current = section.getAttribute("id");
                    }
                });

                if (current) {
                    addActiveClass(current);
                }
                ticking = false;
            });
            ticking = true;
        }
    });

    // Events Filtering Logic
    const btnUpcoming = document.getElementById("btn-upcoming");
    const btnPast = document.getElementById("btn-past");
    const containerUpcoming = document.getElementById("upcoming-events-container");
    const containerPast = document.getElementById("past-events-container");

    if (btnUpcoming && btnPast && containerUpcoming && containerPast) {
        btnUpcoming.addEventListener("click", () => {
             // Activate button
            btnUpcoming.classList.add("active");
            btnPast.classList.remove("active");
            
            // Show/Hide Containers with smooth transition
            containerUpcoming.style.display = "block";
            containerPast.style.display = "none";
        });

        btnPast.addEventListener("click", () => {
             // Activate button
            btnPast.classList.add("active");
            btnUpcoming.classList.remove("active");
            
            // Show/Hide Containers
            containerUpcoming.style.display = "none";
            containerPast.style.display = "block";
        });
    }

    // Resource filter functionality
    const filterBtns = document.querySelectorAll('.resource-filters .filter-btn');
    const resourcesGrid = document.getElementById('resources-grid');
    if (filterBtns.length > 0 && resourcesGrid) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active state
                filterBtns.forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');
                
                // Filter resources
                const filter = btn.dataset.filter;
                const cards = resourcesGrid.querySelectorAll('[data-category]');
                cards.forEach(card => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // Add keyboard navigation for filter buttons
    filterBtns.forEach((btn, index) => {
        btn.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                const nextIndex = (index + 1) % filterBtns.length;
                filterBtns[nextIndex].focus();
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                const prevIndex = (index - 1 + filterBtns.length) % filterBtns.length;
                filterBtns[prevIndex].focus();
            }
        });
    });

    // Navbar scroll effect - add background on scroll
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // ===========================
    // EVENT REGISTRATION MODAL
    // ===========================
    const eventRegisterModal = document.getElementById('eventRegisterModal');
    const registrationForm = document.getElementById('eventRegistrationForm');
    const registrationSuccessModal = document.getElementById('registrationSuccessModal');

    // Update modal with event name when Register Now is clicked
    if (eventRegisterModal) {
        eventRegisterModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (button) {
                const eventName = button.getAttribute('data-event-name');
                const modalEventName = document.getElementById('modalEventName');
                if (modalEventName && eventName) {
                    modalEventName.textContent = eventName;
                }
            }
        });

        // Reset form when modal is closed
        eventRegisterModal.addEventListener('hidden.bs.modal', function() {
            if (registrationForm) {
                registrationForm.reset();
                // Remove validation classes
                registrationForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            }
        });
    }

    // Handle registration form submission
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const firstName = document.getElementById('regFirstName');
            const lastName = document.getElementById('regLastName');
            const email = document.getElementById('regEmail');
            const phone = document.getElementById('regPhone');
            const role = document.getElementById('regRole');
            const terms = document.getElementById('regTerms');
            
            let isValid = true;
            
            // Validate required fields
            [firstName, lastName, email, phone, role].forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    field.style.borderColor = '#ef4444';
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.style.borderColor = 'rgba(255, 255, 255, 0.1)';
                }
            });

            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.value && !emailRegex.test(email.value)) {
                email.classList.add('is-invalid');
                email.style.borderColor = '#ef4444';
                isValid = false;
            }

            // Validate terms checkbox
            if (!terms.checked) {
                terms.classList.add('is-invalid');
                isValid = false;
            } else {
                terms.classList.remove('is-invalid');
            }

            if (!isValid) {
                return;
            }

            // Collect form data
            const formData = {
                firstName: firstName.value.trim(),
                lastName: lastName.value.trim(),
                email: email.value.trim(),
                phone: phone.value.trim(),
                company: document.getElementById('regCompany').value.trim(),
                role: role.value,
                experience: document.getElementById('regExperience').value,
                expectations: document.getElementById('regExpectations').value.trim(),
                updates: document.getElementById('regUpdates').checked,
                eventName: document.getElementById('modalEventName').textContent,
                registeredAt: new Date().toISOString()
            };

            // Store registration in localStorage (for demo/admin viewing)
            let registrations = JSON.parse(localStorage.getItem('eventRegistrations') || '[]');
            formData.id = 'REG-' + Date.now();
            registrations.push(formData);
            localStorage.setItem('eventRegistrations', JSON.stringify(registrations));

            console.log('Registration submitted:', formData);

            // Close registration modal and show success modal
            const bsRegisterModal = bootstrap.Modal.getInstance(eventRegisterModal);
            bsRegisterModal.hide();

            // Show success modal after a brief delay
            setTimeout(() => {
                const successModal = new bootstrap.Modal(registrationSuccessModal);
                successModal.show();
            }, 300);
        });
    }

    // Style invalid inputs on focus
    document.querySelectorAll('#eventRegistrationForm input, #eventRegistrationForm select, #eventRegistrationForm textarea').forEach(field => {
        field.addEventListener('focus', function() {
            if (this.classList.contains('is-invalid')) {
                this.style.borderColor = '#ef4444';
            } else {
                this.style.borderColor = '#7b61ff';
            }
        });
        field.addEventListener('blur', function() {
            if (!this.classList.contains('is-invalid')) {
                this.style.borderColor = 'rgba(255, 255, 255, 0.1)';
            }
        });
    });
});