<?php
/**
 * Class Autoloader
 * 
 * PSR-4 compliant autoloader
 * 
 * @package     LanguageCourses
 * @author      Developer Name
 * @version     1.0.0
 */

spl_autoload_register(function (string $class) {
    // Base directory for the namespace prefix
    $baseDir = __DIR__ . '/';
    
    // Project namespace prefix
    $prefix = 'App\\';
    
    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }
    
    // Get the relative class name
    $relativeClass = substr($class, $len);
    
    // Replace namespace separators with directory separators
    // and append .php
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});