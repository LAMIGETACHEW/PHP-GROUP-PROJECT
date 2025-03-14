
const overlay = document.getElementById('overlay');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');
const showLogin = document.getElementById('showLogin');
const showRegister = document.getElementById('showRegister');
const showLoginHero = document.getElementById('showLoginHero');
const showRegisterHero = document.getElementById('showRegisterHero');
const switchToLogin = document.getElementById('switchToLogin');
const switchToRegister = document.getElementById('switchToRegister');

showLogin.addEventListener('click', () => {
    overlay.style.display = 'flex';
    loginForm.style.display = 'block';
    registerForm.style.display = 'none';
});

showRegister.addEventListener('click', () => {
    overlay.style.display = 'flex';
    loginForm.style.display = 'none';
    registerForm.style.display = 'block';
});

showLoginHero.addEventListener('click', () => {
    overlay.style.display = 'flex';
    loginForm.style.display = 'block';
    registerForm.style.display = 'none';
});

showRegisterHero.addEventListener('click', () => {
    overlay.style.display = 'flex';
    loginForm.style.display = 'none';
    registerForm.style.display = 'block';
});

switchToLogin.addEventListener('click', () => {
    loginForm.style.display = 'block';
    registerForm.style.display = 'none';
});

switchToRegister.addEventListener('click', () => {
    loginForm.style.display = 'none';
    registerForm.style.display = 'block';
});

overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
        overlay.style.display = 'none';
    }
});

function validateLogin() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    if (!email || !password) {
        alert('Please fill in all fields.');
        return false;
    }
    // alert('Login successful!');
    return true;
}

function validateRegister() {
    const name = document.getElementById('registerName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (!name || !email || !password || !confirmPassword) {
        // alert('Please fill in all fields.');
        return false;
    }

    if (password !== confirmPassword) {
        // alert('Passwords do not match.');
        return false;
    }
    // alert('Registration successful!');
    return true;
}
