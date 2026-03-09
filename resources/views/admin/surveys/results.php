<?php
/**
 * TSILIZY LLC — Admin Survey Results View
 */

Core\View::layout('admin', ['page_title' => 'Résultats du sondage']);
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <a href="<?= SITE_URL ?>/admin/sondages" class="text-dark-400 hover:text-white text-sm"><i class="fas fa-arrow-left mr-2"></i> Retour</a>
        <h2 class="text-2xl font-bold text-white mt-2"><?= Core\View::e($survey['title']) ?></h2>
    </div>
    <span class="text-gold-500 font-semibold"><?= count($responses) ?> réponses</span>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <?php foreach ($survey['questions'] as $i => $q): ?>
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-6">
        <h3 class="font-semibold text-white mb-4"><?= ($i + 1) ?>. <?= Core\View::e($q['text'] ?? 'Question') ?></h3>
        
        <?php if ($q['type'] === 'rating'): ?>
        <div class="flex items-center space-x-4">
            <?php
            $ratings = array_fill(1, 5, 0);
            foreach ($responses as $r) {
                $answers = json_decode($r['answers'] ?? '{}', true);
                if (isset($answers[$i])) $ratings[(int)$answers[$i]]++;
            }
            $avgRating = count($responses) > 0 ? array_sum(array_map(fn($v, $k) => $v * $k, $ratings, array_keys($ratings))) / count($responses) : 0;
            ?>
            <div class="text-3xl font-bold text-gold-500"><?= number_format($avgRating, 1) ?></div>
            <div class="flex-1">
                <?php for ($star = 5; $star >= 1; $star--): ?>
                <div class="flex items-center space-x-2 text-sm">
                    <span class="w-4 text-dark-400"><?= $star ?></span>
                    <div class="flex-1 h-2 bg-dark-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gold-500" style="width: <?= count($responses) > 0 ? ($ratings[$star] / count($responses) * 100) : 0 ?>%"></div>
                    </div>
                    <span class="w-8 text-dark-400 text-right"><?= $ratings[$star] ?></span>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            <?php foreach ($responses as $r): ?>
            <?php $answers = json_decode($r['answers'] ?? '{}', true); ?>
            <?php if (isset($answers[$i])): ?>
            <div class="text-dark-300 text-sm p-2 bg-dark-700 rounded"><?= Core\View::e($answers[$i]) ?></div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

<?php if (!empty($responses)): ?>
<div class="bg-dark-800 border border-dark-700 rounded-xl mt-6 overflow-hidden">
    <table class="w-full">
        <thead class="bg-dark-900">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Date</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">IP</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-dark-400 uppercase">Réponses</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-dark-700">
            <?php foreach ($responses as $r): ?>
            <tr class="hover:bg-dark-700/50">
                <td class="px-6 py-4 text-dark-400 text-sm"><?= Core\View::datetime($r['created_at']) ?></td>
                <td class="px-6 py-4 text-dark-500 font-mono text-sm"><?= $r['ip_address'] ?? '-' ?></td>
                <td class="px-6 py-4 text-dark-300 text-sm truncate max-w-xs"><?= Core\View::e(substr(json_encode(json_decode($r['answers'] ?? '{}', true)), 0, 100)) ?>...</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
