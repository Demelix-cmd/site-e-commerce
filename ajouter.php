<?php
session_start();

$titre_page = 'Ajouter un produit';

// Récupérer les données du formulaire en cas d'erreur
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_data']);
?>

<?php include 'includes/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-plus"></i> Ajouter un nouveau produit</h1>
    <p>Remplissez le formulaire ci-dessous pour ajouter un produit à votre boutique</p>
</div>

<section class="form-section">
    <div class="form-container">
        <form action="insert.php" method="POST" enctype="multipart/form-data" class="product-form">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom" class="form-label">
                        <i class="fas fa-tag"></i> Nom du produit *
                    </label>
                    <input type="text" 
                           id="nom" 
                           name="nom" 
                           class="form-input" 
                           placeholder="Ex: iPhone 14 Pro"
                           value="<?php echo isset($form_data['nom']) ? securiserHTML($form_data['nom']) : ''; ?>"
                           required>
                    <small class="form-help">Le nom doit être descriptif et unique</small>
                </div>
                
                <div class="form-group">
                    <label for="prix" class="form-label">
                        <i class="fas fa-money-bill-wave"></i> Prix (CFA) *
                    </label>
                    <input type="number" 
                           id="prix" 
                           name="prix" 
                           class="form-input" 
                           placeholder="0.00"
                           step="0.01"
                           min="0"
                           value="<?php echo isset($form_data['prix']) ? securiserHTML($form_data['prix']) : ''; ?>"
                           required>
                    <small class="form-help">Prix en CFA (ex: 6500)</small>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">
                    <i class="fas fa-align-left"></i> Description *
                </label>
                <textarea id="description" 
                          name="description" 
                          class="form-textarea" 
                          placeholder="Décrivez votre produit en détail..."
                          rows="5"
                          required><?php echo isset($form_data['description']) ? securiserHTML($form_data['description']) : ''; ?></textarea>
                <small class="form-help">Une description détaillée aide les clients à mieux comprendre le produit</small>
            </div>
            
            <div class="form-group">
                <label for="image" class="form-label">
                    <i class="fas fa-image"></i> Image du produit
                </label>
                <div class="file-input-wrapper">
                    <input type="file" 
                           id="image" 
                           name="image" 
                           class="form-file-input" 
                           accept="image/jpeg,image/png,image/gif">
                    <label for="image" class="file-input-label">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Choisir une image</span>
                        <small>ou glisser-déposer ici</small>
                    </label>
                </div>
                <small class="form-help">Formats acceptés: JPEG, PNG, GIF (max 5MB)</small>
                <div id="image-preview" class="image-preview"></div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">
                    <i class="fas fa-save"></i> Ajouter le produit
                </button>
                <a href="index.php" class="btn btn-secondary btn-large">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const fileLabel = document.querySelector('.file-input-label span');
    const imagePreview = document.getElementById('image-preview');
    
    // Gestion de l'upload de fichier
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            fileLabel.textContent = file.name;
            
            // Prévisualisation de l'image
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `
                    <div class="preview-container">
                        <img src="${e.target.result}" alt="Prévisualisation">
                        <button type="button" class="remove-preview" onclick="removePreview()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Drag and drop
    const fileWrapper = document.querySelector('.file-input-wrapper');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileWrapper.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        fileWrapper.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        fileWrapper.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        fileWrapper.classList.add('drag-over');
    }
    
    function unhighlight(e) {
        fileWrapper.classList.remove('drag-over');
    }
    
    fileWrapper.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    }
    
    // Validation du formulaire
    const form = document.querySelector('.product-form');
    form.addEventListener('submit', function(e) {
        const nom = document.getElementById('nom').value.trim();
        const prix = document.getElementById('prix').value;
        const description = document.getElementById('description').value.trim();
        const image = document.getElementById('image').files[0];

        if (!nom || !prix || !description || !image) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires, y compris l\'image du produit.');
            return false;
        }

        if (parseFloat(prix) <= 0) {
            e.preventDefault();
            alert('Le prix doit être supérieur à 0.');
            return false;
        }
    });
});

function removePreview() {
    const imagePreview = document.getElementById('image-preview');
    const fileInput = document.getElementById('image');
    const fileLabel = document.querySelector('.file-input-label span');
    
    imagePreview.innerHTML = '';
    imagePreview.style.display = 'none';
    fileInput.value = '';
    fileLabel.textContent = 'Choisir une image';
}
</script>

<?php include 'includes/footer.php'; ?>

