// ─── Toast ───────────────────────────────────────────────────────────────────

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast show ${type}`;

    setTimeout(() => toast.classList.remove('show'), 4000);
}


// ─── Formulaire profil ────────────────────────────────────────────────────────

document.getElementById('profileForm').addEventListener('submit', function (e) {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName  = document.getElementById('lastName').value.trim();
    const email     = document.getElementById('email').value.trim();

    if (!firstName || !lastName || !email) {
        e.preventDefault();
        showToast('Veuillez remplir tous les champs obligatoires.', 'error');
    }
});


// ─── Modal — changement de mot de passe (API REST) ───────────────────────────

const modal      = document.getElementById('passwordModal');
const modalError = document.getElementById('modalError');
const submitBtn  = document.getElementById('submitPassword');

// Ouvrir
document.getElementById('openPasswordModal').addEventListener('click', () => {
    modalError.style.display = 'none';
    modal.querySelectorAll('input').forEach(i => i.value = '');
    modal.showModal();
});

// Fermer — bouton Annuler ou clic sur le fond
document.getElementById('closePasswordModal').addEventListener('click', () => modal.close());
modal.addEventListener('click', (e) => { if (e.target === modal) modal.close(); });

// Soumettre
submitBtn.addEventListener('click', async () => {
    modalError.style.display = 'none';

    const current      = document.getElementById('current_password').value;
    const password     = document.getElementById('new_password').value;
    const confirmation = document.getElementById('new_password_confirmation').value;

    if (!current || !password || !confirmation) {
        modalError.textContent = 'Veuillez remplir tous les champs.';
        modalError.style.display = 'block';
        return;
    }

    if (password !== confirmation) {
        modalError.textContent = 'Les deux mots de passe ne correspondent pas.';
        modalError.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = 'En cours…';

    try {
        // 1. Initialiser le cookie CSRF Sanctum
        await fetch('/sanctum/csrf-cookie', {
            method: 'GET',
            credentials: 'include',
        });

        // 2. Lire le token XSRF depuis le cookie
        const xsrfToken = decodeURIComponent(
            document.cookie.split('; ').find(r => r.startsWith('XSRF-TOKEN='))?.split('=')[1] ?? ''
        );

        // 3. Envoyer la requête
        const response = await fetch(modal.dataset.url, {
            method: 'PUT',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept':        'application/json',
                'X-XSRF-TOKEN':  xsrfToken,
            },
            body: JSON.stringify({
                current_password:      current,
                password:              password,
                password_confirmation: confirmation,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            modal.close();
            showToast('Mot de passe modifié avec succès !', 'success');
        } else {
            const messages = data.errors
                ? Object.values(data.errors).flat().join('\n')
                : (data.message || 'Une erreur est survenue.');
            modalError.textContent = messages;
            modalError.style.display = 'block';
        }

    } catch {
        modalError.textContent = 'Erreur réseau, veuillez réessayer.';
        modalError.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Modifier';
    }
});
