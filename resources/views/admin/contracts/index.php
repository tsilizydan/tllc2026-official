<?php
/**
 * TSILIZY LLC — Admin Contracts Index View
 */

Core\View::layout('admin', ['page_title' => 'Contrats']);
?>

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-white">Contrats</h2>
    <a href="<?= SITE_URL ?>/admin/contrats/creer" class="px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-plus mr-2"></i> Nouveau</a>
</div>

<?php if (!empty($contracts)): ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Référence</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Client</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Titre</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Valeur</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Statut</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-dark-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php foreach ($contracts as $c): ?>
            <?php
            $statusColors = ['draft' => 'bg-dark-600 text-dark-300', 'pending' => 'bg-yellow-500/20 text-yellow-400', 'active' => 'bg-green-500/20 text-green-400', 'completed' => 'bg-blue-500/20 text-blue-400', 'cancelled' => 'bg-red-500/20 text-red-400'];
            $statusLabels = ['draft' => 'Brouillon', 'pending' => 'En attente', 'active' => 'Actif', 'completed' => 'Terminé', 'cancelled' => 'Annulé'];
            ?>
            <tr class="hover:bg-dark-700/50">
                <td class="px-6 py-4 font-mono text-gold-500"><?= Core\View::e($c['reference']) ?></td>
                <td class="px-6 py-4 text-white"><?= Core\View::e($c['client_name'] ?? '-') ?></td>
                <td class="px-6 py-4 text-dark-300"><?= Core\View::e($c['title']) ?></td>
                <td class="px-6 py-4 text-white"><?= $c['value'] ? Core\View::currency($c['value']) : '-' ?></td>
                <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full <?= $statusColors[$c['status']] ?? '' ?>"><?= $statusLabels[$c['status']] ?? $c['status'] ?></span></td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="<?= SITE_URL ?>/admin/contrats/<?= $c['id'] ?>/pdf" target="_blank" class="text-blue-400 hover:text-blue-300"><i class="fas fa-file-pdf"></i></a>
                    <a href="<?= SITE_URL ?>/admin/contrats/<?= $c['id'] ?>/modifier" class="text-gold-500 hover:text-gold-400"><i class="fas fa-edit"></i></a>
                    <form action="<?= SITE_URL ?>/admin/contrats/<?= $c['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer ?')">
                        <?= Core\CSRF::field() ?><button type="submit" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-file-contract text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucun contrat.</p>
</div>
<?php endif; ?>
