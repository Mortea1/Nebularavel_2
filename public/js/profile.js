    // affiche img1.png puis img2.jpg puis img3.png dans la section profile-avatar
    const avatarImg = document.querySelector('.profile-avatar img');
    const images = ['assets/img_profile/img1.png', 'assets/img_profile/img2.jpg', 'assets/img_profile/img3.png'];
    let currentIndex = 0;

    setInterval(() => {
        currentIndex = (currentIndex + 1) % images.length;
        avatarImg.src = images[currentIndex];
    }, 1000); // Change d'image toutes les 1 secondes




function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast show ${type}`;

    setTimeout(() => {
        toast.classList.remove('show');
        }, 300000);
}


document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();



    if (!email || !firstName || !lastName) {
        showToast('Veuillez remplir tous les champs obligatoires.', 'error');
    } else {
        showToast('Modification effectué', 'success');
    }
});

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const currentPassword = document.getElementById('currentPassword').value.trim();
    const newPassword = document.getElementById('newPassword').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();


    if (!currentPassword || !newPassword || !confirmPassword) {
        showToast('Veuillez remplir tous les champs obligatoires.', 'error');
    } else {
        showToast("Modification du mot de passe effectué et enregistré dans la base de donnée du site web nebula" , 'success');

    }
});