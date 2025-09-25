<?php

// Vercel API routes handler
// This file helps route API requests properly on Vercel

if (isset($_GET['vercel'])) {
    $_GET['url'] = $_GET['vercel'];
}

require_once __DIR__ . '/index.php';