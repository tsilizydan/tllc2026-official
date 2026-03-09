<?php
/**
 * TSILIZY LLC — Admin Services Form
 */

Core\View::layout('admin', ['page_title' => $page_title]);
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
$isEdit = $mode === 'edit';
$s = $service ?? [];
?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white"><?= $isEdit ? 'Modifier le service' : 'Nouveau service' ?></h2>
        <p class="text-dark-400 text-sm mt-1">
            <?= $isEdit ? 'Modifiez les informations du service' : 'Créez un nouveau service' ?>
        </p>
    </div>
    <a href="<?= SITE_URL ?>/admin/services" class="px-4 py-2 border border-dark-600 text-dark-300 rounded-lg hover:text-white hover:border-dark-500 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Retour
    </a>
</div>

<form action="<?= $isEdit ? SITE_URL . '/admin/services/' . $s['id'] : SITE_URL . '/admin/services' ?>" method="POST" enctype="multipart/form-data">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Informations générales</h3>
                
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-dark-300 mb-2">
                            Titre <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="title" name="title" required
                               value="<?= Core\View::e($s['title'] ?? Core\Session::old('title')) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['title']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($errors['title'])): ?>
                        <p class="text-red-400 text-xs mt-1"><?= $errors['title'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Short Description -->
                    <div>
                        <label for="short_description" class="block text-sm font-medium text-dark-300 mb-2">
                            Description courte
                        </label>
                        <textarea id="short_description" name="short_description" rows="2"
                                  class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors resize-none"
                                  placeholder="Résumé en quelques mots..."><?= Core\View::e($s['short_description'] ?? Core\Session::old('short_description')) ?></textarea>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-dark-300 mb-2">
                            Description complète <span class="text-red-400">*</span>
                        </label>
                        <textarea id="description" name="description" rows="10" required
                                  class="tinymce w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['description']) ? 'border-red-500' : '' ?>"><?= $s['description'] ?? Core\Session::old('description') ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                        <p class="text-red-400 text-xs mt-1"><?= $errors['description'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Details -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Détails</h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Icon -->
                    <div>
                        <label for="icon" class="block text-sm font-medium text-dark-300 mb-2">
                            Icône Font Awesome
                        </label>
                        <input type="text" id="icon" name="icon"
                               value="<?= Core\View::e($s['icon'] ?? Core\Session::old('icon') ?? 'fa-star') ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="fa-star">
                        <p class="text-dark-500 text-xs mt-1">Ex: fa-lightbulb, fa-laptop-code</p>
                    </div>
                    
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-dark-300 mb-2">
                            Prix (€)
                        </label>
                        <input type="number" id="price" name="price" step="0.01"
                               value="<?= Core\View::e($s['price'] ?? Core\Session::old('price')) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="0.00">
                    </div>
                    
                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-dark-300 mb-2">
                            Durée
                        </label>
                        <input type="text" id="duration" name="duration"
                               value="<?= Core\View::e($s['duration'] ?? Core\Session::old('duration')) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="Ex: heure, mois, projet">
                    </div>
                    
                    <!-- Order -->
                    <div>
                        <label for="order_index" class="block text-sm font-medium text-dark-300 mb-2">
                            Ordre d'affichage
                        </label>
                        <input type="number" id="order_index" name="order_index"
                               value="<?= Core\View::e($s['order_index'] ?? Core\Session::old('order_index') ?? 0) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors">
                    </div>
                </div>
                
                <!-- Features -->
                <div class="mt-4">
                    <label for="features" class="block text-sm font-medium text-dark-300 mb-2">
                        Caractéristiques (une par ligne)
                    </label>
                    <textarea id="features" name="features" rows="4"
                              class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors resize-none"
                              placeholder="Caractéristique 1&#10;Caractéristique 2&#10;Caractéristique 3"><?php
                              $features = $s['features'] ?? null;
                              if ($features && is_string($features)) {
                                  $features = json_decode($features, true);
                                  echo implode("\n", $features ?: []);
                              }
                              ?></textarea>
                </div>
            </div>
            
            <!-- SEO -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">SEO</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="seo_title" class="block text-sm font-medium text-dark-300 mb-2">
                            Titre SEO
                        </label>
                        <input type="text" id="seo_title" name="seo_title"
                               value="<?= Core\View::e($s['seo_title'] ?? Core\Session::old('seo_title')) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors">
                    </div>
                    
                    <div>
                        <label for="seo_description" class="block text-sm font-medium text-dark-300 mb-2">
                            Description SEO
                        </label>
                        <textarea id="seo_description" name="seo_description" rows="2"
                                  class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors resize-none"><?= Core\View::e($s['seo_description'] ?? Core\Session::old('seo_description')) ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Publish -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Publication</h3>
                
                <div class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-dark-300 mb-2">
                            Statut
                        </label>
                        <select id="status" name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors">
                            <option value="active" <?= ($s['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= ($s['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>
                    
                    <!-- Featured -->
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1"
                               <?= ($s['is_featured'] ?? 0) ? 'checked' : '' ?>
                               class="w-4 h-4 rounded border-dark-600 bg-dark-800 text-gold-500 focus:ring-gold-500 focus:ring-offset-0">
                        <label for="is_featured" class="text-sm text-dark-300">
                            Mettre en avant
                        </label>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-dark-600 space-y-3">
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> <?= $isEdit ? 'Mettre à jour' : 'Créer le service' ?>
                    </button>
                    
                    <?php if ($isEdit): ?>
                    <a href="<?= SITE_URL ?>/services/<?= $s['slug'] ?>" target="_blank" class="block w-full py-3 text-center border border-dark-600 text-dark-300 rounded-lg hover:text-white hover:border-dark-500 transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i> Voir le service
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Image -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Image</h3>
                
                <?php if ($isEdit && !empty($s['image'])): ?>
                <div class="mb-4">
                    <img src="<?= Core\View::upload($s['image']) ?>" alt="" class="w-full rounded-lg">
                </div>
                <?php endif; ?>
                
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full text-sm text-dark-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-dark-700 file:text-white hover:file:bg-dark-600 file:cursor-pointer">
                <p class="text-dark-500 text-xs mt-2">JPG, PNG ou WebP. Max 10MB.</p>
            </div>
        </div>
    </div>
</form>
