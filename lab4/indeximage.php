<?php

declare(strict_types=1);

/**
 * Directory path containing gallery images.
 *
 * @var string
 */
$dir = 'image/';

/**
 * Reads the list of files from the image directory.
 *
 * scandir() returns an array of file and directory names,
 * including "." and "..".
 *
 * @var array<int, string>|false
 */
$files = scandir($dir);

/**
 * Stops script execution if directory reading failed.
 */
if ($files === false) {
    return;
}

/**
 * Stores the number of valid image files found in the directory.
 *
 * @var int
 */
$imageCount = 0;

/**
 * Counts valid files in the directory, excluding "." and "..".
 */
for ($i = 0; $i < count($files); $i++) {
    if (($files[$i] !== ".") && ($files[$i] !== "..")) {
        $imageCount++;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Галерея</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header, footer {
            background: #f2f2f2;
            padding: 15px;
            text-align: center;
        }

        nav {
            background: #dddddd;
            padding: 10px;
            text-align: center;
        }

        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: black;
        }

        main {
            padding: 20px;
        }

        h2, p {
            text-align: center;
        }

        .gallery img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            margin: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<header>
    <h1>Моя галерея</h1>
</header>

<nav>
    <a href="#">Главная</a>
    <a href="#">Галерея</a>
    <a href="#">Контакты</a>
</nav>

<main>
    <h2>Изображения из папки image</h2>
    <p>Найдено: <?php echo $imageCount; ?> изображений</p>

    <div class="gallery">
        <?php
        /**
         * Iterates through all directory entries and displays images,
         * excluding "." and "..".
         */
        for ($i = 0; $i < count($files); $i++) {
            if (($files[$i] !== ".") && ($files[$i] !== "..")) {
                /**
                 * Full relative path to the image file.
                 *
                 * @var string
                 */
                $path = $dir . $files[$i];
                ?>
                <img src="<?php echo $path; ?>" alt="Image">
                <?php
            }
        }
        ?>
    </div>
</main>

<footer>
    <p>2026, Моя галерея</p>
</footer>

</body>
</html>
