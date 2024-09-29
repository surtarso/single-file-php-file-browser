<!DOCTYPE html>
<html lang="en">

<!-- ----------------- GLOBAL VARIABLES ------------------- -->
<?php 
    // Define a default title if there is none from URL
    $defaultTitle = "Stuff";  // "My default title"

    // These extensions will NOT show up in file listings
    $notAllowedExtensions = array('php', 'swp');

    // Allowed extensions to be uploaded by users
    // -- Image types
    // $allowedUploadTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff');
    // -- Video types
    // $allowedUploadTypes = array('mp4', 'mov', 'avi', 'mkv', 'webm', 'flv', 'wmv');
    // -- Audio types
    // $allowedUploadTypes = array('mp3', 'wav', 'flac', 'ogg', 'aac', 'm4a');
    // -- Document types
    // $allowedUploadTypes = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf');
    // -- Compressed types
    $allowedUploadTypes = array('zip', 'rar', 'tar', 'gz', '7z');

    // You can also combine some type arrays like this:
    // $allowedUploadTypes = array_merge(
    //     array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff'),  // images
    //     array('mp4', 'mov', 'avi', 'mkv', 'webm', 'flv', 'wmv')     // videos
    // );

    // File that stores users credentials
    // If changed here, it also needs to be changed in 'create-user' script
    // Global variable: $credentialsFile=".users" and vice-versa
    $credentialsFile = './.users';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- fontawesome css link, update as you see fit -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- placeholder tab icon (favicon) so its not empty -->
    <link rel="shortcut icon" type="image/x-icon" href="data:image/x-icon;base64,c2t1bGw">
    <!-- Dynamic page tab title with capitalized folder name or default title -->
    <?php
        # Extracts the folder name from the URL, capitalizes it, and uses it for the page title and header.
        # If no folder is found, falls back to the default title set in '$defaultTitle'.
        # Examples:
        #   - mydomain.com/myfolder -> Title: Myfolder
        #   - mydomain.com -> Title: $defaultTitle

        // Get the current URL
        $currentUrl = $_SERVER['REQUEST_URI'];

        // Split the URL by "/" and get the last segment
        $urlSegments = explode('/', rtrim($currentUrl, '/'));
        $lastSegment = end($urlSegments);
    
        // Capitalize the first letter of the last segment
        $headerTitle = ucfirst($lastSegment);
    ?>
    <title><?php echo isset($headerTitle) && !empty($headerTitle) ? $headerTitle : $defaultTitle; ?></title>
    <style>
        html, body {font-family: Arial, sans-serif; font-style: normal; margin: 0; padding: 0; background-color: #1a1b1a; height: 100%;}
        header {background-color: #333; color: whitesmoke; text-align: center; padding: 20px 0;}
        h1 {margin: 0; font-size: 32px;}
        footer {position: fixed; bottom: 0; left: 0; right: 0; text-align: center; background-color: #333; color: whitesmoke; padding: 0; font-size: 10px;}
        /* --------------- MAIN CONTENT BOX (for upload form and files/folders display) --------------- */
        .content {max-width: 800px; margin: 5px auto; padding: 10px; background-color: #333; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);}
        ul {list-style-type: none; padding: 0;}
        li {padding: 10px 0;} /* line distance */
        a {color: whitesmoke; text-decoration: none;}
        a:hover {text-decoration: underline;}
        /* Indent folder contents and default icon colors*/
        ul.folder-contents {margin-left: 20px; color: mediumpurple;}
        /* Start folded (hidden) */
        ul.subfolder-contents{display: none;}
        .file-size {float: right; padding: 0 20px; color: whitesmoke;}
        .catchScanErrorMessage {color: red;}
        /* --------------- UPLOAD FORM CONTAINER --------------- */
        #uploadContainer {display: flex; flex-direction: row-reverse; padding: 10px; margin-top: 5px;}
        #uploadContainer form {margin-right: 20px;}
        /* username and password fields */
        input[type="text"] {border-radius: 5px; margin: 0 4px; width: 90px;}
        input[type="password"] {border-radius: 5px; width: 90px;}
        /* browse button */
        input[type="file"] {cursor: pointer; padding: 0 4px; width: 68px;}
        /* upload button */
        button[type="submit"] {cursor: pointer;}
        /* upload related messages */
        .uploadOkMessage, .uploadErrorMessage {display: flex; justify-content: left; margin: 2px 20px; height: 20px;}
        .uploadOkMessage p {color: green; padding: 2px; margin: 0;}
        .uploadErrorMessage p {color: red; padding: 2px; margin: 0;}
        /* selected files list */
        #selectedFilesContainer {display: none;}
        #selectedFilesList {display: block; list-style: square; padding: 0;}
        #selectedFilesList li {margin: 0px 40px; padding: 0 20px 2px 10px; color: whitesmoke;}
        /* --------------- DOWNLOAD RELATED STUFF --------------- */
        /* #downloadControls {margin-left: 20px; padding-bottom: 0;} */
        #downloadControls {display: flex; align-items: center; color: whitesmoke; min-height: 25px; margin-top: 10px;}
        /* download selected button */
        #downloadSelected {cursor: pointer; display: none; margin-left: auto; margin-right: 20px;}
        #toggleCheckboxes {cursor: pointer; margin-left: 24px; margin-right: 7px;}
        #toggleAllCheckboxes {cursor: pointer; margin-left: 12px;}
        /* color of folder names */
        #folderNames {display: inline; color: orange;}
        /* this is the filename/folder row in main view */
        .filesRow {border-bottom: 1px solid #1a1b1a;}
        .filesRow a {margin-left: 4px;}
        /* start checkboxes hidden */
        #selectAllCheckbox {display: none; cursor: pointer;}
        #folderCheckbox, #fileCheckbox {cursor: pointer; margin-right: 8px;/*display:none; is echoed at runtime */}
        /* -------------- COMMON STYLES FOR ALL ICONS --------------- */
        i {font-family: "Font Awesome 6 Free"; font-size: 18px; font-style: normal; margin-right: 8px; margin-left: 4px; cursor: pointer;}
        /* Default fallback style "file-alt" icon in Font Awesome*/
        .icon-default::before {content: "\f15b"; color: white;}
        /* Folder icon style */
        .icon-folder-open::before {content: "\f07c"; color: #428bca; cursor: pointer;}
        .icon-folder-closed::before {content: "\f07b"; color: #428bca; cursor: pointer;}
        /* ---------------------- ICON COLORS ----------------------- */
        .fa-file-lines, .fa-file-image, .fa-android { color: #2ecc71; } /* Green */
        .fa-file-pdf, .fa-windows, .fa-debian { color: #e74c3c; }      /* Red */
        .fa-file-powerpoint, .fa-box-archive { color: #e67e22; }       /* Orange */
        .fa-terminal, .fa-compass-drafting { color: #34495e; }         /* Dark Gray */
        .fa-file-word, .fa-file-video { color: #3498db; }         /* Blue */
        .fa-file-code, .fa-file-audio { color: #9b59b6; }         /* Purple */
        .fa-compact-disc, .fa-book { color: #f39c12; }      /* Amber */
        .fa-apple, .fa-linux { color: #000000; }            /* Black */
        .fa-file-excel { color: #27ae60; }        /* Dark Green */
        .fa-file-csv { color: #f1c40f; }          /* Yellow */
        .fa-file-alt { color: #95a5a6; }          /* Light Gray */  
        .fa-hdd { color: #2980b9; }               /* Dark Blue */
        .fa-redhat { color: #c0392b; }            /* Dark Red */
        .fa-box { color: #f0932b; }               /* Bright Orange */
        .fa-file-zipper { color: #d35400; }       /* Dark Orange */
        .fa-coffee { color: #6f4e37; }            /* Coffee Brown */
        .fa-database { color: #16a085; }          /* Teal */
        .fa-font { color: #e91e63; }              /* Pink */
        .fa-cube { color: #1abc9c; }              /* Turquoise */
    </style>
</head>
<body>
    <!-- --------------- top title header section --------------- -->
    <header>
        <!-- Dynamic header title with capitalized folder name or default title -->
        <h1>List of <?php echo isset($headerTitle) && !empty($headerTitle) ? $headerTitle : $defaultTitle; ?></h1>
    </header>

    <!-- ---------------------------------  UPLOAD SECTION ----------------------------------- -->
    <?php
        // Check if the .users file exists
        if (file_exists($credentialsFile)) {
            // The file exists, so show the upload form section
            echo '<div class="content" id="uploadContainer">';
            echo '<form action="" method="POST" enctype="multipart/form-data">';
            echo '<input type="text" name="username" placeholder="Username">';
            echo '<input type="password" name="password" placeholder="Password">';
            echo '<input type="file" name="files[]" multiple id="browseButton">';
            echo '<button type="submit">Upload</button> </form> </div>';
        
            // show list of selected files for upload, if any
            echo '<div class="content" id="selectedFilesContainer"><ul id="selectedFilesList"></ul></div>';
        
            // Server upload logic
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Read users from the file
                $users = file_get_contents($credentialsFile);
                $usersArray = explode("\n", $users);

                // Check if the user exists
                $userExists = false;
                foreach ($usersArray as $user) {
                    $userParts = explode(':', $user);

                    // passwords in hash
                    if ($userParts[0] === $_POST['username']) {
                        $storedHash = $userParts[1];
            
                        // Verify the password using password_verify
                        if (password_verify($_POST['password'], $storedHash)) {
                            $userExists = true;
                            break;
                        }
                    }
                }

                // upload messages container
                echo '<div class="content">';
                if ($userExists) { // Authenticated, proceed with upload
                    foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                        $fileName = $_FILES['files']['name'][$key];
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        if (in_array($fileExtension, $allowedUploadTypes)) {
                            move_uploaded_file($tmpName, $fileName);
                            // success message
                            echo '<div class="uploadOkMessage"><p>File ' . $fileName . ' uploaded successfully.</p></div>';
                        } else {
                            // bad extension error
                            echo '<div class="uploadErrorMessage"><p>Invalid file type: ' . $fileName . '</p></div>';
                        }
                    }
                } else {
                    // Invalid username or password message
                    echo '<div class="uploadErrorMessage"><p>Invalid username or password.</p></div>';
                }
                echo '</div>';
            }
        }
    ?>

    <!----------------------------------- MULTIPLE DOWNLOADS SECTION (buttons)  ----------------------->
    <div class="content"> 
        <div id="downloadControls">
            <!-- 'Select...' checkbox button -->
            <input type="checkbox" id="toggleCheckboxes">
            <label for="toggleCheckboxes">Select...</label>
            <div id="selectAllCheckbox">
                <!-- 'All' checkbox button -->
                <input type="checkbox" id="toggleAllCheckboxes">
                <label for="toggleAllCheckboxes">All</label>
            </div>
            <!-- 'Download Selected' button -->
            <button id="downloadSelected">Download Selected</button>
        </div>

    <!------------------------------- FILES AND FOLDERS VIEW (main content part) ---------------------->
        <ul>
            <?php
            // Define custom error messages
            $errorMessages = [
                'directory_not_found' => 'The specified directory does not exist.',
                'directory_permission_denied' => 'Permission denied. You do not have access to this directory.',
                'file_not_found' => 'The requested file does not exist.',
                'file_permission_denied' => 'Permission denied. You do not have access to this file.',
            ];

            // Main function to list files and folders
            function listDirectory($directory) {
                global $errorMessages;
                try{
                    // Attempt to scan the directory
                    $files = scandir($directory);

                    // Error check: Check if scandir succeeded
                    if ($files === false) {
                        throw new Exception($errorMessages['directory_not_found']);
                    }
                    // Error check: Check if the directory is not readable (permission denied)
                    if (!is_readable($directory)) {
                        throw new Exception($errorMessages['directory_permission_denied']);
                    }

                    // Array of unwanted file extensions that will not show up in list
                    global $notAllowedExtensions;

                    // Array mapping of file extensions to CSS classes
                    // This will map the file extensions found into fontawesome icons
                    $iconMapping = array(
                        // Documents
                        'pdf' => 'fa-file-pdf',        // PDF document
                        'doc' => 'fa-file-word',       // Microsoft Word document
                        'docx' => 'fa-file-word',      // Microsoft Word document
                        'txt' => 'fa-file-lines',      // Text document
                        'md' => 'fa-solid fa-file-code',        // Markdown document
                        'ppt' => 'fa-file-powerpoint', // PowerPoint presentation
                        'pptx' => 'fa-file-powerpoint',// PowerPoint presentation
                        'xls' => 'fa-file-excel',      // Excel spreadsheet
                        'xlsx' => 'fa-file-excel',     // Excel spreadsheet
                        'csv' => 'fa-file-csv',        // CSV file
                        'rtf' => 'fa-file-alt',        // Rich Text Format
                        'odt' => 'fa-file-word',       // OpenDocument Text
                        'ods' => 'fa-file-excel',      // OpenDocument Spreadsheet
                        'odp' => 'fa-file-powerpoint', // OpenDocument Presentation
                        'pages' => 'fa-file-alt',      // Apple Pages document
                        'numbers' => 'fa-file-excel',  // Apple Numbers spreadsheet
                        'key' => 'fa-file-powerpoint', // Apple Keynote presentation
                        // Compressed Files
                        'zip' => 'fa-file-zipper',     // ZIP archive
                        'tar' => 'fa-solid fa-box-archive',    // TAR archive
                        'gz' => 'fa-solid fa-box-archive',     // GZ archive
                        'rar' => 'fa-solid fa-box-archive',    // RAR archive
                        '7z' => 'fa-solid fa-box-archive',     // 7Z archive
                        '7za' => 'fa-solid fa-box-archive',    // 7ZA archive
                        'xz' => 'fa-solid fa-box-archive',     // XZ archive
                        'bz2' => 'fa-solid fa-box-archive',    // BZ2 archive
                        'lz' => 'fa-solid fa-box-archive',     // LZ archive
                        'z' => 'fa-solid fa-box-archive',      // Z archive
                        // Disk Images
                        'iso' => 'fa-solid fa-compact-disc',    // ISO disk image
                        'dmg' => 'fa-solid fa-compact-disc',    // Apple Disk Image
                        'img' => 'fa-solid fa-compact-disc',    // Disk Image file
                        'vmdk' => 'fa-hard-drive',     // Virtual Machine Disk
                        'vdi' => 'fa-hard-drive',      // VirtualBox Disk Image
                        'qcow2' => 'fa-hard-drive',    // QEMU Copy On Write image
                        // Executable/Scripts
                        'sh' => 'fa-solid fa-terminal',         // Shell script
                        'bat' => 'fa-solid fa-file-code',       // Batch script
                        'exe' => 'fa-brands fa-windows',        // Executable file
                        'ps1' => 'fa-solid fa-file-code',       // PowerShell script
                        'py' => 'fa-solid fa-file-code',        // Python script
                        'js' => 'fa-solid fa-file-code',        // JavaScript code
                        'php' => 'fa-solid fa-file-code',       // PHP script
                        'java' => 'fa-solid fa-file-code',      // Java source code
                        'cpp' => 'fa-solid fa-file-code',       // C++ source code
                        'rb' => 'fa-solid fa-file-code',        // Ruby script
                        'c' => 'fa-solid fa-file-code',         // C source code
                        'css' => 'fa-solid fa-file-code',       // CSS stylesheet
                        'jsx' => 'fa-solid fa-file-code',       // JSX (React) code
                        'html' => 'fa-solid fa-file-code',      // HTML markup
                        'app' => 'fa-solid fa-file-code',       // macOS Application Bundle
                        'jar' => 'fa-brands fa-java',           // Java Archive
                        'pl' => 'fa-solid fa-file-code',        // Perl script
                        'r' => 'fa-solid fa-file-code',         // R script
                        'go' => 'fa-solid fa-file-code',        // Go source code
                        'rs' => 'fa-solid fa-file-code',        // Rust source code
                        'asm' => 'fa-solid fa-file-code',       // Assembly language source code
                        // Linux Packages
                        'deb' => 'fa-brands fa-debian',          // Debian package
                        'rpm' => 'fa-brands fa-redhat',          // Red Hat package
                        'pkg' => 'fa-solid fa-box',              // macOS Installer package
                        'appimage' => 'fa-brands fa-linux',      // Linux AppImage
                        'snap' => 'fa-brands fa-linux',          // Linux Snap package
                        'flatpak' => 'fa-brands fa-linux',       // Linux Flatpak package
                        // Media Files - Video
                        'avi' => 'fa-file-video',      // AVI video
                        'wmv' => 'fa-file-video',      // WMV video
                        'mov' => 'fa-file-video',      // MOV video
                        'mp4' => 'fa-file-video',      // MP4 video
                        'flv' => 'fa-file-video',      // FLV video
                        'webm' => 'fa-file-video',     // WebM video
                        'mkv' => 'fa-file-video',      // MKV video
                        'mxf' => 'fa-file-video',      // MXF video
                        // Media Files - Audio
                        'mp3' => 'fa-file-audio',      // MP3 audio
                        'ogg' => 'fa-file-audio',      // OGG audio
                        'wav' => 'fa-file-audio',      // WAV audio
                        'flac' => 'fa-file-audio',     // FLAC audio
                        'aac' => 'fa-file-audio',      // AAC audio
                        'wma' => 'fa-file-audio',      // WMA audio
                        'm4a' => 'fa-file-audio',      // M4A audio
                        'ape' => 'fa-file-audio',      // APE audio
                        'aaf' => 'fa-file-audio',      // AAF audio
                        // Media Files - Image
                        'jpg' => 'fa-file-image',      // JPEG image
                        'jpeg' => 'fa-file-image',     // JPEG image
                        'png' => 'fa-file-image',      // PNG image
                        'gif' => 'fa-file-image',      // GIF image
                        'bmp' => 'fa-file-image',      // BMP image
                        'svg' => 'fa-file-image',      // SVG image
                        'psd' => 'fa-file-image',      // Adobe Photoshop document
                        'ai' => 'fa-file-image',       // Adobe Illustrator document
                        'eps' => 'fa-file-image',      // EPS vector image
                        'indd' => 'fa-file-alt',       // Adobe InDesign document
                        'raw' => 'fa-file-image',      // RAW image
                        'tiff' => 'fa-file-image',     // TIFF image
                        'webp' => 'fa-file-image',     // WebP image
                        'kra' => 'fa-file-image',      // Krita document
                        // eBooks
                        'mobi' => 'fa-solid fa-book',  // MOBI document
                        'epub' => 'fa-solid fa-book',  // EPUB document
                        // Database Files
                        'sql' => 'fa-solid fa-database',        // SQL database
                        'db' => 'fa-solid fa-database',         // Database file
                        'dbf' => 'fa-solid fa-database',        // Database file (DBF format)
                        'mdf' => 'fa-solid fa-database',        // SQL Server database file
                        // Font Files
                        'ttf' => 'fa-solid fa-font',            // TrueType Font
                        'otf' => 'fa-solid fa-font',            // OpenType Font
                        'woff' => 'fa-solid fa-font',           // Web Open Font Format
                        'woff2' => 'fa-solid fa-font',          // Web Open Font Format 2
                        // CAD Files
                        'dwg' => 'fa-solid fa-compass-drafting',// AutoCAD Drawing
                        'dxf' => 'fa-solid fa-compass-drafting',// AutoCAD Drawing Exchange Format
                        'step' => 'fa-solid fa-cube',           // STEP 3D model file
                        'stl' => 'fa-solid fa-cube',            // Stereolithography file
                        'obj' => 'fa-solid fa-cube',            // Wavefront OBJ file
                        // 3D Model Files
                        'fbx' => 'fa-solid fa-cube',            // Autodesk FBX
                        'glb' => 'fa-solid fa-cube',            // GLB 3D model
                        'gltf' => 'fa-solid fa-cube',           // GLTF 3D model
                        'blend' => 'fa-solid fa-cube',          // Blender project file
                        // Code Files
                        'csharp' => 'fa-solid fa-file-code',    // C# source code
                        'cs' => 'fa-solid fa-file-code',        // C# source code
                        'vb' => 'fa-solid fa-file-code',        // Visual Basic file
                        'mat' => 'fa-solid fa-file-code',       // MATLAB file
                        'scala' => 'fa-solid fa-file-code',     // Scala source code
                        'hs' => 'fa-solid fa-file-code',        // Haskell source code
                        'erl' => 'fa-solid fa-file-code',       // Erlang source code
                        // Apple Extensions
                        'ipa' => 'fa-brands fa-apple',           // iOS App Store Package
                        'apk' => 'fa-brands fa-android',         // Android Package
                        // Other Important Files
                        'json' => 'fa-solid fa-file-code',      // JSON file
                        'xml' => 'fa-solid fa-file-code',       // XML file
                        'yaml' => 'fa-solid fa-file-code',      // YAML file
                        'yml' => 'fa-solid fa-file-code',       // YAML file
                        'log' => 'fa-file-lines',      // Log file
                    );                    

                    echo '<ul class="folder-contents">';

                    foreach ($files as $file) {
                        // Exclude dot files and index files
                        if ($file != '.' && $file != '..' && !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $notAllowedExtensions) && !preg_match('/^\./', $file)) {
                            # $directory is some kind of path that most-likly ends with a '/', so we need to remove it if its there
                            # Now the path is normalized and we can add the file to it
                            if (substr($directory, -1) == '/') {
                                $directory = substr($directory, 0, -1);
                            }

                            # relative path to the file from this script
                            $relPath = $directory . '/' . $file;

                            # the actual download paths to this file (relative to the server root) might be different though, 
                            # So we need to get the current scripts location and then add the relative path to it ( removing the './' at the beginning )
                            
                            # $currentLocation = dirname($_SERVER['SCRIPT_NAME']);
                            # $dlPath = $currentLocation . substr($relPath, 1); # remove the '.' at the beginning
                            $dlPath = substr($relPath, 1); # remove the '.' at the beginning
            
                            # debugging paths
                            # echo "file: " . $file . "<br>";
                            # echo "relPath: " . $relPath . "<br>";
                            # echo "directory: " . $directory . "<br>";
                            # echo "dlPath: " . $dlPath . "<br>";

                            // ----------------------- HANDLE FOLDERS 
                            if (is_dir($relPath)) {
                                $itemCount = countItemsInDirectory($relPath); // Count the number of items (files and subfolders) in the current folder
                                
                                echo '<li>';
                                # ------------------  multiple downloads (folder checkboxes)  --------------------------
                                echo '<input type="checkbox" name="files[]" value="folder" onclick="toggleFolderContentsCheckbox(this)" style="display: none;" id="folderCheckbox">';
                                # -----------------------------------------------------------------------------------------------
                                // only add hyperlink icon to folder names if the target folder contains an index.php file
                                // multiples copies of this file can be copied to child folders or
                                // you can use any file you want, like index.html for a webpage.
                                if (file_exists($relPath . '/index.php')) {
                                    // --------- folders: icons, names and item counts + hyperlink to folder
                                    echo '<i class="icon-folder-closed" onclick="toggleFolderContents(this)"></i> <p id="folderNames">' . $file . '</p> <a href="' . $relPath . '"> <i class="fas fa-xs fa-external-link-alt"></i> </a> <span class="file-size">' . $itemCount . ' item(s)</span>';
                                } else {
                                    // --------- folders: icons, names and item counts (no external link)
                                    echo '<i class="icon-folder-closed" onclick="toggleFolderContents(this)"></i> <p id="folderNames">' . $file . '</p> <span class="file-size">' . $itemCount . ' item(s)</span>';
                                }

                                // ------ Handle SUBFOLDERS
                                echo '<ul class="subfolder-contents">'; // Open a new subfolder list
                                listDirectory($relPath); // Recursively list contents of subfolder
                                echo '</ul>'; // Close the subfolder list
                                echo '</li>';
                                
                            // ------------------------ HANDLE FILES
                            } else {
                                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                $iconClass = isset($iconMapping[$extension]) ? $iconMapping[$extension] : 'icon-default'; // Default to 'icon-default' if no mapping found
                                $filePath = $directory . '/' . $file;
                                
                                // Error checking
                                if (!file_exists($filePath)) {
                                    throw new Exception($errorMessages['file_not_found']);
                                } try {
                                    $fileSize = formatFileSize(filesize($filePath)); // Get and format the file size

                                    if ($fileSize === false) {
                                        throw new Exception($errorMessages['file_permission_denied']);
                                    }
                                } catch (Exception $e) {
                                    /// Handle the filesize() error
                                    echo '<li class="filesRow">';
                                    echo '<i class="' . $iconClass . '"></i>' . $file . ' <span class="file-size">' . $e->getMessage() . '</span>';
                                    echo '</li>';
                                }
                                
                                // Print -files- to screen
                                echo '<li class="filesRow">';
                                # ------------------  multiple downloads (file checkboxes)  --------------------------
                                echo '<input id="fileCheckbox" type="checkbox" name="files[]" value="' . $dlPath . '" style="display: none;">';
                                # ---------------------  files: icons, names and sizes  ------------------------------
                                echo '<i class="' . $iconClass . '" onclick="getFile(this)"></i><a href="' . $relPath . '" download>' . $file . '</a> <span class="file-size">' . $fileSize . '</span>';
                                echo '</li>';
                                
                            }
                        }
                    }

                    echo '</ul>';

                } catch (Exception $e) {
                    // Handle the scandir() error
                    echo '<p class="catchScanErrorMessage">' . $e->getMessage() . '</p>';
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

            // -------------- START OF THE SCRIPT ----------------------
            // Specify the directory you want to list, defaults to current
            // dir, where the folder index.php is placed is the list root.
            $directory = './';
            listDirectory($directory); // Start the main loop
            // ------------------ END OF PHP ---------------------------
            ?>
        </ul>
    </div>
    <footer>
        <p>
            Single File PHP File Browser by <a href="https://tarsogalvao.ddns.net" target="_blank">Tarso Galvão</a>.
            <a href="https://github.com/surtarso/single-file-php-file-browser" target="_blank">More info</a>.
        </p>
    </footer>
</body>
<script>
    // ------------------------  UI ELEMENTS POINTERS ---------------------------
    const downloadSelectedButton = document.getElementById('downloadSelected');  // 'Download' button
    const toggleCheckboxesInput = document.getElementById('toggleCheckboxes');   // 'Select...' checkbox
    const selectAllCheckboxInput = document.getElementById('selectAllCheckbox'); // 'All' checkbox
 
    // ---------------------------- DOWNLOADS LOGIC ------------------------------
    const downloadQueue = [];
    const currentURL = window.location.href;
    const cleanURL = currentURL.slice(0, -1); // removes the final / from the URL
    // console.log(cleanURL);  // https://www.mydomain.com

    // Function to handle download execution
    function downloadNextFile() {
        if (downloadQueue.length > 0) {
            // console.log(downloadQueue);   // Array [ "/file1.MP4", "/file2.mp3" ]
            const filePath = downloadQueue.shift(); // here is the current file name 

            const link = document.createElement('a');
            link.href = cleanURL + filePath; // URL of the file to download
            link.download = filePath.slice(1); // Filename for the downloaded file

            console.log(`Downloading: ${link.href}`);
            link.click(); // execute the download

            // keep calling this function until the queue is finished
            downloadNextFile();
        }
    }

    // Download button clicked, handle selected files to DownloadNextFile() above
    downloadSelectedButton.addEventListener('click', () => {
        // create an array with all checked checkboxes filenames (all selected files)
        const selectedFiles = Array.from(document.querySelectorAll('li input[type="checkbox"]:checked'))
            .map(checkbox => checkbox.value);
        // console.log(selectedFiles); // Array [ "/file1.MP4", "/file2.mp3" ]

        // if a folder is selected, one or more values of selectedFiles == "folder".
        // we need to remove those values so we can continue with only real files.
        if (selectedFiles.some(file => file.includes("folder"))){
            // so we create a filtered list without the value "folder"
            var filteredFiles = selectedFiles.filter(file => !file.includes("folder"));
        } else {
            // or just rename the array so we can continue
            var filteredFiles = selectedFiles;
        }
        // console.log(filteredFiles);

        // if the list has items, we push them to the queue and call the download function
        if (filteredFiles.length > 0) {
            downloadQueue.push(...filteredFiles);
            downloadNextFile();   // Comment this line to debug without actually downloading stuff!
        } else {
            alert('Please select at least one file.');
        }
    });

    // Function to download single files using the icon
    function getFile(icon) {
        const link = document.createElement('a');
        link.href = icon.nextSibling.href;
        link.setAttribute('download', '');
        link.click();
    }

    // --------------------------- UPLOAD FILE LIST ---------------------------------
    const selectedFilesContainer = document.getElementById('selectedFilesContainer');
    const selectedFilesList = document.getElementById('selectedFilesList');
    const browseButton = document.getElementById('browseButton');  // 'Browse' button (upload)

    // list of files selected to upload with the browse button
    if (browseButton) {
        browseButton.addEventListener('change', () => {
            if (browseButton.files.length > 0) {
                selectedFilesContainer.style.display = 'flex';
                selectedFilesList.innerHTML = '';
                for (let i = 0; i < browseButton.files.length; i++) {
                    const li = document.createElement('li');
                    li.textContent = browseButton.files[i].name;  

                    selectedFilesList.appendChild(li);
                }
            } else {
                selectedFilesContainer.style.display = 'none';
            }
        });
    }

    // --------------------------- BUTTONS and CHECKBOXES-----------------------------
    // Event listener for the "Select..." checkbox (show all existing checkboxes)
    toggleCheckboxesInput.addEventListener('change', () => {
        toggleButtonsVisibility(); // toggle 'all' and 'download' buttons

        // Clear all checked checkboxes before toggling visibility
        const checkboxes = document.querySelectorAll('li input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = false);

        // Toggle visibility of all checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.style.display = checkbox.style.display === 'none' ? 'inline-block' : 'none';
        });

        // Update the "All" checkbox to unchecked (uncheck all checkboxes)
        toggleAllCheckboxes.checked = false;
    });

    // Event listener for the "All" checkbox (to select all files/check all checkboxes)
    document.getElementById('toggleAllCheckboxes').addEventListener('change', () => {
        const checkboxes = document.querySelectorAll('li input[type="checkbox"]');
        const isChecked = toggleAllCheckboxes.checked;

        checkboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    });

    // Function to toggle visibility of the buttons 'Download' and 'All'
    function toggleButtonsVisibility() {
        if (toggleCheckboxesInput.checked) {
            downloadSelectedButton.style.display = 'block';
            selectAllCheckboxInput.style.display = 'block';
        } else {
            downloadSelectedButton.style.display = 'none';
            selectAllCheckboxInput.style.display = 'none';
        }
    }

    // Function to select all checkboxes on a single folder (click a folder checkbox)
    function toggleFolderContentsCheckbox(checkbox) {
        // Find the subfolder contents list associated with the clicked checkbox
        var ul = checkbox.parentElement.querySelector('.subfolder-contents');

        // Get the current checked state of the folder checkbox
        var isChecked = checkbox.checked;

        // Set the checked state of all checkboxes within the folder
        ul.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = isChecked;
        });

        // expands folder on checkbox check if unchecked
        if (ul.style.display !== 'block' && isChecked) {
            toggleFolderContents(ul.parentElement.querySelector('i'));
        }
    }

    // Function to toggle folder contents visibility (click a folder icon)
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