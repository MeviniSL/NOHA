<?php
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session Data: " . print_r($_SESSION, true) . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['test'] = 'test data';
    echo json_encode(['success' => true, 'message' => 'Test successful']);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test</title>
</head>
<body>
    <h1>Session Test</h1>
    <button onclick="testFetch()">Test Fetch</button>
    
    <script>
        function testFetch() {
            fetch('test.php', {
                method: 'POST',
                body: new FormData()
            })
            .then(res => res.json())
            .then(data => {
                console.log('Success:', data);
                alert('Response: ' + JSON.stringify(data));
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error: ' + err);
            });
        }
    </script>
</body>
</html>
