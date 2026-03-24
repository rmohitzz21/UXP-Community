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

    // Active Link Highlighting on Scroll
    const sections = document.querySelectorAll("section");
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
        const activeLink = document.querySelector(`.nav-link[href="#${id}"]`);
        if (activeLink) {
            removeActiveClasses();
            activeLink.classList.add("active");
        }
    }

    window.addEventListener("scroll", () => {
        let current = "";
        
        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            // logic: if we scrolled past the top of the section minus a little offset (e.g. navbar height)
            if (window.scrollY >= (sectionTop - 150)) {
                current = section.getAttribute("id");
            }
        });

        // special case for reaching the bottom of the page
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
             // If we are at the bottom, select the last section? 
             // Or maybe just let the loop handle it if the sections cover the whole page.
             // Usually the loop is fine.
        }

        if (current) {
            addActiveClass(current);
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
            
            // Show/Hide Containers
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
});