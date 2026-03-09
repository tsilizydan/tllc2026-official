<?php
/**
 * TSILIZY LLC — Route Definitions
 */

use Core\Router;

// ============================================
// PUBLIC ROUTES
// ============================================

// Home
Router::get('/', 'HomeController@index');
Router::get('/accueil', 'HomeController@index');

// Pages dynamiques
Router::get('/page/{slug}', 'PageController@show');

// Entreprise
Router::get('/a-propos', 'PageController@about');
Router::get('/contact', 'ContactController@index');
Router::post('/contact', 'ContactController@store');

// Services
Router::get('/services', 'ServiceController@index');
Router::get('/services/{slug}', 'ServiceController@show');

// Produits
Router::get('/produits', 'ProductController@index');
Router::get('/produits/{slug}', 'ProductController@show');

// Portfolio
Router::get('/portfolio', 'PortfolioController@index');
Router::get('/portfolio/{slug}', 'PortfolioController@show');

// Partenaires
Router::get('/partenaires', 'PartnerController@index');

// Événements
Router::get('/evenements', 'EventController@index');
Router::get('/evenements/{slug}', 'EventController@show');

// Annonces
Router::get('/annonces', 'AnnouncementController@index');
Router::get('/annonces/{slug}', 'AnnouncementController@show');

// Carrières
Router::get('/carrieres', 'CareerController@index');
Router::get('/carrieres/{slug}', 'CareerController@show');
Router::post('/carrieres/{id}/postuler', 'CareerController@apply');

// Avis
Router::get('/avis', 'ReviewController@index');
Router::post('/avis', 'ReviewController@store');

// Newsletter
Router::post('/newsletter/inscription', 'NewsletterController@subscribe');
Router::get('/newsletter/desinscription/{token}', 'NewsletterController@unsubscribe');

// Assistance
Router::get('/assistance', 'TicketController@create');
Router::post('/assistance', 'TicketController@store');

// Sondages
Router::get('/sondages/{id}', 'SurveyController@show');
Router::post('/sondages/{id}', 'SurveyController@respond');

// Recherche
Router::get('/recherche', 'SearchController@index');

// ============================================
// AUTHENTICATION ROUTES
// ============================================

Router::get('/connexion', 'AuthController@loginForm');
Router::post('/connexion', 'AuthController@login');
Router::get('/inscription', 'AuthController@registerForm');
Router::post('/inscription', 'AuthController@register');
Router::post('/deconnexion', 'AuthController@logout');
Router::get('/mot-de-passe-oublie', 'AuthController@forgotPasswordForm');
Router::post('/mot-de-passe-oublie', 'AuthController@forgotPassword');
Router::get('/reinitialiser-mot-de-passe/{token}', 'AuthController@resetPasswordForm');
Router::post('/reinitialiser-mot-de-passe', 'AuthController@resetPassword');

// ============================================
// USER DASHBOARD ROUTES
// ============================================

Router::get('/mon-compte', 'UserController@dashboard');
Router::get('/mon-compte/profil', 'UserController@profile');
Router::post('/mon-compte/profil/modifier', 'UserController@updateProfile');
Router::post('/mon-compte/profil/mot-de-passe', 'UserController@changePassword');
Router::get('/mon-compte/factures', 'UserController@invoices');
Router::get('/mon-compte/messages', 'UserController@messages');
Router::get('/mon-compte/mes-avis', 'UserController@reviews');
Router::get('/mon-compte/mes-candidatures', 'UserController@applications');
Router::get('/mon-compte/mes-tickets', 'UserController@tickets');

// ============================================
// ADMIN ROUTES
// ============================================

// Dashboard
Router::get('/admin', 'Admin\DashboardController@index');
Router::get('/admin/tableau-de-bord', 'Admin\DashboardController@index');

// Users
Router::get('/admin/utilisateurs', 'Admin\UserController@index');
Router::get('/admin/utilisateurs/creer', 'Admin\UserController@create');
Router::post('/admin/utilisateurs', 'Admin\UserController@store');
Router::get('/admin/utilisateurs/{id}', 'Admin\UserController@show');
Router::get('/admin/utilisateurs/{id}/modifier', 'Admin\UserController@edit');
Router::post('/admin/utilisateurs/{id}', 'Admin\UserController@update');
Router::post('/admin/utilisateurs/{id}/supprimer', 'Admin\UserController@destroy');
Router::post('/admin/utilisateurs/{id}/suspendre', 'Admin\UserController@suspend');
Router::post('/admin/utilisateurs/{id}/bannir', 'Admin\UserController@ban');

// Roles
Router::get('/admin/roles', 'Admin\RoleController@index');
Router::get('/admin/roles/creer', 'Admin\RoleController@create');
Router::post('/admin/roles', 'Admin\RoleController@store');
Router::get('/admin/roles/{id}/modifier', 'Admin\RoleController@edit');
Router::post('/admin/roles/{id}', 'Admin\RoleController@update');
Router::post('/admin/roles/{id}/supprimer', 'Admin\RoleController@destroy');

// Pages
Router::get('/admin/pages', 'Admin\PageController@index');
Router::get('/admin/pages/creer', 'Admin\PageController@create');
Router::post('/admin/pages', 'Admin\PageController@store');
Router::get('/admin/pages/{id}/modifier', 'Admin\PageController@edit');
Router::post('/admin/pages/{id}', 'Admin\PageController@update');
Router::post('/admin/pages/{id}/supprimer', 'Admin\PageController@destroy');

// Media
Router::get('/admin/medias', 'Admin\MediaController@index');
Router::post('/admin/medias/telecharger', 'Admin\MediaController@upload');
Router::post('/admin/medias/{id}/supprimer', 'Admin\MediaController@destroy');

// Announcements
Router::get('/admin/annonces', 'Admin\AnnouncementController@index');
Router::get('/admin/annonces/creer', 'Admin\AnnouncementController@create');
Router::post('/admin/annonces', 'Admin\AnnouncementController@store');
Router::get('/admin/annonces/{id}/modifier', 'Admin\AnnouncementController@edit');
Router::post('/admin/annonces/{id}', 'Admin\AnnouncementController@update');
Router::post('/admin/annonces/{id}/supprimer', 'Admin\AnnouncementController@destroy');

// Clients
Router::get('/admin/clients', 'Admin\ClientController@index');
Router::get('/admin/clients/creer', 'Admin\ClientController@create');
Router::post('/admin/clients', 'Admin\ClientController@store');
Router::get('/admin/clients/{id}', 'Admin\ClientController@show');
Router::get('/admin/clients/{id}/modifier', 'Admin\ClientController@edit');
Router::post('/admin/clients/{id}', 'Admin\ClientController@update');
Router::post('/admin/clients/{id}/supprimer', 'Admin\ClientController@destroy');

// Services
Router::get('/admin/services', 'Admin\ServiceController@index');
Router::get('/admin/services/creer', 'Admin\ServiceController@create');
Router::post('/admin/services', 'Admin\ServiceController@store');
Router::get('/admin/services/{id}/modifier', 'Admin\ServiceController@edit');
Router::post('/admin/services/{id}', 'Admin\ServiceController@update');
Router::post('/admin/services/{id}/supprimer', 'Admin\ServiceController@destroy');

// Products
Router::get('/admin/produits', 'Admin\ProductController@index');
Router::get('/admin/produits/creer', 'Admin\ProductController@create');
Router::post('/admin/produits', 'Admin\ProductController@store');
Router::get('/admin/produits/{id}/modifier', 'Admin\ProductController@edit');
Router::post('/admin/produits/{id}', 'Admin\ProductController@update');
Router::post('/admin/produits/{id}/supprimer', 'Admin\ProductController@destroy');

// Portfolio
Router::get('/admin/portfolio', 'Admin\PortfolioController@index');
Router::get('/admin/portfolio/creer', 'Admin\PortfolioController@create');
Router::post('/admin/portfolio', 'Admin\PortfolioController@store');
Router::get('/admin/portfolio/{id}/modifier', 'Admin\PortfolioController@edit');
Router::post('/admin/portfolio/{id}', 'Admin\PortfolioController@update');
Router::post('/admin/portfolio/{id}/supprimer', 'Admin\PortfolioController@destroy');

// Projects
Router::get('/admin/projets', 'Admin\ProjectController@index');
Router::get('/admin/projets/creer', 'Admin\ProjectController@create');
Router::post('/admin/projets', 'Admin\ProjectController@store');
Router::get('/admin/projets/{id}/modifier', 'Admin\ProjectController@edit');
Router::post('/admin/projets/{id}', 'Admin\ProjectController@update');
Router::post('/admin/projets/{id}/supprimer', 'Admin\ProjectController@destroy');

// Partners
Router::get('/admin/partenaires', 'Admin\PartnerController@index');
Router::get('/admin/partenaires/creer', 'Admin\PartnerController@create');
Router::post('/admin/partenaires', 'Admin\PartnerController@store');
Router::get('/admin/partenaires/{id}/modifier', 'Admin\PartnerController@edit');
Router::post('/admin/partenaires/{id}', 'Admin\PartnerController@update');
Router::post('/admin/partenaires/{id}/supprimer', 'Admin\PartnerController@destroy');

// Events
Router::get('/admin/evenements', 'Admin\EventController@index');
Router::get('/admin/evenements/creer', 'Admin\EventController@create');
Router::post('/admin/evenements', 'Admin\EventController@store');
Router::get('/admin/evenements/{id}/modifier', 'Admin\EventController@edit');
Router::post('/admin/evenements/{id}', 'Admin\EventController@update');
Router::post('/admin/evenements/{id}/supprimer', 'Admin\EventController@destroy');

// Careers
Router::get('/admin/carrieres', 'Admin\CareerController@index');
Router::get('/admin/carrieres/creer', 'Admin\CareerController@create');
Router::post('/admin/carrieres', 'Admin\CareerController@store');
Router::get('/admin/carrieres/{id}/modifier', 'Admin\CareerController@edit');
Router::post('/admin/carrieres/{id}', 'Admin\CareerController@update');
Router::post('/admin/carrieres/{id}/supprimer', 'Admin\CareerController@destroy');
Router::get('/admin/candidatures', 'Admin\CareerController@applications');
Router::get('/admin/candidatures/{id}', 'Admin\CareerController@showApplication');
Router::post('/admin/candidatures/{id}/statut', 'Admin\CareerController@updateApplicationStatus');

// Contracts
Router::get('/admin/contrats', 'Admin\ContractController@index');
Router::get('/admin/contrats/creer', 'Admin\ContractController@create');
Router::post('/admin/contrats', 'Admin\ContractController@store');
Router::get('/admin/contrats/{id}', 'Admin\ContractController@show');
Router::get('/admin/contrats/{id}/modifier', 'Admin\ContractController@edit');
Router::post('/admin/contrats/{id}', 'Admin\ContractController@update');
Router::post('/admin/contrats/{id}/supprimer', 'Admin\ContractController@destroy');
Router::get('/admin/contrats/{id}/imprimer', 'Admin\ContractController@print');

// Invoices
Router::get('/admin/factures', 'Admin\InvoiceController@index');
Router::get('/admin/factures/creer', 'Admin\InvoiceController@create');
Router::post('/admin/factures', 'Admin\InvoiceController@store');
Router::get('/admin/factures/{id}', 'Admin\InvoiceController@show');
Router::get('/admin/factures/{id}/modifier', 'Admin\InvoiceController@edit');
Router::post('/admin/factures/{id}', 'Admin\InvoiceController@update');
Router::post('/admin/factures/{id}/supprimer', 'Admin\InvoiceController@destroy');
Router::get('/admin/factures/{id}/imprimer', 'Admin\InvoiceController@print');

// Agenda
Router::get('/admin/agenda', 'Admin\AgendaController@index');
Router::get('/admin/agenda/creer', 'Admin\AgendaController@create');
Router::post('/admin/agenda', 'Admin\AgendaController@store');
Router::get('/admin/agenda/{id}/modifier', 'Admin\AgendaController@edit');
Router::post('/admin/agenda/{id}', 'Admin\AgendaController@update');
Router::post('/admin/agenda/{id}/supprimer', 'Admin\AgendaController@destroy');
Router::get('/admin/agenda/imprimer', 'Admin\AgendaController@print');

// Contacts
Router::get('/admin/contacts', 'Admin\ContactController@index');
Router::get('/admin/contacts/{id}', 'Admin\ContactController@show');
Router::post('/admin/contacts/{id}/repondre', 'Admin\ContactController@reply');
Router::post('/admin/contacts/{id}/supprimer', 'Admin\ContactController@destroy');
Router::get('/admin/contacts/exporter', 'Admin\ContactController@export');

// Tickets
Router::get('/admin/tickets', 'Admin\TicketController@index');
Router::get('/admin/tickets/{id}', 'Admin\TicketController@show');
Router::post('/admin/tickets/{id}/repondre', 'Admin\TicketController@reply');
Router::post('/admin/tickets/{id}/statut', 'Admin\TicketController@updateStatus');
Router::post('/admin/tickets/{id}/supprimer', 'Admin\TicketController@destroy');

// Reviews
Router::get('/admin/avis', 'Admin\ReviewController@index');
Router::post('/admin/avis/{id}/approuver', 'Admin\ReviewController@approve');
Router::post('/admin/avis/{id}/rejeter', 'Admin\ReviewController@reject');
Router::post('/admin/avis/{id}/vedette', 'Admin\ReviewController@toggleFeatured');
Router::post('/admin/avis/{id}/supprimer', 'Admin\ReviewController@destroy');

// Newsletter
Router::get('/admin/newsletter', 'Admin\NewsletterController@index');
Router::get('/admin/newsletter/abonnes', 'Admin\NewsletterController@subscribers');
Router::get('/admin/newsletter/campagnes', 'Admin\NewsletterController@campaigns');
Router::get('/admin/newsletter/campagnes/creer', 'Admin\NewsletterController@createCampaign');
Router::post('/admin/newsletter/campagnes', 'Admin\NewsletterController@storeCampaign');
Router::post('/admin/newsletter/campagnes/{id}/envoyer', 'Admin\NewsletterController@sendCampaign');
Router::post('/admin/newsletter/abonnes/{id}/supprimer', 'Admin\NewsletterController@removeSubscriber');
Router::get('/admin/newsletter/exporter', 'Admin\NewsletterController@export');

// Surveys
Router::get('/admin/sondages', 'Admin\SurveyController@index');
Router::get('/admin/sondages/creer', 'Admin\SurveyController@create');
Router::post('/admin/sondages', 'Admin\SurveyController@store');
Router::get('/admin/sondages/{id}', 'Admin\SurveyController@show');
Router::get('/admin/sondages/{id}/modifier', 'Admin\SurveyController@edit');
Router::post('/admin/sondages/{id}', 'Admin\SurveyController@update');
Router::post('/admin/sondages/{id}/supprimer', 'Admin\SurveyController@destroy');
Router::get('/admin/sondages/{id}/resultats', 'Admin\SurveyController@results');

// Analytics
Router::get('/admin/analytiques', 'Admin\AnalyticsController@index');
Router::get('/admin/analytiques/trafic', 'Admin\AnalyticsController@traffic');
Router::get('/admin/analytiques/contenu', 'Admin\AnalyticsController@content');
Router::get('/admin/analytiques/engagement', 'Admin\AnalyticsController@engagement');
Router::get('/admin/analytiques/conversions', 'Admin\AnalyticsController@conversions');
Router::get('/admin/analytiques/exporter', 'Admin\AnalyticsController@export');

// Settings
Router::get('/admin/parametres', 'Admin\SettingController@index');
Router::post('/admin/parametres', 'Admin\SettingController@update');
Router::get('/admin/parametres/seo', 'Admin\SettingController@seo');
Router::post('/admin/parametres/seo', 'Admin\SettingController@updateSeo');
Router::get('/admin/parametres/fonctionnalites', 'Admin\SettingController@features');
Router::post('/admin/parametres/fonctionnalites', 'Admin\SettingController@updateFeatures');
Router::get('/admin/parametres/maintenance', 'Admin\SettingController@maintenance');
Router::post('/admin/parametres/maintenance', 'Admin\SettingController@toggleMaintenance');

// Audit Logs
Router::get('/admin/journaux', 'Admin\\LogController@index');
Router::get('/admin/journaux/exporter', 'Admin\\LogController@export');
Router::post('/admin/journaux/nettoyer', 'Admin\\LogController@clear');

// Roles & Permissions
Router::get('/admin/roles', 'Admin\\RoleController@index');
Router::get('/admin/roles/creer', 'Admin\\RoleController@create');
Router::post('/admin/roles', 'Admin\\RoleController@store');
Router::get('/admin/roles/{id}/modifier', 'Admin\\RoleController@edit');
Router::post('/admin/roles/{id}', 'Admin\\RoleController@update');
Router::get('/admin/roles/{id}/utilisateurs', 'Admin\\RoleController@users');
Router::post('/admin/roles/{id}/supprimer', 'Admin\\RoleController@destroy');

// Notifications
Router::get('/admin/notifications', 'Admin\\NotificationController@index');
Router::get('/admin/notifications/creer', 'Admin\\NotificationController@create');
Router::post('/admin/notifications', 'Admin\\NotificationController@store');
Router::get('/admin/notifications/fetch', 'Admin\\NotificationController@fetch');
Router::post('/admin/notifications/lues', 'Admin\\NotificationController@markAllRead');
Router::post('/admin/notifications/nettoyer', 'Admin\\NotificationController@clearOld');
Router::post('/admin/notifications/{id}/lu', 'Admin\\NotificationController@markRead');
Router::post('/admin/notifications/{id}/supprimer', 'Admin\\NotificationController@destroy');

// Messages
Router::get('/admin/messages', 'Admin\\MessageController@index');
Router::get('/admin/messages/composer', 'Admin\\MessageController@compose');
Router::post('/admin/messages/envoyer', 'Admin\\MessageController@send');
Router::get('/admin/messages/{id}', 'Admin\\MessageController@show');
Router::post('/admin/messages/{id}/repondre', 'Admin\\MessageController@reply');
Router::post('/admin/messages/{id}/supprimer', 'Admin\\MessageController@destroy');

// Pages Admin
Router::get('/admin/pages', 'Admin\\PageController@index');
Router::get('/admin/pages/creer', 'Admin\\PageController@create');
Router::post('/admin/pages', 'Admin\\PageController@store');
Router::get('/admin/pages/{id}/modifier', 'Admin\\PageController@edit');
Router::post('/admin/pages/{id}', 'Admin\\PageController@update');
Router::post('/admin/pages/{id}/supprimer', 'Admin\\PageController@destroy');

// Media Admin
Router::get('/admin/medias', 'Admin\\MediaController@index');
Router::post('/admin/medias/telecharger', 'Admin\\MediaController@upload');
Router::post('/admin/medias/{id}/supprimer', 'Admin\\MediaController@destroy');

// Announcements Admin
Router::get('/admin/annonces', 'Admin\\AnnouncementController@index');
Router::get('/admin/annonces/creer', 'Admin\\AnnouncementController@create');
Router::post('/admin/annonces', 'Admin\\AnnouncementController@store');
Router::get('/admin/annonces/{id}/modifier', 'Admin\\AnnouncementController@edit');
Router::post('/admin/annonces/{id}', 'Admin\\AnnouncementController@update');
Router::post('/admin/annonces/{id}/supprimer', 'Admin\\AnnouncementController@destroy');
