<?php
/**
 * TSILIZY LLC — Admin Media Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use Core\Auth;

class MediaController extends Controller
{
    private string $uploadDir;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->uploadDir = BASE_PATH . '/public/uploads/';
    }
    
    public function index(): void
    {
        $folder = $this->query('folder') ?: 'all';
        
        $whereClause = "deleted_at IS NULL";
        $params = [];
        
        if ($folder !== 'all') {
            $whereClause .= " AND folder = ?";
            $params[] = $folder;
        }
        
        $media = Database::query(
            "SELECT * FROM media WHERE $whereClause ORDER BY created_at DESC LIMIT 100",
            $params
        );
        
        $folders = Database::query("SELECT DISTINCT folder FROM media WHERE deleted_at IS NULL ORDER BY folder");
        
        View::layout('admin', ['page_title' => 'Médias']);
        $this->view('admin/media/index', [
            'media' => $media,
            'folders' => array_column($folders, 'folder'),
            'current_folder' => $folder
        ]);
    }
    
    public function upload(): void
    {
        if (!$this->validateCsrf()) return;
        
        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Erreur lors du téléchargement.');
            $this->back();
            return;
        }
        
        $file = $_FILES['file'];
        $folder = $this->input('folder') ?: 'general';
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf', 'video/mp4'];
        if (!in_array($file['type'], $allowedTypes)) {
            Session::flash('error', 'Type de fichier non autorisé.');
            $this->back();
            return;
        }
        
        // Max 10MB
        if ($file['size'] > 10 * 1024 * 1024) {
            Session::flash('error', 'Fichier trop volumineux (max 10 Mo).');
            $this->back();
            return;
        }
        
        // Create folder if needed
        $targetDir = $this->uploadDir . $folder . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $path = $folder . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetDir . $filename)) {
            Database::insert(
                "INSERT INTO media (filename, original_name, path, mime_type, size, folder, uploaded_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                [$filename, $file['name'], $path, $file['type'], $file['size'], $folder, Auth::id()]
            );
            Session::flash('success', 'Fichier téléchargé avec succès.');
        } else {
            Session::flash('error', 'Erreur lors de l\'enregistrement du fichier.');
        }
        
        $this->redirect(SITE_URL . '/admin/medias');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $media = Database::query("SELECT * FROM media WHERE id = ?", [(int)$id])[0] ?? null;
        if ($media) {
            // Delete file
            $filePath = $this->uploadDir . $media['path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            Database::execute("UPDATE media SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        }
        
        Session::flash('success', 'Fichier supprimé.');
        $this->redirect(SITE_URL . '/admin/medias');
    }
}
