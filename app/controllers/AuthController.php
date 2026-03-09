<?php
/**
 * TSILIZY LLC — Authentication Controller
 */

namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Session;
use Core\Security;
use Core\View;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function loginForm(): void
    {
        // Redirect if already logged in
        if (Auth::check()) {
            $this->redirect(SITE_URL . '/mon-compte');
        }
        
        $this->view('auth/login', [
            'page_title' => 'Connexion'
        ]);
    }
    
    /**
     * Process login
     */
    public function login(): void
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        $email = $this->input('email');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validate inputs
        $errors = $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs ci-dessous.');
            Session::flashInput();
            $this->back();
            return;
        }
        
        // Attempt login
        if (Auth::attempt($email, $password)) {
            Session::flash('success', 'Connexion réussie. Bienvenue!');
            
            // Check if admin
            if (Auth::isAdmin()) {
                $this->redirect(SITE_URL . '/admin');
            } else {
                $this->redirect(SITE_URL . '/mon-compte');
            }
        } else {
            Session::flash('error', 'Email ou mot de passe incorrect.');
            Session::flashInput();
            $this->back();
        }
    }
    
    /**
     * Show registration form
     */
    public function registerForm(): void
    {
        // Check if registration is enabled
        if (!ENABLE_REGISTRATION) {
            Session::flash('warning', 'Les inscriptions sont actuellement fermées.');
            $this->redirect(SITE_URL . '/connexion');
            return;
        }
        
        // Redirect if already logged in
        if (Auth::check()) {
            $this->redirect(SITE_URL . '/mon-compte');
        }
        
        $this->view('auth/register', [
            'page_title' => 'Inscription'
        ]);
    }
    
    /**
     * Process registration
     */
    public function register(): void
    {
        if (!ENABLE_REGISTRATION) {
            Session::flash('error', 'Les inscriptions sont actuellement fermées.');
            $this->redirect(SITE_URL . '/connexion');
            return;
        }
        
        if (!$this->validateCsrf()) {
            return;
        }
        
        // Validate inputs
        $errors = $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required'
        ]);
        
        // Check password confirmation
        if ($_POST['password'] !== $_POST['password_confirmation']) {
            $errors['password_confirmation'] = 'Les mots de passe ne correspondent pas.';
        }
        
        // Check if email exists
        $existingUser = \Core\Database::query(
            "SELECT id FROM users WHERE email = ? AND deleted_at IS NULL",
            [$this->input('email')]
        );
        
        if (!empty($existingUser)) {
            $errors['email'] = 'Cette adresse email est déjà utilisée.';
        }
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs ci-dessous.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        // Create user
        $userId = Auth::register([
            'first_name' => $this->input('first_name'),
            'last_name' => $this->input('last_name'),
            'email' => $this->input('email'),
            'password' => $_POST['password'],
            'phone' => $this->input('phone')
        ]);
        
        if ($userId) {
            Session::flash('success', 'Inscription réussie! Vous pouvez maintenant vous connecter.');
            $this->redirect(SITE_URL . '/connexion');
        } else {
            Session::flash('error', 'Une erreur est survenue. Veuillez réessayer.');
            Session::flashInput();
            $this->back();
        }
    }
    
    /**
     * Process logout
     */
    public function logout(): void
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        Auth::logout();
        Session::flash('success', 'Vous avez été déconnecté avec succès.');
        $this->redirect(SITE_URL);
    }
    
    /**
     * Show forgot password form
     */
    public function forgotPasswordForm(): void
    {
        $this->view('auth/forgot-password', [
            'page_title' => 'Mot de passe oublié'
        ]);
    }
    
    /**
     * Process forgot password
     */
    public function forgotPassword(): void
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        $email = $this->input('email');
        
        if (!Security::isValidEmail($email)) {
            Session::flash('error', 'Veuillez entrer une adresse email valide.');
            $this->back();
            return;
        }
        
        $token = Auth::createPasswordReset($email);
        
        // Always show success message (security: don't reveal if email exists)
        Session::flash('success', 'Si cette adresse email est associée à un compte, vous recevrez un lien de réinitialisation.');
        
        // In production, send email here
        if ($token) {
            $resetLink = SITE_URL . '/reinitialiser-mot-de-passe/' . $token;
            // mail($email, 'Réinitialisation de mot de passe', "Cliquez ici : $resetLink");
            // For development, log the link
            error_log("Password reset link for {$email}: {$resetLink}");
        }
        
        $this->redirect(SITE_URL . '/connexion');
    }
    
    /**
     * Show reset password form
     */
    public function resetPasswordForm(string $token): void
    {
        $email = Auth::verifyPasswordReset($token);
        
        if (!$email) {
            Session::flash('error', 'Ce lien de réinitialisation est invalide ou expiré.');
            $this->redirect(SITE_URL . '/mot-de-passe-oublie');
            return;
        }
        
        $this->view('auth/reset-password', [
            'page_title' => 'Réinitialiser le mot de passe',
            'token' => $token,
            'email' => $email
        ]);
    }
    
    /**
     * Process reset password
     */
    public function resetPassword(): void
    {
        if (!$this->validateCsrf()) {
            return;
        }
        
        $token = $this->input('token');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';
        
        // Verify token
        $email = Auth::verifyPasswordReset($token);
        
        if (!$email) {
            Session::flash('error', 'Ce lien de réinitialisation est invalide ou expiré.');
            $this->redirect(SITE_URL . '/mot-de-passe-oublie');
            return;
        }
        
        // Validate password
        if (strlen($password) < 8) {
            Session::flash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            $this->back();
            return;
        }
        
        if ($password !== $passwordConfirmation) {
            Session::flash('error', 'Les mots de passe ne correspondent pas.');
            $this->back();
            return;
        }
        
        // Reset password
        if (Auth::resetPassword($email, $password)) {
            Session::flash('success', 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter.');
            $this->redirect(SITE_URL . '/connexion');
        } else {
            Session::flash('error', 'Une erreur est survenue. Veuillez réessayer.');
            $this->back();
        }
    }
}
