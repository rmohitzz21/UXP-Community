<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Share Your Idea - UX Pacific</title>

        <!-- Bootstrap 5 CSS -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />

        <!-- Google Font -->
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap"
            rel="stylesheet"
        />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="./css/style.css" />
        <link rel="stylesheet" href="./css/join-style.css" />
        <style>
                /* Specific overrides for Idea Page */
                .idea-section {
                        padding: 140px 0 80px;
                        min-height: 80vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                }
                
                .idea-card {
                        background: linear-gradient(180deg, rgba(23, 23, 23, 0.4), rgba(10, 10, 20, 0.6));
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        border-radius: 32px;
                        padding: 36px 40px;
                        max-width: 800px;
                        margin: 0 auto;
                        backdrop-filter: blur(16px);
                        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5);
                        transition: opacity 0.3s ease;
                }

                .idea-form .contact-field input,
                .idea-form .contact-field textarea {
                          width: 100%;
                          background: linear-gradient(#121212, #121212) padding-box, linear-gradient(to right, #ffffff 0%, #2e2e3e 100%) border-box;
                          border: 2px solid transparent;
                          border-radius: 6px;
                          color: #eee;
                          padding: 16px 20px;
                          font-size: 1.1rem;
                          outline: none;
                          margin-bottom: 16px;
                }
                
                .idea-form .contact-field textarea {
                        min-height: 115px;
                        resize: vertical;
                }

                .idea-form .contact-field input:focus,
                .idea-form .contact-field textarea:focus {
                          border-color: #7b61ff;
                }

                .idea-form .contact-row {
                        display: flex;
                        gap: 20px;
                        margin-bottom: 0;
                }
                
                .idea-form .contact-row .contact-field {
                        flex: 1;
                }

                @media(max-width: 768px) {
                        .idea-form .contact-row {
                                flex-direction: column;
                                gap: 0;
                        }
                }

                /* Success Animation Styles */
                .success-checkmark-anim {
                        width: 80px;
                        height: 80px;
                        border-radius: 50%;
                        background: rgba(74, 222, 128, 0.15);
                        color: #4ade80;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                        position: relative;
                }
                
                .success-checkmark-anim::after {
                        content: "";
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        border: 1px solid #4ade80;
                        animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
                }
                
                .success-checkmark-anim i {
                        display: inline-block;
                        opacity: 0;
                        transform: scale(0);
                }

                /* Class that triggers animation */
                .animate-trigger .success-checkmark-anim i {
                        animation: pop-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.1s forwards;
                }

                @keyframes pulse-ring {
                        0% { transform: scale(0.9); opacity: 0.8; }
                        100% { transform: scale(1.5); opacity: 0; }
                }

                @keyframes pop-in {
                        0% { transform: scale(0); opacity: 0; }
                        100% { transform: scale(1); opacity: 1; }
                }
        </style>
    </head>

    <body class="main">
        <!-- NAVBAR -->
          <nav class="navbar navbar-expand-lg fixed-top mt-3">
            <div class="container">
                <div class="navbar-glass w-100 d-flex align-items-center flex-wrap">
                    <a class="navbar-brand text-white" href="index.php">
                        <img src="img/ux-community.png" alt="UX Pacific Logo" class="navbar-logo" />
                    </a>

                    <button
                        class="navbar-toggler text-white ms-auto"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navMenu"
                    >
                        ☰
                    </button>

                    <div
                        class="collapse navbar-collapse justify-content-center"
                        id="navMenu"
                    >
                        <ul class="navbar-nav gap-2">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#about-section">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#resources-section">Resources</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="index.php#event-section">Event</a></li>
                            <!-- Mobile Contact Button -->
                            <li class="nav-item d-lg-none mt-3">
                                <a href="contact.php" class="btn btn-primary-gradient w-100">Contact Us</a>
                            </li>
                        </ul>
                    </div>

                    <div class="d-none d-lg-block">
                        <a href="contact.php" class="btn btn-primary-gradient">Contact Us</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="main">
            <section class="idea-section">
                <div class="container text-center">
                   
                    <h1 class="join-main-title">Share Your  <span>Ideas.</span></h1>
                    <p class="join-main-subtitle">
                        Got an idea, a concept, or an event in mind?<br>
                        If it aligns with the community, we'd love to build it together.
                    </p>

                    <!-- FORM CONTAINER -->
                    <div class="idea-card" id="idea-card-container">
                        <form class="idea-form" onsubmit="handleIdeaPageSubmit(event)">
                            <div class="contact-row">
                                <div class="contact-field">
                                    <input type="text" placeholder="Your Name" required>
                                </div>
                                <div class="contact-field">
                                    <input type="email" placeholder="Your Email" required>
                                </div>
                            </div>

                            <div class="contact-field mt-3">
                                <textarea rows="5" placeholder="Share your idea / proposal..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary-gradient mt-4 px-5 py-3 rounded-pill fw-bold">
                                Send my Idea
                            </button>
                        </form>
                    </div>

                    <!-- SUCCESS MESSAGE container (Hidden by default) -->
                    <div class="idea-card success-card" id="idea-success-msg" style="display: none;">
                          <div class="success-checkmark-anim mb-3">
                              <i class="bi bi-check-lg display-4"></i>
                          </div>
                          <h2 class="text-white fw-bold mb-2">Thanks!</h2>
                          <p class="text-white-50 fs-5">One real human will read this!</p>
                    </div>

                    <!-- EXTRA INFO -->
                    <div class="mt-5 text-white-50">
                        <p>Preferred email for detailed proposals pitch us on <a href="mailto:hello@uxpacific.com" class="text-primary text-decoration-none">hello@uxpacific.com</a></p>
                    </div>
                </div>
            </section>
        </main>

        <!-- FOOTER -->
        <footer class="site-footer">
                <div class="footer-main">
                    <div class="footer-top">
                        <div class="footer-brand">
                            <img src="img/ux-community.png" alt="UX Pacific" />
                            <p>
                                Join our community of designers and developers creating the
                                future of digital experiences.
                            </p>
                            <div class="footer-socials">
                                <a href="https://dribbble.com/social-ux-pacific" target="_blank" rel="noopener">
                                    <img src="img/bl.png" alt="Dribbble" />
                                </a>
                                <a href="https://www.instagram.com/official_uxpacific/" target="_blank" rel="noopener">
                                    <img src="img/i.png" alt="Instagram" />
                                </a>
                                <a href="https://www.linkedin.com/company/uxpacific/" target="_blank" rel="noopener">
                                    <img src="img/in1.png" alt="LinkedIn" />
                                </a>
                                <a href="https://in.pinterest.com/uxpacific/" target="_blank" rel="noopener">
                                    <img src="img/p.png" alt="Pinterest" />
                                </a>
                                <a href="https://www.behance.net/ux_pacific" target="_blank" rel="noopener">
                                    <img src="img/be.png" alt="Behance" />
                                </a>
                            </div>
                        </div>

                        <div class="footer-contact">
                            <p>+91 9274061063&nbsp;&nbsp;&nbsp;&nbsp;|</p>
                            <p>hello@uxpacific.com&nbsp;&nbsp;&nbsp;&nbsp;|</p>
                            <p>UX Pacific, Ahmedabad.</p>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>© 2026 UX Pacific Community. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a>
                        <span>•</span>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function handleIdeaPageSubmit(e) {
                e.preventDefault();
                const card = document.getElementById('idea-card-container');
                const success = document.getElementById('idea-success-msg');
                
                // Simple fade out/in effect
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.display = 'none';
                    success.style.display = 'block'; // block to show
                    // trigger layout reflow
                    void success.offsetWidth;
                    
                    // Add animation triggers
                    success.classList.add('animate-trigger');
                    
                    success.style.opacity = '1';
                }, 300);
            }
        </script>
    </body>
</html>
