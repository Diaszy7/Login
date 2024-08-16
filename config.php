<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "sistema_login";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Inicializando variáveis para mensagens
$message = '';

// Função para registrar usuários
if (isset($_POST['register'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o email já está cadastrado
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Email já cadastrado!";
    } else {
        // Criptografando a senha
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        // Insere o novo usuário no banco de dados
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);
        if ($stmt->execute()) {
            $message = "Cadastro realizado com sucesso!";
        } else {
            $message = "Erro: " . $stmt->error;
        }
    }
    $stmt->close();
}

// Função para login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco de dados
    $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nome, $senha_hash);
        $stmt->fetch();
       // Remove o password_verify e compare diretamente
if ($senha == $senha_hash) {
    $message = "Você está logado, HELLO WORD";
        } else {
            $message = "Senha incorreta!";
        }
    } else {
        $message = "Usuário não encontrado!";
    }
    $stmt->close();
}

// Fechando a conexão
$conn->close();

// Exibindo a mensagem (certifique-se de colocar isso no HTML onde deseja exibir a mensagem)
if (!empty($message)) {
    echo "<p>$message</p>";
}
?>
