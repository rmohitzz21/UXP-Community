(() => {
  function getSubmitButton(form) {
    return form.querySelector('button[type="submit"], input[type="submit"]');
  }

  function setLoading(form, isLoading) {
    const btn = getSubmitButton(form);
    if (!btn) return;

    if (!btn.dataset.originalText) {
      btn.dataset.originalText = btn.innerHTML;
    }

    if (isLoading) {
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...';
    } else {
      btn.disabled = false;
      btn.innerHTML = btn.dataset.originalText;
    }
  }

  async function submitForm(form) {
    const endpoint = form.getAttribute('action') || 'includes/form-handler.php';
    const body = new FormData(form);
    if (!body.get('form_type')) {
      throw new Error('Missing form type.');
    }

    const response = await fetch(endpoint, {
      method: 'POST',
      body,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    const data = await response.json().catch(() => ({
      success: false,
      message: 'Invalid server response.'
    }));

    if (!response.ok || !data.success) {
      throw new Error(data.message || 'Submission failed.');
    }
    return data;
  }

  function showSuccess() {
    Swal.fire({
      icon: 'success',
      title: 'Submitted Successfully!',
      text: 'We’ll contact you soon.',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  }

  function showError(message) {
    Swal.fire({
      icon: 'error',
      title: 'Submission Failed',
      text: message || 'Something went wrong. Please try again.',
      confirmButtonColor: '#dc3545'
    });
  }

  function bindAjaxForms() {
    const forms = document.querySelectorAll('form.js-ajax-form');
    forms.forEach((form) => {
      let inFlight = false;
      form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (inFlight) return;

        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        inFlight = true;
        setLoading(form, true);
        try {
          await submitForm(form);
          showSuccess();
          form.reset();
        } catch (error) {
          showError(error.message);
        } finally {
          setLoading(form, false);
          inFlight = false;
        }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', bindAjaxForms);
})();
