/**
 * JavaScript pour le switcher de langue FR/EN
 * Laboratoire de Recherche - IUT Fotso Victor
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // Gestion du switcher de langue
    // ==========================================
    const langButtons = document.querySelectorAll('.lang-btn');
    const langSwitchers = document.querySelectorAll('.lang-switcher');
    
    // Charger la langue depuis localStorage ou utiliser FR par défaut
    let currentLang = localStorage.getItem('lang') || 'fr';
    applyLanguage(currentLang);
    
    // Gestion des clics sur les boutons de langue
    langSwitchers.forEach(switcher => {
        const buttons = switcher.querySelectorAll('.lang-btn');
        
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                const selectedLang = this.dataset.lang;
                
                if (selectedLang !== currentLang) {
                    currentLang = selectedLang;
                    localStorage.setItem('lang', currentLang);
                    applyLanguage(currentLang);
                    
                    // Mettre à jour l'état actif des boutons
                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    });
    
    // Mettre à jour l'état actif des boutons au chargement
    langButtons.forEach(btn => {
        if (btn.dataset.lang === currentLang) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    /**
     * Applique la traduction pour tous les éléments avec data-fr et data-en
     */
    function applyLanguage(lang) {
        const translatableElements = document.querySelectorAll('[data-fr][data-en]');
        
        translatableElements.forEach(element => {
            const frenchText = element.getAttribute('data-fr');
            const englishText = element.getAttribute('data-en');
            
            if (lang === 'en' && englishText) {
                // Sauvegarder le texte français dans un attribut temporaire
                if (!element.hasAttribute('data-original-text')) {
                    element.setAttribute('data-original-text', element.textContent.trim());
                }
                element.textContent = englishText;
            } else if (frenchText) {
                element.textContent = frenchText;
            }
        });
        
        // Mettre à jour l'attribut lang du HTML
        document.documentElement.lang = lang;
        
        // Déclencher un événement personnalisé pour informer d'autres scripts
        window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang } }));
    }
    
    // ==========================================
    // Dictionnaire de traductions supplémentaires
    // (pour les contenus dynamiques ou textes complexes)
    // ==========================================
    const translations = {
        fr: {
            'no_results': 'Aucun résultat trouvé.',
            'loading': 'Chargement...',
            'error': 'Une erreur est survenue.',
            'success': 'Opération réussie.',
            'confirm_delete': 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            'search_placeholder': 'Rechercher...',
            'read_more': 'Lire la suite',
            'download': 'Télécharger',
            'view_online': 'Voir en ligne',
            'contact_us': 'Contactez-nous',
            'send_message': 'Envoyer le message',
            'required_field': 'Ce champ est requis',
            'invalid_email': 'Adresse email invalide',
            'message_too_short': 'Le message doit contenir au moins 20 caractères'
        },
        en: {
            'no_results': 'No results found.',
            'loading': 'Loading...',
            'error': 'An error occurred.',
            'success': 'Operation successful.',
            'confirm_delete': 'Are you sure you want to delete this item?',
            'search_placeholder': 'Search...',
            'read_more': 'Read more',
            'download': 'Download',
            'view_online': 'View online',
            'contact_us': 'Contact us',
            'send_message': 'Send message',
            'required_field': 'This field is required',
            'invalid_email': 'Invalid email address',
            'message_too_short': 'Message must be at least 20 characters'
        }
    };
    
    /**
     * Fonction utilitaire pour obtenir une traduction
     */
    window.getTranslation = function(key) {
        return translations[currentLang]?.[key] || translations['fr'][key] || key;
    };
    
    /**
     * Fonction pour mettre à jour des éléments spécifiques avec des traductions
     */
    window.updateTranslation = function(elementId, key) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = getTranslation(key);
        }
    };
    
    // Écouter les changements de langue depuis d'autres scripts
    window.addEventListener('languageChanged', function(e) {
        console.log('Langue changée:', e.detail.lang);
        // Ici, on peut ajouter des traitements supplémentaires si nécessaire
    });
    
});
