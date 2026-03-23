// ─── Slideshow avatar ────────────────────────────────────────────────────────
// Fait défiler les images de profil toutes les 3 secondes
const avatarImg = document.querySelector('.profile-avatar img');
const images = ['assets/img_profile/img1.png', 'assets/img_profile/img2.jpg', 'assets/img_profile/img3.png'];
let currentIndex = 0;

setInterval(() => {
    currentIndex = (currentIndex + 1) % images.length;
    avatarImg.src = images[currentIndex];
}, 3000); // 3 secondes (1000ms était trop rapide)


// ─── Toast (notification flottante) ──────────────────────────────────────────
// type = 'success' ou 'error'
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast show ${type}`;

    // Disparaît après 4 secondes
    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}


// ─── Formulaire profil (nom, email, etc.) ────────────────────────────────────
// Ce formulaire utilise un submit HTML classique — pas d'API, pas de fetch.
// Laravel gère la validation et redirige avec un message flash.
document.getElementById('profileForm').addEventListener('submit', function (e) {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName  = document.getElementById('lastName').value.trim();
    const email     = document.getElementById('email').value.trim();

    if (!email || !firstName || !lastName) {
        e.preventDefault(); // Bloque l'envoi du formulaire
        showToast('Veuillez remplir tous les champs obligatoires.', 'error');
    }
    // Si tout est rempli → le formulaire s'envoie normalement (pas de preventDefault)
});


// ─── Formulaire mot de passe (appel API — pas de rechargement de page) ───────
document.getElementById('passwordForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // On gère l'envoi nous-mêmes via fetch

    const currentPassword = document.getElementById('currentPassword').value.trim();
    const newPassword     = document.getElementById('newPassword').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();

    // Validation basique côté client avant d'envoyer au serveur
    if (!currentPassword || !newPassword || !confirmPassword) {
        showToast('Veuillez remplir tous les champs.', 'error');
        return;
    }

    if (newPassword !== confirmPassword) {
        showToast('Les deux nouveaux mots de passe ne correspondent pas.', 'error');
        return;
    }

    // Récupère le token CSRF depuis la balise <meta name="csrf-token"> dans le layout
    // Ce token est obligatoire pour toutes les requêtes POST/PUT/DELETE avec Laravel
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    try {
        // Envoi de la requête au serveur sans recharger la page
        const response = await fetch('/profile/password', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                current_password:       currentPassword,
                password:               newPassword,
                password_confirmation:  confirmPassword,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            // Succès — on vide les champs et on affiche le toast
            document.getElementById('passwordForm').reset();
            showToast('Mot de passe mis à jour avec succès.', 'success');
        } else {
            // Erreur de validation renvoyée par Laravel (ex: mauvais mot de passe actuel)
            const firstError = Object.values(data.errors)[0][0];
            showToast(firstError, 'error');
        }

    } catch (err) {
        // Erreur réseau ou serveur inattendu
        showToast('Une erreur est survenue, réessayez.', 'error');
    }
});
