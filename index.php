<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Stuff</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1a1b1a;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        h1 {
            margin: 0;
            font-size: 36px;
        }
        .content {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #333;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px 0;
            border-bottom: 1px solid #1a1b1a;
        }
        a {
            color: white;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .icon-default::before {
        content: "\1F480"; /* Unicode character code for a skull icon */
        font-family: "Font Awesome 5 Free";
        margin-right: 15px;
        margin-left: 5px;
        color: #FF5733;
        }
    </style>
</head>
<body>
    <header>
        <h1>Stuff</h1>
    </header>
    <div class="content">
        <ul>
            <?php
                $directory = './'; // Specify the directory you want to list
                $files = scandir($directory);

                // Create an array to store the file extensions
                $notAllowedExtensions = array('html', 'php', 'swp', 'css');

                echo '<ul>';
                foreach ($files as $file) {
                    // Exclude dot files and index files
                    if ($file != '.' && $file != '..' && !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $notAllowedExtensions)) {
                        echo '<li><i class="icon-default"></i><a href="' . $file . '">' . $file . '</a></li>';
                    }
                }
                echo '</ul>';
            ?>
        </ul>
    </div>
</body>
</html>
