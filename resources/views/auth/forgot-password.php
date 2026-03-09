<?php
/**
 * TSILIZY LLC — Forgot Password Page
 */

Core\View::layout('public', ['page_title' => 'Mot de passe oublié']);
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
            <h1 class="text-3xl font-serif font-bold text-white mt-6 mb-2">Mot de passe oublié ?</h1>
            <p class="text-dark-400">Entrez votre email pour recevoir un lien de réinitialisation</p>
        </div>
        
        <!-- Form -->
        <div class="bg-dark-900 border border-dark-800 rounded-2xl p-8">
            <form action="<?= SITE_URL ?>/mot-de-passe-oublie" method="POST" class="space-y-6">
                <?= Core\CSRF::field() ?>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-2">
                        Adresse email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-dark-500">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email" required
                               class="w-full pl-11 pr-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="vous@exemple.com">
                    </div>
                </div>
                
                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                    <i class="fas fa-paper-plane mr-2"></i> Envoyer le lien
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
