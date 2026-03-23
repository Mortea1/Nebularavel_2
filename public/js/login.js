

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast show ${type}`;

    setTimeout(() => {
        toast.classList.remove('show');
        }, 3000);
}


document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!email || !password) {
        showToast('Veuillez remplir tous les champs obligatoires.', 'error');
    } else {
        showToast('Connextion', 'success');
        setTimeout(() => {
            window.location.href = 'dashboard';
            }, 1200);
    }
});
