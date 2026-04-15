<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Join UX Pacific Community - Connect with designers, developers, and creators. Get your invite link to join our growing design community." />
    <meta name="theme-color" content="#6147bd" />
    <title>Join Our Community | UX Pacific</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/faviconUXP444@4x-789.png" />

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
      href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/join-style.css" />
  </head>

  <body class="main">
    <!-- Skip to main content link -->
    <a href="#join-section" class="skip-link">Skip to main content</a>

    <!-- NAVBAR -->
       <nav class="navbar navbar-expand-lg fixed-top mt-3" role="navigation" aria-label="Main navigation">
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
      <!-- JOIN SECTION -->
      <section class="join-section" id="join-section" aria-labelledby="join-title">
        <div class="container text-center">
          <h1 id="join-title" class="join-main-title">Join the <span>UXPacific</span><br>Community</h1>
          <p class="join-main-subtitle">
            Enter your email address to receive the community invite link directly to your inbox.<br>
            Join us to connect, share, and grow.
          </p>
          <div class="share-ideas-card">
            <form class="join-form js-ajax-form" action="includes/form-handler.php" method="post" novalidate>
              <input type="hidden" name="form_type" value="join_community">
              <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off">
              <div class="input-group-wrapper">
                <label for="join-email" class="visually-hidden">Email address</label>
                <input type="email" id="join-email" name="email" class="form-control join-input" placeholder="Enter your email address" aria-label="Email address" required autocomplete="email">
                <button class="btn btn-primary-gradient join-btn" type="submit" aria-label="Submit email to join">
                  <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
                </button>
              </div>
            </form>
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
                    <a href="https://medium.com/@uxpacific" target="_blank" rel="noopener">
              <img src="img/medium.png" alt="Medium" /> 
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./assets/js/form.js"></script>

  </body>
</html>
