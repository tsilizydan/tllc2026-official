<?php
/**
 * TSILIZY LLC — Services Listing Page
 */

Core\View::layout('public', [
    'page_title' => 'Nos Services',
    'seo_title' => $seo_title ?? 'Nos Services | ' . SITE_NAME,
    'seo_description' => $seo_description ?? ''
]);
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-16 bg-gradient-to-b from-dark-900 to-dark-950">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23C9A227\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
            Nos Services
        </span>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
            Des solutions <span class="text-gold-gradient">sur mesure</span>
        </h1>
        <p class="text-xl text-dark-400 max-w-2xl mx-auto">
            Découvrez notre gamme complète de services conçus pour répondre à vos besoins les plus exigeants.
        </p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if (!empty($services)): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $service): ?>
            <div class="group relative bg-dark-800/50 border border-dark-700 rounded-2xl p-8 hover:border-gold-500/50 transition-all duration-500 hover:-translate-y-2">
                <!-- Icon -->
                <div class="w-16 h-16 bg-gradient-to-br from-gold-500/20 to-gold-600/10 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas <?= Core\View::e($service['icon'] ?? 'fa-star') ?> text-2xl text-gold-500"></i>
                </div>
                
                <!-- Content -->
                <h3 class="text-xl font-semibold text-white mb-4 group-hover:text-gold-500 transition-colors">
                    <?= Core\View::e($service['title']) ?>
                </h3>
                <p class="text-dark-400 leading-relaxed mb-6">
                    <?= Core\View::e($service['short_description'] ?? Core\View::truncate($service['description'], 150)) ?>
                </p>
                
                <?php if ($service['price']): ?>
                <div class="mb-6">
                    <span class="text-gold-500 font-semibold text-lg"><?= Core\View::currency($service['price']) ?></span>
                    <?php if ($service['duration']): ?>
                    <span class="text-dark-500 text-sm">/ <?= Core\View::e($service['duration']) ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Link -->
                <a href="<?= SITE_URL ?>/services/<?= Core\View::e($service['slug']) ?>" class="inline-flex items-center text-gold-500 hover:text-gold-400 transition-colors text-sm font-medium">
                    En savoir plus <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
                
                <!-- Decorative -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-500/5 to-transparent rounded-tr-2xl"></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- Default Services -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $defaultServices = [
                ['icon' => 'fa-lightbulb', 'title' => 'Conseil Stratégique', 'desc' => 'Accompagnement personnalisé pour optimiser votre stratégie d\'entreprise et atteindre vos objectifs.'],
                ['icon' => 'fa-laptop-code', 'title' => 'Développement Digital', 'desc' => 'Solutions technologiques innovantes pour digitaliser et moderniser votre activité.'],
                ['icon' => 'fa-palette', 'title' => 'Design & Créativité', 'desc' => 'Création visuelle impactante pour renforcer votre image de marque et captiver votre audience.'],
                ['icon' => 'fa-chart-line', 'title' => 'Marketing & Growth', 'desc' => 'Stratégies marketing ciblées pour accélérer votre croissance et maximiser votre ROI.'],
                ['icon' => 'fa-users-cog', 'title' => 'Gestion de Projets', 'desc' => 'Pilotage expert de vos projets de A à Z pour garantir leur succès dans les délais.'],
                ['icon' => 'fa-headset', 'title' => 'Support & Formation', 'desc' => 'Accompagnement continu et formation de vos équipes pour une autonomie optimale.']
            ];
            foreach ($defaultServices as $service): 
            ?>
            <div class="group relative bg-dark-800/50 border border-dark-700 rounded-2xl p-8 hover:border-gold-500/50 transition-all duration-500 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-gold-500/20 to-gold-600/10 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas <?= $service['icon'] ?> text-2xl text-gold-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-4 group-hover:text-gold-500 transition-colors">
                    <?= $service['title'] ?>
                </h3>
                <p class="text-dark-400 leading-relaxed mb-6">
                    <?= $service['desc'] ?>
                </p>
                <a href="<?= SITE_URL ?>/contact" class="inline-flex items-center text-gold-500 hover:text-gold-400 transition-colors text-sm font-medium">
                    Demander un devis <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-500/5 to-transparent rounded-tr-2xl"></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<!-- CTA Section -->
<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-r from-gold-600/20 to-gold-500/10 rounded-2xl p-12 border border-gold-500/20">
            <h2 class="text-3xl font-serif font-bold text-white mb-4">
                Besoin d'un service sur mesure ?
            </h2>
            <p class="text-dark-300 mb-8">
                Contactez-nous pour discuter de vos besoins spécifiques. Nous créerons une solution adaptée à votre entreprise.
            </p>
            <a href="<?= SITE_URL ?>/contact" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                <i class="fas fa-envelope mr-2"></i> Demander un devis
            </a>
        </div>
    </div>
</section>
