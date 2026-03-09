<?php
/**
 * TSILIZY LLC — Public Portfolio Detail
 */

Core\View::layout('public', ['page_title' => $item['title'] ?? 'Projet']);
?>

<section class="py-24 bg-dark-950">
    <div class="max-w-5xl mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <a href="<?= SITE_URL ?>/portfolio" class="text-dark-400 hover:text-gold-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Retour au portfolio
            </a>
        </nav>
        
        <!-- Header -->
        <header class="mb-12">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <?php if ($item['category']): ?>
                <span class="px-3 py-1 bg-gold-500/20 text-gold-500 text-sm rounded-full"><?= Core\View::e($item['category']) ?></span>
                <?php endif; ?>
                <?php if ($item['completed_at']): ?>
                <span class="text-dark-500 text-sm"><i class="fas fa-calendar mr-1"></i> <?= Core\View::date($item['completed_at']) ?></span>
                <?php endif; ?>
            </div>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4"><?= Core\View::e($item['title']) ?></h1>
            <?php if ($item['short_description']): ?>
            <p class="text-xl text-dark-400"><?= Core\View::e($item['short_description']) ?></p>
            <?php endif; ?>
        </header>
        
        <!-- Featured Image -->
        <?php if ($item['image']): ?>
        <div class="mb-12">
            <img src="<?= Core\View::upload($item['image']) ?>" alt="<?= Core\View::e($item['title']) ?>" 
                 class="w-full rounded-2xl shadow-2xl">
        </div>
        <?php endif; ?>
        
        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Content -->
            <div class="lg:col-span-2">
                <div class="prose prose-lg prose-invert max-w-none">
                    <?= $item['description'] ?>
                </div>
            </div>
            
            <!-- Sidebar -->
            <aside>
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-white mb-6">Détails du projet</h3>
                    
                    <div class="space-y-4">
                        <?php if ($item['client_name']): ?>
                        <div>
                            <span class="text-dark-500 text-sm block">Client</span>
                            <span class="text-white font-medium"><?= Core\View::e($item['client_name']) ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($item['category']): ?>
                        <div>
                            <span class="text-dark-500 text-sm block">Catégorie</span>
                            <span class="text-white font-medium"><?= Core\View::e($item['category']) ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($item['completed_at']): ?>
                        <div>
                            <span class="text-dark-500 text-sm block">Date</span>
                            <span class="text-white font-medium"><?= Core\View::date($item['completed_at']) ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($item['tags']): ?>
                        <div>
                            <span class="text-dark-500 text-sm block mb-2">Technologies</span>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach (explode(',', $item['tags']) as $tag): ?>
                                <span class="px-3 py-1 bg-dark-800 text-dark-300 text-sm rounded-full"><?= Core\View::e(trim($tag)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($item['project_url']): ?>
                    <div class="mt-6 pt-6 border-t border-dark-700">
                        <a href="<?= Core\View::e($item['project_url']) ?>" target="_blank" 
                           class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-external-link-alt mr-2"></i> Voir le projet
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-gradient-to-b from-dark-950 to-dark-900">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Un projet similaire ?</h2>
        <p class="text-dark-400 mb-8">Discutons de vos besoins et voyons comment nous pouvons vous aider à atteindre vos objectifs.</p>
        <a href="<?= SITE_URL ?>/contact" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-xl hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
            <i class="fas fa-envelope mr-2"></i> Contactez-nous
        </a>
    </div>
</section>
