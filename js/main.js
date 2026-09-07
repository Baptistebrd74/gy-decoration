// ===== BURGER MENU =====
const burger = document.getElementById('burger');
const navLinks = document.getElementById('navLinks');

if (burger && navLinks) {
  burger.addEventListener('click', () => {
    burger.classList.toggle('active');
    navLinks.classList.toggle('open');
  });

  // Fermer le menu au clic sur un lien
  navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      burger.classList.remove('active');
      navLinks.classList.remove('open');
    });
  });
}

// ===== HEADER SCROLL =====
const header = document.getElementById('header');

window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

// ===== COMPTEUR ANIMATION =====
function animateCounters() {
  const counters = document.querySelectorAll('.number[data-count]');

  counters.forEach(counter => {
    const target = parseInt(counter.getAttribute('data-count'));
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;

    const updateCounter = () => {
      current += step;
      if (current < target) {
        counter.textContent = Math.floor(current);
        requestAnimationFrame(updateCounter);
      } else {
        counter.textContent = target;
      }
    };

    updateCounter();
  });
}

// Observer pour lancer l'animation quand les compteurs sont visibles
const statsObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      animateCounters();
      statsObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

const statsSection = document.querySelector('.about-stats');
if (statsSection) {
  statsObserver.observe(statsSection);
}

// ===== ANIMATION AU SCROLL =====
const fadeElements = document.querySelectorAll('.service-card, .gallery-item, .about-content, .contact-wrapper');

const fadeObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
      fadeObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.1 });

fadeElements.forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(30px)';
  el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
  fadeObserver.observe(el);
});

// ===== FORMULAIRE CONTACT =====
const contactForm = document.getElementById('contactForm');

function afficherErreurFormulaire(message) {
  let erreurEl = contactForm.querySelector('.form-error');
  if (!erreurEl) {
    erreurEl = document.createElement('p');
    erreurEl.className = 'form-error';
    erreurEl.style.cssText = 'color: #dc2626; font-size: 0.9rem; margin-bottom: 16px;';
    contactForm.querySelector('.form-submit').insertAdjacentElement('beforebegin', erreurEl);
  }
  erreurEl.textContent = message;
}

if (contactForm) {
  contactForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(contactForm);
    const nom = formData.get('nom');
    const prenom = formData.get('prenom');

    const submitBtn = contactForm.querySelector('.form-submit');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Envoi en cours...';
    submitBtn.disabled = true;

    try {
      const response = await fetch('contact.php', {
        method: 'POST',
        body: formData,
      });
      const data = await response.json();

      if (data.success) {
        contactForm.innerHTML = `
          <div style="text-align: center; padding: 40px 0;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="48" height="48" style="color: var(--blue-700); margin: 0 auto 16px;"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5 5.5-6"/></svg>
            <h3 style="color: var(--blue-900); margin-bottom: 12px;">Merci ${prenom} ${nom} !</h3>
            <p style="color: var(--gray-600);">Votre demande de devis a bien été envoyée.<br>Nous vous recontacterons sous 24h.</p>
          </div>
        `;
      } else {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        afficherErreurFormulaire("Merci de vérifier les champs du formulaire, certains sont manquants ou invalides.");
      }
    } catch (error) {
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;
      afficherErreurFormulaire("Une erreur est survenue lors de l'envoi. Merci de réessayer ou de nous contacter par téléphone.");
    }
  });
}
