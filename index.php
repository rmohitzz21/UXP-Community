<?php
require_once __DIR__ . '/includes/db.php';

// Fetch upcoming events
$upcomingEvents = [];
$pastEvents = [];
$teamMembers = [];

try {
    $pdo = getDB();
    
    // Upcoming events
    $stmt = $pdo->query("
        SELECT e.*, 
               (SELECT COUNT(*) FROM event_registrations WHERE event_id = e.id) as registration_count 
        FROM events e 
        WHERE e.event_date >= CURDATE() AND e.status = 'active' 
        ORDER BY e.event_date ASC 
        LIMIT 8
    ");
    $upcomingEvents = $stmt->fetchAll();
    
    // Past events
    $stmt = $pdo->query("
        SELECT e.*, 
               (SELECT COUNT(*) FROM event_registrations WHERE event_id = e.id) as registration_count 
        FROM events e 
        WHERE e.event_date < CURDATE() OR e.status = 'completed' 
        ORDER BY e.event_date DESC 
        LIMIT 8
    ");
    $pastEvents = $stmt->fetchAll();
    
    // Team members
    $stmt = $pdo->query("
        SELECT * FROM team_members 
        WHERE is_active = 1 
        ORDER BY display_order ASC, created_at DESC 
        LIMIT 12
    ");
    $teamMembers = $stmt->fetchAll();
} catch (PDOException $e) {
    // Database not set up yet, use empty arrays
}
?>
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
    <link rel="icon" type="image/png" href="img/ux.png" style="height: 20px; width: 20px;"/>

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

   <link rel="stylesheet" href="./css/style.css">
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

    <!-- HERO SECTION -->
    <!-- HERO SECTION -->
    <section class="hero" id="home">
      
      <div class="container">
        <div class="row align-items-center">
          <!-- Text Content -->
          <div class="col-lg-6 hero-content">
            <div class="hero-badge">
              <span class="badge-icon" aria-hidden="true">
                <img src="img/star.png" alt="" height="15" width="15" loading="eager">
              </span> 
              <span>Welcome to the Future of Collaboration</span>
            </div>

            <h1>
              Where creative minds<br />
              <span>connect &amp; build.</span>
            </h1>

            <p>
              Join the UX Pacific community. A space for designers, developers, and creators to collaborate on real projects, share resources, and grow together.
            </p>

            <div class="hero-actions d-flex gap-3">
              <a href="join.php" class="btn btn-primary-gradient btn-lg">
                Join Community
              </a>
              <a href="idea.php" class="btn btn-outline-light btn-lg">
                Share Your Ideas
              </a>
            </div>
          </div>

          <!-- Hero Visual / Mockup -->
          <div class="col-lg-6 position-relative d-none d-lg-block">
            <div class="hero-visual">
              <!-- Main Glass Card (Simulating a Chat/Post Interface) -->
              <div class="glass-ui-card main-ui">
                <div class="ui-header">
                  <div class="ui-dots">
                    <span></span><span></span><span></span>
                  </div>
                  <div class="ui-title">#general-chat</div>
                </div>
                <div class="ui-body">
                  <div class="ui-message">
                    <div class="ui-avatar" style="background: #7b61ff">AA</div>
                    <div class="ui-text">
                      <span class="name">Aaradhya Aarya</span>
                      <p>Has anyone tried the new Figma update? It's intense! 🎨</p>
                    </div>
                  </div>
                  <div class="ui-message">
                    <div class="ui-avatar" style="background: #2dd4bf">ZP</div>
                    <div class="ui-text">
                      <span class="name">Zula Prajapati</span>
                      <p>Yes! The new dev mode is a lifesaver for handoffs. code excellence 🚀</p>
                    </div>
                  </div>
                   <div class="ui-input">
                    <span>Message #general...</span>
                    <div class="ui-send">➤</div>
                  </div>
                </div>
              </div>

              <!-- Floating Elements -->
              <div class="glass-ui-card floating-card card-1">
                <div class="icon-box-sm">
                  <img src="img/noto_fire.svg" alt="" srcset="" height="20px" width="20px">
                </div>
                <div>
                  <strong>New Project</strong>
                  <span>Just started!</span>
                </div>
              </div>

               <div class="glass-ui-card floating-card card-2">
                <div class="avatars-stack">
                  <span style="background:#f43f5e"></span>
                  <span style="background:#3b82f6"></span>
                  <span style="background:#10b981"></span>
                </div>
                <div>
                  <strong>500+</strong>
                  <span>Members</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="about-section" id="about-section">
      <div class="container text-center">
        <!-- Small badge -->
        <div class="section-badge mb-4">About Us</div>

        <!-- Heading -->
        <h2 class="about-title">Built by <span style="color: #6147bd;">Designers</span>, for <span style="color: #6147bd;">Designers</span></h2>

        <!-- Description -->
        <p class="about-desc mx-auto">
          UXP is where designers, developers, students, and professionals come
          together to learn, collaborate, and create cool projects that actually
          make an impact. We're here to bridge the gap between design and code,
          spark fresh ideas, and help everyone grow from curious beginners to
          seasoned experts.
        </p>

        <p class="about-subdesc">
          <strong>Our vibe?</strong> Less formality, more creativity.<br />
          We're building a space where collaboration fuels opportunity, and
          where hiring, networking, and learning all happen naturally. 
        </p>

        <!-- Cards -->
        <div class="row g-4 mt-5">
          <!-- Card 1 -->
          <div class="col-lg-3 col-md-6">
            <div class="about-card">
              <div class="icon-box">
                <img src="img/paint.png" alt="Design icon" loading="lazy">
              </div>
              <h4>Design First</h4>
              <p>Beautiful, functional experiences that users love</p>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="col-lg-3 col-md-6">
            <div class="about-card">
              <div class="icon-box">
                <img src="img/ar.png" alt="Code icon" loading="lazy">
              </div>
              <h4>Code Excellence</h4>
              <p>Clean, scalable solutions built to last</p>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="col-lg-3 col-md-6">
            <div class="about-card">
              <div class="icon-box">
                <img src="img/id.png" alt="Innovation icon" loading="lazy">
              </div>
              <h4>Innovation</h4>
              <p>Pushing boundaries and exploring new possibilities</p>
            </div>
          </div>

          <!-- Card 4 -->
          <div class="col-lg-3 col-md-6">
            <div class="about-card">
              <div class="icon-box">
                <img src="img/l.png" alt="Community icon" loading="lazy">
              </div>
              <h4>Community</h4>
              <p>Supporting and growing together as one</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="team-section">
      <div class="container text-center">
        <!-- Badge -->
        <div class="section-badge mb-4">Meet Our Team</div>

        <!-- Title -->
        <h2 class="team-title">Meet Our <span style="color: #6147bd;">Team</span></h2>
        <p class="team-subtitle">
          Passionate individuals dedicated to making this community thrive
        </p>

        <!-- Team Grid -->
        <div class="row g-4 mt-5">
          <?php if (empty($teamMembers)): ?>
          <!-- Fallback static content if no team members in database -->
          <div class="col-lg-3 col-md-6">
            <div class="team-card-v3">
              <div class="team-card-v3-img">
                <img src="img/p1.png" alt="Team Member" />
              </div>
              <div class="team-card-v3-content">
                <div class="team-info">
                  <h5>Team Member</h5>
                  <span>Community Head</span>
                </div>
                <div class="team-social-v3">
                  <a href="#" class="social-icon-box" aria-label="LinkedIn">
                    <img src="img/linked.svg" alt="LinkedIn" height="32" width="32" loading="lazy">
                  </a>
                  <a href="#" class="social-icon-box" aria-label="Behance">
                    <img src="img/behn.svg" alt="Behance" height="32" width="32" loading="lazy">
                  </a>
                </div>
              </div>
            </div>
          </div>
          <?php else: ?>
          <?php foreach ($teamMembers as $member): ?>
          <div class="col-lg-3 col-md-6">
            <div class="team-card-v3">
              <div class="team-card-v3-img">
                <?php if ($member['image']): ?>
                <img src="<?php echo h($member['image']); ?>" alt="<?php echo h($member['name']); ?>" />
                <?php else: ?>
                <img src="img/p1.png" alt="<?php echo h($member['name']); ?>" />
                <?php endif; ?>
              </div>
              
              <div class="team-card-v3-content">
                <div class="team-info">
                  <h5><?php echo h($member['name']); ?></h5>
                  <span><?php echo h($member['role']); ?></span>
                </div>

                <div class="team-social-v3">
                  <?php if ($member['linkedin_url']): ?>
                  <a href="<?php echo h($member['linkedin_url']); ?>" class="social-icon-box" aria-label="Visit <?php echo h($member['name']); ?>'s LinkedIn profile" target="_blank">
                    <img src="img/linked.svg" alt="LinkedIn" height="32" width="32" loading="lazy">
                  </a>
                  <?php endif; ?>
                  <?php if ($member['behance_url']): ?>
                  <a href="<?php echo h($member['behance_url']); ?>" class="social-icon-box" aria-label="Visit <?php echo h($member['name']); ?>'s Behance profile" target="_blank">
                    <img src="img/behn.svg" alt="Behance" height="32" width="32" loading="lazy">
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="resources-section" id="resources-section">
      <div class="container text-center">
        <!-- Badge -->
        <div class="section-badge mb-4">Resources</div>

        <!-- Title -->
        <h2 class="resources-title">Learn, Download &amp; <span style="color: #6147bd;">Grow</span></h2>

        <!-- Subtitle -->
        <p class="resources-subtitle mx-auto">
          Explore design tools, guides, and templates created to help you learn
          faster and design smarter. Everything here is made by designers for
          designers.
        </p>

        <!-- Filter Pills -->
        <div class="resource-filters mt-4" role="tablist" aria-label="Filter resources">
          <button class="filter-btn active" role="tab" aria-selected="true" data-filter="all">All</button>
          <button class="filter-btn" role="tab" aria-selected="false" data-filter="design">Design</button>
          <button class="filter-btn" role="tab" aria-selected="false" data-filter="development">Development</button>
          <button class="filter-btn" role="tab" aria-selected="false" data-filter="strategy">Strategy</button>
        </div>

        <!-- Resource Cards -->
        <div class="row g-4 mt-5">
          <!-- Card 1 -->
          <div class="col-lg-3 col-md-6">
            <article class="resource-card">
              <img src="img/et.png" alt="UX Booklets preview" loading="lazy" />
              <span class="resource-tag">Booklet</span>

              <div class="resource-body">
                <h5>UX Booklets</h5>
                <p>
                  Explore our 10-series UX Booklet collection covering
                  everything from research to usability testing.
                </p>
                <a href="#" class="resource-link">View Booklets <span aria-hidden="true">→</span></a>
              </div>
            </article>
          </div>

          <!-- Card 2 -->
          <div class="col-lg-3 col-md-6">
            <article class="resource-card">
              <img src="img/et.png" alt="AI/ML Article preview" loading="lazy" />
              <span class="resource-tag">Articles</span>

              <div class="resource-body">
                <h5>AI/ML Article</h5>
                <p>
                  Read stories, tips, and insights from our design community.
                  Stay updated with the latest UX trends.
                </p>
                <a href="#" class="resource-link">Read Articles <span aria-hidden="true">→</span></a>
              </div>
            </article>
          </div>

          <!-- Card 3 -->
          <div class="col-lg-3 col-md-6">
            <article class="resource-card">
              <img src="img/et.png" alt="UXPacific Badges preview" loading="lazy" />
              <span class="resource-tag">Merchandise</span>

              <div class="resource-body">
                <h5>Badges</h5>
                <p>
                  Wear your creativity! Explore UXPacific T-shirts, badges,
                  stickers, and accessories.
                </p>
                <a href="#" class="resource-link">Go to Store <span aria-hidden="true">→</span></a>
              </div>
            </article>
          </div>

          <!-- Card 4 -->
          <div class="col-lg-3 col-md-6">
            <article class="resource-card">
              <img src="img/et.png" alt="Case Studies preview" loading="lazy" />
              <span class="resource-tag">Case Studies</span>

              <div class="resource-body">
                <h5>Case Studies</h5>
                <p>
                  Dive into real design projects, UX challenges, and research
                  stories crafted by our community.
                </p>
                <a href="#" class="resource-link">Read More <span aria-hidden="true">→</span></a>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

    <!-- EVENTS SECTION -->
    <section class="events-section" id="event-section">
      <!-- Header -->
      <div class="events-header">
        <span class="events-pill mb-4">Events</span>
        <h2>Join Our <span style="color: #6147bd;">Design </span>Events</h2>
        <p>
          Be part of UX Pacific's creative journey where designers, thinkers,
          and creators come together to learn, collaborate, and grow.
        </p>
      </div>

      <div class="events-filters mt-4 mb-5" style="display: flex; gap: 14px; justify-content: center;">
        <button class="filter-btn active" id="btn-upcoming">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
          Upcoming
        </button>
        <button class="filter-btn" id="btn-past">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
          Past
        </button>
      </div>

      <div id="upcoming-events-container">
        <!-- Upcoming Events -->
        <h3 class="events-subtitle">Upcoming Events</h3>

        <div class="events-grid">
        <?php if (empty($upcomingEvents)): ?>
        <div class="text-center" style="grid-column: 1/-1; padding: 60px 20px;">
          <p style="color: #a0a0b8; font-size: 1.1rem;">No upcoming events at the moment. Check back soon!</p>
        </div>
        <?php else: ?>
        <?php foreach ($upcomingEvents as $event): ?>
        <article class="event-card">
          <span class="event-badge">Upcoming Event</span>

          <div class="event-img">
            <?php if (!empty($event['image'])): ?>
            <img src="<?php echo h($event['image']); ?>" alt="<?php echo h($event['title']); ?>" />
            <?php else: ?>
            <img src="img/et.png" alt="<?php echo h($event['title']); ?>" />
            <?php endif; ?>
          </div>

          <div class="event-body">
            <h4><?php echo h($event['title']); ?></h4>
            <p>
              <?php echo h(mb_strimwidth($event['description'] ?? 'Join us for this exciting event!', 0, 120, '...')); ?>
            </p>

            <ul class="event-meta">
              <li>
                <img src="img/Icon.png" alt="" height="15px" width="15px">
                 <?php echo date('M d, Y', strtotime($event['event_date'])); ?> · <?php echo date('h:i A', strtotime($event['event_time'])); ?></li>
              <li>
                <img src="img/Icon-1.png" alt="" height="15px" width="15px">
                <?php echo h($event['location'] ?: 'Virtual'); ?></li>
              <li>
                <img src="img/Icon-2.png" alt="" height="15px" width="15px">
                <?php echo $event['registration_count']; ?>/<?php echo $event['max_registrations']; ?> registered</li>
            </ul>

            <a href="event-register.php?id=<?php echo $event['id']; ?>" class="btn-primary small full">Register Now</a>
          </div>
        </article>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- Duplicate cards as needed -->
        </div>
      </div>

      <div id="past-events-container" style="display: none;">
        <!-- Past Events -->
        <h3 class="events-subtitle">Past Events</h3>

        <div class="events-grid">
        <?php if (empty($pastEvents)): ?>
        <div class="text-center" style="grid-column: 1/-1; padding: 60px 20px;">
          <p style="color: #a0a0b8; font-size: 1.1rem;">No past events to show.</p>
        </div>
        <?php else: ?>
        <?php foreach ($pastEvents as $event): ?>
        <article class="event-card past">
          <span class="event-badge muted">Past Event</span>
          <div class="event-img">
            <?php if (!empty($event['image'])): ?>
            <img src="<?php echo h($event['image']); ?>" alt="<?php echo h($event['title']); ?>" />
            <?php else: ?>
            <img src="img/et.png" alt="<?php echo h($event['title']); ?>" />
            <?php endif; ?>
          </div>
          <div class="event-body">
            <h4><?php echo h($event['title']); ?></h4>
            <p>
              <?php echo h(mb_strimwidth($event['description'] ?? 'This event has concluded.', 0, 120, '...')); ?>
            </p>
            <ul class="event-meta">
              <li>
                <img src="img/Icon.png" alt="" height="15px" width="15px">
                 <?php echo date('M d, Y', strtotime($event['event_date'])); ?> · <?php echo date('h:i A', strtotime($event['event_time'])); ?></li>
              <li>
                <img src="img/Icon-1.png" alt="" height="15px" width="15px">
                <?php echo h($event['location'] ?: 'Virtual'); ?></li>
              <li>
                <img src="img/Icon-2.png" alt="" height="15px" width="15px">
                <?php echo $event['registration_count']; ?> attended</li>
            </ul>
          </div>
        </article>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
      </div>
    </section>


    <div class="reviews-grid-container">
        <div class="reviews-grid">
          <!-- This is the original set of cards that will be duplicated by JS -->
          <div class="review-card-set" id="original-cards">
            <!-- Card 1 -->
            <div class="testimonial-card" style="background-color: #312e81">
              <div class="card-header">
                <div class="stars">
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                </div>
                <div class="pill">Project Experience</div>
              </div>
              <div class="quote">
                We appreciate the professionalism and clarity UX Pacific brought
                to the process and would gladly collaborate again.
              </div>
              <div class="author">
                <div class="avatar">
                  <img alt="Andrew Ajai Singh" src="img/Oval (1).png" />
                </div>
                <div class="author-info">
                  <div class="name">Andrew Ajai Singh</div>
                  <div class="title">Distinct Buzz</div>
                </div>
              </div>
            </div>
            <!-- Card 2 -->
            <div class="testimonial-card" style="background-color: #4338ca">
              <div class="card-header">
                <div class="stars">
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                </div>
                <div class="pill">Workshop Experience</div>
              </div>
              <div class="quote">
                Use engaging activities and mix up groups to boost interaction,
                cut distractions, and make the workshop more effective.
              </div>
              <div class="author">
                <div class="avatar">
                  <img alt="Dharmik Bhavsar" src="img/Oval (2).png" />
                </div>
                <div class="author-info">
                  <div class="name">Dharmik Bhavsar</div>
                  <div class="title">Student</div>
                </div>
              </div>
            </div>
            <!-- Card 3 -->
            <div class="testimonial-card" style="background-color: #4f46e5">
              <div class="card-header">
                <div class="stars">
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                </div>
                <div class="pill">Workshop Experience</div>
              </div>
              <div class="quote">
                Got to explore fresh and engaging activities throughout the
                session. They added a fun and creative touch to the overall
                learning experience!
              </div>
              <div class="author">
                <div class="avatar">
                  <img alt="Diya Mehta" src="./img/DMDM.png" />
                </div>
                <div class="author-info">
                  <div class="name">Diya Mehta</div>
                  <div class="title">Student</div>
                </div>
              </div>
            </div>
            <!-- Card 4 -->
            <div class="testimonial-card" style="background-color: #312e81">
              <div class="card-header">
                <div class="stars">
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                </div>
                <div class="pill">Project Experience</div>
              </div>
              <div class="quote">
                Working with UXPacific for the UX draft was an insightful
                experience. The team's approach was structured, detailed, and
                highly actionable.
              </div>
              <div class="author">
                <div class="avatar">
                  <img alt="Dr. Vishal Singh" src="img/Oval.png" />
                </div>
                <div class="author-info">
                  <div class="name">Dr. Vishal Singh</div>
                  <div class="title">CEDAR Himalaya</div>
                </div>
              </div>
            </div>
            <!-- Card 5 -->
            <div class="testimonial-card" style="background-color: #312e81">
              <div class="card-header">
                <div class="stars">
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                </div>
                <div class="pill">Workshop Experience</div>
              </div>
              <div class="quote">
                Loved the hands-on activities and feedback they really clarified
                the concepts.Great experience connecting with the team
                and participants!
              </div>
              <div class="author">
                <div class="avatar">
                  <img alt="Andrew Ajai Singh" src="img/yuggie.png" />
                </div>
                <div class="author-info">
                  <div class="name">Yugaan Parmar</div>
                  <div class="title">UI/UX & Graphic Designer</div>
                </div>
              </div>
            </div>

            <!-- Card 6 -->
            <div class="testimonial-card" style="background-color: #312e81">
              <div class="card-header">
                <div class="stars">
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                  <svg fill="#ffd166" viewbox="0 0 24 24">
                    <path
                      d="M12 .587l3.668 7.431L24 9.748l-6 5.847L19.335 24 12 20.201 4.665 24 6 15.595 0 9.748l8.332-1.73L12 .587z"
                    ></path>
                  </svg>
                </div>
                <div class="pill">Workshop Experience</div>
              </div>
              <div class="quote">
               Gained deeper knowledge, especially through the process of creating the hero and i understood how structure and design come together !
              </div>
              <div class="author">
                <div class="avatar">
                  <img alt="Andrew Ajai Singh" src="img/Devanshi.png" />
                </div>
                <div class="author-info">
                  <div class="name">Devanshi Akhja</div>
                  <div class="title">Student</div>
                </div>
              </div>
            </div>

            <!-- Card 7  -->
        
         </div>
      </div>
     </div> 

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



    <!-- Event Registration Modal -->
    <div class="modal fade" id="eventRegisterModal" tabindex="-1" aria-labelledby="eventRegisterModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: linear-gradient(180deg, rgba(15, 15, 35, 0.98), rgba(10, 10, 26, 0.99)); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px;">
          <div class="modal-header border-0 pb-0">
            <div class="w-100 text-center">
              <div class="modal-event-badge mb-2">
                <span style="background: rgba(123, 97, 255, 0.2); color: #7b61ff; padding: 6px 16px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">EVENT REGISTRATION</span>
              </div>
              <h5 class="modal-title" id="eventRegisterModalLabel" style="font-family: 'Gabarito', sans-serif; font-size: 1.5rem; color: #fff;">Register for Event</h5>
              <p id="modalEventName" style="color: #a0a0b8; font-size: 0.9rem; margin-top: 8px;">Design Systems Summit 2026</p>
            </div>
            <button type="button" class="btn-close btn-close-white position-absolute" style="top: 20px; right: 20px;" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body px-4 py-4">
            <form id="eventRegistrationForm" novalidate>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="regFirstName" class="form-label" style="color: #b5b5c3; font-size: 0.875rem;">First Name <span style="color: #ef4444;">*</span></label>
                  <input type="text" class="form-control" id="regFirstName" name="firstName" placeholder="Enter first name" required 
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: #fff; padding: 12px 16px;">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="regLastName" class="form-label" style="color: #b5b5c3; font-size: 0.875rem;">Last Name <span style="color: #ef4444;">*</span></label>
                  <input type="text" class="form-control" id="regLastName" name="lastName" placeholder="Enter last name" required
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: #fff; padding: 12px 16px;">
                </div>
              </div>
              <div class="mb-3">
                <label for="regEmail" class="form-label" style="color: #b5b5c3; font-size: 0.875rem;">Email Address <span style="color: #ef4444;">*</span></label>
                <input type="email" class="form-control" id="regEmail" name="email" placeholder="Enter your email address" required
                  style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: #fff; padding: 12px 16px;">
              </div>
              <div class="mb-3">
                <label for="regPhone" class="form-label" style="color: #b5b5c3; font-size: 0.875rem;">Phone Number <span style="color: #ef4444;">*</span></label>
                <input type="tel" class="form-control" id="regPhone" name="phone" placeholder="+91 XXXXX-XXXXX" required
                  style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: #fff; padding: 12px 16px;">
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="regCompany" class="form-label" style="color: #b5b5c3; font-size: 0.875rem;">Company / Organization</label>
                  <input type="text" class="form-control" id="regCompany" name="company" placeholder="Your company name"
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: #fff; padding: 12px 16px;">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="regRole" class="form-label" style="color: #b5b5c3; font-size: 0.875rem;">Your Role <span style="color: #ef4444;">*</span></label>
                  <select class="form-select" id="regRole" name="role" required
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: #fff; padding: 12px 16px;">
                    <option value="" disabled selected style="background: #1a1a2e; color: #6b6b80;">Select your role</option>
                    <option value="designer" style="background: #1a1a2e; color: #fff;">UX/UI Designer</option>
                    <option value="developer" style="background: #1a1a2e; color: #fff;">Developer</option>
                    <option value="product-manager" style="background: #1a1a2e; color: #fff;">Product Manager</option>
                    <option value="researcher" style="background: #1a1a2e; color: #fff;">UX Researcher</option>
                    <option value="student" style="background: #1a1a2e; color: #fff;">Student</option>
                    <option value="freelancer" style="background: #1a1a2e; color: #fff;">Freelancer</option>
                    <option value="other" style="background: #1a1a2e; color: #fff;">Other</option>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label for="regExperience" class="form-label" style="color: #b5b5c3; font-size: 0.875rem;">Experience Level</label>
                <select class="form-select" id="regExperience" name="experience"
                  style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: #fff; padding: 12px 16px;">
                  <option value="" disabled selected style="background: #1a1a2e; color: #6b6b80;">Select experience level</option>
                  <option value="beginner" style="background: #1a1a2e; color: #fff;">Beginner (0-1 years)</option>
                  <option value="intermediate" style="background: #1a1a2e; color: #fff;">Intermediate (2-4 years)</option>
                  <option value="advanced" style="background: #1a1a2e; color: #fff;">Advanced (5+ years)</option>
                  <option value="expert" style="background: #1a1a2e; color: #fff;">Expert (10+ years)</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="regExpectations" class="form-label" style="color: #b5b5c3; font-size: 0.875rem;">What do you hope to learn?</label>
                <textarea class="form-control" id="regExpectations" name="expectations" rows="3" placeholder="Tell us what you're hoping to gain from this event..."
                  style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; color: #fff; padding: 12px 16px; resize: none;"></textarea>
              </div>
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="regUpdates" name="updates" checked
                  style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                <label class="form-check-label" for="regUpdates" style="color: #b5b5c3; font-size: 0.8125rem;">
                  Send me updates about future events and community news
                </label>
              </div>
              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="regTerms" name="terms" required
                  style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                <label class="form-check-label" for="regTerms" style="color: #b5b5c3; font-size: 0.8125rem;">
                  I agree to the <a href="#" style="color: #7b61ff;">Terms & Conditions</a> and <a href="#" style="color: #7b61ff;">Privacy Policy</a> <span style="color: #ef4444;">*</span>
                </label>
              </div>
              <button type="submit" class="btn btn-primary-gradient w-100" style="padding: 14px; font-size: 1rem; font-weight: 600;">
                Complete Registration
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Registration Success Modal -->
    <div class="modal fade" id="registrationSuccessModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center" style="background: linear-gradient(180deg, rgba(15, 15, 35, 0.98), rgba(10, 10, 26, 0.99)); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px 30px;">
          <div class="success-icon mb-4" style="width: 80px; height: 80px; background: rgba(34, 197, 94, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#22c55e" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
          </div>
          <h4 style="font-family: 'Gabarito', sans-serif; color: #fff; margin-bottom: 12px;">Registration Successful!</h4>
          <p style="color: #a0a0b8; font-size: 0.9rem; margin-bottom: 24px;">You've been registered for the event. Check your email for confirmation details.</p>
          <button type="button" class="btn btn-primary-gradient" data-bs-dismiss="modal" style="padding: 12px 32px;">Got it!</button>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/script.js"></script>
  </body>
</html>
