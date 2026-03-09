-- ============================================
-- TSILIZY LLC — Legal Pages Seed Data
-- Run after main schema.sql
-- ============================================

-- Politique de Confidentialité
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `status`, `seo_title`, `seo_description`, `template`, `order_index`, `created_at`, `updated_at`) VALUES
('Politique de Confidentialité', 'politique-confidentialite', 
'<h2>1. Introduction</h2>
<p>TSILIZY LLC s''engage à protéger la vie privée de ses utilisateurs. Cette politique de confidentialité explique comment nous collectons, utilisons, stockons et protégeons vos informations personnelles.</p>

<h2>2. Données collectées</h2>
<p>Nous collectons les types de données suivants :</p>
<ul>
    <li><strong>Données d''identification</strong> : nom, prénom, adresse email, numéro de téléphone</li>
    <li><strong>Données de connexion</strong> : adresse IP, type de navigateur, pages visitées</li>
    <li><strong>Données de communication</strong> : messages envoyés via le formulaire de contact</li>
</ul>

<h2>3. Utilisation des données</h2>
<p>Vos données sont utilisées pour :</p>
<ul>
    <li>Fournir et améliorer nos services</li>
    <li>Répondre à vos demandes et communications</li>
    <li>Vous envoyer des informations sur nos services (avec votre consentement)</li>
    <li>Analyser l''utilisation de notre site pour l''améliorer</li>
</ul>

<h2>4. Conservation des données</h2>
<p>Vos données personnelles sont conservées pendant la durée nécessaire aux finalités pour lesquelles elles ont été collectées, conformément aux obligations légales.</p>

<h2>5. Vos droits</h2>
<p>Conformément au RGPD, vous disposez des droits suivants :</p>
<ul>
    <li>Droit d''accès à vos données</li>
    <li>Droit de rectification</li>
    <li>Droit à l''effacement (« droit à l''oubli »)</li>
    <li>Droit à la limitation du traitement</li>
    <li>Droit à la portabilité des données</li>
    <li>Droit d''opposition</li>
</ul>

<h2>6. Cookies</h2>
<p>Notre site utilise des cookies pour améliorer votre expérience. Vous pouvez gérer vos préférences de cookies à tout moment.</p>

<h2>7. Contact</h2>
<p>Pour toute question concernant cette politique, contactez-nous à : <a href="mailto:contact@tsilizy.com">contact@tsilizy.com</a></p>

<p><em>Dernière mise à jour : Janvier 2026</em></p>',
'Découvrez comment TSILIZY LLC protège vos données personnelles et respecte votre vie privée.', 
'published', 
'Politique de Confidentialité | TSILIZY LLC', 
'Politique de confidentialité et protection des données personnelles de TSILIZY LLC', 
'default', 1, NOW(), NOW());

-- Mentions Légales
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `status`, `seo_title`, `seo_description`, `template`, `order_index`, `created_at`, `updated_at`) VALUES
('Mentions Légales', 'mentions-legales', 
'<h2>1. Éditeur du site</h2>
<p>
    <strong>TSILIZY LLC</strong><br>
    Société à responsabilité limitée<br>
    Siège social : Paris, France<br>
    Email : <a href="mailto:contact@tsilizy.com">contact@tsilizy.com</a><br>
    Téléphone : +33 1 23 45 67 89
</p>

<h2>2. Directeur de la publication</h2>
<p>Le directeur de la publication est le représentant légal de TSILIZY LLC.</p>

<h2>3. Hébergement</h2>
<p>
    Ce site est hébergé par :<br>
    <strong>Byethost / iFastNet</strong><br>
    Site web : https://byet.host
</p>

<h2>4. Propriété intellectuelle</h2>
<p>L''ensemble du contenu de ce site (textes, images, graphismes, logo, icônes, sons, logiciels) est la propriété exclusive de TSILIZY LLC ou de ses partenaires et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.</p>
<p>Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de TSILIZY LLC.</p>

<h2>5. Limitation de responsabilité</h2>
<p>TSILIZY LLC ne pourra être tenue responsable des dommages directs et indirects causés au matériel de l''utilisateur, lors de l''accès au site, résultant soit de l''utilisation d''un matériel ne répondant pas aux spécifications techniques requises, soit de l''apparition d''un bug ou d''une incompatibilité.</p>

<h2>6. Liens hypertextes</h2>
<p>Le site peut contenir des liens hypertextes vers d''autres sites. TSILIZY LLC n''exerce aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.</p>

<h2>7. Droit applicable</h2>
<p>Le présent site et les présentes mentions légales sont soumis au droit français. En cas de litige, les tribunaux français seront seuls compétents.</p>

<p><em>Dernière mise à jour : Janvier 2026</em></p>',
'Mentions légales du site TSILIZY LLC - Informations sur l''éditeur, l''hébergeur et les conditions d''utilisation.', 
'published', 
'Mentions Légales | TSILIZY LLC', 
'Mentions légales de TSILIZY LLC - Éditeur, hébergeur, propriété intellectuelle et droit applicable', 
'default', 2, NOW(), NOW());

-- Conditions Générales de Vente (CGV)
INSERT INTO `pages` (`title`, `slug`, `content`, `excerpt`, `status`, `seo_title`, `seo_description`, `template`, `order_index`, `created_at`, `updated_at`) VALUES
('Conditions Générales de Vente', 'cgv', 
'<h2>Article 1 - Objet</h2>
<p>Les présentes Conditions Générales de Vente (CGV) régissent les relations contractuelles entre TSILIZY LLC et ses clients pour toute prestation de services ou vente de produits.</p>

<h2>Article 2 - Prix</h2>
<p>Les prix sont indiqués en euros (EUR) et sont susceptibles d''être modifiés à tout moment. Le prix applicable est celui en vigueur au moment de la commande.</p>
<p>Les prix ne comprennent pas les frais de livraison, qui sont indiqués séparément avant validation de la commande.</p>

<h2>Article 3 - Commande</h2>
<p>Toute commande implique l''acceptation des présentes CGV. La commande n''est définitive qu''après confirmation par TSILIZY LLC et réception du paiement.</p>

<h2>Article 4 - Paiement</h2>
<p>Le paiement s''effectue selon les modalités suivantes :</p>
<ul>
    <li>Virement bancaire</li>
    <li>Carte bancaire (via notre plateforme sécurisée)</li>
    <li>Autres moyens de paiement convenus au cas par cas</li>
</ul>
<p>Pour les prestations de services, un acompte de 30% peut être demandé à la commande.</p>

<h2>Article 5 - Livraison et exécution</h2>
<p>Les délais de livraison ou d''exécution sont donnés à titre indicatif. Un retard raisonnable ne peut donner lieu à annulation de la commande ou à indemnisation.</p>

<h2>Article 6 - Droit de rétractation</h2>
<p>Conformément à la législation en vigueur, le client dispose d''un délai de 14 jours à compter de la réception du produit ou de la signature du contrat de service pour exercer son droit de rétractation, sans avoir à justifier de motifs ni à payer de pénalités.</p>
<p>Ce droit ne s''applique pas aux prestations de services commencées avec l''accord exprès du client avant la fin du délai de rétractation.</p>

<h2>Article 7 - Garanties</h2>
<p>TSILIZY LLC garantit la conformité des produits et services aux spécifications convenues. Tout défaut doit être signalé dans les 30 jours suivant la livraison ou l''exécution.</p>

<h2>Article 8 - Responsabilité</h2>
<p>La responsabilité de TSILIZY LLC est limitée au montant de la commande. TSILIZY LLC ne saurait être tenue responsable des dommages indirects, pertes de données ou manque à gagner.</p>

<h2>Article 9 - Protection des données</h2>
<p>Les données personnelles collectées sont traitées conformément à notre <a href="/page/politique-confidentialite">Politique de Confidentialité</a>.</p>

<h2>Article 10 - Propriété intellectuelle</h2>
<p>Sauf accord écrit contraire, TSILIZY LLC conserve tous les droits de propriété intellectuelle sur les créations réalisées dans le cadre des prestations de services.</p>

<h2>Article 11 - Litiges</h2>
<p>En cas de litige, une solution amiable sera recherchée avant tout recours judiciaire. À défaut, les tribunaux français seront seuls compétents.</p>

<h2>Article 12 - Modification des CGV</h2>
<p>TSILIZY LLC se réserve le droit de modifier les présentes CGV à tout moment. Les CGV applicables sont celles en vigueur à la date de la commande.</p>

<p><em>Dernière mise à jour : Janvier 2026</em></p>',
'Conditions Générales de Vente de TSILIZY LLC - Conditions applicables à toutes nos prestations et ventes.', 
'published', 
'Conditions Générales de Vente (CGV) | TSILIZY LLC', 
'Conditions Générales de Vente de TSILIZY LLC - Prix, commandes, paiement, livraison et garanties', 
'default', 3, NOW(), NOW());
