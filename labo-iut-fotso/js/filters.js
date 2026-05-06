/**
 * JavaScript pour les filtres dynamiques
 * Laboratoire de Recherche - IUT Fotso Victor
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // Filtres par boutons (grade, type, statut, catégorie)
    // ==========================================
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const filterGroup = this.closest('.filter-buttons');
            const filterValue = this.dataset.filter;
            const targetSelector = this.dataset.target;
            
            // Gestion de la classe active
            if (filterGroup) {
                filterGroup.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            }
            
            // Application du filtre
            if (targetSelector && filterValue) {
                applyFilter(targetSelector, filterValue);
            }
        });
    });
    
    function applyFilter(selector, value) {
        const elements = document.querySelectorAll(selector);
        let visibleCount = 0;
        
        elements.forEach(element => {
            const elementValue = element.dataset.value || '';
            
            if (value === 'all' || elementValue === value) {
                element.style.display = '';
                visibleCount++;
            } else {
                element.style.display = 'none';
            }
        });
        
        // Afficher/masquer le message "aucun résultat"
        const emptyState = document.getElementById(selector.replace('.', '') + '-empty');
        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }
    
    // ==========================================
    // Filtre combiné: Type + Année pour les publications
    // ==========================================
    const publicationTypeFilter = document.getElementById('publicationTypeFilter');
    const publicationYearFilter = document.getElementById('publicationYearFilter');
    
    if (publicationTypeFilter && publicationYearFilter) {
        publicationTypeFilter.addEventListener('change', filterPublicationsCombined);
        publicationYearFilter.addEventListener('change', filterPublicationsCombined);
    }
    
    function filterPublicationsCombined() {
        const typeValue = publicationTypeFilter?.value || 'all';
        const yearValue = publicationYearFilter?.value || 'all';
        
        const publications = document.querySelectorAll('.publication-item');
        let visibleCount = 0;
        
        publications.forEach(pub => {
            const pubType = pub.dataset.type || '';
            const pubYear = pub.dataset.year || '';
            
            const typeMatch = typeValue === 'all' || pubType === typeValue;
            const yearMatch = yearValue === 'all' || pubYear === yearValue;
            
            if (typeMatch && yearMatch) {
                pub.style.display = '';
                visibleCount++;
            } else {
                pub.style.display = 'none';
            }
        });
        
        const emptyState = document.getElementById('publications-empty');
        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }
    
    // ==========================================
    // Filtre par statut pour les projets
    // ==========================================
    const projetStatutFilter = document.getElementById('projetStatutFilter');
    
    if (projetStatutFilter) {
        projetStatutFilter.addEventListener('change', function() {
            const statutValue = this.value;
            const projets = document.querySelectorAll('.projet-card');
            let visibleCount = 0;
            
            projets.forEach(projet => {
                const projetStatut = projet.dataset.statut || '';
                
                if (statutValue === 'all' || projetStatut === statutValue) {
                    projet.style.display = '';
                    visibleCount++;
                } else {
                    projet.style.display = 'none';
                }
            });
            
            const emptyState = document.getElementById('projets-empty');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    }
    
    // ==========================================
    // Filtre par catégorie pour les actualités
    // ==========================================
    const actualiteCategorieFilter = document.getElementById('actualiteCategorieFilter');
    
    if (actualiteCategorieFilter) {
        actualiteCategorieFilter.addEventListener('change', function() {
            const categorieValue = this.value;
            const actualites = document.querySelectorAll('.actualite-card');
            let visibleCount = 0;
            
            actualites.forEach(actualite => {
                const actualiteCategorie = actualite.dataset.categorie || '';
                
                if (categorieValue === 'all' || actualiteCategorie === categorieValue) {
                    actualite.style.display = '';
                    visibleCount++;
                } else {
                    actualite.style.display = 'none';
                }
            });
            
            const emptyState = document.getElementById('actualites-empty');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    }
    
    // ==========================================
    // Filtre par grade pour l'équipe
    // ==========================================
    const equipeGradeFilter = document.getElementById('equipeGradeFilter');
    
    if (equipeGradeFilter) {
        equipeGradeFilter.addEventListener('change', function() {
            const gradeValue = this.value;
            const chercheurs = document.querySelectorAll('.chercheur-card');
            let visibleCount = 0;
            
            chercheurs.forEach(chercheur => {
                const chercheurGrade = chercheur.dataset.grade || '';
                
                if (gradeValue === 'all' || chercheurGrade === gradeValue) {
                    chercheur.style.display = '';
                    visibleCount++;
                } else {
                    chercheur.style.display = 'none';
                }
            });
            
            const emptyState = document.getElementById('equipe-empty');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    }
    
    // ==========================================
    // Accordéon pour événements passés
    // ==========================================
    const accordionTriggers = document.querySelectorAll('.accordion-trigger');
    
    accordionTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const accordionContent = this.nextElementSibling;
            const isCollapsed = this.classList.contains('collapsed');
            
            // Fermer tous les autres accordéons
            accordionTriggers.forEach(t => {
                if (t !== this) {
                    t.classList.add('collapsed');
                    t.nextElementSibling.style.maxHeight = null;
                }
            });
            
            // Basculer l'état actuel
            if (isCollapsed) {
                this.classList.remove('collapsed');
                accordionContent.style.maxHeight = accordionContent.scrollHeight + 'px';
            } else {
                this.classList.add('collapsed');
                accordionContent.style.maxHeight = null;
            }
        });
    });
    
    // ==========================================
    // Tri des éléments (par date, par nom, etc.)
    // ==========================================
    const sortSelect = document.getElementById('sortSelect');
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const container = document.querySelector(this.dataset.container);
            
            if (!container) return;
            
            const items = Array.from(container.children);
            
            items.sort((a, b) => {
                if (sortValue === 'date-desc') {
                    const dateA = new Date(a.dataset.date || 0);
                    const dateB = new Date(b.dataset.date || 0);
                    return dateB - dateA;
                } else if (sortValue === 'date-asc') {
                    const dateA = new Date(a.dataset.date || 0);
                    const dateB = new Date(b.dataset.date || 0);
                    return dateA - dateB;
                } else if (sortValue === 'name-asc') {
                    const nameA = a.querySelector('.card-title')?.textContent || '';
                    const nameB = b.querySelector('.card-title')?.textContent || '';
                    return nameA.localeCompare(nameB, 'fr');
                } else if (sortValue === 'name-desc') {
                    const nameA = a.querySelector('.card-title')?.textContent || '';
                    const nameB = b.querySelector('.card-title')?.textContent || '';
                    return nameB.localeCompare(nameA, 'fr');
                }
                return 0;
            });
            
            // Réinsérer les éléments triés
            items.forEach(item => container.appendChild(item));
        });
    }
    
    // ==========================================
    // Pagination simple (affichage par lots)
    // ==========================================
    const loadMoreButtons = document.querySelectorAll('.load-more-btn');
    
    loadMoreButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const container = document.querySelector(this.dataset.container);
            const itemSelector = this.dataset.item;
            const increment = parseInt(this.dataset.increment) || 6;
            
            if (!container) return;
            
            const items = container.querySelectorAll(itemSelector);
            let shownCount = 0;
            
            items.forEach(item => {
                if (shownCount < increment && item.style.display === 'none') {
                    item.style.display = '';
                    shownCount++;
                }
            });
            
            // Masquer le bouton si tous les éléments sont affichés
            const hiddenItems = container.querySelectorAll(itemSelector + '[style*="display: none"]');
            if (hiddenItems.length === 0) {
                this.style.display = 'none';
            }
        });
    });
    
});
