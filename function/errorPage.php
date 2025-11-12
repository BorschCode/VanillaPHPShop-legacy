<?php
/**
 * Simple error message file.
 * This file outputs a basic HTML page with an error image.
 */

// Define the complete HTML output for the error page
$errorMessage = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Error Page</title>
</head>
<body>
    <img 
        src="https://xplayon.com/images/softimages0/17-10/Eto-Fiasko-bratan/Eto-Fiasko-bratan-1.jpg" 
        alt="You are on an error page" 
    />
</body>
</html>';

echo $errorMessage;