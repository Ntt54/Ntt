
</main>
    </div>

    <!-- Scripts -->
    <script src="../js/main.js"></script>
    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('admin-sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        }

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }

        // Fermer le sidebar sur mobile quand on clique en dehors
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // Confirmation de suppression
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>
</html>
