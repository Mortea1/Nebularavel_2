// Gestion des filtres et de la recherche pour les projets
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const searchInput = document.querySelector('.search-box input');
    const tableRows = document.querySelectorAll('.data-table tbody tr');

    // Fonction principale de filtrage
    function filterProjects() {
        const statusValue = statusFilter.value.toLowerCase();
        const searchValue = searchInput.value.toLowerCase().trim();

        tableRows.forEach(row => {
            const status = row.querySelector('.badge').textContent.toLowerCase();
            const projectName = row.cells[0].textContent.toLowerCase();
            const client = row.cells[1].textContent.toLowerCase();
            const deadline = row.cells[4].textContent.toLowerCase();

            // Vérifier le filtre de statut
            let statusMatch = statusValue === 'all';
            if (!statusMatch) {
                if (statusValue === 'planning' && status.includes('planification')) statusMatch = true;
                if (statusValue === 'progress' && status.includes('en cours')) statusMatch = true;
                if (statusValue === 'completed' && status.includes('terminé')) statusMatch = true;
            }

            // Vérifier la recherche
            let searchMatch = searchValue === '';
            if (!searchMatch) {
                searchMatch = projectName.includes(searchValue) ||
                             client.includes(searchValue) ||
                             deadline.includes(searchValue);
            }

            // Afficher ou masquer la ligne
            if (statusMatch && searchMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Afficher un message si aucun résultat
        updateNoResultsMessage();
    }

    // Fonction pour afficher un message si aucun résultat
    function updateNoResultsMessage() {
        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        let noResultsRow = document.querySelector('.no-results-row');

        if (visibleRows.length === 0) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = '<td colspan="6" style="text-align: center; padding: 2rem; color: #666;">Aucun projet ne correspond à vos critères de recherche.</td>';
                document.querySelector('.data-table tbody').appendChild(noResultsRow);
            }
        } else {
            if (noResultsRow) {
                noResultsRow.remove();
            }
        }
    }

    // Événements
    statusFilter.addEventListener('change', filterProjects);
    searchInput.addEventListener('input', filterProjects);

    // Réinitialiser les filtres au chargement
    statusFilter.value = 'all';
    searchInput.value = '';
});