<?php
/**
 * TSILIZY LLC — Admin Partners List
 */

Core\View::layout('admin', ['page_title' => 'Partenaires']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-white">Partenaires</h2>
        <p class="text-dark-400 text-sm mt-1"><?= count($partners) ?> partenaire(s)</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/partenaires/creer" class="px-4 py-2 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-medium rounded-lg hover:shadow-lg">
        <i class="fas fa-plus mr-2"></i> Nouveau partenaire
    </a>
</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (!empty($partners)): ?>
        <?php foreach ($partners as $partner): ?>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 group hover:border-gold-500/50 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="w-16 h-16 bg-dark-700 rounded-xl flex items-center justify-center overflow-hidden">
                    <?php if ($partner['logo']): ?>
                    <img src="<?= Core\View::upload($partner['logo']) ?>" class="w-full h-full object-contain p-2">
                    <?php else: ?>
                    <i class="fas fa-building text-dark-500 text-2xl"></i>
                    <?php endif; ?>
                </div>
                <span class="px-2 py-1 text-xs rounded-full <?= $partner['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-dark-600 text-dark-400' ?>">
                    <?= $partner['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                </span>
            </div>
            
            <h3 class="text-white font-semibold mb-1"><?= Core\View::e($partner['name']) ?></h3>
            <?php if ($partner['category']): ?>
            <p class="text-dark-500 text-sm mb-2"><?= Core\View::e($partner['category']) ?></p>
            <?php endif; ?>
            
            <?php if ($partner['website']): ?>
            <a href="<?= Core\View::e($partner['website']) ?>" target="_blank" class="text-gold-500 text-sm hover:text-gold-400">
                <i class="fas fa-external-link-alt mr-1"></i> Site web
            </a>
            <?php endif; ?>
            
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-dark-700">
                <?php if ($partner['is_featured']): ?>
                <span class="text-gold-500 text-xs"><i class="fas fa-star mr-1"></i> En vedette</span>
                <?php else: ?>
                <span></span>
                <?php endif; ?>
                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="<?= SITE_URL ?>/admin/partenaires/<?= $partner['id'] ?>/modifier" class="p-2 text-dark-400 hover:text-white"><i class="fas fa-edit"></i></a>
                    <form action="<?= SITE_URL ?>/admin/partenaires/<?= $partner['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                        <?= Core\CSRF::field() ?>
                        <button type="submit" class="p-2 text-dark-400 hover:text-red-400"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-span-full bg-dark-800 border border-dark-700 rounded-xl p-12 text-center text-dark-400">
            <i class="fas fa-handshake text-4xl mb-4 block"></i>Aucun partenaire
        </div>
    <?php endif; ?>
</div>
