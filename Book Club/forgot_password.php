<?php
require_once 'config.php';
session_start();

class PasswordRecovery {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function generateResetToken($email) {
        // Validate email
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'E-mail não encontrado'];
        }

        // Generate reset token
        $reset_token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        $stmt = $this->pdo->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email");
        $stmt->execute([
            'token' => password_hash($reset_token, PASSWORD_DEFAULT),
            'expiry' => $expiry,
            'email' => $email
        ]);

        // Send reset email (simplified, you'd want to use a proper email library)
        $reset_link = "http://localhost/Book%20Club/reset_password.php?email=" . urlencode($email) . "&token=" . $reset_token;
        
        // In a real application, use a proper email sending method
        $to = $email;
        $subject = "Recuperação de Senha - Book Club";
        $message = "Clique no link para redefinir sua senha: " . $reset_link;
        $headers = "From: noreply@bookclub.com";

        mail($to, $subject, $message, $headers);

        return ['success' => true, 'message' => 'Link de recuperação enviado para seu e-mail'];
    }

    public function resetPassword($email, $token, $new_password) {
        // Validate reset request
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($token, $user['reset_token']) || strtotime($user['reset_token_expiry']) < time()) {
            return ['success' => false, 'message' => 'Token inválido ou expirado'];
        }

        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = :password, reset_token = NULL, reset_token_expiry = NULL WHERE email = :email");
        $stmt->execute([
            'password' => $hashed_password,
            'email' => $email
        ]);

        return ['success' => true, 'message' => 'Senha redefinida com sucesso'];
    }
}

// Handle form submissions
$passwordRecovery = new PasswordRecovery($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        // Request reset token
        $result = $passwordRecovery->generateResetToken($_POST['email']);
        echo json_encode($result);
        exit;
    } elseif (isset($_POST['reset_email']) && isset($_POST['reset_token']) && isset($_POST['new_password'])) {
        // Reset password
        $result = $passwordRecovery->resetPassword($_POST['reset_email'], $_POST['reset_token'], $_POST['new_password']);
        echo json_encode($result);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperação de Senha - Book Club</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="forgot-password-form">
            <h2>Recuperar Senha</h2>
            <form id="forgotPasswordForm">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit">Enviar Link de Recuperação</button>
            </form>
            <div id="message"></div>
        </div>
    </div>

    <script>
    document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const messageDiv = document.getElementById('message');

        fetch('forgot_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `email=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
            messageDiv.textContent = data.message;
            messageDiv.style.color = data.success ? 'green' : 'red';
        })
        .catch(error => {
            messageDiv.textContent = 'Erro ao processar solicitação';
            messageDiv.style.color = 'red';
        });
    });
    </script>
</body>
</html>
