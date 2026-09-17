<?php

// Ensure serverless temporary views cache directory exists
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0777, true);
}

// Forward Vercel requests to Laravel's public index
require __DIR__ . '/../public/index.php';