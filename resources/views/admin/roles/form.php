<?php
/**
 * TSILIZY LLC — Admin Role Form View
 */

Core\View::layout('admin', ['page_title' => $mode === 'create' ? 'Nouveau rôle' : 'Modifier le rôle']);
$r = $role ?? [];
$isSystemRole = in_array($r['slug'] ?? '', ['super_admin', 'admin']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/roles" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= $mode === 'create' ? 'Nouveau rôle' : 'Modifier le rôle' ?></h2>
    </div>
</div>

<form action="<?= $mode === 'create' ? SITE_URL . '/admin/roles' : SITE_URL . '/admin/roles/' . ($r['id'] ?? '') ?>" method="POST">
    <?= Core\CSRF::field() ?>
    
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Nom du rôle <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required value="<?= Core\View::e($r['name'] ?? '') ?>" <?= $isSystemRole ? 'readonly' : '' ?> class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white <?= $isSystemRole ? 'opacity-50' : '' ?>">
                </div>
                
                <?php if ($mode === 'create'): ?>
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Slug</label>
                    <input type="text" name="slug" value="" placeholder="Généré automatiquement" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white font-mono text-sm">
                </div>
                <?php endif; ?>
                
                <div>
                    <label class="block text-sm font-medium text-dark-300 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 bg-dark-900 border border-dark-600 rounded-lg text-white"><?= Core\View::e($r['description'] ?? '') ?></textarea>
                </div>
                
                <div class="pt-4 border-t border-dark-600">
                    <button type="submit" class="w-full py-3 bg-gold-500 text-dark-950 font-semibold rounded-lg hover:bg-gold-400 transition-colors"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-2">
            <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6">Permissions</h3>
                
                <div class="space-y-6">
                    <?php foreach ($permissionGroups as $group => $permissions): ?>
                    <div>
                        <h4 class="text-sm font-semibold text-gold-500 uppercase tracking-wider mb-3"><?= Core\View::e(ucfirst($group)) ?></h4>
                        <div class="grid md:grid-cols-2 gap-3">
                            <?php foreach ($permissions as $p): ?>
                            <label class="flex items-center p-3 bg-dark-700 rounded-lg hover:bg-dark-600 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="<?= $p['id'] ?>" <?= in_array($p['id'], $rolePermissions) ? 'checked' : '' ?> class="w-4 h-4 rounded border-dark-500 text-gold-500 focus:ring-gold-500 bg-dark-900">
                                <span class="ml-3 text-sm text-dark-300"><?= Core\View::e($p['name']) ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (empty($permissionGroups)): ?>
                <p class="text-dark-400 text-center py-8">Aucune permission définie dans le système.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>
