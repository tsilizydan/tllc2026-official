<?php
/**
 * TSILIZY LLC — User Profile Page
 */

Core\View::layout('dashboard', ['page_title' => 'Mon Profil']);
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
$user = $current_user;
?>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-white">Mon Profil</h1>
    <p class="text-dark-400 mt-1">Gérez vos informations personnelles</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Profile Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Personal Information -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-6">Informations personnelles</h2>
            
            <form action="<?= SITE_URL ?>/mon-compte/profil" method="POST" class="space-y-4">
                <?= Core\CSRF::field() ?>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-dark-300 mb-2">Prénom</label>
                        <input type="text" id="first_name" name="first_name" required
                               value="<?= Core\View::e($user['first_name'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['first_name']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($errors['first_name'])): ?>
                        <p class="text-red-400 text-xs mt-1"><?= $errors['first_name'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-dark-300 mb-2">Nom</label>
                        <input type="text" id="last_name" name="last_name" required
                               value="<?= Core\View::e($user['last_name'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['last_name']) ? 'border-red-500' : '' ?>">
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                           value="<?= Core\View::e($user['email'] ?? '') ?>"
                           class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['email']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['email'])): ?>
                    <p class="text-red-400 text-xs mt-1"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-dark-300 mb-2">Téléphone</label>
                    <input type="tel" id="phone" name="phone"
                           value="<?= Core\View::e($user['phone'] ?? '') ?>"
                           class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors">
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Change Password -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-6">Changer le mot de passe</h2>
            
            <form action="<?= SITE_URL ?>/mon-compte/mot-de-passe" method="POST" class="space-y-4">
                <?= Core\CSRF::field() ?>
                
                <div>
                    <label for="current_password" class="block text-sm font-medium text-dark-300 mb-2">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors">
                </div>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-dark-300 mb-2">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" required minlength="8"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors">
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-dark-300 mb-2">Confirmer</label>
                        <input type="password" id="confirm_password" name="confirm_password" required
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 transition-colors">
                    </div>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="px-6 py-3 border border-gold-500 text-gold-500 font-medium rounded-lg hover:bg-gold-500/10 transition-all duration-300">
                        <i class="fas fa-key mr-2"></i> Modifier le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Profile Summary -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 text-center">
            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gold-500 to-gold-600 rounded-2xl flex items-center justify-center mb-4">
                <span class="text-4xl font-bold text-dark-950">
                    <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) ?>
                </span>
            </div>
            <h3 class="text-lg font-semibold text-white">
                <?= Core\View::e(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?>
            </h3>
            <p class="text-dark-400 text-sm"><?= Core\View::e($user['email'] ?? '') ?></p>
            
            <div class="mt-4 pt-4 border-t border-dark-700 text-sm text-dark-400">
                <p>Membre depuis</p>
                <p class="text-white"><?= Core\View::date($user['created_at'] ?? null) ?></p>
            </div>
        </div>
        
        <!-- Account Status -->
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Statut du compte</h3>
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-green-400">Compte actif</span>
            </div>
        </div>
    </div>
</div>
