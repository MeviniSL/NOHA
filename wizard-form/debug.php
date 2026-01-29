<?php
session_start();
require 'config/api.php';

// Test API calls
$vehicles  = callAPI('/vehicles');
$locations = callAPI('/locations');

echo "<!-- DEBUG INFO -->\n";
echo "<!-- Vehicles API Response: " . json_encode($vehicles) . " -->\n";
echo "<!-- Locations API Response: " . json_encode($locations) . " -->\n";
echo "<!-- Session ID: " . session_id() . " -->\n";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; }
        code { background: #fff; padding: 5px; display: block; }
    </style>
</head>
<body>
    <h1>Debug Test</h1>
    
    <div class="debug">
        <h3>Vehicles API Response:</h3>
        <code><?= htmlspecialchars(json_encode($vehicles, JSON_PRETTY_PRINT)) ?></code>
    </div>
    
    <div class="debug">
        <h3>Locations API Response:</h3>
        <code><?= htmlspecialchars(json_encode($locations, JSON_PRETTY_PRINT)) ?></code>
    </div>
    
    <div class="debug">
        <h3>Session ID:</h3>
        <code><?= session_id() ?></code>
    </div>
    
    <h2>Test Form Submission</h2>
    <form id="testForm">
        <input type="text" name="pickup" value="Colombo" required>
        <input type="text" name="return_loc" value="Kandy" required>
        <input type="date" name="pickup_date" required>
        <input type="date" name="return_date" required>
        <input type="hidden" name="vehicle_id" value="1">
        <input type="hidden" name="vehicle_count" value="1">
        <button type="submit">Test Submit</button>
    </form>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            console.log('Form Data:', Object.fromEntries(formData));
            
            fetch('save-step1.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                console.log('Response:', data);
                alert('Response: ' + JSON.stringify(data));
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error: ' + err);
            });
        });
    </script>
</body>
</html>
