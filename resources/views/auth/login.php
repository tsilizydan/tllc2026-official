<?php
/**
 * TSILIZY LLC — Login Page
 */

Core\View::layout('public', ['page_title' => 'Connexion']);
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
            <h1 class="text-3xl font-serif font-bold text-white mt-6 mb-2">Connexion</h1>
            <p class="text-dark-400">Accédez à votre espace personnel</p>
        </div>
        
        <!-- Form -->
        <div class="bg-dark-900 border border-dark-800 rounded-2xl p-8">
            <form action="<?= SITE_URL ?>/connexion" method="POST" class="space-y-6">
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
                               value="<?= Core\View::e(Core\Session::old('email')) ?>"
                               class="w-full pl-11 pr-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="vous@exemple.com">
                    </div>
                </div>
                
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-2">
                        Mot de passe
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-dark-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" id="password" name="password" required
                               class="w-full pl-11 pr-12 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-dark-500 hover:text-white">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-dark-600 bg-dark-800 text-gold-500 focus:ring-gold-500 focus:ring-offset-0">
                        <span class="text-sm text-dark-400">Se souvenir de moi</span>
                    </label>
                    <a href="<?= SITE_URL ?>/mot-de-passe-oublie" class="text-sm text-gold-500 hover:text-gold-400 transition-colors">
                        Mot de passe oublié ?
                    </a>
                </div>
                
                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-dark-700"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-dark-900 text-dark-500">Pas encore de compte ?</span>
                </div>
            </div>
            
            <!-- Register Link -->
            <?php if (ENABLE_REGISTRATION): ?>
            <a href="<?= SITE_URL ?>/inscription" class="block w-full py-3 text-center border border-dark-600 text-white font-medium rounded-lg hover:border-gold-500 hover:text-gold-500 transition-all duration-300">
                <i class="fas fa-user-plus mr-2"></i> Créer un compte
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Back to Home -->
        <div class="text-center mt-8">
            <a href="<?= SITE_URL ?>" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</section>
