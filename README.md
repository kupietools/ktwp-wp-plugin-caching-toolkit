# KupieTools Caching Toolkit

A powerful WordPress plugin that provides a framework for developers to implement function caching for dramatic performance improvements. This toolkit intelligently caches function results and automatically clears those caches when content changes.

## Features

- Function result caching with automatic invalidation
- Configurable cache invalidation triggers
- Integration with WordPress core hooks for cache management
- Admin interface under the KupieTools menu
- Optimized for performance with minimal overhead
- Uses WordPress transients and object cache for maximum compatibility
- Developer-friendly API for implementing caching in custom code

## Core Functions

### For Developers

- `getFunctionTransient($functionName, $arguments=[], $manualClearOnly=false)` - Retrieve cached function results
- `setFunctionTransient($functionName, $value, $arguments=[])` - Cache function results
- `hashArguments($arguments)` - Utility function to create consistent hashes of function arguments

### Cache Invalidation

The plugin intelligently clears caches when content changes, with configurable triggers for:
- Post saving/updating
- Category/taxonomy creation, editing, and deletion
- Post trashing
- Theme and plugin file editing

## Configuration Options

In the WordPress admin panel under KupieTools:
- Enable/disable cache clearing for different WordPress actions
- Configure cache behaviors for different types of content updates
- Special handling for theme/plugin file editing

## Benefits

- Dramatically improves performance for expensive function calls
- Reduces database queries and server processing time
- Intelligent cache invalidation means content stays fresh
- No manual cache management required - it all happens automatically
- Preserves dynamic content functionality while improving speed

## For WordPress Developers

```php
// Example usage in your theme or plugin:
function my_expensive_function($param1, $param2) {
    // Check if we have a cached result
    $cached = getFunctionTransient('my_expensive_function', [$param1, $param2]);
    if ($cached !== null) {
        return $cached;
    }
    
    // If not cached, do the expensive operation
    $result = // ... expensive operations ...
    
    // Cache the result for future use
    setFunctionTransient('my_expensive_function', $result, [$param1, $param2]);
    
    return $result;
}
```

## Installation

1. Upload the plugin files to the `/wp-content/plugins/ktwp-caching-toolkit` directory
2. Activate the plugin through the WordPress admin interface
3. Configure settings under the KupieTools menu in WordPress admin

## License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details.
