<?php
/**
 * TSILIZY LLC — Newsletter Controller (Public)
 */

namespace App\Controllers;

use Core\Controller;
use Core\Database;
use Core\Session;
use Core\Security;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(): void
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        $email = $this->input('email');
        
        // Validate email
        if (!Security::isValidEmail($email)) {
            Session::flash('error', 'Veuillez entrer une adresse email valide.');
            $this->back();
            return;
        }
        
        // Rate limiting
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!Security::checkRateLimit('newsletter_' . $ip, 5, 3600)) {
            Session::flash('error', 'Trop de tentatives. Veuillez réessayer plus tard.');
            $this->back();
            return;
        }
        
        // Check if already subscribed
        $existing = Database::query(
            "SELECT id, status FROM newsletter_subscribers WHERE email = ?",
            [$email]
        );
        
        if (!empty($existing)) {
            if ($existing[0]['status'] === 'active') {
                Session::flash('info', 'Cette adresse email est déjà inscrite à notre newsletter.');
            } else {
                // Reactivate
                Database::execute(
                    "UPDATE newsletter_subscribers SET status = 'active', subscribed_at = NOW() WHERE id = ?",
                    [$existing[0]['id']]
                );
                Session::flash('success', 'Votre inscription a été réactivée !');
            }
            $this->back();
            return;
        }
        
        // Subscribe
        $token = bin2hex(random_bytes(32));
        
        Database::execute(
            "INSERT INTO newsletter_subscribers (email, token, status, subscribed_at) VALUES (?, ?, 'active', NOW())",
            [$email, $token]
        );
        
        Session::flash('success', 'Merci ! Vous êtes maintenant inscrit à notre newsletter.');
        $this->back();
    }
    
    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(string $token): void
    {
        $subscriber = Database::query(
            "SELECT id, email FROM newsletter_subscribers WHERE token = ?",
            [$token]
        );
        
        if (empty($subscriber)) {
            Session::flash('error', 'Lien de désinscription invalide.');
            $this->redirect(SITE_URL);
            return;
        }
        
        Database::execute(
            "UPDATE newsletter_subscribers SET status = 'unsubscribed', unsubscribed_at = NOW() WHERE id = ?",
            [$subscriber[0]['id']]
        );
        
        Session::flash('success', 'Vous avez été désinscrit de notre newsletter.');
        $this->redirect(SITE_URL);
    }
}
