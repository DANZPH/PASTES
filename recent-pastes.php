<?php
// Database connection
    $conn = new mysqli('localhost', 'danceltk_name', 'qwerty123', 'danceltk_name', 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$password = ""; // Set the password here

$stmt = $conn->prepare("SELECT `id`, IFNULL(`password`, '') as `password`, `title`, `code` FROM `pastes` ORDER BY `id` DESC LIMIT 20");
$execval = $stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    die("No pastes found!");
}

$stmt->bind_result($paste_id, $password, $title, $code);
$view_url = "view.php?id=";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:image" content="https://i.ibb.co/8ddzQhd/fake-smile.jpg">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="view.css">
    <title>Recent Pastes</title>
    <style>
        p { color: white;
        position: relative;
            top: 10px;
            left: 80px;}
        pre { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 100%; }
        .view-button {
            display: inline-block;
            background-color: #4CAF50;
            border: none;
            color: white;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            padding: 10px 20px;
            margin-bottom: 5px;
            border-radius: 5px;
            position: relative;
            top: 80px;
            left: 0px;}
            h2 {
              position: relative;
            top: 5px;
            left: 65px;
            }
            h1 {
              text-align: center;
              font-size: 48px;
  font-weight: bold;
            }
    </style>
    <!-- Rest of the head section remains unchanged -->
</head>
<body>
    <div id="container">
        <header>
            <h1>Recent Pastes</h1>
        </header>
        <div id="recent-pastes">
            <?php while ($stmt->fetch()): ?>
                <div class="paste">
                    <?php if (empty($password)): ?>
                        <a href="<?php echo $view_url . $paste_id; ?>" class="view-button">View</a>
                    <?php else: ?>
                    <a href="<?php echo $view_url . $paste_id; ?>" class="view-button">View</a>
                        <p>Password Protected</p>
                    <?php endif; ?>
                    <?php if (empty($password)): ?>
                        <h2><svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" height="1.5em" width="1.5em"><path fill="currentColor" d="M22.55 31.45q-.7.7-1.625.325Q20 31.4 20 30.4V17.6q0-1 .925-1.375t1.625.325l6.4 6.4q.25.25.35.5.1.25.1.55 0 .3-.1.55-.1.25-.35.5Z"/></svg> <?php echo htmlspecialchars($title); ?></h2>
                        <pre><?php echo htmlspecialchars($code); ?></pre>
                    <?php else: ?>
                        <p></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
