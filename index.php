<?php
    // Get the current URL
    $currentUrl = $_SERVER['REQUEST_URI'];

    // Split the URL by "/" and get the last segment
    $urlSegments = explode('/', rtrim($currentUrl, '/'));
    $lastSegment = end($urlSegments);

    // Capitalize the first letter of the last segment
    $headerTitle = ucfirst($lastSegment);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title><?php echo $headerTitle; ?></title>
    <style>
        body {
            font-family: "Font Awesome 5 Free", Arial, sans-serif;
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
        }
        a {
            color: white;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        ul.folder-contents {
            margin-left: 20px; /* Indent folder contents */
        }
        ul.subfolder-contents{
            display: none; /* Start folded (hidden) */
        }
        .file-size {
            float: right;
            padding: 0 15px;
            color: whitesmoke;
        }
        /* Common styles for all icons */
        i {
            font-family: "Font Awesome 5 Free";
            font-style: normal;
            margin-right: 15px;
            margin-left: 5px;
        }
        /* Default fallback icon style */
        .icon-default::before {
            content: "\f15b"; /* Unicode character code for the "file-alt" icon in Font Awesome */
            color: white;
        }
        /* Folder icon style */
        .icon-folder-open::before {
            content: "\f07c";
            color: #428bca;
            cursor: pointer;
        }
        .icon-folder-closed::before {
            content: "\f07b";
            color: #428bca;
            cursor: pointer;
        }
        /* Icons for specific file types mapping */
        /* Documents */
        .icon-pdf::before,
        .icon-doc::before,
        .icon-docx::before,
        .icon-ppt::before,
        .icon-pptx::before,
        .icon-xls::before,
        .icon-xlsx::before,
        .icon-csv::before,
        .icon-rtf::before {
            content: "\f1c1"; /* PDF document, Word document, PowerPoint presentation, Excel spreadsheet, CSV file, Rich Text Format */
            color: #FF9800;
        }
        .icon-txt::before,
        .icon-md::before {
            content: "\f15c"; /* Text document, Markdown document */
            color: #4CAF50;
        }
        /* Compressed Files */
        .icon-zip::before,
        .icon-rar::before,
        .icon-tar::before,
        .icon-gz::before {
            content: "\f1c6"; /* ZIP archive, RAR archive (similar to ZIP) */
            color: #E91E63;
        }
        /* Executable/Scripts */
        .icon-sh::before,
        .icon-bat::before,
        .icon-exe::before,
        .icon-ps1::before,
        .icon-py::before,
        .icon-js::before,
        .icon-php::before,
        .icon-java::before,
        .icon-cpp::before,
        .icon-rb::before,
        .icon-c::before {
            content: "\f017"; /* Shell script, Batch script, Executable file, PowerShell script, Python script, JavaScript code, PHP script, Java source code, C++ source code, Ruby script, C source code */
            color: gray; 
        }
        /* Media Files */
        .icon-avi::before,
        .icon-wmv::before,
        .icon-mov::before,
        .icon-mp4::before,
        .icon-flv::before,
        .icon-webm::before {
            content: "\f03d"; /* AVI video, WMV video, MOV video, MP4 video, FLV video, WebM video */
            color: #E91E63;
        }
        .icon-mp3::before,
        .icon-ogg::before,
        .icon-wav::before,
        .icon-flac::before,
        .icon-aac::before,
        .icon-wma::before,
        .icon-m4a::before,
        .icon-ape::before {
            content: "\f001"; /* MP3 audio, OGG audio/video, WAV audio, FLAC audio, AAC audio, WMA audio, M4A audio, APE audio */
            color: #FF9800;
        }
        .icon-jpg::before,
        .icon-jpeg::before,
        .icon-png::before,
        .icon-gif::before,
        .icon-bmp::before,
        .icon-svg::before,
        .icon-tiff::before,
        .icon-webp::before {
            content: "\f1c5"; /* JPEG image, PNG image, GIF image, BMP image, SVG image, TIFF image, WebP image */
            color: #FF9800;
        }
        .icon-psd::before,
        .icon-ai::before,
        .icon-eps::before,
        .icon-indd::before,
        .icon-raw::before,
        .icon-mkv::before {
            content: "\f03d"; /* Adobe Photoshop document, Adobe Illustrator document, EPS vector image, Adobe InDesign document, RAW image, MKV video (similar to other video formats) */
            color: #FF9800;
        }
        /* Add more mappings as needed */
    </style>
</head>
<body>
    <header>
        <h1>List of <?php echo $headerTitle; ?></h1>
    </header>
    <div class="content">
        <ul>
            <?php
                function listDirectory($directory) {
                    $files = scandir($directory);

                    // Create an array to store the file extensions
                    $notAllowedExtensions = array('php', 'swp');

                    // Create a mapping of file extensions to CSS icons
                    $iconMapping = array(
                        // Documents
                        'pdf' => 'icon-pdf',     // PDF document
                        'doc' => 'icon-doc',     // Microsoft Word document
                        'docx' => 'icon-doc',    // Microsoft Word document
                        'txt' => 'icon-txt',     // Text document
                        'md' => 'icon-md',       // Markdown document
                        'ppt' => 'icon-ppt',     // PowerPoint presentation
                        'pptx' => 'icon-ppt',    // PowerPoint presentation
                        'xls' => 'icon-xls',     // Excel spreadsheet
                        'xlsx' => 'icon-xls',    // Excel spreadsheet
                        'csv' => 'icon-csv',     // CSV file
                        'rtf' => 'icon-rtf',     // Rich Text Format
                        // Compressed Files
                        'zip' => 'icon-zip',     // ZIP archive
                        'tar' => 'icon-tar',     // TAR archive
                        'gz' => 'icon-gz',       // GZ archive
                        'rar' => 'icon-rar',     // RAR archive
                        // Executable/Scripts
                        'sh' => 'icon-sh',       // Shell script
                        'bat' => 'icon-bat',     // Batch script
                        'exe' => 'icon-exe',     // Executable file
                        'ps1' => 'icon-ps1',     // PowerShell script
                        'py' => 'icon-py',       // Python script
                        'js' => 'icon-js',       // JavaScript code
                        'php' => 'icon-php',     // PHP script
                        'java' => 'icon-java',   // Java source code
                        'cpp' => 'icon-cpp',     // C++ source code
                        'rb' => 'icon-rb',       // Ruby script
                        'c' => 'icon-c',         // C source code
                        'css' => 'icon-css',    // CSS stylesheet
                        'jsx' => 'icon-jsx',    // JSX (React) code
                        'html' => 'icon-html',   // HTML markup
                        // Media Files
                        'avi' => 'icon-avi',       // AVI video
                        'mp3' => 'icon-mp3',       // MP3 audio
                        'jpg' => 'icon-jpg',       // JPEG image
                        'jpeg' => 'icon-jpg',      // JPEG image
                        'png' => 'icon-png',       // PNG image
                        'gif' => 'icon-gif',       // GIF image
                        'bmp' => 'icon-bmp',       // BMP image
                        'svg' => 'icon-svg',       // SVG image
                        'wmv' => 'icon-wmv',       // WMV video
                        'mov' => 'icon-mov',       // MOV video
                        'mp4' => 'icon-mp4',       // MP4 video
                        'flv' => 'icon-flv',       // FLV video
                        'webm' => 'icon-webm',     // WebM video
                        'ogg' => 'icon-ogg',       // OGG audio/video
                        'wav' => 'icon-wav',       // WAV audio
                        'flac' => 'icon-flac',     // FLAC audio
                        'aac' => 'icon-aac',       // AAC audio
                        'wma' => 'icon-wma',       // WMA audio
                        'm4a' => 'icon-m4a',       // M4A audio
                        'ogg' => 'icon-ogg',       // OGG audio
                        'psd' => 'icon-psd',       // Adobe Photoshop document
                        'ai' => 'icon-ai',         // Adobe Illustrator document
                        'eps' => 'icon-eps',       // EPS vector image
                        'indd' => 'icon-indd',     // Adobe InDesign document
                        'raw' => 'icon-raw',       // RAW image
                        'tiff' => 'icon-tiff',     // TIFF image
                        'webp' => 'icon-webp',     // WebP image
                        'mkv' => 'icon-mkv',       // MKV video
                        'flac' => 'icon-flac',     // FLAC audio
                        'ape' => 'icon-ape',       // APE audio
                        'ogg' => 'icon-ogg',       // OGG audio
                        // Add more mappings as needed
                    );                    

                    echo '<ul class="folder-contents">';

                    foreach ($files as $file) {
                        // Exclude dot files and index files
                        if ($file != '.' && $file != '..' && !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $notAllowedExtensions)) {
                            $path = $directory . '/' . $file;

                            if (is_dir($path)) {
                                $itemCount = countItemsInDirectory($path); // Count the number of items (files and subfolders) in the current folder

                                echo '<li style="color: whitesmoke;">';
                                echo '<i class="icon-folder-closed" onclick="toggleFolderContents(this)"></i>' . $file . ' <span class="file-size">' . $itemCount . ' item(s)</span>';
                                echo '<ul class="subfolder-contents">'; // Open a new subfolder list
                                listDirectory($path); // Recursively list contents of subfolder
                                echo '</ul>'; // Close the subfolder list
                                echo '</li>';

                            } else {
                                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                $iconClass = isset($iconMapping[$extension]) ? $iconMapping[$extension] : 'icon-default'; // Default to 'icon-default' if no mapping found
                                $filePath = $directory . '/' . $file;
                                $fileSize = formatFileSize(filesize($filePath)); // Get and format the file size

                                echo '<li style="border-bottom: 1px solid #1a1b1a;">';
                                echo '<i class="' . $iconClass . '"></i><a href="' . $directory . '/' . $file . '">' . $file . '</a> <span class="file-size">' . $fileSize . '</span>';
                                echo '</li>';
                            }
                        }
                    }

                    echo '</ul>';
                }
                
                function countItemsInDirectory($directory) {
                    $items = scandir($directory);
                    $count = 0;

                    foreach ($items as $item) {
                        // Exclude dot files and index files
                        if ($item != '.' && $item != '..') {
                            $path = $directory . '/' . $item;
                            if (is_dir($path)) {
                                $count++; // Increment the count for subfolders
                                $count += countItemsInDirectory($path); // Recursively count items in subfolder

                            } else {
                                $count++; // Increment the count for files
                            }
                        }
                    }
                    return $count;
                }

                function formatFileSize($size) {
                    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

                    for ($i = 0; $size > 1024; $i++) {
                        $size /= 1024;
                    }
                    return round($size, 2) . ' ' . $units[$i];
                }

                $directory = './'; // Specify the directory you want to list
                listDirectory($directory);
            ?>
        </ul>
    </div>
</body>
<script>
    function toggleFolderContents(icon) {
        var ul = icon.parentElement.querySelector('.subfolder-contents');
        if (ul.style.display === 'block') {
            ul.style.display = 'none';
            icon.classList.remove('icon-folder-open');
            icon.classList.add('icon-folder-closed');
        } else {
            ul.style.display = 'block';
            icon.classList.remove('icon-folder-closed');
            icon.classList.add('icon-folder-open');
        }
    }
</script>
</html>
