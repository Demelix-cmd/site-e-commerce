<?php
session_start();
require_once 'functions.php';

$titre_page = 'Panier';

// Initialiser le panier s'il n'existe pas
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Calculer le total
$total = 0;
foreach ($_SESSION['panier'] as $item) {
    $total += $item['prix'] * $item['quantite'];
}
?>

<?php include 'includes/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-shopping-cart"></i> Mon Panier</h1>
    <p>Gérez les produits de votre panier</p>
</div>

<section class="cart-section">
    <?php if (empty($_SESSION['panier'])): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3>Votre panier est vide</h3>
            <p>Découvrez nos produits et ajoutez-les à votre panier.</p>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Continuer mes achats
            </a>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <div class="cart-items">
                <div class="cart-header">
                    <h2>Articles dans votre panier (<?php echo count($_SESSION['panier']); ?>)</h2>
                    <button onclick="viderPanier()" class="btn btn-secondary">
                        <i class="fas fa-trash"></i> Vider le panier
                    </button>
                </div>
                
                <div class="cart-list">
                    <?php foreach ($_SESSION['panier'] as $produit_id => $item): ?>
                        <div class="cart-item" data-product-id="<?php echo $produit_id; ?>">
                            <div class="item-image">
                                <?php if (!empty($item['image']) && file_exists('assets/uploads/' . $item['image'])): ?>
                                    <img src="assets/uploads/<?php echo securiserHTML($item['image']); ?>" 
                                         alt="<?php echo securiserHTML($item['nom']); ?>">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="item-details">
                                <h3 class="item-name"><?php echo securiserHTML($item['nom']); ?></h3>
                                <p class="item-price"><?php echo formaterPrix($item['prix']); ?> / unité</p>
                            </div>
                            
                            <div class="item-quantity">
                                <label for="qty-<?php echo $produit_id; ?>">Quantité :</label>
                                <div class="quantity-controls">
                                    <button onclick="modifierQuantite(<?php echo $produit_id; ?>, -1)" class="qty-btn">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           id="qty-<?php echo $produit_id; ?>"
                                           value="<?php echo $item['quantite']; ?>" 
                                           min="1" 
                                           max="99"
                                           onchange="changerQuantite(<?php echo $produit_id; ?>, this.value)">
                                    <button onclick="modifierQuantite(<?php echo $produit_id; ?>, 1)" class="qty-btn">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="item-total">
                                <span class="total-price"><?php echo formaterPrix($item['prix'] * $item['quantite']); ?></span>
                            </div>
                            
                            <div class="item-actions">
                                <button onclick="supprimerDuPanier(<?php echo $produit_id; ?>)" 
                                        class="btn btn-delete" 
                                        title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="cart-summary">
                <div class="summary-card">
                    <h3><i class="fas fa-calculator"></i> Récapitulatif</h3>
                    
                    <div class="summary-line">
                        <span>Sous-total :</span>
                        <span id="subtotal"><?php echo formaterPrix($total); ?></span>
                    </div>
                    
                    <div class="summary-line">
                        <span>Livraison :</span>
                        <span class="free">Gratuite</span>
                    </div>
                    
                    <div class="summary-line total-line">
                        <span>Total :</span>
                        <span id="total"><?php echo formaterPrix($total); ?></span>
                    </div>
                    <div class="summary-actions">
                        <button class="btn btn-primary btn-large" onclick="procederCommande()">
                            <i class="fas fa-credit-card"></i> Procéder au paiement
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Continuer mes achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<style>
.cart-section {
    margin-bottom: 3rem;
}

.cart-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
    align-items: start;
}

.cart-items {
    background: var(--surface-color);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}

.cart-header h2 {
    color: var(--text-primary);
    margin: 0;
}

.cart-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.cart-item {
    display: grid;
    grid-template-columns: 80px 1fr auto auto auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    transition: var(--transition);
}

.cart-item:hover {
    border-color: var(--primary-color);
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: var(--radius-md);
    overflow: hidden;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-image .no-image {
    width: 100%;
    height: 100%;
    background: var(--background-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
}

.item-details h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
}

.item-price {
    color: var(--text-secondary);
    margin: 0;
}

.item-quantity label {
    display: block;
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.qty-btn {
    background: var(--border-color);
    border: none;
    width: 2rem;
    height: 2rem;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.qty-btn:hover {
    background: var(--primary-color);
    color: white;
}

.quantity-controls input {
    width: 3rem;
    text-align: center;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-sm);
    padding: 0.25rem;
}

.item-total {
    text-align: right;
}

.total-price {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 1.125rem;
}

.cart-summary {
    position: sticky;
    top: 2rem;
}

.summary-card {
    background: var(--surface-color);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
}

.summary-card h3 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    color: var(--text-primary);
}

.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.summary-line:last-of-type {
    border-bottom: none;
}

.total-line {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    border-top: 2px solid var(--border-color);
    margin-top: 1rem;
    padding-top: 1rem;
}

.free {
    color: var(--success-color);
    font-weight: 500;
}

.summary-actions {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (max-width: 768px) {
    .cart-container {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .cart-item {
        grid-template-columns: 60px 1fr;
        gap: 1rem;
    }
    
    .item-quantity,
    .item-total,
    .item-actions {
        grid-column: 1 / -1;
        justify-self: start;
    }
    
    .item-quantity {
        margin-top: 1rem;
    }
    
    .item-total {
        text-align: left;
    }
    
    .cart-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<script>
function modifierQuantite(produitId, delta) {
    const input = document.getElementById('qty-' + produitId);
    const nouvelleQuantite = Math.max(1, parseInt(input.value) + delta);
    input.value = nouvelleQuantite;
    changerQuantite(produitId, nouvelleQuantite);
}

function changerQuantite(produitId, quantite) {
    quantite = Math.max(1, parseInt(quantite));
    
    fetch('panier_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=modifier&produit_id=' + produitId + '&quantite=' + quantite
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Recharger pour mettre à jour les totaux
        } else {
            alert('Erreur lors de la modification : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la modification');
    });
}

function supprimerDuPanier(produitId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article du panier ?')) {
        fetch('panier_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=supprimer&produit_id=' + produitId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

function viderPanier() {
    if (confirm('Êtes-vous sûr de vouloir vider complètement votre panier ?')) {
        fetch('panier_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=vider'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors du vidage : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du vidage');
        });
    }
}

function procederCommande() {
    alert('Fonctionnalité de paiement non implémentée dans cette démo.\nDans un vrai site, ceci redirigerait vers une page de paiement sécurisée.');
}
</script>

<?php include 'includes/footer.php'; ?>

