<?php
require_once 'config.php';
session_start();

// Validate reset token from URL
$reset_email = $_GET['email'] ?? '';
$reset_token = $_GET['token'] ?? '';

if (empty($reset_email) || empty($reset_token)) {
    die("Link de recuperação inválido.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha - Book Club</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="reset-password-form">
            <h2>Redefinir Senha</h2>
            <form id="resetPasswordForm">
                <input type="hidden" id="reset_email" name="reset_email" value="<?= htmlspecialchars($reset_email) ?>">
                <input type="hidden" id="reset_token" name="reset_token" value="<?= htmlspecialchars($reset_token) ?>">
                
                <div class="form-group">
                    <label for="new_password">Nova Senha</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Nova Senha</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit">Redefinir Senha</button>
            </form>
            <div id="message"></div>
        </div>
    </div>

    <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const resetEmail = document.getElementById('reset_email').value;
        const resetToken = document.getElementById('reset_token').value;
        const messageDiv = document.getElementById('message');

        if (newPassword !== confirmPassword) {
            messageDiv.textContent = 'Senhas não coincidem';
            messageDiv.style.color = 'red';
            return;
        }

        fetch('forgot_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `reset_email=${encodeURIComponent(resetEmail)}&reset_token=${encodeURIComponent(resetToken)}&new_password=${encodeURIComponent(newPassword)}`
        })
        .then(response => response.json())
        .then(data => {
            messageDiv.textContent = data.message;
            messageDiv.style.color = data.success ? 'green' : 'red';
            
            if (data.success) {
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000);
            }
        })
        .catch(error => {
            messageDiv.textContent = 'Erro ao processar solicitação';
            messageDiv.style.color = 'red';
        });
    });
    </script>
</body>
</html>
