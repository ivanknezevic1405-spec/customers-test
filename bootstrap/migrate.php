<?php

// Database migration check for Vercel
// This runs migrations automatically on first request

function runMigrationsIfNeeded() {
    try {
        // Check if migrations table exists
        $pdo = new PDO(
            "pgsql:host=" . $_ENV['POSTGRES_HOST'] . ";port=5432;dbname=" . $_ENV['POSTGRES_DATABASE'],
            $_ENV['POSTGRES_USER'],
            $_ENV['POSTGRES_PASSWORD']
        );
        
        // Check if migrations table exists
        $stmt = $pdo->prepare("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'migrations')");
        $stmt->execute();
        $exists = $stmt->fetchColumn();
        
        if (!$exists) {
            // Run migrations programmatically
            error_log("Running initial database migrations...");
            
            // Create migrations table
            $pdo->exec("CREATE TABLE migrations (
                id SERIAL PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INTEGER NOT NULL
            )");
            
            // Create users table
            $pdo->exec("CREATE TABLE users (
                id BIGSERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )");
            
            // Create customers table  
            $pdo->exec("CREATE TABLE customers (
                id BIGSERIAL PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                phone VARCHAR(255) NULL,
                address TEXT NULL,
                city VARCHAR(255) NULL,
                state VARCHAR(255) NULL,
                postal_code VARCHAR(255) NULL,
                country VARCHAR(255) NOT NULL DEFAULT 'US',
                date_of_birth DATE NULL,
                status VARCHAR(20) CHECK (status IN ('active', 'inactive')) NOT NULL DEFAULT 'active',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )");
            
            // Create other necessary tables
            $pdo->exec("CREATE TABLE cache (
                key VARCHAR(255) PRIMARY KEY,
                value TEXT NOT NULL,
                expiration INTEGER NOT NULL
            )");
            
            $pdo->exec("CREATE TABLE jobs (
                id BIGSERIAL PRIMARY KEY,
                queue VARCHAR(255) NOT NULL,
                payload TEXT NOT NULL,
                attempts SMALLINT NOT NULL,
                reserved_at INTEGER NULL,
                available_at INTEGER NOT NULL,
                created_at INTEGER NOT NULL
            )");
            
            // Record migrations
            $pdo->exec("INSERT INTO migrations (migration, batch) VALUES 
                ('0001_01_01_000000_create_users_table', 1),
                ('0001_01_01_000001_create_cache_table', 1),
                ('0001_01_01_000002_create_jobs_table', 1),
                ('2025_09_25_083808_create_customers_table', 1)");
            
            error_log("Database migrations completed successfully!");
        }
        
    } catch (Exception $e) {
        error_log("Migration error: " . $e->getMessage());
    }
}

// Only run if we're on Vercel and have database credentials
if ((isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL'])) && isset($_ENV['POSTGRES_HOST'])) {
    runMigrationsIfNeeded();
}