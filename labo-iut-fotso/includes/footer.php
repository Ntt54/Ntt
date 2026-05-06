<?php
/**
 * Pied de page global pour le site public
 * Laboratoire de Recherche - IUT Fotso Victor
 */
?>
    </main>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <!-- Section 1: Logo & Description -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-flask"></i>
                        <h3 data-fr="Laboratoire de Recherche" data-en="Research Laboratory">Laboratoire de Recherche</h3>
                    </div>
                    <p data-fr="Excellence scientifique au cœur du Cameroun. Nous menons des recherches innovantes dans les domaines de l'informatique, du génie civil, de l'électronique et des mathématiques appliquées." 
                       data-en="Scientific excellence in the heart of Cameroon. We conduct innovative research in computer science, civil engineering, electronics and applied mathematics.">
                        Excellence scientifique au cœur du Cameroun. Nous menons des recherches innovantes dans les domaines de l'informatique, du génie civil, de l'électronique et des mathématiques appliquées.
                    </p>
                </div>
                
                <!-- Section 2: Liens Rapides -->
                <div class="footer-section">
                    <h4 data-fr="Liens Rapides" data-en="Quick Links">Liens Rapides</h4>
                    <ul>
                        <li><a href="about.php" data-fr="À propos" data-en="About">À propos</a></li>
                        <li><a href="equipe.php" data-fr="Équipe" data-en="Team">Équipe</a></li>
                        <li><a href="axes.php" data-fr="Axes de recherche" data-en="Research Areas">Axes de recherche</a></li>
                        <li><a href="publications.php" data-fr="Publications" data-en="Publications">Publications</a></li>
                        <li><a href="projets.php" data-fr="Projets" data-en="Projects">Projets</a></li>
                        <li><a href="contact.php" data-fr="Contact" data-en="Contact">Contact</a></li>
                        <li><a href="admin/login.php" data-fr="Espace Admin" data-en="Admin Area">Espace Admin</a></li>
                    </ul>
                </div>
                
                <!-- Section 3: Coordonnées -->
                <div class="footer-section">
                    <h4 data-fr="Coordonnées" data-en="Contact Info">Coordonnées</h4>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span data-fr="IUT Fotso Victor, Bandjoun, Cameroun" data-en="IUT Fotso Victor, Bandjoun, Cameroon">IUT Fotso Victor, Bandjoun, Cameroun</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:labo.recherche@iutfv.cm">labo.recherche@iutfv.cm</a>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <a href="tel:+237699000000">+237 699 000 000</a>
                        </li>
                    </ul>
                    
                    <!-- Réseaux Sociaux -->
                    <div class="social-links">
                        <a href="#" aria-label="Twitter/X"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                
                <!-- Section 4: Newsletter -->
                <div class="footer-section">
                    <h4 data-fr="Newsletter" data-en="Newsletter">Newsletter</h4>
                    <p data-fr="Restez informé de nos actualités" data-en="Stay informed about our news">Restez informé de nos actualités</p>
                    <form action="contact.php?action=newsletter" method="POST" class="newsletter-form">
                        <input type="email" name="email" placeholder="Votre email" required aria-label="Email pour newsletter">
                        <button type="submit" class="btn btn-primary" data-fr="S'inscrire" data-en="Subscribe">S'inscrire</button>
                    </form>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <span data-fr="Laboratoire de Recherche – IUT Fotso Victor de Bandjoun" data-en="Research Laboratory – IUT Fotso Victor Bandjoun">Laboratoire de Recherche – IUT Fotso Victor de Bandjoun</span>. <span data-fr="Tous droits réservés." data-en="All rights reserved.">Tous droits réservés.</span></p>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <button id="backToTop" class="back-to-top" aria-label="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Scripts JavaScript -->
    <script src="js/main.js"></script>
    <script src="js/filters.js"></script>
    <script src="js/lang.js"></script>
</body>
</html>
