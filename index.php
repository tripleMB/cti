<?php
// Database connection test page
$connectionStatus = "Not tested";
$details = "";

try {
    // Get connection string from environment variables
    $connectionString = getenv("DatabaseConnection");
    
    if (!$connectionString) {
        throw new Exception("Database connection string not found in environment variables");
    }

    // Parse connection string
    $parts = [];
    parse_str(str_replace(";", "&", $connectionString), $parts);
    
    // Extract details for display
    $server = $parts['Server'] ?? 'Unknown';
    $database = $parts['Database'] ?? 'Unknown';
    
    // Attempt connection
    $conn = new PDO(
        "sqlsrv:Server=" . $parts['Server'] . ";Database=" . $parts['Database'],
        $parts['User ID'],
        $parts['Password']
    );
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test query
    $stmt = $conn->query("SELECT 1 AS test_value");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['test_value'] == 1) {
        $connectionStatus = "✅ Connection successful!";
    } else {
        $connectionStatus = "⚠️ Connection test query failed";
    }
    
    $details = "Server: $server<br>Database: $database";
    
} catch (Exception $e) {
    $connectionStatus = "❌ Connection failed";
    $details = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; }
        .status { font-size: 24px; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .failure { background-color: #f8d7da; color: #721c24; }
        .details { background-color: #e2e3e5; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Database Connection Test</h1>
    
    <div class="status <?= strpos($connectionStatus, 'successful') !== false ? 'success' : 'failure' ?>">
        <?= $connectionStatus ?>
    </div>
    
    <div class="details">
        <h3>Connection Details:</h3>
        <?= $details ?>
    </div>
    
    <h3>Environment Variables:</h3>
    <pre><?php print_r($_SERVER); ?></pre>
</body>
</html>