<?php
/**
 * TSILIZY LLC — Registration Page
 */

Core\View::layout('public', ['page_title' => 'Inscription']);
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
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
            <h1 class="text-3xl font-serif font-bold text-white mt-6 mb-2">Créer un compte</h1>
            <p class="text-dark-400">Rejoignez notre communauté</p>
        </div>
        
        <!-- Form -->
        <div class="bg-dark-900 border border-dark-800 rounded-2xl p-8">
            <form action="<?= SITE_URL ?>/inscription" method="POST" class="space-y-6">
                <?= Core\CSRF::field() ?>
                
                <!-- Name Row -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-dark-300 mb-2">
                            Prénom
                        </label>
                        <input type="text" id="first_name" name="first_name" required
                               value="<?= Core\View::e(Core\Session::old('first_name')) ?>"
                               class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['first_name']) ? 'border-red-500' : '' ?>"
                               placeholder="Jean">
                        <?php if (isset($errors['first_name'])): ?>
                        <p class="text-red-400 text-xs mt-1"><?= $errors['first_name'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-dark-300 mb-2">
                            Nom
                        </label>
                        <input type="text" id="last_name" name="last_name" required
                               value="<?= Core\View::e(Core\Session::old('last_name')) ?>"
                               class="w-full px-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['last_name']) ? 'border-red-500' : '' ?>"
                               placeholder="Dupont">
                        <?php if (isset($errors['last_name'])): ?>
                        <p class="text-red-400 text-xs mt-1"><?= $errors['last_name'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
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
                               class="w-full pl-11 pr-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['email']) ? 'border-red-500' : '' ?>"
                               placeholder="vous@exemple.com">
                    </div>
                    <?php if (isset($errors['email'])): ?>
                    <p class="text-red-400 text-xs mt-1"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Phone (Optional) -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-dark-300 mb-2">
                        Téléphone <span class="text-dark-500">(optionnel)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-dark-500">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input type="tel" id="phone" name="phone"
                               value="<?= Core\View::e(Core\Session::old('phone')) ?>"
                               class="w-full pl-11 pr-4 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors"
                               placeholder="+33 6 12 34 56 78">
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
                        <input :type="show ? 'text' : 'password'" id="password" name="password" required minlength="8"
                               class="w-full pl-11 pr-12 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['password']) ? 'border-red-500' : '' ?>"
                               placeholder="Minimum 8 caractères">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-dark-500 hover:text-white">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p class="text-dark-500 text-xs mt-1">Minimum 8 caractères</p>
                    <?php if (isset($errors['password'])): ?>
                    <p class="text-red-400 text-xs mt-1"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-dark-300 mb-2">
                        Confirmer le mot de passe
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-dark-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                               class="w-full pl-11 pr-12 py-3 bg-dark-800 border border-dark-700 rounded-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors <?= isset($errors['password_confirmation']) ? 'border-red-500' : '' ?>"
                               placeholder="Répétez le mot de passe">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-dark-500 hover:text-white">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <?php if (isset($errors['password_confirmation'])): ?>
                    <p class="text-red-400 text-xs mt-1"><?= $errors['password_confirmation'] ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Terms -->
                <div class="flex items-start space-x-2">
                    <input type="checkbox" name="terms" id="terms" required class="w-4 h-4 mt-1 rounded border-dark-600 bg-dark-800 text-gold-500 focus:ring-gold-500 focus:ring-offset-0">
                    <label for="terms" class="text-sm text-dark-400">
                        J'accepte les <a href="<?= SITE_URL ?>/page/cgv" class="text-gold-500 hover:text-gold-400">conditions générales</a> et la <a href="<?= SITE_URL ?>/page/politique-confidentialite" class="text-gold-500 hover:text-gold-400">politique de confidentialité</a>
                    </label>
                </div>
                
                <!-- Submit -->
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                    <i class="fas fa-user-plus mr-2"></i> Créer mon compte
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-dark-700"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-dark-900 text-dark-500">Déjà un compte ?</span>
                </div>
            </div>
            
            <!-- Login Link -->
            <a href="<?= SITE_URL ?>/connexion" class="block w-full py-3 text-center border border-dark-600 text-white font-medium rounded-lg hover:border-gold-500 hover:text-gold-500 transition-all duration-300">
                <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
            </a>
        </div>
        
        <!-- Back to Home -->
        <div class="text-center mt-8">
            <a href="<?= SITE_URL ?>" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</section>
