<?php
session_start();
require_once 'functions.php';

$titre_page = 'Accueil';



// Gestion de la recherche
$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

// Récupération des produits
$produits = obtenirTousProduits($recherche);
?>

<?php include 'includes/header.php'; ?>

<div class="hero-section">
    <div class="hero-content">
       
    </div>
</div>

<section class="search-section">
    <div class="search-container">
        <form method="GET" action="index.php" class="search-form">
            <div class="search-input-group">
                <input type="text" 
                       name="recherche" 
                       placeholder="Rechercher un produit..." 
                       value="<?php echo securiserHTML($recherche); ?>"
                       class="search-input">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        
        <?php if (!empty($recherche)): ?>
            <div class="search-results-info">
                <p>Résultats pour : <strong>"<?php echo securiserHTML($recherche); ?>"</strong> 
                   (<?php echo count($produits); ?> produit<?php echo count($produits) > 1 ? 's' : ''; ?> trouvé<?php echo count($produits) > 1 ? 's' : ''; ?>)</p>
                <a href="index.php" class="clear-search">
                    <i class="fas fa-times"></i> Effacer la recherche
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="products-section">
    <div class="section-header">
        <h2><i class="fas fa-box"></i> Nos Produits</h2>
        <a href="ajouter.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un produit
        </a>
    </div>
    
    <?php if (empty($produits)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h3><?php echo !empty($recherche) ? 'Aucun produit trouvé' : 'Aucun produit disponible'; ?></h3>
            <p><?php echo !empty($recherche) ? 'Essayez avec d\'autres mots-clés.' : 'Commencez par ajouter votre premier produit.'; ?></p>
            <?php if (empty($recherche)): ?>
                <a href="ajouter.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter le premier produit
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($produits as $produit): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if (!empty($produit['image']) && file_exists('assets/uploads/' . $produit['image'])): ?>
                            <img src="assets/uploads/<?php echo securiserHTML($produit['image']); ?>" 
                                 alt="<?php echo securiserHTML($produit['nom']); ?>">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-image"></i>
                                <span>Aucune image</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-actions">
                            <button onclick="ajouterAuPanier(<?php echo $produit['id']; ?>)" 
                                    class="btn btn-cart" 
                                    title="Ajouter au panier">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <a href="modifier_image.php?id=<?php echo $produit['id']; ?>" 
                               class="btn btn-secondary" 
                               title="Modifier l'image">
                                <i class="fas fa-image"></i>
                            </a>
                            <a href="supprimer.php?id=<?php echo $produit['id']; ?>" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')"
                               class="btn btn-delete" 
                               title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-name"><?php echo securiserHTML($produit['nom']); ?></h3>
                        <p class="product-description">
                            <?php echo securiserHTML(tronquerTexte($produit['description'], 80)); ?>
                        </p>
                        <div class="product-price">
                            <?php echo formaterPrix($produit['prix']); ?>
                        </div>
                        <div class="product-date">
                            <i class="fas fa-calendar"></i>
                            Ajouté le <?php echo date('d/m/Y', strtotime($produit['date_ajout'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
function ajouterAuPanier(produitId) {
    fetch('panier_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=ajouter&produit_id=' + produitId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour le badge du panier
            const badge = document.querySelector('.badge');
            if (badge) {
                badge.textContent = data.nombre_articles;
            } else {
                // Créer le badge s'il n'existe pas
                const panierLink = document.querySelector('a[href="panier.php"]');
                if (panierLink) {
                    panierLink.innerHTML += ' <span class="badge">' + data.nombre_articles + '</span>';
                }
            }
            
            // Afficher un message de succès temporaire
            showTempMessage('Produit ajouté au panier !', 'success');
        } else {
            showTempMessage('Erreur lors de l\'ajout au panier', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showTempMessage('Erreur lors de l\'ajout au panier', 'error');
    });
}

function showTempMessage(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + type + ' temp-alert';
    alertDiv.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        setTimeout(() => {
            alertDiv.remove();
        }, 300);
    }, 3000);
}
</script>

<?php include 'includes/footer.php'; ?>

