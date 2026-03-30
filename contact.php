<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Contact UX Pacific Community - Get in touch with our design team for collaborations, projects, or inquiries." />
    <meta name="theme-color" content="#6147bd" />
    <title>Contact Us | UX Pacific Community</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/LOGO.png" />

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://cdn.jsdelivr.net" />

    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="./css/style.css" />
  </head>

  <body class="main">
    <!-- Skip to main content link -->
    <a href="#contact" class="skip-link">Skip to main content</a>

    <!-- NAVBAR -->
     <nav class="navbar navbar-expand-lg fixed-top mt-3" role="navigation" aria-label="Main navigation">
      <div class="container">
        <div class="navbar-glass w-100 d-flex align-items-center flex-wrap">
          <a class="navbar-brand text-white" href="index.php">
            <img src="img/5.png" alt="UX Pacific Logo" class="navbar-logo" />
          </a>

          <button
            class="navbar-toggler text-white ms-auto"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navMenu"
            aria-controls="navMenu"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon">☰</span>
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
                <a href="contact.php" class="btn btn-primary-gradient w-100" aria-current="page">Contact Us</a>
              </li>
            </ul>
          </div>

          <div class="d-none d-lg-block">
            <a href="contact.php" class="btn btn-primary-gradient" aria-current="page">Contact Us</a>
          </div>
        </div>
      </div>
    </nav>


      <main id="home" class="main">
        <section id="contact" class="contact-section" aria-labelledby="contact-title">

                <div class="contact-grid">
                <!-- LEFT: Info panel -->
                <div class="contact-info-panel">
                    <h2 id="contact-title" class="contact-title">Connect <span class="contact-title-gradient">With Us</span></h2>
                    <p class="contact-subtitle">
                        Whether you're a startup with a big idea, an enterprise looking to level up
                        your UX, or a designer wanting to collaborate we're here for all of it.
                    </p>

                    <div class="contact-info-list">
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <span class="contact-info-label">LOCATION</span>
                                <p class="contact-info-text">512, D&C Majestic, Near Law Garden, Ahmedabad</p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.7 13.65a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.61 3h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 10.6a16 16 0 0 0 6 6l.94-.94a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 18z"/></svg>
                            </div>
                            <div>
                                <span class="contact-info-label">PHONE</span>
                                <p class="contact-info-text">+91 92740-61063</p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <span class="contact-info-label">EMAIL</span>
                                <p class="contact-info-text">hello@uxpacific.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-benefits">
                        <div class="contact-benefit-item">
                            <span class="contact-benefit-check">✔</span>
                            <span>We respond within 24 hours</span>
                        </div>
                        <div class="contact-benefit-item">
                            <span class="contact-benefit-check">✔</span>
                            <span>Free initial consultation call</span>
                        </div>
                        <div class="contact-benefit-item">
                            <span class="contact-benefit-check">✔</span>
                            <span>No commitment required</span>
                        </div>
                        <div class="contact-benefit-item">
                            <span class="contact-benefit-check">✔</span>
                            <span>Your data is always private</span>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Form -->
                <form class="contact-form" action="#" method="post" novalidate>
                    <div class="contact-row">
                        <div class="contact-field">
                            <label for="name">Name <span class="visually-hidden">(required)</span></label>
                            <input id="name" name="name" type="text" placeholder="Enter your name here" required autocomplete="name">
                        </div>
                        <div class="contact-field">
                            <label for="email">Email <span class="visually-hidden">(required)</span></label>
                            <input id="email" name="email" type="email" placeholder="Enter your email address" required autocomplete="email">
                        </div>
                    </div>

                    <div class="contact-row">
                        <div class="contact-field">
                            <label for="phone">Phone Number</label>
                            <input id="phone" name="phone" type="tel" placeholder="+91 xxxxx-xxxxx" autocomplete="tel">
                        </div>
                        <div class="contact-field">
                            <label for="industry">Industry</label>
                            <select id="industry" name="industry" class="contact-select">
                                <option value="" disabled selected style="background: #1a1a2e; color: #6b6b80;">Select your Industry</option>
                                <option value="tech" style="background: #1a1a2e; color: #fff;">Technology</option>
                                <option value="design" style="background: #1a1a2e; color: #fff;">Design</option>
                                <option value="education" style="background: #1a1a2e; color: #fff;">Education</option>
                                <option value="healthcare" style="background: #1a1a2e; color: #fff;">Healthcare</option>
                                <option value="finance" style="background: #1a1a2e; color: #fff;">Finance</option>
                                <option value="ecommerce" style="background: #1a1a2e; color: #fff;">E-Commerce</option>
                                <option value="other" style="background: #1a1a2e; color: #fff;">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="contact-field">
                        <label for="message">Message <span class="visually-hidden">(required)</span></label>
                        <textarea id="message" name="message" rows="5"
                            placeholder="Enter your message here..." required></textarea>
                    </div>

                    <div class="contact-footer">
                        <label class="contact-checkbox">
                            <input type="checkbox" required aria-describedby="terms-desc">
                            <span id="terms-desc">
                                I Agree to
                                <a href="#" class="contact-link">Terms &amp; Conditions</a>
                                of UX Pacific
                            </span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary-gradient w-100">Send Message</button>
                </form>
            </div>
        </section>

         <!-- CTA / READY SECTION -->
    
    </main>


      <!-- FINAL CTA SECTION -->
    <section class="final-cta">
      <div class="final-cta-card">
        <h2>Shop. Collab. Create.</h2>

        <p>
          Explore UX Pacific's exclusive merchandise, booklets, and design
          templates made for creators like you. Collaborate with our growing
          community to learn, share, and bring new ideas to life.
        </p>

        <div class="final-cta-actions">
          <a href="#" class="btn-primary">Start a Collaboration</a>
          <a href="#" class="btn-outline">Visit the Shop</a>
        </div>
      </div>
    </section>

      <footer id="" class="site-footer">
        <div class="footer-main">
          <div class="footer-top">
            <div class="footer-brand">
              <img src="img/LOGO.png" alt="UX Pacific" />
              <p>
                Join our community of designers and developers creating the
                future of digital experiences.
              </p>
              <div class="footer-socials">
                <a
                  href="https://dribbble.com/social-ux-pacific"
                  target="_blank"
                  rel="noopener"
                >
                  <img src="img/bl.png" alt="Dribbble" />
                </a>

                <a
                  href="https://www.instagram.com/official_uxpacific/"
                  target="_blank"
                  rel="noopener"
                >
                  <img src="img/i.png" alt="Instagram" />
                </a>

                <a
                  href="https://www.linkedin.com/company/uxpacific/"
                  target="_blank"
                  rel="noopener"
                >
                  <img src="img/in1.png" alt="LinkedIn" />
                </a>

                <a
                  href="https://in.pinterest.com/uxpacific/"
                  target="_blank"
                  rel="noopener"
                >
                  <img src="img/p.png" alt="Pinterest" />
                </a>

                <a
                  href="https://www.behance.net/ux_pacific"
                  target="_blank"
                  rel="noopener"
                >
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
          <p>© 2026 UXPacific Community. All rights reserved.</p>
          <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <span>•</span>
            <a href="#">Terms of Service</a>
          </div>
        </div>
      </footer>



     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/script.js"></script>
  </body>
</html>
