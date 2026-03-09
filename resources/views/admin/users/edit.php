<?php
/**
 * TSILIZY LLC — Admin Users Edit Form
 */

Core\View::layout('admin', ['page_title' => 'Modifier l\'utilisateur']);
$errors = Core\Session::get('validation_errors', []);
Core\Session::remove('validation_errors');
?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Modifier l'utilisateur</h2>
        <p class="text-dark-400 text-sm mt-1"><?= Core\View::e($user['first_name'] . ' ' . $user['last_name']) ?></p>
    </div>
    <a href="<?= SITE_URL ?>/admin/utilisateurs" class="px-4 py-2 border border-dark-600 text-dark-300 rounded-lg hover:text-white hover:border-dark-500 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Retour
    </a>
</div>

<form action="<?= SITE_URL ?>/admin/utilisateurs/<?= $user['id'] ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <h3 class="text-lg font-semibold text-white">Informations</h3>
                
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-dark-300 mb-2">
                            Prénom <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="first_name" name="first_name" required
                               value="<?= Core\View::e($user['first_name']) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-dark-300 mb-2">
                            Nom <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="last_name" name="last_name" required
                               value="<?= Core\View::e($user['last_name']) ?>"
                               class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-300 mb-2">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                           value="<?= Core\View::e($user['email']) ?>"
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
                           value="<?= Core\View::e($user['phone'] ?? '') ?>"
                           class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-dark-300 mb-2">
                        Nouveau mot de passe
                    </label>
                    <input type="password" id="password" name="password" minlength="8"
                           class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                    <p class="text-dark-500 text-xs mt-1">Laissez vide pour ne pas modifier</p>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Role & Status -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Paramètres</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-dark-300 mb-2">
                            Rôle
                        </label>
                        <select id="role_id" name="role_id" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                            <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= ($user['role_id'] ?? null) == $role['id'] ? 'selected' : '' ?>>
                                <?= Core\View::e($role['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-dark-300 mb-2">
                            Statut
                        </label>
                        <select id="status" name="status" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white focus:outline-none focus:border-gold-500">
                            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Actif</option>
                            <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspendu</option>
                            <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>Banni</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-dark-600 space-y-3">
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </div>
            
            <!-- Info -->
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Informations</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-dark-400">Créé le</span>
                        <span class="text-white"><?= Core\View::date($user['created_at']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-dark-400">Dernière connexion</span>
                        <span class="text-white"><?= $user['last_login_at'] ? Core\View::datetime($user['last_login_at']) : 'Jamais' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
