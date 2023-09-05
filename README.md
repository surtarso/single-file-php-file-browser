# Single File PHP Directory Listing

## Introduction

The **Single File PHP Directory Listing** is a lightweight and straightforward project that provides a web-based directory listing for the files contained within a specified directory. This project serves as a quick and easy way to share files or documents with others via a web interface.

## Functionality

The project offers the following functionalities:

- Lists files and folders contained within a specified directory.
- Allows users to navigate through the directory structure.
- Differentiates between files and folders. (TODO)
- Excludes specific file extensions, such as HTML, PHP, SWP, and CSS, from the listing.

## Technologies Used

- **PHP**: PHP is used to generate the directory listing and handle file system operations.
- **HTML/CSS**: HTML and CSS are used for the presentation and styling of the directory listing.
- **Font Awesome**: Font Awesome icons are used to enhance the visual representation of files and folders.

## Best Practices

To maintain simplicity and effectiveness, this project follows some best practices:

- **Minimalism**: The code is kept minimal and straightforward to ensure ease of understanding and maintenance.
- **Security**: Security measures are taken by excluding certain file extensions to prevent exposing sensitive files (e.g., PHP files).
- **User Experience**: The interface is designed for user-friendliness, with clear differentiations between files and folders. (TODO)

## Code Explanation

Let's break down the provided PHP code step by step:

```php
$directory = './'; // Specify the directory you want to list
$files = scandir($directory);
```

- `$directory`: Specifies the directory to be listed. In this code, it's set to the current directory (where the script resides). You can change this to list a different directory.

- `$files`: Uses the `scandir` function to retrieve a list of all items (files and folders) in the specified directory.

```php
// Create an array to store the file extensions
$notAllowedExtensions = array('html', 'php', 'swp', 'css');
```

- `$notAllowedExtensions`: An array that stores file extensions to be excluded from the listing. This array includes extensions like HTML, PHP, SWP, and CSS.

```php
echo '<ul>';
foreach ($files as $file) {
    // Exclude dot files and index files
    if ($file != '.' && $file != '..' && !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $notAllowedExtensions)) {
        echo '<li><i class="icon-default"></i><a href="' . $file . '">' . $file . '</a></li>';
    }
}
echo '</ul>';
```

- `echo '<ul>';`: Starts an unordered list in HTML to display the directory listing.

- `foreach ($files as $file) {`: Iterates through each item in the directory.

- `if ($file != '.' && $file != '..' && !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $notAllowedExtensions)) {`: Checks whether the item should be included in the listing.

    - `$file != '.' && $file != '..'`: Excludes dot files (current directory `.` and parent directory `..`).

    - `!in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $notAllowedExtensions))`: Excludes files with extensions listed in `$notAllowedExtensions`.

- `echo '<li><i class="icon-default"></i><a href="' . $file . '">' . $file . '</a></li>';`: Displays each item as a list item (`<li>`) with a link (`<a>`) and an icon represented by the CSS class `icon-default`.

- `echo '</ul>';`: Closes the unordered list.

This code generates a simple directory listing, excluding specific file extensions, and displays files and folders in a user-friendly manner.
