<?php
/**
 * TSILIZY LLC — Public Careers Page
 */

Core\View::layout('public', ['page_title' => 'Carrières']);
?>

<!-- Hero -->
<section class="relative py-24 bg-dark-950">
    <div class="absolute inset-0 bg-gradient-to-br from-gold-500/5 to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 text-center">
        <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/30 rounded-full text-gold-500 text-sm font-medium mb-6">
            <i class="fas fa-users mr-2"></i> Rejoignez notre équipe
        </span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6">Carrières</h1>
        <p class="text-xl text-dark-400 max-w-2xl mx-auto">Découvrez nos opportunités d'emploi et construisez votre carrière avec nous.</p>
    </div>
</section>

<!-- Why Join Us -->
<section class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white mb-4">Pourquoi nous rejoindre ?</h2>
            <p class="text-dark-400 max-w-2xl mx-auto">Nous offrons un environnement de travail stimulant et des avantages compétitifs.</p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-gold-500/10 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-laptop-house text-gold-500 text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold mb-2">Télétravail flexible</h3>
                <p class="text-dark-400 text-sm">Travaillez d'où vous voulez</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-gold-500/10 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-graduation-cap text-gold-500 text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold mb-2">Formation continue</h3>
                <p class="text-dark-400 text-sm">Développez vos compétences</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-gold-500/10 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-heart text-gold-500 text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold mb-2">Avantages santé</h3>
                <p class="text-dark-400 text-sm">Mutuelle premium</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-gold-500/10 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-rocket text-gold-500 text-2xl"></i>
                </div>
                <h3 class="text-white font-semibold mb-2">Évolution rapide</h3>
                <p class="text-dark-400 text-sm">Progressez dans votre carrière</p>
            </div>
        </div>
    </div>
</section>

<!-- Job Listings -->
<section class="py-20 bg-dark-950">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-white mb-8">Postes ouverts</h2>
        
        <?php if (!empty($jobs)): ?>
        <div class="space-y-4">
            <?php foreach ($jobs as $job): ?>
            <a href="<?= SITE_URL ?>/carrieres/<?= $job['slug'] ?>" class="block group">
                <div class="bg-dark-800 border border-dark-700 rounded-xl p-6 hover:border-gold-500/50 transition-all duration-300">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-white group-hover:text-gold-500 transition-colors"><?= Core\View::e($job['title']) ?></h3>
                            <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-dark-400">
                                <?php if ($job['department']): ?>
                                <span><i class="fas fa-building mr-1"></i> <?= Core\View::e($job['department']) ?></span>
                                <?php endif; ?>
                                <span><i class="fas fa-map-marker-alt mr-1"></i> <?= Core\View::e($job['location'] ?? 'Non spécifié') ?></span>
                                <?php if ($job['is_remote']): ?>
                                <span class="text-blue-400"><i class="fas fa-home mr-1"></i> Remote</span>
                                <?php endif; ?>
                                <span><i class="fas fa-clock mr-1"></i> <?php echo match($job['employment_type'] ?? '') { 'full_time' => 'Temps plein', 'part_time' => 'Temps partiel', 'contract' => 'Contrat', 'internship' => 'Stage', default => '-' }; ?></span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <?php if ($job['salary_min'] && $job['salary_max']): ?>
                            <span class="text-gold-500 font-medium"><?= number_format($job['salary_min']) ?>€ - <?= number_format($job['salary_max']) ?>€</span>
                            <?php endif; ?>
                            <button class="px-4 py-2 bg-gold-500/10 text-gold-500 rounded-lg group-hover:bg-gold-500 group-hover:text-dark-950 transition-all">
                                Postuler <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16 bg-dark-800 border border-dark-700 rounded-xl">
            <i class="fas fa-briefcase text-5xl text-dark-600 mb-4 block"></i>
            <h3 class="text-white text-xl font-semibold mb-2">Aucun poste ouvert</h3>
            <p class="text-dark-400 mb-6">Nous n'avons pas de postes ouverts pour le moment, mais n'hésitez pas à nous envoyer une candidature spontanée.</p>
            <a href="<?= SITE_URL ?>/contact" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-xl">
                <i class="fas fa-paper-plane mr-2"></i> Candidature spontanée
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>
