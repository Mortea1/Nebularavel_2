@extends('layouts.app')

@section('title', 'Projets')

@section('content')
    <!-- Main Content -->
        <header class="page-header">
            <h1>Centre d'aide</h1>
        </header>

        <!-- Search Help
        <div class="help-search">
            <input type="search" placeholder="Rechercher dans l'aide..." class="help-search-input">
            <button class="btn btn-primary">Rechercher</button>
        </div> -->

        <!-- Quick Links -->
        <div class="content-section">
            <h2>Liens rapides</h2>
            <div class="help-links-grid">
                <a href="{{ url('dashboard') }}" class="help-link-card">
                    <span class="help-icon">📊</span>
                    <h3>Tableau de bord</h3>
                    <p>Accueil de l'application</p>
                </a>

                <a href="{{ url('projects') }}" class="help-link-card">
                    <span class="help-icon">📁</span>
                    <h3>Gérer les projets</h3>
                    <p>Créer et organiser vos projets</p>
                </a>

                <a href="{{ url('tickets') }}" class="help-link-card">
                    <span class="help-icon">🎫</span>
                    <h3>Système de tickets</h3>
                    <p>Créer et suivre les tickets</p>
                </a>

            </div>
        </div>

        <!-- FAQ -->
        <div class="content-section">
            <h2>Questions fréquentes (FAQ)</h2>

            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Comment créer un nouveau projet ?</span>
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        <p>Pour créer un nouveau projet :</p>
                        <ol>
                            <li>Cliquez sur "Projets" dans le menu latéral</li>
                            <li>Cliquez sur le bouton "+ Nouveau projet"</li>
                            <li>Remplissez les informations du projet (nom, client, description, etc.)</li>
                            <li>Définissez les dates de début et de fin</li>
                            <li>Cliquez sur "Créer" pour enregistrer</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Comment assigner un ticket à un membre de l'équipe ?</span>
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        <p>Il existe deux méthodes pour assigner un ticket :</p>
                        <p><strong>Méthode 1 - Lors de la création :</strong></p>
                        <ol>
                            <li>Lors de la création d'un ticket, utilisez le champ "Assigner à"</li>
                            <li>Sélectionnez le membre de l'équipe dans la liste déroulante</li>
                        </ol>
                        <p><strong>Méthode 2 - Depuis un ticket existant :</strong></p>
                        <ol>
                            <li>Ouvrez le ticket en cliquant dessus</li>
                            <li>Dans la barre latérale droite, modifiez le champ "Assigné à"</li>
                            <li>Cliquez sur "Enregistrer les modifications"</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Comment modifier la priorité d'un ticket ?</span>
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        <p>Pour modifier la priorité d'un ticket :</p>
                        <ol>
                            <li>Ouvrez le ticket concerné</li>
                            <li>Dans la barre latérale droite, trouvez le champ "Priorité"</li>
                            <li>Sélectionnez la nouvelle priorité (Basse, Moyenne, Haute, Urgente)</li>
                            <li>Cliquez sur "Enregistrer les modifications"</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Comment ajouter un commentaire à un ticket ?</span>
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        <p>Pour ajouter un commentaire :</p>
                        <ol>
                            <li>Ouvrez le ticket</li>
                            <li>Faites défiler jusqu'à la section "Commentaires"</li>
                            <li>Écrivez votre commentaire dans la zone de texte</li>
                            <li>Cliquez sur "Publier"</li>
                        </ol>
                        <p>Tous les membres assignés au projet recevront une notification.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Comment filtrer les tickets ?</span>
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        <p>Sur la page des tickets, vous pouvez utiliser plusieurs filtres :</p>
                        <ul>
                            <li><strong>Par priorité :</strong> Basse, Moyenne, Haute, Urgente</li>
                            <li><strong>Par statut :</strong> Ouvert, En cours, Résolu, Fermé</li>
                            <li><strong>Par recherche :</strong> Utilisez la barre de recherche pour trouver des mots-clés</li>
                        </ul>
                        <p>Vous pouvez combiner plusieurs filtres pour affiner votre recherche.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Comment changer mon mot de passe ?</span>
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        <p>Pour modifier votre mot de passe :</p>
                        <ol>
                            <li>Allez dans "Profil" depuis le menu latéral</li>
                            <li>Faites défiler jusqu'à la section "Changer le mot de passe"</li>
                            <li>Entrez votre mot de passe actuel</li>
                            <li>Entrez votre nouveau mot de passe</li>
                            <li>Confirmez le nouveau mot de passe</li>
                            <li>Cliquez sur "Modifier le mot de passe"</li>
                        </ol>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Comment gérer les notifications ?</span>
                        <span class="faq-toggle">+</span>
                    </button>
                    <div class="faq-answer">
                        <p>Pour personnaliser vos notifications :</p>
                        <ol>
                            <li>Allez dans "Paramètres" depuis le menu latéral</li>
                            <li>Trouvez la section "Notifications"</li>
                            <li>Activez ou désactivez chaque type de notification selon vos préférences</li>
                            <li>Cliquez sur "Enregistrer tous les paramètres"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="content-section">
            <h2>Besoin d'aide supplémentaire ?</h2>
            <div class="support-options">
                <div class="support-card">
                    <span class="support-icon">📧</span>
                    <h3>Email</h3>
                    <p>support@gestion-projets.com</p>
                    <p class="support-meta">Réponse sous 24h</p>
                </div>

                <div class="support-card">
                    <span class="support-icon">📚</span>
                    <h3>Documentation</h3>
                    <p>Guide complet et tutoriels</p>
                    <a href="#" class="btn btn-outline btn-small">Voir la FAQ</a>
                </div>
            </div>
        </div>
<script>

// FAQ Toggle
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', () => {
            const faqItem = button.parentElement;
            const isActive = faqItem.classList.contains('active');

        // Close all FAQ items
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
        });

        // Open clicked item if it wasn't active
        if (!isActive) {
            faqItem.classList.add('active');
            button.querySelector('.faq-toggle').textContent = '−';
        } else {
            button.querySelector('.faq-toggle').textContent = '+';
        }
    });
});
</script>
@endsection
