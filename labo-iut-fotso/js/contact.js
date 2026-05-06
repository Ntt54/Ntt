/**
 * JavaScript pour la validation du formulaire de contact
 * Laboratoire de Recherche - IUT Fotso Victor
 */

document.addEventListener('DOMContentLoaded', function() {
    
    const contactForm = document.getElementById('contactForm');
    
    if (!contactForm) return;
    
    // ==========================================
    // Validation en temps réel des champs
    // ==========================================
    const formFields = contactForm.querySelectorAll('input, textarea');
    
    formFields.forEach(field => {
        // Validation au blur (quand on quitte le champ)
        field.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Nettoyage de l'erreur quand on commence à taper
        field.addEventListener('input', function() {
            clearError(this);
        });
    });
    
    // ==========================================
    // Validation globale avant soumission
    // ==========================================
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const fieldsToValidate = ['nom', 'prenom', 'email', 'sujet', 'message'];
        
        fieldsToValidate.forEach(fieldName => {
            const field = contactForm.querySelector(`[name="${fieldName}"]`);
            if (field && !validateField(field)) {
                isValid = false;
            }
        });
        
        // Validation spécifique pour l'email
        const emailField = contactForm.querySelector('[name="email"]');
        if (emailField && !validateEmail(emailField.value)) {
            showError(emailField, getTranslation('invalid_email') || 'Adresse email invalide');
            isValid = false;
        }
        
        // Validation spécifique pour la longueur du message
        const messageField = contactForm.querySelector('[name="message"]');
        if (messageField && messageField.value.trim().length < 20) {
            showError(messageField, getTranslation('message_too_short') || 'Le message doit contenir au moins 20 caractères');
            isValid = false;
        }
        
        // Si tout est valide, soumettre le formulaire
        if (isValid) {
            // Désactiver le bouton de soumission pour éviter les doubles envois
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
            }
            
            // Soumettre le formulaire
            contactForm.submit();
        } else {
            // Scroll vers le premier champ en erreur
            const firstError = contactForm.querySelector('.field-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    // ==========================================
    // Fonctions de validation
    // ==========================================
    
    /**
     * Valide un champ individuel
     */
    function validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        
        // Champ requis vide
        if (isRequired && value === '') {
            showError(field, getTranslation('required_field') || 'Ce champ est requis');
            return false;
        }
        
        // Email invalide
        if (field.type === 'email' && value !== '' && !validateEmail(value)) {
            showError(field, getTranslation('invalid_email') || 'Adresse email invalide');
            return false;
        }
        
        // Message trop court
        if (field.name === 'message' && value.length < 20) {
            showError(field, getTranslation('message_too_short') || 'Le message doit contenir au moins 20 caractères');
            return false;
        }
        
        // Champ valide
        clearError(field);
        markAsValid(field);
        return true;
    }
    
    /**
     * Valide un format email avec regex
     */
    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    /**
     * Affiche une erreur sous un champ
     */
    function showError(field, message) {
        // Supprimer la classe de validité
        field.classList.remove('field-valid');
        field.classList.add('field-error');
        
        // Créer ou mettre à jour le message d'erreur
        let errorElement = field.parentElement.querySelector('.error-message');
        
        if (!errorElement) {
            errorElement = document.createElement('span');
            errorElement.className = 'error-message';
            errorElement.setAttribute('role', 'alert');
            field.parentElement.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
    
    /**
     * Efface l'erreur d'un champ
     */
    function clearError(field) {
        field.classList.remove('field-error');
        
        const errorElement = field.parentElement.querySelector('.error-message');
        if (errorElement) {
            errorElement.style.display = 'none';
            errorElement.textContent = '';
        }
    }
    
    /**
     * Marque un champ comme valide
     */
    function markAsValid(field) {
        field.classList.remove('field-error');
        field.classList.add('field-valid');
    }
    
    // ==========================================
    // Styles CSS dynamiques pour les erreurs
    // ==========================================
    const style = document.createElement('style');
    style.textContent = `
        .field-error {
            border-color: #E74C3C !important;
            background-color: #FDEDEC !important;
        }
        
        .field-valid {
            border-color: #2ECC71 !important;
            background-color: #EAFAF1 !important;
        }
        
        .error-message {
            display: none;
            color: #E74C3C;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            font-weight: 500;
        }
        
        input:focus, textarea:focus {
            outline: none;
            border-color: #3498DB !important;
        }
    `;
    document.head.appendChild(style);
    
});
