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
            font-style: normal;
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
            content: "\f15b"; /* Unicode character code for the "file-alt" icon in Font Awesome */
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            margin-right: 15px;
            margin-left: 5px;
            color: white;
        }
        .icon-folder::before {
            content: "\f07c"; /* Unicode character code for a folder icon */
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            margin-right: 15px;
            margin-left: 5px;
            color: #428bca;
        }
        /* Icons for specific file types mapping*/
        .icon-zip::before {
            content: "\f1c6"; /* Unicode character code for a zip file icon */
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            margin-right: 15px;
            margin-left: 5px;
            color: #E91E63;
        }
        .icon-pdf::before {
            content: "\f1c1"; /* Unicode character code for a pdf file icon */
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            margin-right: 15px;
            margin-left: 5px;
            color: #FF9800;
        }
        .icon-doc::before {
            content: "\f1c2"; /* Unicode character code for a doc file icon */
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            margin-right: 15px;
            margin-left: 5px;
            color: #2196F3;
        }
        .icon-txt::before {
            content: "\f15c"; /* Unicode character code for a txt file icon */
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            margin-right: 15px;
            margin-left: 5px;
            color: #4CAF50;
        }
        /* Indent folder contents */
        ul.folder-contents {
            margin-left: 20px; /* Adjust the amount of indentation as needed */
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

                // Create a mapping of file extensions to CSS icons
                $iconMapping = array(
                    'pdf' => 'icon-pdf',
                    'doc' => 'icon-doc',
                    'txt' => 'icon-txt',
                    'zip' => 'icon-zip',
                    // Add more mappings as needed
                );

                foreach ($files as $file) {
                    // Exclude dot files and index files
                    if ($file != '.' && $file != '..' && !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $notAllowedExtensions)) {
                        $path = $directory . $file;
                        if (is_dir($path)) {
                            echo '<li><i class="icon-folder"></i><a href="' . $file . '">' . $file . '</a>';
                            echo '<ul class="folder-contents">';
                            $subfiles = scandir($path);
                            foreach ($subfiles as $subfile) {
                                if ($subfile != '.' && $subfile != '..') {
                                    $extension = strtolower(pathinfo($subfile, PATHINFO_EXTENSION));
                                    $iconClass = isset($iconMapping[$extension]) ? $iconMapping[$extension] : 'icon-default'; // Default to 'icon-default' if no mapping found
                                    echo '<li><i class="' . $iconClass . '"></i><a href="' . $file . '/' . $subfile . '">' . $subfile . '</a></li>';
                                }
                            }
                            echo '</ul></li>';
                        } else {
                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $iconClass = isset($iconMapping[$extension]) ? $iconMapping[$extension] : 'icon-default'; // Default to 'icon-default' if no mapping found
                            echo '<li><i class="' . $iconClass . '"></i><a href="' . $file . '">' . $file . '</a></li>';
                        }
                    }
                }
            ?>
        </ul>
    </div>
</body>
</html>
