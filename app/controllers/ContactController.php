<?php
/**
 * TSILIZY LLC — Contact Controller (Public)
 */

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Session;
use Core\Security;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display contact page
     */
    public function index(): void
    {
        $this->view('public/contact', [
            'page_title' => 'Contact',
            'seo_title' => 'Contactez-nous | ' . SITE_NAME,
            'seo_description' => 'Contactez TSILIZY LLC pour toute question ou demande de devis. Notre équipe est à votre disposition.'
        ]);
    }
    
    /**
     * Store contact message
     */
    public function store(): void
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        // Rate limiting - max 5 submissions per hour per IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!Security::checkRateLimit('contact_' . $ip, 5, 3600)) {
            Session::flash('error', 'Trop de messages envoyés. Veuillez réessayer plus tard.');
            $this->back();
            return;
        }
        
        // Validate
        $errors = $this->validate([
            'name' => 'required|min:2|max:255',
            'email' => 'required|email',
            'subject' => 'required|min:5|max:255',
            'message' => 'required|min:20'
        ]);
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs ci-dessous.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        // Honeypot check (anti-spam)
        if (!empty($_POST['website'])) {
            // Bot detected, silently fail
            Session::flash('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
            $this->redirect(SITE_URL . '/contact');
            return;
        }
        
        // Create contact
        require_once BASE_PATH . '/app/models/Contact.php';
        
        $contactId = Contact::create([
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $this->input('phone'),
            'subject' => $this->input('subject'),
            'message' => Security::cleanHtml($_POST['message']),
            'ip_address' => $ip,
            'status' => 'new'
        ]);
        
        if ($contactId) {
            // Send notification email to admin (in production)
            // mail(SITE_EMAIL, 'Nouveau message de contact', '...');
            
            Session::flash('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
            Session::clearOldInput();
            $this->redirect(SITE_URL . '/contact');
        } else {
            Session::flash('error', 'Une erreur est survenue. Veuillez réessayer.');
            Session::flashInput();
            $this->back();
        }
    }
}
