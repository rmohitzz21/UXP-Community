<footer id="" class="site-footer">
        <div class="footer-main">
          <div class="footer-top">
            <div class="footer-brand">
              <img src=\"assets/img/LOGO.png" alt="UX Pacific" />
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
                  <img src=\"assets/img/bl.png" alt="Dribbble" />
                </a>

                <a
                  href="https://www.instagram.com/official_uxpacific/"
                  target="_blank"
                  rel="noopener"
                >
                  <img src=\"assets/img/i.png" alt="Instagram" />
                </a>

                <a
                  href="https://www.linkedin.com/company/uxpacific/"
                  target="_blank"
                  rel="noopener"
                >
                  <img src=\"assets/img/in1.png" alt="LinkedIn" />
                </a>

                <a
                  href="https://in.pinterest.com/uxpacific/"
                  target="_blank"
                  rel="noopener"
                >
                  <img src=\"assets/img/p.png" alt="Pinterest" />
                </a>

                <a
                  href="https://www.behance.net/ux_pacific"
                  target="_blank"
                  rel="noopener"
                >
                  <img src=\"assets/img/be.png" alt="Behance" />
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
    <script src="assets/js/script.js"></script>
    <script>
      // Example API integration
      document.addEventListener('DOMContentLoaded', () => {
        fetch('../api/events.php')
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              console.log('Events loaded from API:', data.data);
              // Integration logic here
            }
          })
          .catch(err => console.error('Error fetching events:', err));
      });
    </script>
  </body>
</html>
