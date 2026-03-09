<?php
/**
 * TSILIZY LLC — About Page
 */

Core\View::layout('public', [
    'page_title' => 'À Propos',
    'seo_title' => 'À Propos de Nous | ' . SITE_NAME,
    'seo_description' => 'Découvrez TSILIZY LLC, votre partenaire de confiance pour l\'excellence et l\'innovation depuis plus de 10 ans.'
]);
?>

<!-- Hero Section -->
<section class="relative pt-32 pb-16 bg-gradient-to-b from-dark-900 to-dark-950">
    <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23C9A227\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
            À Propos
        </span>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">
            Notre <span class="text-gold-gradient">histoire</span>
        </h1>
        <p class="text-xl text-dark-400 max-w-2xl mx-auto">
            Depuis plus de 10 ans, nous accompagnons les entreprises vers le succès avec passion, expertise et dévouement.
        </p>
    </div>
</section>

<!-- Story Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Image Placeholder -->
            <div class="relative">
                <div class="bg-gradient-to-br from-dark-800 to-dark-900 rounded-2xl p-8 border border-dark-700 aspect-square flex items-center justify-center">
                    <div class="text-center text-dark-500">
                        <i class="fas fa-building text-6xl mb-4"></i>
                        <p>Image de l'entreprise</p>
                    </div>
                </div>
                <!-- Decorative -->
                <div class="absolute -bottom-4 -right-4 w-full h-full border-2 border-gold-500/20 rounded-2xl -z-10"></div>
            </div>
            
            <!-- Content -->
            <div>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-6">
                    Une vision née de la <span class="text-gold-gradient">passion</span>
                </h2>
                <div class="space-y-4 text-dark-400 leading-relaxed">
                    <p>
                        Fondée en 2014, TSILIZY LLC est née d'une vision simple mais ambitieuse : créer une entreprise qui place l'excellence et l'innovation au cœur de chaque projet.
                    </p>
                    <p>
                        Au fil des années, nous avons développé une expertise reconnue dans notre domaine, en accompagnant des entreprises de toutes tailles vers le succès. Notre approche personnalisée et notre engagement envers la qualité nous ont permis de bâtir des relations durables avec nos clients.
                    </p>
                    <p>
                        Aujourd'hui, nous sommes fiers de compter parmi nos clients des entreprises de renom qui nous font confiance pour leurs projets les plus stratégiques.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-dark-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
                Nos Valeurs
            </span>
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-6">
                Ce qui nous <span class="text-gold-gradient">définit</span>
            </h2>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php 
            $values = [
                ['icon' => 'fa-award', 'title' => 'Excellence', 'desc' => 'Nous visons l\'excellence dans chaque projet, sans compromis sur la qualité.'],
                ['icon' => 'fa-shield-alt', 'title' => 'Intégrité', 'desc' => 'La transparence et l\'honnêteté guident toutes nos actions.'],
                ['icon' => 'fa-lightbulb', 'title' => 'Innovation', 'desc' => 'Nous explorons constamment de nouvelles solutions créatives.'],
                ['icon' => 'fa-handshake', 'title' => 'Partenariat', 'desc' => 'Votre succès est notre succès, nous travaillons ensemble.']
            ];
            foreach ($values as $value): 
            ?>
            <div class="text-center group">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-gold-500/20 to-gold-600/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas <?= $value['icon'] ?> text-3xl text-gold-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3"><?= $value['title'] ?></h3>
                <p class="text-dark-400"><?= $value['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block px-4 py-2 bg-gold-500/10 border border-gold-500/20 rounded-full text-gold-500 text-sm font-medium mb-4">
                Notre Équipe
            </span>
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-6">
                Des experts <span class="text-gold-gradient">passionnés</span>
            </h2>
            <p class="text-dark-400 text-lg">
                Une équipe de professionnels dévoués, unis par la passion de l'excellence.
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php 
            $team = [
                ['name' => 'Jean Dupont', 'role' => 'Directeur Général', 'initial' => 'JD'],
                ['name' => 'Marie Martin', 'role' => 'Directrice Technique', 'initial' => 'MM'],
                ['name' => 'Pierre Bernard', 'role' => 'Directeur Commercial', 'initial' => 'PB'],
                ['name' => 'Sophie Leroy', 'role' => 'Directrice Créative', 'initial' => 'SL']
            ];
            foreach ($team as $member): 
            ?>
            <div class="group text-center">
                <div class="relative mb-6">
                    <div class="w-40 h-40 mx-auto bg-gradient-to-br from-dark-800 to-dark-900 rounded-2xl border border-dark-700 flex items-center justify-center overflow-hidden group-hover:border-gold-500/50 transition-all duration-300">
                        <span class="text-4xl font-bold text-gold-500"><?= $member['initial'] ?></span>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-white mb-1"><?= $member['name'] ?></h3>
                <p class="text-gold-500 text-sm"><?= $member['role'] ?></p>
                
                <!-- Social -->
                <div class="flex justify-center space-x-3 mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="#" class="w-8 h-8 bg-dark-700 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                        <i class="fab fa-linkedin-in text-sm"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-dark-700 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-dark-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-5xl font-serif font-bold text-gold-500 mb-2">10+</div>
                <div class="text-dark-400">Années d'expérience</div>
            </div>
            <div class="text-center">
                <div class="text-5xl font-serif font-bold text-gold-500 mb-2">500+</div>
                <div class="text-dark-400">Projets réalisés</div>
            </div>
            <div class="text-center">
                <div class="text-5xl font-serif font-bold text-gold-500 mb-2">50+</div>
                <div class="text-dark-400">Collaborateurs</div>
            </div>
            <div class="text-center">
                <div class="text-5xl font-serif font-bold text-gold-500 mb-2">99%</div>
                <div class="text-dark-400">Clients satisfaits</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-r from-gold-600/20 to-gold-500/10 rounded-2xl p-12 border border-gold-500/20">
            <h2 class="text-3xl font-serif font-bold text-white mb-4">
                Prêt à travailler ensemble ?
            </h2>
            <p class="text-dark-300 mb-8">
                Discutons de votre projet et voyons comment nous pouvons vous accompagner vers le succès.
            </p>
            <a href="<?= SITE_URL ?>/contact" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-lg hover:shadow-lg hover:shadow-gold-500/25 transition-all duration-300">
                <i class="fas fa-envelope mr-2"></i> Contactez-nous
            </a>
        </div>
    </div>
</section>
