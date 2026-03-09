<?php
/**
 * TSILIZY LLC — Reset Password Page
 */

Core\View::layout('public', ['page_title' => 'Réinitialiser le mot de passe']);
?>

<section class="min-h-screen flex items-center justify-center py-24 px-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?= SITE_URL ?>" class="inline-flex items-center space-x-3">
                <div class="w-14 h-14 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center">
                    <span class="text-dark-950 font-serif font-bold text-2xl">T</span>
                </div>
            </a>
            <h1 class="text-3xl font-serif font-bold text-white mt-6 mb-2">Nouveau mot de passe</h1>
            <p class="text-dark-400">Choisissez un nouveau mot de passe sécurisé</p>
        </div>
        
        <!-- Form -->
        <div class="bg-dark-900 border border-dark-800 rounded-2xl p-8">
            <form action="<?= SITE_URL ?>/reinitialiser-mot-de-passe" method="POST" class="space-y-6">
                <?= Core\CSRF::field() ?>
                <input type="hidden" name="token" value="<?= Core\View::e($token ?? '') ?>">
                <input type="hidden" name="email" value="<?= Core\View::e($email ?? '') ?>">
                
                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-2">
                        Nouveau mot de passe
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-dark-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password" name="password" required minlength="8"
                               class="w-full pl-11 pr-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="Minimum 8 caractères">
                    </div>
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-dark-300 mb-2">
                        Confirmer le mot de passe
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-dark-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full pl-11 pr-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="Confirmez votre mot de passe">
                    </div>
                </div>
                
                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                    <i class="fas fa-key mr-2"></i> Réinitialiser
                </button>
            </form>
            
            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="<?= SITE_URL ?>/connexion" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la connexion
                </a>
            </div>
        </div>
    </div>
</section>
