var btnSignin = document.querySelector("#signin");
var btnSignup = document.querySelector("#signup");

var body = document.querySelector("body");


btnSignin.addEventListener("click", function () {
   body.className = "sign-in-js"; 
});

btnSignup.addEventListener("click", function () {
    body.className = "sign-up-js";
})

document.addEventListener('DOMContentLoaded', function() {
    // Exemplo de manipulação de evento para botões
    document.getElementById('signin').addEventListener('click', function() {
        // Lógica para mostrar a seção de login
    });

    document.getElementById('signup').addEventListener('click', function() {
        // Lógica para mostrar a seção de registro
    });
});
fetch('config.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        google_nome: decodedToken.name,
        google_email: decodedToken.email
    })
})
.then(response => response.text())
.then(data => {
    console.log('Success:', data);
})
.catch((error) => {
    console.error('Error:', error);
});

