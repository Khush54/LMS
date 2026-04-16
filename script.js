function show(id) {
    const flipBox = document.getElementById('flip-box');

    if (id === 'admin-login') {
        flipBox.classList.add('is-flipped');
    } else {
        flipBox.classList.remove('is-flipped');
    }
}

function fillStudent() {
    const userInput = document.getElementById('s-user');
    const passInput = document.getElementById('s-pass');
    
    userInput.value = "aman@gmail.com";
    passInput.value = "pass123";
}

function fillAdmin() {
    const userInput = document.getElementById('a-user');
    const passInput = document.getElementById('a-pass');
    
    userInput.value = "admin@lms.com";
    passInput.value = "admin123";
}