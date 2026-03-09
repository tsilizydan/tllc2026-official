<?php
/**
 * TSILIZY LLC — User Profile View
 */
?>

<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-white mb-6">Mon profil</h1>
    
    <!-- Profile Info Form -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Informations personnelles</h2>
        
        <form action="<?= SITE_URL ?>/mon-compte/profil/modifier" method="POST">
            <?= Core\CSRF::field() ?>
            
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm text-dark-400 mb-1">Prénom</label>
                    <input type="text" name="first_name" value="<?= Core\View::e($user['first_name'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm text-dark-400 mb-1">Nom</label>
                    <input type="text" name="last_name" value="<?= Core\View::e($user['last_name'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500 focus:outline-none">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm text-dark-400 mb-1">Email</label>
                <input type="email" value="<?= Core\View::e($user['email'] ?? '') ?>" disabled class="w-full px-4 py-3 bg-dark-950 border border-dark-700 rounded-lg text-dark-500 cursor-not-allowed">
                <p class="text-xs text-dark-500 mt-1">L'adresse email ne peut pas être modifiée.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm text-dark-400 mb-1">Téléphone</label>
                    <input type="tel" name="phone" value="<?= Core\View::e($user['phone'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm text-dark-400 mb-1">Entreprise</label>
                    <input type="text" name="company" value="<?= Core\View::e($user['company'] ?? '') ?>" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500 focus:outline-none">
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm text-dark-400 mb-1">Adresse</label>
                <textarea name="address" rows="2" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500 focus:outline-none"><?= Core\View::e($user['address'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" class="px-6 py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg hover:bg-gold-400 transition-colors">
                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
        </form>
    </div>
    
    <!-- Change Password Form -->
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Changer le mot de passe</h2>
        
        <form action="<?= SITE_URL ?>/mon-compte/profil/mot-de-passe" method="POST">
            <?= Core\CSRF::field() ?>
            
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm text-dark-400 mb-1">Mot de passe actuel</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm text-dark-400 mb-1">Nouveau mot de passe</label>
                    <input type="password" name="new_password" required minlength="8" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500 focus:outline-none">
                    <p class="text-xs text-dark-500 mt-1">Minimum 8 caractères</p>
                </div>
                <div>
                    <label class="block text-sm text-dark-400 mb-1">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:border-gold-500 focus:outline-none">
                </div>
            </div>
            
            <button type="submit" class="px-6 py-3 bg-dark-700 text-white font-medium rounded-lg hover:bg-dark-600 transition-colors">
                <i class="fas fa-lock mr-2"></i> Changer le mot de passe
            </button>
        </form>
    </div>
</div>
