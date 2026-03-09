<?php
/**
 * TSILIZY LLC — Contract PDF Template
 */

$c = $contract;
$siteName = SITE_NAME ?? 'TSILIZY LLC';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', serif; background: white; color: #1E293B; line-height: 1.8; font-size: 14px; }
        .contract { max-width: 800px; margin: 0 auto; padding: 50px; }
        .header { text-align: center; margin-bottom: 50px; border-bottom: 3px double #C9A227; padding-bottom: 30px; }
        .logo { font-size: 28px; font-weight: bold; color: #C9A227; letter-spacing: 3px; margin-bottom: 20px; }
        h1 { font-size: 28px; color: #0F172A; margin-bottom: 10px; }
        .ref { color: #64748B; font-family: monospace; font-size: 14px; }
        .parties { display: flex; justify-content: space-between; margin: 40px 0; padding: 30px; background: #F8FAFC; border-radius: 8px; }
        .party { width: 45%; }
        .party h3 { color: #C9A227; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }
        .party .name { font-size: 18px; font-weight: bold; color: #0F172A; margin-bottom: 5px; }
        .party p { color: #475569; margin-bottom: 3px; }
        .section { margin-bottom: 30px; }
        .section h2 { font-size: 16px; color: #0F172A; border-bottom: 2px solid #E2E8F0; padding-bottom: 8px; margin-bottom: 15px; }
        .section p { text-align: justify; margin-bottom: 12px; }
        .dates { background: #0F172A; color: white; padding: 25px; border-radius: 8px; margin: 40px 0; display: flex; justify-content: space-around; }
        .date-item { text-align: center; }
        .date-item label { display: block; font-size: 11px; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .date-item span { font-size: 18px; font-weight: bold; }
        .amount { text-align: center; margin: 40px 0; padding: 30px; background: linear-gradient(135deg, #C9A227, #B8860B); border-radius: 8px; color: #0F172A; }
        .amount label { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; }
        .amount .value { font-size: 36px; font-weight: bold; margin-top: 5px; }
        .signatures { display: flex; justify-content: space-between; margin-top: 80px; padding-top: 40px; border-top: 1px solid #E2E8F0; }
        .signature { width: 45%; text-align: center; }
        .signature .line { border-bottom: 1px solid #0F172A; height: 60px; margin-bottom: 10px; }
        .signature label { font-size: 12px; color: #64748B; }
        .footer { margin-top: 60px; text-align: center; font-size: 11px; color: #94A3B8; }
        @media print { body { print-color-adjust: exact; -webkit-print-color-adjust: exact; } }
    </style>
</head>
<body>
    <div class="contract">
        <div class="header">
            <div class="logo"><?= htmlspecialchars($siteName) ?></div>
            <h1>CONTRAT DE SERVICE</h1>
            <div class="ref">Réf: <?= htmlspecialchars($c['reference']) ?></div>
        </div>
        
        <div class="parties">
            <div class="party">
                <h3>Le Prestataire</h3>
                <p class="name"><?= htmlspecialchars($siteName) ?></p>
                <p><?= SITE_ADDRESS ?? '123 Rue Example' ?></p>
                <p><?= SITE_EMAIL ?? 'contact@tsilizy.com' ?></p>
            </div>
            <div class="party">
                <h3>Le Client</h3>
                <p class="name"><?= htmlspecialchars($c['user_name'] ?? 'Client') ?></p>
                <p><?= htmlspecialchars($c['user_email'] ?? '') ?></p>
            </div>
        </div>
        
        <div class="dates">
            <div class="date-item">
                <label>Date de début</label>
                <span><?= date('d/m/Y', strtotime($c['start_date'])) ?></span>
            </div>
            <div class="date-item">
                <label>Date de fin</label>
                <span><?= $c['end_date'] ? date('d/m/Y', strtotime($c['end_date'])) : 'Indéterminée' ?></span>
            </div>
            <div class="date-item">
                <label>Statut</label>
                <span style="color: <?= $c['status'] === 'active' ? '#22C55E' : '#94A3B8' ?>">
                    <?php echo match($c['status']) { 'active' => 'ACTIF', 'pending' => 'EN ATTENTE', 'completed' => 'TERMINÉ', default => 'ANNULÉ' }; ?>
                </span>
            </div>
        </div>
        
        <div class="section">
            <h2>Article 1 — Objet du contrat</h2>
            <p><?= nl2br(htmlspecialchars($c['title'] ?? 'Prestation de services')) ?></p>
        </div>
        
        <div class="section">
            <h2>Article 2 — Description des prestations</h2>
            <div><?= $c['description'] ?? '<p>Les prestations seront définies d\'un commun accord entre les parties.</p>' ?></div>
        </div>
        
        <?php if ($c['value']): ?>
        <div class="amount">
            <label>Montant du contrat</label>
            <div class="value"><?= number_format($c['value'], 2, ',', ' ') ?> €</div>
        </div>
        <?php endif; ?>
        
        <div class="section">
            <h2>Article 3 — Conditions générales</h2>
            <p>Les parties s'engagent à respecter les termes du présent contrat. Toute modification devra faire l'objet d'un avenant signé par les deux parties.</p>
        </div>
        
        <div class="signatures">
            <div class="signature">
                <div class="line"></div>
                <label>Signature du Prestataire</label>
            </div>
            <div class="signature">
                <div class="line"></div>
                <label>Signature du Client</label>
            </div>
        </div>
        
        <div class="footer">
            <p>Document généré le <?= date('d/m/Y à H:i') ?> — <?= htmlspecialchars($siteName) ?></p>
        </div>
    </div>
</body>
</html>
