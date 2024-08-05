<?php
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Reverse Search</title>
</head>
<body>
    <h1>Image Reverse Search</h1>

    <?php
    if (isset($_GET['message'])) {
        echo "<p style='color: green;'>" . htmlspecialchars($_GET['message']) . "</p>";
    }
    if (isset($_GET['error'])) {
        echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
    }
    ?>

    <h2>Add Image to Database</h2>
    <form action="process.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*" required>
        <input type="hidden" name="action" value="add">
        <button type="submit">Add Image</button>
    </form>

    <h2>Search Image</h2>
    <form action="process.php" method="post" enctype="multipart/form-data">
        <input type="file" name="search_image" accept="image/*" required>
        <input type="hidden" name="action" value="search">
        <button type="submit">Search</button>
    </form>

    <h2>Results</h2>
    <div id="results">
        <?php
        if (isset($_GET['results'])) {
            $results = json_decode(urldecode($_GET['results']), true);
            foreach ($results as $result) {
                echo "<img src='{$result['path']}' width='200'>";
                echo "<p>Path: {$result['path']}</p>";
                echo "<p>Similarity: " . number_format($result['similarity'] * 100, 2) . "%</p>";
            }
        }
        ?>
    </div>

    <h2>Images in Database</h2>
    <div id="database-images">
        <?php
        $imageDatabase = loadDatabase();
        foreach ($imageDatabase as $image) {
            echo "<img src='{$image['path']}' width='100'>";
            echo "<p>Path: {$image['path']}</p>";
        }
        ?>
    </div>
</body>
</html>