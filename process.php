<?php
require_once 'functions.php';

$imageDatabase = loadDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add' && isset($_FILES['image'])) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $hash = calculateHash($uploadFile);
            $imageDatabase[] = ['path' => $uploadFile, 'hash' => $hash];
            saveDatabase($imageDatabase);
            header("Location: index.php?message=" . urlencode("Image added to database"));
            exit;
        } else {
            header("Location: index.php?error=" . urlencode("Failed to upload image"));
            exit;
        }
    } elseif ($_POST['action'] === 'search' && isset($_FILES['search_image'])) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['search_image']['name']);
        
        if (move_uploaded_file($_FILES['search_image']['tmp_name'], $uploadFile)) {
            $searchHash = calculateHash($uploadFile);
            
            $results = array_map(function($img) use ($searchHash) {
                return [
                    'path' => $img['path'],
                    'similarity' => calculateSimilarity($searchHash, $img['hash'])
                ];
            }, $imageDatabase);
            
            usort($results, function($a, $b) {
                return $b['similarity'] <=> $a['similarity'];
            });
            
            $topResults = array_slice($results, 0, 5);
            header("Location: index.php?results=" . urlencode(json_encode($topResults)));
            exit;
        } else {
            header("Location: index.php?error=" . urlencode("Failed to upload search image"));
            exit;
        }
    }
}

header("Location: index.php");
exit;