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

// Verifica se é uma requisição AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo dados JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (isset($data['name'], $data['email'], $data['googleId'])) {
        // Processamento para login via Google
        $nome = $data['name'];
        $email = $data['email'];
        $googleId = $data['googleId'];

        // Verifica se o email já está cadastrado
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // Adiciona o novo usuário
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, google_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $googleId);
            if ($stmt->execute()) {
                $message = "Usuário Google salvo com sucesso!";
            } else {
                $message = "Erro: " . $stmt->error;
            }
        } else {
            $message = "Usuário já registrado.";
        }
        $stmt->close();
    } else if (isset($_POST['register'])) {
        // Processamento para registro manual
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
    } else {
        $message = "Dados inválidos.";
    }
} else {
    $message = "Método de requisição inválido.";
}

$conn->close();

// Exibindo a mensagem
echo $message;
?>
