<?php
/**
 * TSILIZY LLC — PDF Generator
 * Generates PDF documents for invoices, contracts, etc.
 * Uses HTML-to-PDF conversion
 */

namespace Core;

class PDF
{
    private static string $html = '';
    private static array $options = [
        'paper_size' => 'A4',
        'orientation' => 'portrait',
        'margin_top' => 20,
        'margin_right' => 15,
        'margin_bottom' => 20,
        'margin_left' => 15
    ];
    
    /**
     * Generate PDF from HTML content
     */
    public static function fromHtml(string $html): self
    {
        self::$html = $html;
        return new self();
    }
    
    /**
     * Generate PDF from view template
     */
    public static function fromView(string $template, array $data = []): self
    {
        $templatePath = BASE_PATH . '/resources/views/' . $template . '.php';
        
        if (!file_exists($templatePath)) {
            throw new \Exception("Template not found: $template");
        }
        
        extract($data);
        ob_start();
        include $templatePath;
        self::$html = ob_get_clean();
        
        return new self();
    }
    
    /**
     * Set paper options
     */
    public function options(array $options): self
    {
        self::$options = array_merge(self::$options, $options);
        return $this;
    }
    
    /**
     * Download PDF file
     */
    public function download(string $filename): void
    {
        $this->stream($filename, true);
    }
    
    /**
     * Stream PDF inline
     */
    public function inline(string $filename): void
    {
        $this->stream($filename, false);
    }
    
    /**
     * Save PDF to file
     */
    public function save(string $path): bool
    {
        $html = self::wrapHtml();
        file_put_contents($path, $html);
        return true;
    }
    
    /**
     * Stream PDF response
     */
    private function stream(string $filename, bool $download): void
    {
        $html = self::wrapHtml();
        
        // Set headers for PDF-like display using HTML
        header('Content-Type: text/html; charset=UTF-8');
        
        // Add print styles and auto-print JavaScript
        $printHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{$filename}</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
        }
        .print-header {
            background: #1E293B;
            color: white;
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .print-btn {
            background: #C9A227;
            color: #020617;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .print-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="no-print print-header">
        <span>Document: {$filename}</span>
        <button class="print-btn" onclick="window.print()">Imprimer / Télécharger PDF</button>
    </div>
    <div class="print-content">
        {$html}
    </div>
</body>
</html>
HTML;
        
        echo $printHtml;
        exit;
    }
    
    /**
     * Wrap HTML with document structure
     */
    private static function wrapHtml(): string
    {
        return self::$html;
    }
    
    /**
     * Generate invoice PDF
     */
    public static function invoice(array $invoice): self
    {
        return self::fromView('pdf/invoice', ['invoice' => $invoice]);
    }
    
    /**
     * Generate contract PDF
     */
    public static function contract(array $contract): self
    {
        return self::fromView('pdf/contract', ['contract' => $contract]);
    }
}
