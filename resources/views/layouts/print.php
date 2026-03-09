<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Document' ?> | <?= SITE_NAME ?></title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #1a1a1a;
            background: white;
        }
        
        .page {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm;
            background: white;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #C9A227;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #C9A227, #B8860B);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 24px;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .logo-text span {
            display: block;
            font-size: 10px;
            font-weight: 400;
            color: #666;
            letter-spacing: 2px;
        }
        
        .company-info {
            text-align: right;
            font-size: 10pt;
            color: #666;
        }
        
        /* Document Info */
        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .document-title {
            font-size: 28px;
            font-weight: 700;
            color: #C9A227;
            margin-bottom: 10px;
        }
        
        .document-meta {
            font-size: 10pt;
            color: #666;
        }
        
        .document-meta strong {
            color: #1a1a1a;
        }
        
        /* Client Info */
        .client-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .client-box h3 {
            font-size: 12pt;
            font-weight: 600;
            color: #C9A227;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th {
            background: #1a1a1a;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-size: 10pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Totals */
        .totals {
            margin-left: auto;
            width: 300px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .totals-row.total {
            font-size: 14pt;
            font-weight: 700;
            color: #C9A227;
            border-bottom: 2px solid #C9A227;
            padding: 15px 0;
        }
        
        /* Notes */
        .notes {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            font-size: 10pt;
        }
        
        .notes h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 9pt;
            color: #999;
        }
        
        /* Print styles */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .page {
                padding: 0;
                margin: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            @page {
                size: A4;
                margin: 20mm;
            }
        }
        
        /* Print button */
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #C9A227, #B8860B);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(201, 162, 39, 0.3);
        }
        
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(201, 162, 39, 0.4);
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button class="print-btn no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Imprimer
    </button>
    
    <div class="page">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="logo-icon">T</div>
                <div class="logo-text">
                    TSILIZY
                    <span>LLC</span>
                </div>
            </div>
            <div class="company-info">
                <strong><?= SITE_NAME ?></strong><br>
                <?= SITE_ADDRESS ?><br>
                <?= SITE_PHONE ?><br>
                <?= SITE_EMAIL ?>
            </div>
        </div>
        
        <!-- Content will be injected here -->
        <?= $content ?? '' ?>
        
        <!-- Footer -->
        <div class="footer">
            <p><?= SITE_NAME ?> — <?= SITE_ADDRESS ?></p>
            <p>Tél: <?= SITE_PHONE ?> | Email: <?= SITE_EMAIL ?></p>
            <p style="margin-top: 10px;">Document généré le <?= date('d/m/Y à H:i') ?></p>
        </div>
    </div>
    
    <script>
        // Auto print if requested
        if (window.location.search.includes('autoprint=1')) {
            window.onload = function() {
                window.print();
            };
        }
    </script>
</body>
</html>
