<?php
// modifier_image.php
require_once 'config.php';
require_once 'functions.php';

// Récupérer l'ID du produit
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Produit invalide.');
}

// Récupérer le produit
$produit = obtenirProduitParId($id);
if (!$produit) {
    die('Produit non trouvé.');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['nouvelle_image'])) {
    $fichier = $_FILES['nouvelle_image'];
    if ($fichier['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        $nomFichier = uniqid('img_') . '.' . $ext;
        $chemin = 'assets/uploads/' . $nomFichier;
        if (move_uploaded_file($fichier['tmp_name'], $chemin)) {
            // Supprimer l'ancienne image si elle existe
            if (!empty($produit['image']) && file_exists('assets/uploads/' . $produit['image'])) {
                unlink('assets/uploads/' . $produit['image']);
            }
            // Mettre à jour la base
            $stmt = $pdo->prepare('UPDATE produits SET image = ? WHERE id = ?');
            $stmt->execute([$nomFichier, $id]);
            $message = 'Image modifiée avec succès !';
            // Recharger le produit
            $produit = obtenirProduitParId($id);
        } else {
            $message = "Erreur lors de l'upload.";
        }
    } else {
        $message = "Erreur lors de l'upload.";
    }
}

include 'includes/header.php';
?>
<div class="container" style="max-width:500px; margin:2rem auto;">
    <h1>Modifier l'image du produit</h1>
    <p><strong>Produit :</strong> <?= htmlspecialchars($produit['nom']) ?></p>
    <?php if ($produit['image'] && file_exists('assets/uploads/' . $produit['image'])): ?>
        <img src="assets/uploads/<?= htmlspecialchars($produit['image']) ?>" alt="Image actuelle" style="max-width:200px; display:block; margin-bottom:1rem;">
    <?php else: ?>
        <p>Aucune image actuelle.</p>
    <?php endif; ?>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nouvelle_image" class="form-label">Nouvelle image</label>
            <input type="file" name="nouvelle_image" id="nouvelle_image" class="form-input" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="index.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
