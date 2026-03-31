<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="UX Pacific Community - A space for designers, developers, and creators to collaborate on real projects, share resources, and grow together." />
    <meta name="keywords" content="UX design, UI design, design community, collaboration, designers, developers" />
    <meta name="author" content="UX Pacific" />
    <meta name="theme-color" content="#6147bd" />
    <title>UX Pacific Community | Design & Development Hub</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/img/faviconUXP444@4x-789.png" />

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://cdn.jsdelivr.net" />

    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Google Fonts - Gabarito for headings -->
    <link
      href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

   <link rel="stylesheet" href="assets/css/style.css">
  </head>

  <body class="main">
    <!-- Skip to main content link for accessibility -->
    <a href="#home" class="skip-link">Skip to main content</a>
    <!-- NAVBAR -->
    <div class="hero-shapes">
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
      </div>
    <nav class="navbar navbar-expand-lg fixed-top mt-3">
      <div class="container">
        <div class="navbar-glass w-100 d-flex align-items-center flex-wrap">
          <a class="navbar-brand text-white" href="#">
            <img src=\"assets/img/logo1.webp" alt="UX Pacific Logo" class="navbar-logo" />
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
                <a class="nav-link active" href="#home">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#about-section">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#resources-section">Resources</a>
              </li>
              <li class="nav-item"><a class="nav-link" href="#event-section">Event</a></li>
              <!-- Mobile Contact Button -->
              <li class="nav-item d-lg-none mt-3">
                <a href="contact.html" class="btn btn-primary-gradient w-100">Contact Us</a>
              </li>
            </ul>
          </div>

          <div class="d-none d-lg-block">
            <a href="contact.html" class="btn btn-primary-gradient">Contact Us</a>
          </div>
        </div>
      </div>
    </nav>
