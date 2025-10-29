<?php
// Simple test page to check consumer panel access
echo "<h1>Consumer Panel Access Test</h1>";
echo "<p>This is a test page to verify consumer panel access.</p>";

// Try to include the consumer panel file
if (file_exists('frontend/pages/consumer.php')) {
    echo "<p>✅ consumer.php file exists</p>";
    echo "<p><a href='/Küresel/?page=consumer'>Try accessing consumer panel</a></p>";
} else {
    echo "<p>❌ consumer.php file does not exist</p>";
}
?>