<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta -->
    <title><?= $seo_title ?? $page_title ?? SITE_NAME ?></title>
    <meta name="description" content="<?= $seo_description ?? 'Découvrez TSILIZY LLC, votre partenaire de confiance pour des solutions innovantes et un service d\'excellence.' ?>">
    <meta name="keywords" content="<?= $seo_keywords ?? 'tsilizy, entreprise, innovation, excellence, services' ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= $seo_title ?? $page_title ?? SITE_NAME ?>">
    <meta property="og:description" content="<?= $seo_description ?? '' ?>">
    <meta property="og:image" content="<?= $og_image ?? SITE_URL . '/assets/images/og-image.jpg' ?>">
    <meta property="og:url" content="<?= Core\View::currentUrl() ?>">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= SITE_URL ?>/assets/images/favicon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#FFFBEB',
                            100: '#FEF3C7',
                            200: '#FDE68A',
                            300: '#FCD34D',
                            400: '#FBBF24',
                            500: '#C9A227',
                            600: '#B8860B',
                            700: '#92700C',
                            800: '#78570D',
                            900: '#5C4409'
                        },
                        dark: {
                            50: '#F8FAFC',
                            100: '#F1F5F9',
                            200: '#E2E8F0',
                            300: '#CBD5E1',
                            400: '#94A3B8',
                            500: '#64748B',
                            600: '#475569',
                            700: '#334155',
                            800: '#1E293B',
                            900: '#0F172A',
                            950: '#020617'
                        }
                    },
                    fontFamily: {
                        'serif': ['Cormorant Garamond', 'Georgia', 'serif'],
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- CSRF Token -->
    <?= Core\CSRF::meta() ?>
    
    <!-- Custom Styles -->
    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1E293B;
        }
        ::-webkit-scrollbar-thumb {
            background: #C9A227;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #B8860B;
        }
        
        /* Gold gradient text */
        .text-gold-gradient {
            background: linear-gradient(135deg, #C9A227 0%, #FDE68A 50%, #C9A227 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Gold border animation */
        .gold-border-animated {
            position: relative;
        }
        .gold-border-animated::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #C9A227, #FDE68A);
            transition: width 0.3s ease;
        }
        .gold-border-animated:hover::after {
            width: 100%;
        }
        
        /* Glass effect */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Luxury button */
        .btn-luxury {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-luxury::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-luxury:hover::before {
            left: 100%;
        }
        
        /* Page transitions */
        .page-enter {
            animation: fadeInUp 0.6s ease forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Loading overlay */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #0F172A;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        .loader-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
    
    <?php if (isset($extra_css)): ?>
    <?= $extra_css ?>
    <?php endif; ?>
</head>
<body class="bg-dark-950 text-white font-sans antialiased" x-data="{ mobileMenu: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 50">
    
    <!-- Loading Overlay -->
    <div class="loader-overlay" id="loader">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 border-4 border-gold-500 border-t-transparent rounded-full animate-spin"></div>
            <p class="mt-4 text-gold-500 font-serif text-lg">Chargement...</p>
        </div>
    </div>
    
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" :class="scrolled ? 'bg-dark-950/95 backdrop-blur-md shadow-lg shadow-black/20' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="<?= SITE_URL ?>" class="flex items-center space-x-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                        <span class="text-dark-950 font-serif font-bold text-xl">T</span>
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-xl font-serif font-bold text-gold-gradient">TSILIZY</span>
                        <span class="text-xs block text-dark-400 tracking-widest uppercase">LLC</span>
                    </div>
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center space-x-8">
                    <a href="<?= SITE_URL ?>" class="text-sm font-medium text-white hover:text-gold-500 transition-colors gold-border-animated py-2">Accueil</a>
                    <a href="<?= SITE_URL ?>/a-propos" class="text-sm font-medium text-dark-300 hover:text-gold-500 transition-colors gold-border-animated py-2">À Propos</a>
                    <a href="<?= SITE_URL ?>/services" class="text-sm font-medium text-dark-300 hover:text-gold-500 transition-colors gold-border-animated py-2">Services</a>
                    <a href="<?= SITE_URL ?>/produits" class="text-sm font-medium text-dark-300 hover:text-gold-500 transition-colors gold-border-animated py-2">Produits</a>
                    <a href="<?= SITE_URL ?>/portfolio" class="text-sm font-medium text-dark-300 hover:text-gold-500 transition-colors gold-border-animated py-2">Portfolio</a>
                    <a href="<?= SITE_URL ?>/carrieres" class="text-sm font-medium text-dark-300 hover:text-gold-500 transition-colors gold-border-animated py-2">Carrières</a>
                    <a href="<?= SITE_URL ?>/contact" class="text-sm font-medium text-dark-300 hover:text-gold-500 transition-colors gold-border-animated py-2">Contact</a>
                </nav>
                
                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <?php if (Core\Auth::check()): ?>
                        <div class="hidden md:flex items-center space-x-4">
                            <?php if (Core\Auth::isAdmin()): ?>
                            <a href="<?= SITE_URL ?>/admin" class="text-sm text-dark-300 hover:text-gold-500 transition-colors">
                                <i class="fas fa-cog mr-1"></i> Admin
                            </a>
                            <?php endif; ?>
                            <a href="<?= SITE_URL ?>/mon-compte" class="text-sm text-dark-300 hover:text-gold-500 transition-colors">
                                <i class="fas fa-user mr-1"></i> <?= Core\View::e($current_user['name'] ?? 'Mon Compte') ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?= SITE_URL ?>/connexion" class="hidden md:inline-flex items-center px-5 py-2.5 text-sm font-medium text-gold-500 border border-gold-500/30 rounded-lg hover:bg-gold-500/10 transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i> Connexion
                        </a>
                    <?php endif; ?>
                    
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 text-white hover:text-gold-500 transition-colors">
                        <i class="fas fa-bars text-xl" x-show="!mobileMenu"></i>
                        <i class="fas fa-times text-xl" x-show="mobileMenu" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="lg:hidden bg-dark-900/95 backdrop-blur-md border-t border-dark-800" x-cloak>
            <div class="max-w-7xl mx-auto px-4 py-6 space-y-4">
                <a href="<?= SITE_URL ?>" class="block text-white hover:text-gold-500 py-2">Accueil</a>
                <a href="<?= SITE_URL ?>/a-propos" class="block text-dark-300 hover:text-gold-500 py-2">À Propos</a>
                <a href="<?= SITE_URL ?>/services" class="block text-dark-300 hover:text-gold-500 py-2">Services</a>
                <a href="<?= SITE_URL ?>/produits" class="block text-dark-300 hover:text-gold-500 py-2">Produits</a>
                <a href="<?= SITE_URL ?>/portfolio" class="block text-dark-300 hover:text-gold-500 py-2">Portfolio</a>
                <a href="<?= SITE_URL ?>/carrieres" class="block text-dark-300 hover:text-gold-500 py-2">Carrières</a>
                <a href="<?= SITE_URL ?>/contact" class="block text-dark-300 hover:text-gold-500 py-2">Contact</a>
                <div class="pt-4 border-t border-dark-800">
                    <?php if (Core\Auth::check()): ?>
                        <a href="<?= SITE_URL ?>/mon-compte" class="block text-gold-500 py-2">
                            <i class="fas fa-user mr-2"></i> Mon Compte
                        </a>
                    <?php else: ?>
                        <a href="<?= SITE_URL ?>/connexion" class="block text-gold-500 py-2">
                            <i class="fas fa-sign-in-alt mr-2"></i> Connexion
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Flash Messages -->
    <?php $flash = Core\Session::getAllFlash(); ?>
    <?php if (!empty($flash)): ?>
    <div class="fixed top-24 right-4 z-50 space-y-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
        <?php if (isset($flash['success'])): ?>
        <div class="flex items-center px-4 py-3 bg-green-500/20 border border-green-500/30 text-green-400 rounded-lg animate__animated animate__fadeInRight">
            <i class="fas fa-check-circle mr-3"></i>
            <span><?= Core\View::e($flash['success']) ?></span>
            <button @click="show = false" class="ml-4 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <?php endif; ?>
        <?php if (isset($flash['error'])): ?>
        <div class="flex items-center px-4 py-3 bg-red-500/20 border border-red-500/30 text-red-400 rounded-lg animate__animated animate__fadeInRight">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <span><?= Core\View::e($flash['error']) ?></span>
            <button @click="show = false" class="ml-4 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <?php endif; ?>
        <?php if (isset($flash['warning'])): ?>
        <div class="flex items-center px-4 py-3 bg-yellow-500/20 border border-yellow-500/30 text-yellow-400 rounded-lg animate__animated animate__fadeInRight">
            <i class="fas fa-exclamation-triangle mr-3"></i>
            <span><?= Core\View::e($flash['warning']) ?></span>
            <button @click="show = false" class="ml-4 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="page-enter">
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark-900 border-t border-dark-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- Brand -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold-500 to-gold-700 rounded-lg flex items-center justify-center">
                            <span class="text-dark-950 font-serif font-bold text-xl">T</span>
                        </div>
                        <div>
                            <span class="text-xl font-serif font-bold text-gold-gradient">TSILIZY</span>
                            <span class="text-xs block text-dark-400 tracking-widest uppercase">LLC</span>
                        </div>
                    </div>
                    <p class="text-dark-400 text-sm leading-relaxed">
                        Votre partenaire de confiance pour des solutions innovantes et un service d'excellence.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-dark-800 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-dark-800 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-dark-800 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-dark-800 rounded-lg flex items-center justify-center text-dark-400 hover:bg-gold-500 hover:text-dark-950 transition-all duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold mb-6">Navigation</h3>
                    <ul class="space-y-3">
                        <li><a href="<?= SITE_URL ?>" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">Accueil</a></li>
                        <li><a href="<?= SITE_URL ?>/a-propos" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">À Propos</a></li>
                        <li><a href="<?= SITE_URL ?>/services" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">Services</a></li>
                        <li><a href="<?= SITE_URL ?>/portfolio" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">Portfolio</a></li>
                        <li><a href="<?= SITE_URL ?>/contact" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div>
                    <h3 class="text-white font-semibold mb-6">Services</h3>
                    <ul class="space-y-3">
                        <li><a href="<?= SITE_URL ?>/services" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">Conseil</a></li>
                        <li><a href="<?= SITE_URL ?>/services" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">Développement</a></li>
                        <li><a href="<?= SITE_URL ?>/services" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">Design</a></li>
                        <li><a href="<?= SITE_URL ?>/services" class="text-dark-400 hover:text-gold-500 transition-colors text-sm">Marketing</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h3 class="text-white font-semibold mb-6">Contact</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-gold-500 mt-1"></i>
                            <span class="text-dark-400 text-sm"><?= SITE_ADDRESS ?></span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone text-gold-500"></i>
                            <a href="tel:<?= SITE_PHONE ?>" class="text-dark-400 hover:text-gold-500 text-sm"><?= SITE_PHONE ?></a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-gold-500"></i>
                            <a href="mailto:<?= SITE_EMAIL ?>" class="text-dark-400 hover:text-gold-500 text-sm"><?= SITE_EMAIL ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Newsletter -->
            <div class="mt-12 pt-12 border-t border-dark-800">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <div>
                        <h3 class="text-white font-semibold mb-2">Inscrivez-vous à notre newsletter</h3>
                        <p class="text-dark-400 text-sm">Recevez nos dernières actualités et offres exclusives.</p>
                    </div>
                    <form action="<?= SITE_URL ?>/newsletter/inscription" method="POST" class="flex w-full lg:w-auto">
                        <?= Core\CSRF::field() ?>
                        <input type="email" name="email" placeholder="Votre email" required class="flex-1 lg:w-64 px-4 py-3 bg-dark-800 border border-dark-700 rounded-l-lg text-white placeholder-dark-500 focus:outline-none focus:border-gold-500 transition-colors">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-500 to-gold-600 text-dark-950 font-semibold rounded-r-lg hover:from-gold-400 hover:to-gold-500 transition-all duration-300">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Bottom -->
            <div class="mt-12 pt-8 border-t border-dark-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-dark-500 text-sm">
                    © <?= date('Y') ?> <?= SITE_NAME ?>. Tous droits réservés.
                </p>
                <div class="flex items-center space-x-6">
                    <a href="<?= SITE_URL ?>/page/mentions-legales" class="text-dark-500 hover:text-gold-500 text-sm transition-colors">Mentions Légales</a>
                    <a href="<?= SITE_URL ?>/page/politique-confidentialite" class="text-dark-500 hover:text-gold-500 text-sm transition-colors">Confidentialité</a>
                    <a href="<?= SITE_URL ?>/page/cgv" class="text-dark-500 hover:text-gold-500 text-sm transition-colors">CGV</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrolled" x-transition class="fixed bottom-8 right-8 w-12 h-12 bg-gold-500 text-dark-950 rounded-full shadow-lg flex items-center justify-center hover:bg-gold-400 transition-all duration-300 z-40">
        <i class="fas fa-chevron-up"></i>
    </button>
    
    <!-- Scripts -->
    <script>
        // Hide loader after page load
        window.addEventListener('load', function() {
            document.getElementById('loader').classList.add('hidden');
        });
        
        // Lazy loading for images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                });
            });
            images.forEach(img => imageObserver.observe(img));
        });
    </script>
    
    <?php if (isset($extra_js)): ?>
    <?= $extra_js ?>
    <?php endif; ?>
</body>
</html>
