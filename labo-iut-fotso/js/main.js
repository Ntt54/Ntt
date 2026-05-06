/**
 * JavaScript principal - Navigation, scroll, animations
 * Laboratoire de Recherche - IUT Fotso Victor
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // Menu Hamburger Mobile
    // ==========================================
    const menuToggle = document.getElementById('menuToggle');
    const mainNav = document.getElementById('mainNav');
    
    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            mainNav.classList.toggle('open');
        });
        
        // Fermer le menu au clic sur un lien
        const navLinks = mainNav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                menuToggle.classList.remove('active');
                mainNav.classList.remove('open');
            });
        });
    }
    
    // ==========================================
    // Smooth Scroll pour les ancres
    // ==========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId !== '#' && targetId !== '#!') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // ==========================================
    // Animation Fade-in au scroll (IntersectionObserver)
    // ==========================================
    const fadeElements = document.querySelectorAll('.fade-in');
    
    if (fadeElements.length > 0) {
        const fadeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    fadeObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        fadeElements.forEach(element => {
            fadeObserver.observe(element);
        });
    }
    
    // ==========================================
    // Bouton Back-to-Top
    // ==========================================
    const backToTopBtn = document.getElementById('backToTop');
    
    if (backToTopBtn) {
        // Afficher/masquer le bouton selon le scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        });
        
        // Scroll vers le haut au clic
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // ==========================================
    // Modales
    // ==========================================
    const modals = document.querySelectorAll('.modal');
    const modalCloseButtons = document.querySelectorAll('.modal-close');
    
    // Fermeture via bouton X
    modalCloseButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Fermeture via clic sur l'overlay
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Fermeture via touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modals.forEach(modal => {
                if (modal.classList.contains('active')) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
    });
    
    // ==========================================
    // Barre de recherche globale
    // ==========================================
    const globalSearchInput = document.getElementById('globalSearch');
    
    if (globalSearchInput) {
        globalSearchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            // Selon la page actuelle, filtrer différents éléments
            const currentPage = window.location.pathname.split('/').pop();
            
            if (currentPage === 'equipe.php') {
                filterEquipe(searchTerm);
            } else if (currentPage === 'publications.php') {
                filterPublications(searchTerm);
            } else if (currentPage === 'projets.php') {
                filterProjets(searchTerm);
            } else if (currentPage === 'actualites.php') {
                filterActualites(searchTerm);
            } else if (currentPage === 'partenaires.php') {
                filterPartenaires(searchTerm);
            }
        });
    }
    
    // Fonctions de filtre (seront appelées depuis filters.js si besoin)
    window.filterEquipe = function(term) {
        const cards = document.querySelectorAll('.chercheur-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const name = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
            const specialite = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
            
            if (name.includes(term) || specialite.includes(term)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        toggleEmptyState('equipe-empty', visibleCount === 0);
    };
    
    window.filterPublications = function(term) {
        const items = document.querySelectorAll('.publication-item');
        let visibleCount = 0;
        
        items.forEach(item => {
            const title = item.querySelector('.publication-title')?.textContent.toLowerCase() || '';
            const authors = item.querySelector('.publication-authors')?.textContent.toLowerCase() || '';
            const keywords = item.dataset.keywords || '';
            
            if (title.includes(term) || authors.includes(term) || keywords.includes(term)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        toggleEmptyState('publications-empty', visibleCount === 0);
    };
    
    window.filterProjets = function(term) {
        const cards = document.querySelectorAll('.projet-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
            const description = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
            
            if (title.includes(term) || description.includes(term)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        toggleEmptyState('projets-empty', visibleCount === 0);
    };
    
    window.filterActualites = function(term) {
        const cards = document.querySelectorAll('.actualite-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
            const content = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
            
            if (title.includes(term) || content.includes(term)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        toggleEmptyState('actualites-empty', visibleCount === 0);
    };
    
    window.filterPartenaires = function(term) {
        const cards = document.querySelectorAll('.partenaire-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const name = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
            const country = card.querySelector('.partenaire-pays')?.textContent.toLowerCase() || '';
            
            if (name.includes(term) || country.includes(term)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        toggleEmptyState('partenaires-empty', visibleCount === 0);
    };
    
    function toggleEmptyState(emptyId, show) {
        const emptyState = document.getElementById(emptyId);
        if (emptyState) {
            emptyState.style.display = show ? 'block' : 'none';
        }
    }
    
    // ==========================================
    // Animation des compteurs de statistiques
    // ==========================================
    const statNumbers = document.querySelectorAll('.stat-number');
    
    if (statNumbers.length > 0) {
        const animateCounter = (element) => {
            const target = parseInt(element.textContent);
            if (isNaN(target)) return;
            
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 30);
        };
        
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        statNumbers.forEach(stat => {
            counterObserver.observe(stat);
        });
    }
    
    // ==========================================
    // Gestion des formulaires avec confirmation
    // ==========================================
    const confirmForms = document.querySelectorAll('[data-confirm]');
    
    confirmForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const message = this.dataset.confirm || 'Êtes-vous sûr de vouloir continuer ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // ==========================================
    // Auto-dismiss des alertes après 5 secondes
    // ==========================================
    const alerts = document.querySelectorAll('.alert-dismissible');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
    
});
