# 🛍️ Système de vente en ligne pour commerce local

##  Contexte
Dans le cadre d’un besoin réel, j’ai développé un site e-commerce pour une commerçante locale.
Le problème principal était la perte de temps liée à la gestion manuelle des stocks : à chaque commande,
il fallait vérifier physiquement la disponibilité des articles (modèles, tailles, etc.).

##  Objectif du projet
- Digitaliser la présentation des produits
- Faciliter la prise de commandes en ligne
- Réduire le temps perdu dans la vérification manuelle des articles
- Tester la faisabilité d’une solution e-commerce pour un petit commerce

##  Technologies utilisées
- (PHP / MySQL )
- Hébergement web gratuit (phase de test)

## 🚀 Fonctionnalités

### ✅ Fonctionnalités principales
- **Gestion des produits** : Ajout, affichage, suppression
- **Upload d'images** : Stockage sécurisé des images produits
- **Recherche** : Barre de recherche en temps réel
- **Panier** : Système de panier avec sessions PHP
- **Interface responsive** : Compatible mobile et desktop
- **Messages d'alerte** : Notifications de succès/erreur

### 🎨 Interface utilisateur
- Design moderne avec CSS3
- Animations et transitions fluides
- Navigation intuitive
- Cartes produits attractives
- Formulaires avec validation

### 🔒 Sécurité
- Utilisation de PDO pour éviter les injections SQL
- Validation des données côté serveur
- Sécurisation des uploads de fichiers
- Protection XSS avec htmlspecialchars

## 📁 Structure du projet

```
site_vente/
├── index.php              # Page d'accueil avec liste des produits
├── ajouter.php            # Formulaire d'ajout de produit
├── insert.php             # Traitement de l'ajout de produit
├── supprimer.php          # Suppression de produit
├── panier.php             # Page du panier
├── panier_action.php      # Actions AJAX du panier
├── config.php             # Configuration de la base de données
├── functions.php          # Fonctions utilitaires
├── test_db.php            # Test de connexion à la BDD
├── database.sql           # Script de création de la base
├── README.md              # Documentation
├── assets/
│   ├── style.css          # Styles CSS responsive
│   └── uploads/           # Dossier des images uploadées
└── includes/
    ├── header.php         # En-tête et navigation
    └── footer.php         # Pied de page
```

## 🛠️ Installation

### Prérequis
- **XAMPP** ou **WAMP** (Apache + MySQL + PHP)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur

### Étapes d'installation

1. **Télécharger et extraire**
   ```bash
   # Placer le dossier site_vente dans htdocs (XAMPP) ou www (WAMP)
   C:/xampp/htdocs/site_vente/
   ```

2. **Démarrer les services**
   - Lancer XAMPP/WAMP
   - Démarrer Apache et MySQL

3. **Créer la base de données**
   - Ouvrir phpMyAdmin : http://localhost/phpmyadmin
   - Importer le fichier `database.sql`
   - Ou exécuter manuellement :
   ```sql
   CREATE DATABASE IF NOT EXISTS site_vente;
   USE site_vente;
   
   CREATE TABLE IF NOT EXISTS produits (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nom VARCHAR(255) NOT NULL,
       description TEXT,
       prix DECIMAL(10, 2) NOT NULL,
       image VARCHAR(255),
       date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP
   );
   ```

4. **Configurer la connexion**
   - Modifier `config.php` si nécessaire :
   ```php
   $host = 'localhost';
   $db   = 'site_vente';
   $user = 'root';        // Votre utilisateur MySQL
   $pass = '';            // Votre mot de passe MySQL
   ```

5. **Tester l'installation**
   - Accéder à : http://localhost/site_vente/test_db.php
   - Vérifier que la connexion fonctionne

6. **Accéder au site**
   - URL : http://localhost/site_vente/
   - Commencer par ajouter des produits !

## 🎯 Utilisation

### Ajouter un produit
1. Cliquer sur "Ajouter un produit"
2. Remplir le formulaire (nom, prix, description)
3. Optionnel : Ajouter une image (JPEG, PNG, GIF max 5MB)
4. Valider le formulaire

### Gérer le panier
1. Cliquer sur l'icône panier sur un produit
2. Accéder au panier via le menu
3. Modifier les quantités ou supprimer des articles
4. Procéder au "paiement" (démo)

### Rechercher des produits
1. Utiliser la barre de recherche sur la page d'accueil
2. La recherche porte sur le nom et la description
3. Effacer la recherche pour voir tous les produits

## 🔧 Personnalisation

### Modifier les couleurs
Éditer les variables CSS dans `assets/style.css` :
```css
:root {
    --primary-color: #2563eb;    /* Couleur principale */
    --success-color: #10b981;    /* Couleur de succès */
    --error-color: #ef4444;      /* Couleur d'erreur */
    /* ... */
}
```

### Ajouter des fonctionnalités
- **Catégories** : Modifier la table `produits` et les fonctions
- **Utilisateurs** : Créer un système d'authentification
- **Commandes** : Ajouter une table `commandes` et le processus de paiement
- **Stock** : Ajouter une gestion des quantités en stock

## 🐛 Dépannage

### Erreur de connexion à la base
- Vérifier que MySQL est démarré
- Contrôler les identifiants dans `config.php`
- Tester avec `test_db.php`

### Images ne s'affichent pas
- Vérifier les permissions du dossier `assets/uploads/`
- S'assurer que le dossier existe
- Contrôler les chemins dans le code

### Erreur 500
- Activer l'affichage des erreurs PHP
- Vérifier les logs d'erreur Apache
- Contrôler la syntaxe PHP

## 📝 Code expliqué

### Connexion à la base (config.php)
```php
// Utilisation de PDO pour la sécurité
$pdo = new PDO($dsn, $user, $pass, $options);
```

### Sécurisation des données (functions.php)
```php
// Protection contre XSS
function securiserHTML($chaine) {
    return htmlspecialchars($chaine, ENT_QUOTES, 'UTF-8');
}
```

### Upload sécurisé (insert.php)
```php
// Vérification du type MIME
$types_autorises = ['image/jpeg', 'image/png', 'image/gif'];
// Limitation de taille
if ($_FILES['image']['size'] > 5 * 1024 * 1024) { ... }
```

### Système de panier (panier_action.php)
```php
// Stockage en session
$_SESSION['panier'][$produit_id] = [
    'id' => $produit['id'],
    'quantite' => 1
];
```

## 🚀 Améliorations possibles

- [ ] Système d'authentification utilisateur
- [ ] Gestion des catégories de produits
- [ ] Système de notation et commentaires
- [ ] Intégration d'un vrai système de paiement
- [ ] Gestion des stocks et alertes
- [ ] Interface d'administration
- [ ] Optimisation SEO
- [ ] Cache et performances

## 🧪 Tests et résultats
Le site a été hébergé et testé pendant environ un mois.
Les tests ont permis de valider le fonctionnement global de la solution.
Le projet n’a pas été déployé à long terme en raison de contraintes financières,
et non pour des raisons techniques.

## 📚 Compétences développées
- Analyse d’un problème réel
- Conception d’une solution web adaptée
- Développement front-end / back-end
- Déploiement et tests d’une application web
- Communication avec un utilisateur non technique

## 📄 Licence

Ce projet est libre d'utilisation pour l'apprentissage et les projets personnels.

## 👨‍💻 Support

Pour toute question ou problème :
1. Vérifier cette documentation
2. Consulter les commentaires dans le code
3. Tester avec des données simples
4. Vérifier les logs d'erreur

## 👤 Auteur
Étudiant en génie informatique (L2), orienté développement web et maintenance IT.



**Bon développement ! 🎉**

