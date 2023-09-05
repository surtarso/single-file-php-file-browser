# Single File PHP Directory Listing

## Introduction

The **Single File PHP Directory Listing** is a lightweight and straightforward project that provides a web-based directory listing for the files contained within a specified directory. This project serves as a quick and easy way to share files or documents with others via a web interface.

## Functionality

The project offers the following functionalities:

- Lists files and folders contained within a specified directory.
- Allows users to navigate through the directory structure.
- Differentiates between files and folders.
- Excludes specific file extensions, such as HTML, PHP, SWP, and CSS, from the listing.

## Usage

To use the Single File PHP Directory Listing, follow these simple steps:

1. Download the `index.php` file from this project.

2. Place the `index.php` file in the folder you want to share on the web.

3. Access the folder using a web browser. You can do this by entering the folder's URL in your web browser's address bar. For example, if you placed `index.php` in a folder called "MyFiles" on your web server, you would access it like this: `http://yourdomain.com/MyFiles/`.

The `index.php` file will automatically generate a directory listing for the specified folder, allowing you to view and access the contained files and folders via a user-friendly web interface.

## Technologies Used

- **PHP**: PHP is used to generate the directory listing and handle file system operations.
- **HTML/CSS**: HTML and CSS are used for the presentation and styling of the directory listing.
- **Font Awesome**: Font Awesome icons are used to enhance the visual representation of files and folders.

## Best Practices

To maintain simplicity and effectiveness, this project follows some best practices:

- **Minimalism**: The code is kept minimal and straightforward to ensure ease of understanding and maintenance.
- **Security**: Security measures are taken by excluding certain file extensions to prevent exposing sensitive files (e.g., PHP files).
- **User Experience**: The interface is designed for user-friendliness, with clear differentiations between files and folders.

## Code Explanation

Here's a line-by-line explanation of the provided PHP code:

```php
<?php
    $directory = './'; // Specify the directory you want to list
```
- This line sets the `$directory` variable to the current directory (`'./'`). You can change this value to specify a different directory that you want to list.

```php
    $files = scandir($directory);
```
- This line uses the `scandir` function to retrieve an array of all files and directories in the specified `$directory`. It stores the result in the `$files` variable.

```php
    // Create an array to store the file extensions
    $notAllowedExtensions = array('html', 'php', 'swp', 'css');
```
- This line defines an array called `$notAllowedExtensions` that contains a list of file extensions that you want to exclude from the listing. Files with these extensions will not be displayed.

```php
    // Create a mapping of file extensions to CSS icons
    $iconMapping = array(
        'pdf' => 'icon-pdf',
        'doc' => 'icon-doc',
        'txt' => 'icon-txt',
        'zip' => 'icon-zip',
        // Add more mappings as needed
    );
```
- Here, an associative array called `$iconMapping` is created. It maps file extensions to CSS classes for icons. For example, 'pdf' is mapped to 'icon-pdf', and 'doc' is mapped to 'icon-doc'. You can add more mappings for other file extensions and icons as needed.

```php
    foreach ($files as $file) {
```
- This line starts a `foreach` loop that iterates through each item (files and directories) in the `$files` array.

```php
        // Exclude dot files and index files
        if ($file != '.' && $file != '..' && !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $notAllowedExtensions)) {
```
- This `if` condition checks whether the current `$file` is not a dot file ('.' or '..') and whether its file extension (after converting to lowercase) is not in the list of `$notAllowedExtensions`. If these conditions are met, the code inside the `if` block is executed.

```php
            $path = $directory . $file;
```
- This line constructs the full path to the current file or directory by concatenating the `$directory` and `$file` variables.

```php
            if (is_dir($path)) {
```
- Here, it checks whether the current item (specified by `$path`) is a directory. If it is a directory, the code within this `if` block is executed.

```php
                echo '<li><i class="icon-folder"></i><a href="' . $file . '">' . $file . '</a>';
```
- This line displays a list item (`<li>`) with an icon representing a folder ('icon-folder') and a hyperlink (`<a>`) with the name of the directory (`$file`) as the link text.

```php
                echo '<ul class="folder-contents">';
```
- This line starts an unordered list (`<ul>`) with the class 'folder-contents' to display the contents of the folder. This is the beginning of nested content.

```php
                $subfiles = scandir($path);
```
- It uses `scandir` to retrieve an array of all files and directories inside the current folder (specified by `$path`) and stores them in the `$subfiles` variable.

```php
                foreach ($subfiles as $subfile) {
```
- This line starts another `foreach` loop to iterate through each item (files and directories) in the `$subfiles` array.

```php
                    if ($subfile != '.' && $subfile != '..') {
```
- Similar to the previous condition, this `if` condition checks if the current `$subfile` is not a dot file ('.' or '..'). If it's not, the code inside this `if` block is executed.

```php
                        $extension = strtolower(pathinfo($subfile, PATHINFO_EXTENSION));
```
- This line extracts and stores the lowercase file extension of the current `$subfile` in the `$extension` variable.

```php
                        $iconClass = isset($iconMapping[$extension]) ? $iconMapping[$extension] : 'icon-default';
```
- Here, it checks if there is an entry in the `$iconMapping` array for the current `$extension`. If an entry exists, it assigns the corresponding icon class to the `$iconClass` variable. Otherwise, it defaults to 'icon-default'.

```php
                        echo '<li><i class="' . $iconClass . '"></i><a href="' . $file . '/' . $subfile . '">' . $subfile . '</a></li>';
```
- This line displays a list item (`<li>`) with an icon based on the `$iconClass` and a hyperlink (`<a>`) with the name of the file or directory (`$subfile`) as the link text. This represents the individual files or subdirectories within the folder.

```php
                }
```

```php
                echo '</ul></li>';
``

`
- This line closes the unordered list (`</ul>`) that contains the contents of the folder and the list item (`</li>`) for the folder itself.

```php
            } else {
```
- If the current item is not a directory, this `else` block is executed.

```php
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
```
- Similar to before, it extracts and stores the lowercase file extension of the current file in the `$extension` variable.

```php
                $iconClass = isset($iconMapping[$extension]) ? $iconMapping[$extension] : 'icon-default';
```
- This line assigns the appropriate icon class to the `$iconClass` variable based on the file's extension, using the `$iconMapping` array.

```php
                echo '<li><i class="' . $iconClass . '"></i><a href="' . $file . '">' . $file . '</a></li>';
```
- This line displays a list item (`<li>`) with an icon based on the `$iconClass` and a hyperlink (`<a>`) with the name of the file (`$file`) as the link text. This represents individual files (not directories).

```php
            }
```

```php
        }
```

```php
    }
?>
```

I've provided comments explaining each part of the code to help you understand its functionality and purpose. You can use these comments as documentation to clarify how the code works and what each section does.
