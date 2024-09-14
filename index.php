<!DOCTYPE html>
<html lang="en">

<?php
    // Extracts the name from the URL and capitalizes it for the page title and header.
    // If no folder is found, uses the default title set in '$defaultTitle'.
    // ex.: mydomain.com/myfolder will use captalized Myfolder.
    // ex.: mydomain.com/ will use the default title set below.

    // Define a default title if there is none from URL
    $defaultTitle = "Stuff";  // "My default title"

    // Get the current URL
    $currentUrl = $_SERVER['REQUEST_URI'];

    // Split the URL by "/" and get the last segment
    $urlSegments = explode('/', rtrim($currentUrl, '/'));
    $lastSegment = end($urlSegments);

    // Capitalize the first letter of the last segment
    $headerTitle = ucfirst($lastSegment);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- fontawesome css link, update as you see fit -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- placeholder tab icon (favicon) so its not empty -->
    <link rel="shortcut icon" type="image/x-icon" href="data:image/x-icon;base64,c2t1bGw">
    <!-- Dynamic page tab title with capitalized folder name or default title -->
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
        #downloadControls {display: flex; align-items: center; color: whitesmoke; min-height: 25px;}
        /* download selected button */
        #downloadSelected {cursor: pointer; display: none; margin-left: auto; margin-right: 20px;}
        #toggleCheckboxes {cursor: pointer; margin-left: 24px; margin-right: 7px;}
        #toggleAllCheckboxes {cursor: pointer; margin-left: 12px;}
        /* color of folder names */
        #folderNames {display: inline; color: orange;}
        /* this is the filename/folder row in main view */
        .fileUnderline {border-bottom: 1px solid #1a1b1a;}
        .fileUnderline a {margin-left: 4px;}
        /* start checkboxes hidden */
        #selectAllCheckbox {display: none; cursor: pointer;}
        #folderCheckbox, #fileCheckbox {cursor: pointer; margin-right: 8px;/*display:none; is echoed at runtime */}
        .catchScanErrorMessage {color: red;}
        /* -------------- COMMON STYLES FOR ALL ICONS --------------- */
        i {font-family: "Font Awesome 6 Free"; font-size: 18px; font-style: normal; margin-right: 8px; margin-left: 4px; cursor: pointer;}
        /* Default fallback style "file-alt" icon in Font Awesome*/
        .icon-default::before {content: "\f15b"; color: white;}
        /* Folder icon style */
        .icon-folder-open::before {content: "\f07c"; color: #428bca; cursor: pointer;}
        .icon-folder-closed::before {content: "\f07b"; color: #428bca; cursor: pointer;}
        /* --------------- ICON COLORS -------------- */
        .fa-file-pdf { color: #e74c3c; }
        .fa-file-word { color: #3498db; }
        .fa-file-lines { color: #2ecc71; }
        .fa-file-code { color: #9b59b6; }
        .fa-file-powerpoint { color: #e67e22; }
        .fa-file-excel { color: #27ae60; }
        .fa-file-csv { color: #f1c40f; }
        .fa-file-alt { color: #95a5a6; }
        .fa-file-zipper { color: #e67e22; }
        .fa-file-archive { color: #e67e22; }
        .fa-terminal { color: #34495e; }
        .fa-file-exclamation { color: #e74c3c; }
        .fa-file-video { color: #3498db; }
        .fa-file-audio { color: #9b59b6; }
        .fa-file-image { color: #2ecc71; }
        .fa-book { color: #f39c12; }
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
        // file that stores username and passwords
        $usersFile = './.users';
        // allowed extensions to be uploaded by the user
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

        // Check if the .users file exists
        if (file_exists($usersFile)) {
            // The file exists, so show the upload form section
            echo '<div class="content" id="uploadContainer">';
            echo '<form action="" method="POST" enctype="multipart/form-data">';
            echo '<input type="text" name="username" placeholder="Username">';
            echo '<input type="password" name="password" placeholder="Password">';
            echo '<input type="file" name="files[]" multiple id="browseButton">';
            echo '<button type="submit">Upload</button> </form> </div>';
        }

        // show list selected files for upload, if any
        echo '<div class="content" id="selectedFilesContainer"><ul id="selectedFilesList"></ul></div>';
    
        // Server upload logic
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Read users from the file
            $users = file_get_contents($usersFile);
            $usersArray = explode("\n", $users);

            // Check if the user exists
            $userExists = false;
            foreach ($usersArray as $user) {
                $userParts = explode(':', $user);
                if ($userParts[0] === $_POST['username'] && $userParts[1] === $_POST['password']) {
                    $userExists = true;
                    break;
                }
            }

            // upload messages container
            echo '<div class="content">';
            if ($userExists) { // Authenticated, proceed with upload
                foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
                    $fileName = $_FILES['files']['name'][$key];
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if (in_array($fileExtension, $allowedTypes)) {
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
    ?>

    <!----------------------------------- MULTIPLE DOWNLOADS SECTION (buttons)  ----------------------->
    <div class="content" id="downloadControls">
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

                    // Array to store unwanted file extensions
                    // These extensions will not show up in list
                    $notAllowedExtensions = array('php', 'swp');

                    // Array mapping of file extensions to CSS classes
                    // This will map the file extensions found into fontawesome icons
                    $iconMapping = array(
                        // Documents
                        'pdf' => 'fa-file-pdf',     // PDF document
                        'doc' => 'fa-file-word',    // Microsoft Word document
                        'docx' => 'fa-file-word',   // Microsoft Word document
                        'txt' => 'fa-file-lines',   // Text document
                        'md' => 'fa-file-code',     // Markdown document
                        'ppt' => 'fa-file-powerpoint', // PowerPoint presentation
                        'pptx' => 'fa-file-powerpoint', // PowerPoint presentation
                        'xls' => 'fa-file-excel',   // Excel spreadsheet
                        'xlsx' => 'fa-file-excel',  // Excel spreadsheet
                        'csv' => 'fa-file-csv',     // CSV file
                        'rtf' => 'fa-file-alt',     // Rich Text Format
                        // Compressed Files
                        'zip' => 'fa-file-zipper',  // ZIP archive
                        'tar' => 'fa-file-archive', // TAR archive
                        'gz' => 'fa-file-archive',  // GZ archive
                        'rar' => 'fa-file-archive', // RAR archive
                        // Executable/Scripts
                        'sh' => 'fa-terminal',      // Shell script
                        'bat' => 'fa-file-code',    // Batch script
                        'exe' => 'fa-file-exclamation', // Executable file
                        'ps1' => 'fa-file-code',    // PowerShell script
                        'py' => 'fa-file-code',     // Python script
                        'js' => 'fa-file-code',     // JavaScript code
                        'php' => 'fa-file-code',    // PHP script
                        'java' => 'fa-file-code',   // Java source code
                        'cpp' => 'fa-file-code',    // C++ source code
                        'rb' => 'fa-file-code',     // Ruby script
                        'c' => 'fa-file-code',      // C source code
                        'css' => 'fa-file-code',    // CSS stylesheet
                        'jsx' => 'fa-file-code',    // JSX (React) code
                        'html' => 'fa-file-code',   // HTML markup
                        // Media Files - Video
                        'avi' => 'fa-file-video',   // AVI video
                        'wmv' => 'fa-file-video',   // WMV video
                        'mov' => 'fa-file-video',   // MOV video
                        'mp4' => 'fa-file-video',   // MP4 video
                        'flv' => 'fa-file-video',   // FLV video
                        'webm' => 'fa-file-video',  // WebM video
                        'mkv' => 'fa-file-video',   // MKV video
                        'mxf' => 'fa-file-video',   // MXF video
                        // Media Files - Audio
                        'mp3' => 'fa-file-audio',   // MP3 audio
                        'ogg' => 'fa-file-audio',   // OGG audio
                        'wav' => 'fa-file-audio',   // WAV audio
                        'flac' => 'fa-file-audio',  // FLAC audio
                        'aac' => 'fa-file-audio',   // AAC audio
                        'wma' => 'fa-file-audio',   // WMA audio
                        'm4a' => 'fa-file-audio',   // M4A audio
                        'ape' => 'fa-file-audio',   // APE audio
                        'aaf' => 'fa-file-audio',   // AAF audio
                        // Media Files - Image
                        'jpg' => 'fa-file-image',   // JPEG image
                        'jpeg' => 'fa-file-image',  // JPEG image
                        'png' => 'fa-file-image',   // PNG image
                        'gif' => 'fa-file-image',   // GIF image
                        'bmp' => 'fa-file-image',   // BMP image
                        'svg' => 'fa-file-image',   // SVG image
                        'psd' => 'fa-file-image',   // Adobe Photoshop document
                        'ai' => 'fa-file-image',    // Adobe Illustrator document
                        'eps' => 'fa-file-image',   // EPS vector image
                        'indd' => 'fa-file-alt',    // Adobe InDesign document
                        'raw' => 'fa-file-image',   // RAW image
                        'tiff' => 'fa-file-image',  // TIFF image
                        'webp' => 'fa-file-image',  // WebP image
                        'kra' => 'fa-file-image',   // Krita document
                        // eBooks
                        'mobi' => 'fa-book',        // MOBI document
                        'epub' => 'fa-book',        // EPUB document
                        // Add more mappings as needed
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
                                    echo '<li class="fileUnderline">';
                                    echo '<i class="' . $iconClass . '"></i>' . $file . ' <span class="file-size">' . $e->getMessage() . '</span>';
                                    echo '</li>';
                                }
                                
                                // Print -files- to screen
                                echo '<li class="fileUnderline">';
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
    const browseButton = document.getElementById('browseButton');                // 'Browse' button (upload)
 
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

    // list of files selected to upload with the browse button
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

        // expands folder on checkbox check
        if (ul.style.display !== 'block') {
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