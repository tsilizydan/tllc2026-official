<?php
/**
 * TSILIZY LLC — Admin Newsletter View
 */

Core\View::layout('admin', ['page_title' => 'Newsletter']);
?>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-white">Newsletter</h2>
    <a href="<?= SITE_URL ?>/admin/newsletter/export" class="px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30">
        <i class="fas fa-download mr-2"></i> Exporter CSV
    </a>
</div>

<div class="grid md:grid-cols-2 gap-4 mb-6">
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div><p class="text-dark-400 text-sm">Total abonnés</p><p class="text-3xl font-bold text-white"><?= number_format($total) ?></p></div>
            <div class="w-12 h-12 bg-gold-500/10 rounded-lg flex items-center justify-center"><i class="fas fa-users text-gold-500"></i></div>
        </div>
    </div>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div><p class="text-dark-400 text-sm">Actifs</p><p class="text-3xl font-bold text-green-400"><?= number_format($active_count) ?></p></div>
            <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center"><i class="fas fa-check-circle text-green-400"></i></div>
        </div>
    </div>
</div>

<?php if (!empty($subscribers)): ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Email</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Date</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php foreach ($subscribers as $sub): ?>
            <tr class="hover:bg-dark-700/50">
                <td class="px-6 py-4 text-white"><?= Core\View::e($sub['email']) ?></td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full <?= $sub['status'] === 'subscribed' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                        <?= $sub['status'] === 'subscribed' ? 'Actif' : 'Désabonné' ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-dark-400 text-sm"><?= Core\View::date($sub['created_at']) ?></td>
                <td class="px-6 py-4 text-right">
                    <form action="<?= SITE_URL ?>/admin/newsletter/<?= $sub['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer cet abonné ?')">
                        <?= Core\CSRF::field() ?>
                        <button type="submit" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($last_page > 1): ?>
<div class="flex justify-center mt-6 space-x-2">
    <?php for ($i = 1; $i <= $last_page; $i++): ?>
    <a href="?page=<?= $i ?>" class="px-3 py-2 rounded-lg <?= $i === $current_page ? 'bg-gold-500 text-dark-950' : 'bg-dark-800 text-dark-300 hover:bg-dark-700' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-envelope text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucun abonné pour le moment.</p>
</div>
<?php endif; ?>
