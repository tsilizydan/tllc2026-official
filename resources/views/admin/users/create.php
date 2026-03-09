<?php
/**
 * TSILIZY LLC — Admin Users Create Form
 */

Core\View::layout('admin', ['page_title' => 'Créer un utilisateur']);
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Nouvel utilisateur</h2>
        <p class="text-dark-400 text-sm mt-1">Créez un nouveau compte utilisateur</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/utilisateurs" class="px-4 py-2 border border-dark-600 text-dark-300 rounded-lg hover:text-white hover:border-dark-500 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Retour
    </a>
</div>

<form action="<?= SITE_URL ?>/admin/utilisateurs" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Informations</h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-dark-300 mb-2">
                            Prénom <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="first_name" name="first_name" required
                               value="<?= Core\View::e(Core\Session::old('first_name')) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 <?= isset($errors['first_name']) ? 'border-red-500' : '' ?>">
                        <?php if (isset($errors['first_name'])): ?>
                        <p class="text-red-400 text-xs mt-1"><?= $errors['first_name'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-dark-300 mb-2">
                            Nom <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="last_name" name="last_name" required
                               value="<?= Core\View::e(Core\Session::old('last_name')) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 <?= isset($errors['last_name']) ? 'border-red-500' : '' ?>">
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-2">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                           value="<?= Core\View::e(Core\Session::old('email')) ?>"
                           class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 <?= isset($errors['email']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['email'])): ?>
                    <p class="text-red-400 text-xs mt-1"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-dark-300 mb-2">
                        Téléphone
                    </label>
                    <input type="tel" id="phone" name="phone"
                           value="<?= Core\View::e(Core\Session::old('phone')) ?>"
                           class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-2">
                        Mot de passe <span class="text-red-400">*</span>
                    </label>
                    <input type="password" id="password" name="password" required minlength="8"
                           class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500 <?= isset($errors['password']) ? 'border-red-500' : '' ?>">
                    <p class="text-dark-500 text-xs mt-1">Minimum 8 caractères</p>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Rôle</h3>
                
                <div>
                    <label for="role_id" class="block text-sm font-medium text-dark-300 mb-2">
                        Attribuer un rôle <span class="text-red-400">*</span>
                    </label>
                    <select id="role_id" name="role_id" required class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                        <option value="">Sélectionner...</option>
                        <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>"><?= Core\View::e($role['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mt-6 pt-6 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-user-plus mr-2"></i> Créer l'utilisateur
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
