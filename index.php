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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Dynamic page title with capitalized folder name -->
    <title><?php echo $headerTitle; ?></title>
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
            font-family: "Font Awesome 6 Free";
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
        /* Generated CSS for file extension icons */
        /* Documents */
        .icon-pdf::before {
            content: "\f1c1"; /* PDF document */
            color: #FF5733; /* Reddish-Orange */
        }

        .icon-doc::before,
        .icon-docx::before {
            content: "\f15c"; /* File Alt (for Word documents) */
            color: #3498db; /* Blue */
        }

        .icon-txt::before {
            content: "\f0f6"; /* File Alt (for Text documents) */
            color: #2ecc71; /* Green */
        }

        .icon-md::before {
            content: "\f15c"; /* File Alt (for Markdown documents) */
            color: #2ecc71; /* Green */
        }

        .icon-ppt::before,
        .icon-pptx::before {
            content: "\f1c4"; /* PowerPoint presentation */
            color: #e74c3c; /* Red */
        }

        .icon-xls::before,
        .icon-xlsx::before {
            content: "\f1c3"; /* Excel spreadsheet */
            color: #f1c40f; /* Yellow */
        }

        .icon-csv::before {
            content: "\f1c0"; /* CSV file */
            color: #f39c12; /* Orange */
        }

        .icon-rtf::before {
            content: "\f15c"; /* File Alt (for RTF documents) */
            color: #9b59b6; /* Purple */
        }

        /* Compressed Files */
        .icon-zip::before {
            content: "\f1c6"; /* Archive */
            color: #1abc9c; /* Turquoise */
        }

        .icon-tar::before {
            content: "\f1c6"; /* Archive (similar to ZIP) */
            color: #1abc9c; /* Turquoise */
        }

        .icon-gz::before {
            content: "\f1c6"; /* Archive (similar to ZIP) */
            color: #1abc9c; /* Turquoise */
        }

        .icon-rar::before {
            content: "\f1c6"; /* Archive (similar to ZIP) */
            color: #1abc9c; /* Turquoise */
        }

        /* Executable/Scripts */
        .icon-sh::before {
            content: "\f02e"; /* Terminal */
            color: #f39c12; /* Orange */
        }

        .icon-bat::before {
            content: "\f02e"; /* Terminal */
            color: #f39c12; /* Orange */
        }

        .icon-exe::before {
            content: "\f17b"; /* Cog */
            color: #f39c12; /* Orange */
        }

        .icon-ps1::before {
            content: "\f02e"; /* Terminal */
            color: #f39c12; /* Orange */
        }

        .icon-py::before {
            content: "\f02d"; /* Python */
            color: #3498db; /* Blue */
        }

        .icon-js::before {
            content: "\f023"; /* Code */
            color: #f39c12; /* Orange */
        }

        .icon-php::before {
            content: "\f069"; /* PHP */
            color: #f39c12; /* Orange */
        }

        .icon-java::before {
            content: "\f0b1"; /* Coffee */
            color: #f39c12; /* Orange */
        }

        .icon-cpp::before {
            content: "\f0b2"; /* Code Branch */
            color: #f39c12; /* Orange */
        }

        .icon-rb::before {
            content: "\f178"; /* Gem */
            color: #f39c12; /* Orange */
        }

        .icon-c::before {
            content: "\f1c3"; /* Cogs */
            color: #f39c12; /* Orange */
        }

        .icon-css::before {
            content: "\f219"; /* CSS3 */
            color: #e74c3c; /* Red */
        }

        .icon-jsx::before {
            content: "\f288"; /* React */
            color: #f39c12; /* Orange */
        }

        .icon-html::before {
            content: "\f13b"; /* Html5 */
            color: #e74c3c; /* Red */
        }

        /* Media Files */
        .icon-avi::before,
        .icon-wmv::before,
        .icon-mov::before,
        .icon-mp4::before,
        .icon-flv::before,
        .icon-webm::before {
            content: "\f008"; /* Film */
            color: #e74c3c; /* Red */
        }

        .icon-mp3::before,
        .icon-ogg::before,
        .icon-wav::before,
        .icon-flac::before,
        .icon-aac::before,
        .icon-wma::before,
        .icon-m4a::before,
        .icon-ape::before {
            content: "\f28b"; /* Music */
            color: #2ecc71; /* Green */
        }

        .icon-jpg::before,
        .icon-jpeg::before,
        .icon-png::before,
        .icon-gif::before,
        .icon-bmp::before,
        .icon-svg::before,
        .icon-tiff::before,
        .icon-webp::before {
            content: "\f03e"; /* Image */
            color: #e74c3c; /* Red */
        }

        .icon-psd::before,
        .icon-ai::before,
        .icon-eps::before,
        .icon-indd::before,
        .icon-raw::before,
        .icon-tiff::before,
        .icon-webp::before,
        .icon-mkv::before,
        .icon-ape::before {
            content: "\f03d"; /* Image */
            color: #e74c3c; /* Red */
        }

        /* Add more CSS rules for other file extensions and their colors here */
    </style>
</head>
<body>
    <header>
        <!-- Dynamic header title with capitalized folder name -->
        <h1>List of <?php echo $headerTitle; ?></h1>
    </header>
    <div class="content">
        <ul>
            <?php
            // Define custom error messages
            $errorMessages = [
                'directory_not_found' => 'The specified directory does not exist.',
                'directory_permission_denied' => 'Permission denied. You do not have access to this directory.',
                'file_not_found' => 'The requested file does not exist.',
                'file_permission_denied' => 'Permission denied. You do not have access to this file.',
            ];
            function listDirectory($directory) {
                try{
                    // Attempt to scan the directory
                    $files = scandir($directory);

                    // Check if scandir succeeded
                    if ($files === false) {
                        throw new Exception($errorMessages['directory_not_found']);
                    }

                    // Check if the directory is not readable (permission denied)
                    if (!is_readable($directory)) {
                        throw new Exception($errorMessages['directory_permission_denied']);
                    }

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
                                
                                if (!file_exists($filePath)) {
                                    throw new Exception($errorMessages['file_not_found']);
                                }

                                try {
                                    $fileSize = formatFileSize(filesize($filePath)); // Get and format the file size

                                    if ($fileSize === false) {
                                        throw new Exception($errorMessages['file_permission_denied']);
                                    }

                                } catch (Exception $e) {
                                    /// Handle the filesize() error
                                    echo '<li style="border-bottom: 1px solid #1a1b1a;">';
                                    echo '<i class="' . $iconClass . '"></i>' . $file . ' <span class="file-size">' . $e->getMessage() . '</span>';
                                    echo '</li>';
                                }

                                echo '<li style="border-bottom: 1px solid #1a1b1a;">';
                                echo '<i class="' . $iconClass . '"></i><a href="' . $directory . '/' . $file . '">' . $file . '</a> <span class="file-size">' . $fileSize . '</span>';
                                echo '</li>';
                            }
                        }
                    }

                    echo '</ul>';

                } catch (Exception $e) {
                    // Handle the scandir() error
                    echo '<p style="color: red;">' . $e->getMessage() . '</p>';
                }

            }
                
            // Folder content count
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

            // Human readable file sizes
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
    // Javascript to toggle folder contents visibility
    function toggleFolderContents(icon) {
        // Find the subfolder contents list associated with the clicked folder icon
        var ul = icon.parentElement.querySelector('.subfolder-contents');
        // Check if the subfolder contents list is currently visible
        if (ul.style.display === 'block') {
            // If visible, hide it
            ul.style.display = 'none';
            icon.classList.remove('icon-folder-open');
            icon.classList.add('icon-folder-closed');
        } else {
            // If hidden, show it
            ul.style.display = 'block';
            icon.classList.remove('icon-folder-closed');
            icon.classList.add('icon-folder-open');
        }
    }
</script>
</html>
