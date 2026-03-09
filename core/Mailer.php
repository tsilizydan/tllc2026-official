<?php
/**
 * TSILIZY LLC — Email Templates Manager
 */

namespace Core;

class Mailer
{
    private static array $config = [];
    
    /**
     * Initialize mailer with SMTP config
     */
    public static function init(): void
    {
        self::$config = [
            'host' => defined('SMTP_HOST') ? SMTP_HOST : 'localhost',
            'port' => defined('SMTP_PORT') ? SMTP_PORT : 587,
            'username' => defined('SMTP_USERNAME') ? SMTP_USERNAME : '',
            'password' => defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '',
            'from_name' => defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : SITE_NAME,
            'from_email' => defined('MAIL_FROM_ADDRESS') ? MAIL_FROM_ADDRESS : 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
        ];
    }
    
    /**
     * Send email using template
     */
    public static function send(string $to, string $subject, string $template, array $data = []): bool
    {
        $html = self::renderTemplate($template, $data);
        
        // Use PHP mail() as fallback, can be replaced with SMTP
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . self::$config['from_name'] . ' <' . self::$config['from_email'] . '>',
            'Reply-To: ' . self::$config['from_email'],
            'X-Mailer: PHP/' . phpversion()
        ];
        
        return @mail($to, $subject, $html, implode("\r\n", $headers));
    }
    
    /**
     * Render email template
     */
    public static function renderTemplate(string $template, array $data = []): string
    {
        $templatePath = BASE_PATH . '/resources/emails/' . $template . '.php';
        
        if (!file_exists($templatePath)) {
            return self::wrapInLayout($template, $data);
        }
        
        extract($data);
        ob_start();
        include $templatePath;
        $content = ob_get_clean();
        
        return self::wrapInLayout($content, $data);
    }
    
    /**
     * Wrap content in email layout
     */
    private static function wrapInLayout(string $content, array $data): string
    {
        $siteName = SITE_NAME ?? 'TSILIZY LLC';
        $year = date('Y');
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0F172A; color: #CBD5E1; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; background: #1E293B; }
        .header { padding: 32px; text-align: center; background: linear-gradient(135deg, #1E293B, #0F172A); border-bottom: 2px solid #C9A227; }
        .logo { font-size: 28px; font-weight: bold; color: #C9A227; letter-spacing: 2px; }
        .content { padding: 40px 32px; }
        .content h1 { color: #FFFFFF; font-size: 24px; margin-bottom: 20px; }
        .content p { margin-bottom: 16px; }
        .button { display: inline-block; padding: 14px 28px; background: linear-gradient(135deg, #C9A227, #B8860B); color: #020617 !important; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { padding: 24px 32px; text-align: center; background: #0F172A; font-size: 13px; color: #64748B; }
        .footer a { color: #C9A227; text-decoration: none; }
        .divider { height: 1px; background: #334155; margin: 24px 0; }
        .highlight { background: #C9A227; color: #020617; padding: 2px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{$siteName}</div>
        </div>
        <div class="content">
            {$content}
        </div>
        <div class="footer">
            <p>&copy; {$year} {$siteName}. Tous droits réservés.</p>
            <p style="margin-top: 8px;">
                <a href="%SITE_URL%">Site web</a> • 
                <a href="%SITE_URL%/contact">Contact</a>
            </p>
        </div>
    </div>
</body>
</html>
HTML;
    }
    
    /**
     * Quick send methods
     */
    public static function sendWelcome(string $to, string $name): bool
    {
        return self::send($to, 'Bienvenue chez ' . SITE_NAME, 'welcome', ['name' => $name]);
    }
    
    public static function sendPasswordReset(string $to, string $name, string $resetUrl): bool
    {
        return self::send($to, 'Réinitialisation de votre mot de passe', 'password-reset', [
            'name' => $name,
            'reset_url' => $resetUrl
        ]);
    }
    
    public static function sendContactConfirmation(string $to, string $name): bool
    {
        return self::send($to, 'Nous avons bien reçu votre message', 'contact-confirmation', ['name' => $name]);
    }
    
    public static function sendTicketCreated(string $to, string $name, string $reference): bool
    {
        return self::send($to, 'Ticket #' . $reference . ' créé', 'ticket-created', [
            'name' => $name,
            'reference' => $reference
        ]);
    }
    
    public static function sendInvoice(string $to, string $name, array $invoice): bool
    {
        return self::send($to, 'Facture ' . $invoice['reference'], 'invoice', [
            'name' => $name,
            'invoice' => $invoice
        ]);
    }
}

// Initialize on load
Mailer::init();
