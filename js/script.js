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
    if (filterBtns.length > 0) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');
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
});