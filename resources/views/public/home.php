<?php
/**
 * TSILIZY LLC — Homepage
 */

Core\View::layout('public', ['page_title' => 'Accueil']);
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-dark-950 via-dark-900 to-dark-950">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23C9A227\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    
    <!-- Animated Gradient Orbs -->
    <div class="absolute top-1/4 -left-20 w-96 h-96 bg-gold-500/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-gold-600/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    
    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-24">
        <div class="animate__animated animate__fadeInUp">
            <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-8">
                <i class="fas fa-star mr-2"></i> Excellence & Innovation
            </span>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <span class="text-white">Bienvenue chez</span>
            <br>
            <span class="text-gold-gradient">TSILIZY LLC</span>
        </h1>
        
        <p class="text-xl md:text-2xl text-dark-300 max-w-3xl mx-auto mb-12 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            Votre partenaire de confiance pour des solutions innovantes et un service d'excellence qui transforme vos ambitions en réalité.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
            <a href="<?= SITE_URL ?>/services" class="btn-luxury px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                <i class="fas fa-arrow-right mr-2"></i> Découvrir nos services
            </a>
            <a href="<?= SITE_URL ?>/contact" class="px-8 py-4 border border-dark-600 text-white font-medium rounded-lg hover:border-gold-500 hover:text-gold-500 transition-all duration-300">
                <i class="fas fa-phone mr-2"></i> Nous contacter
            </a>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-20 animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-serif font-bold text-gold-500 mb-2">10+</div>
                <div class="text-dark-400 text-sm uppercase tracking-wider">Années d'expérience</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-serif font-bold text-gold-500 mb-2">500+</div>
                <div class="text-dark-400 text-sm uppercase tracking-wider">Projets réalisés</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-serif font-bold text-gold-500 mb-2">99%</div>
                <div class="text-dark-400 text-sm uppercase tracking-wider">Clients satisfaits</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-serif font-bold text-gold-500 mb-2">24/7</div>
                <div class="text-dark-400 text-sm uppercase tracking-wider">Support disponible</div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <a href="#services" class="text-dark-400 hover:text-gold-500 transition-colors">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-24 bg-dark-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
                Nos Services
            </span>
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
                Des solutions sur mesure pour votre <span class="text-gold-gradient">succès</span>
            </h2>
            <p class="text-dark-400 text-lg">
                Découvrez notre gamme complète de services conçus pour répondre à vos besoins les plus exigeants.
            </p>
        </div>
        
        <!-- Services Grid -->
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
            $services = $services ?? $defaultServices;
            foreach ($services as $service): 
            ?>
            <div class="group relative bg-dark-800/50 border border-dark-700 rounded-2xl p-8 hover:border-gold-500/50 transition-all duration-500 hover:-translate-y-2">
                <!-- Icon -->
                <div class="w-16 h-16 bg-gradient-to-br from-gold-500/20 to-gold-600/10 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas <?= $service['icon'] ?> text-2xl text-gold-500"></i>
                </div>
                
                <!-- Content -->
                <h3 class="text-xl font-semibold text-white mb-4 group-hover:text-gold-500 transition-colors">
                    <?= $service['title'] ?>
                </h3>
                <p class="text-dark-400 leading-relaxed mb-6">
                    <?= $service['desc'] ?>
                </p>
                
                <!-- Link -->
                <a href="<?= SITE_URL ?>/services" class="inline-flex items-center text-gold-500 hover:text-gold-400 transition-colors text-sm font-medium">
                    En savoir plus <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
                
                <!-- Decorative -->
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-500/5 to-transparent rounded-tr-2xl"></div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-12">
            <a href="<?= SITE_URL ?>/services" class="inline-flex items-center px-6 py-3 border border-gold-500/30 text-gold-500 rounded-lg hover:bg-gold-500/10 transition-all duration-300">
                Voir tous nos services <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left -->
            <div class="relative">
                <div class="relative z-10">
                    <div class="bg-gradient-to-br from-dark-800 to-dark-900 rounded-2xl p-8 border border-dark-700">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-dark-800 rounded-xl p-6 text-center border border-dark-700">
                                <i class="fas fa-award text-4xl text-gold-500 mb-4"></i>
                                <h4 class="text-white font-semibold">Excellence</h4>
                            </div>
                            <div class="bg-dark-800 rounded-xl p-6 text-center border border-dark-700">
                                <i class="fas fa-shield-alt text-4xl text-gold-500 mb-4"></i>
                                <h4 class="text-white font-semibold">Confiance</h4>
                            </div>
                            <div class="bg-dark-800 rounded-xl p-6 text-center border border-dark-700">
                                <i class="fas fa-rocket text-4xl text-gold-500 mb-4"></i>
                                <h4 class="text-white font-semibold">Innovation</h4>
                            </div>
                            <div class="bg-dark-800 rounded-xl p-6 text-center border border-dark-700">
                                <i class="fas fa-handshake text-4xl text-gold-500 mb-4"></i>
                                <h4 class="text-white font-semibold">Partenariat</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Decorative -->
                <div class="absolute -top-4 -left-4 w-full h-full border-2 border-gold-500/20 rounded-2xl"></div>
                <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl"></div>
            </div>
            
            <!-- Right -->
            <div>
                <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
                    À Propos
                </span>
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
                    Une vision <span class="text-gold-gradient">d'excellence</span> pour votre réussite
                </h2>
                <p class="text-dark-400 text-lg leading-relaxed mb-8">
                    Chez TSILIZY LLC, nous croyons que chaque entreprise mérite un partenaire qui comprend ses défis uniques et propose des solutions sur mesure. Depuis plus de 10 ans, nous accompagnons nos clients vers le succès avec passion et expertise.
                </p>
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-gold-500 mt-1"></i>
                        <span class="text-dark-300">Équipe d'experts passionnés et dévoués</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-gold-500 mt-1"></i>
                        <span class="text-dark-300">Approche personnalisée pour chaque projet</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <i class="fas fa-check-circle text-gold-500 mt-1"></i>
                        <span class="text-dark-300">Engagement qualité et résultats garantis</span>
                    </li>
                </ul>
                <a href="<?= SITE_URL ?>/a-propos" class="btn-luxury inline-flex items-center px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                    En savoir plus <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Portfolio Section -->
<section class="py-24 bg-dark-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
                Portfolio
            </span>
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
                Nos <span class="text-gold-gradient">réalisations</span>
            </h2>
            <p class="text-dark-400 text-lg">
                Découvrez quelques-uns de nos projets récents qui témoignent de notre expertise et de notre engagement.
            </p>
        </div>
        
        <!-- Portfolio Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php for ($i = 1; $i <= 6; $i++): ?>
            <div class="group relative bg-dark-800 rounded-2xl overflow-hidden border border-dark-700 hover:border-gold-500/50 transition-all duration-500">
                <!-- Image -->
                <div class="aspect-video bg-gradient-to-br from-gold-500/20 to-gold-600/10 relative overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-image text-5xl text-gold-500/30"></i>
                    </div>
                    <div class="absolute inset-0 bg-dark-950/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <a href="<?= SITE_URL ?>/portfolio" class="w-12 h-12 bg-gold-500 rounded-full flex items-center justify-center text-dark-950 transform scale-0 group-hover:scale-100 transition-transform duration-300">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <span class="text-xs text-gold-500 uppercase tracking-wider">Projet <?= $i ?></span>
                    <h3 class="text-lg font-semibold text-white mt-2 mb-2 group-hover:text-gold-500 transition-colors">
                        Réalisation Premium
                    </h3>
                    <p class="text-dark-400 text-sm">
                        Description du projet et des résultats obtenus.
                    </p>
                </div>
            </div>
            <?php endfor; ?>
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-12">
            <a href="<?= SITE_URL ?>/portfolio" class="inline-flex items-center px-6 py-3 border border-gold-500/30 text-gold-500 rounded-lg hover:bg-gold-500/10 transition-all duration-300">
                Voir tout le portfolio <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
                Témoignages
            </span>
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
                Ce que disent nos <span class="text-gold-gradient">clients</span>
            </h2>
        </div>
        
        <!-- Testimonials Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $testimonials = [
                ['name' => 'Marie Dupont', 'role' => 'CEO, TechCorp', 'text' => 'Une équipe exceptionnelle qui a su transformer notre vision en réalité. Résultats impressionnants!'],
                ['name' => 'Pierre Martin', 'role' => 'Directeur, Innovate SA', 'text' => 'Professionnalisme et créativité au rendez-vous. Je recommande vivement leurs services.'],
                ['name' => 'Sophie Bernard', 'role' => 'Fondatrice, StartupXYZ', 'text' => 'Partenaire de confiance depuis 5 ans. Toujours à l\'écoute et force de proposition.']
            ];
            foreach ($testimonials as $review):
            ?>
            <div class="bg-dark-800/50 border border-dark-700 rounded-2xl p-8 relative">
                <!-- Quote Icon -->
                <div class="absolute top-6 right-6 text-gold-500/20">
                    <i class="fas fa-quote-right text-4xl"></i>
                </div>
                
                <!-- Stars -->
                <div class="flex space-x-1 mb-4">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <i class="fas fa-star text-gold-500"></i>
                    <?php endfor; ?>
                </div>
                
                <!-- Text -->
                <p class="text-dark-300 leading-relaxed mb-6">
                    "<?= $review['text'] ?>"
                </p>
                
                <!-- Author -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-gold-500 to-gold-600 rounded-full flex items-center justify-center text-dark-950 font-bold">
                        <?= strtoupper(substr($review['name'], 0, 1)) ?>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold"><?= $review['name'] ?></h4>
                        <p class="text-dark-400 text-sm"><?= $review['role'] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- CTA -->
        <div class="text-center mt-12">
            <a href="<?= SITE_URL ?>/avis" class="inline-flex items-center px-6 py-3 border border-gold-500/30 text-gold-500 rounded-lg hover:bg-gold-500/10 transition-all duration-300">
                Voir tous les avis <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-gold-600/20 to-gold-500/10"></div>
    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23C9A227\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
            Prêt à transformer votre <span class="text-gold-gradient">vision</span> en réalité ?
        </h2>
        <p class="text-xl text-dark-300 mb-10">
            Contactez-nous dès aujourd'hui pour discuter de votre projet et découvrir comment nous pouvons vous accompagner vers le succès.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= SITE_URL ?>/contact" class="btn-luxury px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                <i class="fas fa-envelope mr-2"></i> Nous contacter
            </a>
            <a href="tel:<?= SITE_PHONE ?>" class="px-8 py-4 bg-white/10 text-white font-medium rounded-lg hover:bg-white/20 transition-all duration-300">
                <i class="fas fa-phone mr-2"></i> <?= SITE_PHONE ?>
            </a>
        </div>
    </div>
</section>
