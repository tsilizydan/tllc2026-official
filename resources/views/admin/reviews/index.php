<?php
/**
 * TSILIZY LLC — Admin Reviews List
 */

Core\View::layout('admin', ['page_title' => 'Avis clients']);
?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-white">Avis clients</h2>
    <p class="text-dark-400 text-sm mt-1"><?= number_format($total) ?> avis</p>
</div>

<!-- Stats -->
<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div><p class="text-dark-400 text-sm">En attente</p><p class="text-2xl font-bold text-yellow-400"><?= $stats['pending'] ?></p></div>
            <i class="fas fa-clock text-yellow-400 text-2xl"></i>
        </div>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div><p class="text-dark-400 text-sm">Approuvés</p><p class="text-2xl font-bold text-green-400"><?= $stats['approved'] ?></p></div>
            <i class="fas fa-check text-green-400 text-2xl"></i>
        </div>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div><p class="text-dark-400 text-sm">Rejetés</p><p class="text-2xl font-bold text-red-400"><?= $stats['rejected'] ?></p></div>
            <i class="fas fa-times text-red-400 text-2xl"></i>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="flex gap-2 mb-6">
    <a href="<?= SITE_URL ?>/admin/avis" class="px-4 py-2 rounded-lg text-sm <?= !$status ? 'bg-gold-500 text-dark-950' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Tous</a>
    <a href="?status=pending" class="px-4 py-2 rounded-lg text-sm <?= $status === 'pending' ? 'bg-yellow-500 text-dark-950' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">En attente</a>
    <a href="?status=approved" class="px-4 py-2 rounded-lg text-sm <?= $status === 'approved' ? 'bg-green-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Approuvés</a>
    <a href="?status=rejected" class="px-4 py-2 rounded-lg text-sm <?= $status === 'rejected' ? 'bg-red-500 text-white' : 'bg-dark-700 text-dark-300 hover:text-white' ?>">Rejetés</a>
</div>

<div class="space-y-4">
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gold-500/20 rounded-full flex items-center justify-center">
                        <span class="text-gold-500 font-bold"><?= strtoupper(substr($review['name'], 0, 1)) ?></span>
                    </div>
                    <div>
                        <p class="text-white font-medium"><?= Core\View::e($review['name']) ?></p>
                        <p class="text-dark-500 text-sm"><?= Core\View::e($review['company'] ?? $review['email']) ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Rating -->
                    <div class="flex text-gold-500">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star<?= $i <= $review['rating'] ? '' : ' text-dark-600' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full <?php echo match($review['status']) { 'approved' => 'bg-green-500/20 text-green-400', 'pending' => 'bg-yellow-500/20 text-yellow-400', 'rejected' => 'bg-red-500/20 text-red-400', default => 'bg-dark-600 text-dark-400' }; ?>">
                        <?php echo match($review['status']) { 'approved' => 'Approuvé', 'pending' => 'En attente', 'rejected' => 'Rejeté', default => $review['status'] }; ?>
                    </span>
                </div>
            </div>
            
            <?php if ($review['title']): ?>
            <h4 class="text-white font-medium mt-4"><?= Core\View::e($review['title']) ?></h4>
            <?php endif; ?>
            <p class="text-dark-400 mt-2"><?= Core\View::e($review['content']) ?></p>
            
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-dark-700">
                <span class="text-dark-500 text-sm"><?= Core\View::datetime($review['created_at']) ?></span>
                <div class="flex items-center space-x-2">
                    <?php if ($review['status'] === 'pending'): ?>
                    <form action="<?= SITE_URL ?>/admin/avis/<?= $review['id'] ?>/approuver" method="POST" class="inline">
                        <?= Core\CSRF::field() ?>
                        <button type="submit" class="px-3 py-1 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 text-sm"><i class="fas fa-check mr-1"></i> Approuver</button>
                    </form>
                    <form action="<?= SITE_URL ?>/admin/avis/<?= $review['id'] ?>/rejeter" method="POST" class="inline">
                        <?= Core\CSRF::field() ?>
                        <button type="submit" class="px-3 py-1 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 text-sm"><i class="fas fa-times mr-1"></i> Rejeter</button>
                    </form>
                    <?php endif; ?>
                    <form action="<?= SITE_URL ?>/admin/avis/<?= $review['id'] ?>/vedette" method="POST" class="inline">
                        <?= Core\CSRF::field() ?>
                        <button type="submit" class="px-3 py-1 <?= $review['is_featured'] ? 'bg-gold-500/20 text-gold-400' : 'bg-dark-700 text-dark-400' ?> rounded-lg hover:bg-gold-500/30 text-sm">
                            <i class="fas fa-star mr-1"></i> <?= $review['is_featured'] ? 'En vedette' : 'Vedette' ?>
                        </button>
                    </form>
                    <form action="<?= SITE_URL ?>/admin/avis/<?= $review['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                        <?= Core\CSRF::field() ?>
                        <button type="submit" class="p-2 text-dark-400 hover:text-red-400"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center text-dark-400">
            <i class="fas fa-star text-4xl mb-4 block"></i>Aucun avis
        </div>
    <?php endif; ?>
</div>
