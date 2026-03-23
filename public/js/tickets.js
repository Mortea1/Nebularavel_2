// Gestion des filtres et de la recherche pour les tickets
document.addEventListener('DOMContentLoaded', function() {
    const priorityFilter = document.getElementById('priority-filter');
    const statusFilter = document.getElementById('status-filter');
    const searchInput = document.querySelector('.search-box input');
    const tableRows = document.querySelectorAll('.data-table tbody tr');

    // Fonction principale de filtrage
    function filterTickets() {
        const priorityValue = priorityFilter.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const searchValue = searchInput.value.toLowerCase().trim();

        tableRows.forEach(row => {
            const priority = row.querySelector('.badge').textContent.toLowerCase();
            const status = row.querySelectorAll('.badge')[1].textContent.toLowerCase();
            const ticketId = row.cells[0].textContent.toLowerCase();
            const title = row.cells[1].textContent.toLowerCase();
            const project = row.cells[2].textContent.toLowerCase();
            const assignee = row.cells[5].textContent.toLowerCase();

            // Vérifier le filtre de priorité
            let priorityMatch = priorityValue === 'all';
            if (!priorityMatch) {
                if (priorityValue === 'low' && priority.includes('basse')) priorityMatch = true;
                if (priorityValue === 'medium' && priority.includes('moyenne')) priorityMatch = true;
                if (priorityValue === 'high' && priority.includes('haute')) priorityMatch = true;
                if (priorityValue === 'urgent' && priority.includes('urgente')) priorityMatch = true;
            }

            // Vérifier le filtre de statut
            let statusMatch = statusValue === 'all';
            if (!statusMatch) {
                if (statusValue === 'open' && status.includes('ouvert')) statusMatch = true;
                if (statusValue === 'progress' && status.includes('en cours')) statusMatch = true;
                if (statusValue === 'resolved' && status.includes('résolu')) statusMatch = true;
                if (statusValue === 'closed' && status.includes('fermé')) statusMatch = true;
            }

            // Vérifier la recherche
            let searchMatch = searchValue === '';
            if (!searchMatch) {
                searchMatch = ticketId.includes(searchValue) ||
                             title.includes(searchValue) ||
                             project.includes(searchValue) ||
                             assignee.includes(searchValue);
            }

            // Afficher ou masquer la ligne
            if (priorityMatch && statusMatch && searchMatch) {
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
                noResultsRow.innerHTML = '<td colspan="8" style="text-align: center; padding: 2rem; color: #666;">Aucun ticket ne correspond à vos critères de recherche.</td>';
                document.querySelector('.data-table tbody').appendChild(noResultsRow);
            }
        } else {
            if (noResultsRow) {
                noResultsRow.remove();
            }
        }
    }

    // Événements
    priorityFilter.addEventListener('change', filterTickets);
    statusFilter.addEventListener('change', filterTickets);
    searchInput.addEventListener('input', filterTickets);

    // Réinitialiser les filtres au chargement
    priorityFilter.value = 'all';
    statusFilter.value = 'all';
    searchInput.value = '';
});