<?php
require_once 'config.php';

// Traitement du formulaire
$success = false;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($nom === '' || $email === '' || $message === '') {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse email invalide.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $message]);
        $success = true;
    }
}
if (isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: " . $_SERVER['PHP_SELF']); // Recharge propre
    exit;
}

// Récupérer les messages (admin ou test)
$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY date_envoi DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'includes/header.php'; ?>
<div class="container" style="max-width:600px; margin:2rem auto;">
    <h1>Contactez-nous</h1>
    <?php if ($success): ?>
        <div class="alert alert-success">Votre message a bien été envoyé !</div>
    <?php elseif ($errors): ?>
        <div class="alert alert-error"><ul><?php foreach($errors as $e) echo "<li>$e</li>"; ?></ul></div>
    <?php endif; ?>
    <form method="post" class="form-section" style="margin-bottom:2rem;">
        <div class="form-group">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-input" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-textarea" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    <h2>Messages reçus</h2>
    <div style="background:#f8fafc; border-radius:8px; padding:1rem;">
        <?php if (count($messages) === 0): ?>
            <p>Aucun message pour le moment.</p>
        <?php else: ?>
            <ul style="list-style:none; padding:0;">
                <?php foreach($messages as $msg): ?>
                    <li style="margin-bottom:1.5rem; border-bottom:1px solid #e2e8f0; padding-bottom:1rem;">
                        <strong><?= htmlspecialchars($msg['nom']) ?></strong> (<a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a>)<br>
                        <small><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></small><br>
                        <div><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
                    </li>
                    <li>
                    <form method="post" action="" onsubmit="return confirm('Supprimer ce message ?');" style="display:inline;">
    <input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
    <button type="submit" class="btn btn-danger" style="background-color:#dc2626; color:white; border:none; padding:0.3rem 0.8rem; border-radius:5px; cursor:pointer;">
        Supprimer
    </button>
</form>
</li>

                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
