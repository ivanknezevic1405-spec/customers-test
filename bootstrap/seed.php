<?php

// Create admin user for production
function createAdminUser() {
    try {
        $pdo = new PDO(
            "pgsql:host=" . $_ENV['POSTGRES_HOST'] . ";port=5432;dbname=" . $_ENV['POSTGRES_DATABASE'],
            $_ENV['POSTGRES_USER'],
            $_ENV['POSTGRES_PASSWORD']
        );
        
        // Check if admin user exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute(['admin@admin.com']);
        $exists = $stmt->fetchColumn();
        
        if (!$exists) {
            // Create admin user
            $hashedPassword = password_hash('admin123', PASSWORD_BCRYPT);
            $now = date('Y-m-d H:i:s');
            
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(['Admin', 'admin@admin.com', $hashedPassword, $now, $now]);
            
            error_log("Admin user created: admin@admin.com / admin123");
        }
        
    } catch (Exception $e) {
        error_log("Admin user creation error: " . $e->getMessage());
    }
}

// Create admin user if on Vercel
if ((isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL'])) && isset($_ENV['POSTGRES_HOST'])) {
    createAdminUser();
}