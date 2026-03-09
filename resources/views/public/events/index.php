<?php
/**
 * TSILIZY LLC — Public Events Page
 */

Core\View::layout('public', ['page_title' => 'Événements']);
?>

<!-- Hero -->
<section class="relative py-24 bg-dark-950">
    <div class="absolute inset-0 bg-gradient-to-br from-gold-500/5 to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 text-center">
        <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/30 rounded-full text-gold-500 text-sm font-medium mb-6">
            <i class="fas fa-calendar-star mr-2"></i> Événements
        </span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6">Nos événements</h1>
        <p class="text-xl text-dark-400 max-w-2xl mx-auto">Rejoignez-nous pour des conférences, ateliers et rencontres exclusives.</p>
    </div>
</section>

<!-- Upcoming Events -->
<section class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-white mb-8">À venir</h2>
        
        <?php if (!empty($upcoming)): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($upcoming as $event): ?>
            <article class="group bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden hover:border-gold-500/50 transition-all duration-500">
                <div class="relative h-48 overflow-hidden">
                    <?php if ($event['image']): ?>
                    <img src="<?= Core\View::upload($event['image']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-gold-500/20 to-dark-700 flex items-center justify-center">
                        <i class="fas fa-calendar text-4xl text-dark-500"></i>
                    </div>
                    <?php endif; ?>
                    <div class="absolute top-4 left-4">
                        <div class="bg-dark-950/90 backdrop-blur-sm rounded-lg p-3 text-center">
                            <p class="text-gold-500 font-bold text-2xl"><?= date('d', strtotime($event['start_date'])) ?></p>
                            <p class="text-white text-sm uppercase"><?= strftime('%b', strtotime($event['start_date'])) ?></p>
                        </div>
                    </div>
                    <?php if ($event['is_online']): ?>
                    <div class="absolute top-4 right-4 px-3 py-1 bg-blue-500/90 text-white text-xs rounded-full">En ligne</div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <?php if ($event['category']): ?>
                    <span class="text-gold-500 text-sm"><?= Core\View::e($event['category']) ?></span>
                    <?php endif; ?>
                    <h3 class="text-xl font-bold text-white mt-2 mb-3 group-hover:text-gold-500 transition-colors"><?= Core\View::e($event['title']) ?></h3>
                    
                    <div class="space-y-2 text-sm text-dark-400 mb-4">
                        <p><i class="fas fa-clock mr-2 text-gold-500"></i> <?= date('H:i', strtotime($event['start_date'])) ?></p>
                        <?php if ($event['location']): ?>
                        <p><i class="fas fa-map-marker-alt mr-2 text-gold-500"></i> <?= Core\View::e($event['location']) ?></p>
                        <?php endif; ?>
                        <?php if ($event['price']): ?>
                        <p><i class="fas fa-euro-sign mr-2 text-gold-500"></i> <?= Core\View::currency($event['price']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?= SITE_URL ?>/evenements/<?= $event['slug'] ?>" class="inline-flex items-center text-gold-500 font-medium hover:text-gold-400 transition-colors">
                        En savoir plus <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16 text-dark-400">
            <i class="fas fa-calendar-times text-5xl mb-4 block"></i>
            <p>Aucun événement à venir pour le moment.</p>
            <p class="text-sm mt-2">Inscrivez-vous à notre newsletter pour être informé des prochains événements.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Past Events -->
<?php if (!empty($past)): ?>
<section class="py-20 bg-dark-950">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-white mb-8">Événements passés</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($past as $event): ?>
            <article class="bg-dark-800/50 border border-dark-700 rounded-xl p-4 opacity-75">
                <p class="text-dark-500 text-sm mb-2"><?= Core\View::date($event['start_date']) ?></p>
                <h4 class="text-white font-medium mb-1"><?= Core\View::e($event['title']) ?></h4>
                <p class="text-dark-500 text-sm"><?= Core\View::e($event['location'] ?? 'En ligne') ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
