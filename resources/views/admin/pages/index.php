<?php
/**
 * TSILIZY LLC — Admin Pages Index View
 */

Core\View::layout('admin', ['page_title' => 'Pages']);
?>

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <h2 class="text-2xl font-bold text-white">Pages</h2>
    <a href="<?= SITE_URL ?>/admin/pages/creer" class="inline-flex items-center px-4 py-2 bg-gold-500 text-dark-950 font-semibold rounded-lg"><i class="fas fa-plus mr-2"></i> Nouvelle page</a>
</div>

<?php if (!empty($pages)): ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[600px]">
            <thead class="bg-dark-900">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Titre</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase hidden md:table-cell">Slug</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-dark-400 uppercase">Statut</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-dark-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700">
                <?php foreach ($pages as $p): ?>
                <tr class="hover:bg-dark-700/50">
                    <td class="px-6 py-4">
                        <a href="<?= SITE_URL ?>/page/<?= Core\View::e($p['slug']) ?>" target="_blank" class="text-white hover:text-gold-500"><?= Core\View::e($p['title']) ?></a>
                    </td>
                    <td class="px-6 py-4 text-dark-400 font-mono text-sm hidden md:table-cell">/page/<?= Core\View::e($p['slug']) ?></td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs rounded-full <?= $p['status'] === 'published' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' ?>">
                            <?= $p['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="<?= SITE_URL ?>/admin/pages/<?= $p['id'] ?>/modifier" class="text-gold-500 hover:text-gold-400"><i class="fas fa-edit"></i></a>
                        <form action="<?= SITE_URL ?>/admin/pages/<?= $p['id'] ?>/supprimer" method="POST" class="inline" onsubmit="return confirm('Supprimer cette page ?')">
                            <?= Core\CSRF::field() ?><button type="submit" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php else: ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl p-12 text-center">
    <i class="fas fa-file-alt text-4xl text-dark-600 mb-4"></i>
    <p class="text-dark-400">Aucune page.</p>
    <a href="<?= SITE_URL ?>/admin/pages/creer" class="inline-block mt-4 text-gold-500 hover:text-gold-400">Créer une page</a>
</div>
<?php endif; ?>
