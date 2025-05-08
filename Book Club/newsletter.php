<?php
require_once 'config.php';

class NewsletterManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function subscribe($email) {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email inválido'];
        }

        // Check if email already exists
        $stmt = $this->pdo->prepare("SELECT * FROM newsletter_subscribers WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Email já cadastrado'];
        }

        // Insert new subscriber
        $stmt = $this->pdo->prepare("INSERT INTO newsletter_subscribers (email, subscribed_at) VALUES (:email, NOW())");
        
        try {
            $stmt->execute(['email' => $email]);
            return ['success' => true, 'message' => 'Inscrição realizada com sucesso'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao cadastrar: ' . $e->getMessage()];
        }
    }
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $newsletterManager = new NewsletterManager($pdo);
    echo json_encode($newsletterManager->subscribe($_POST['email']));
}
?>
