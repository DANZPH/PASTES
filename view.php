<?php
if (!isset($_GET['id'])) {
   header('Location: 404.php');
    die("Paste ID not specified!");
}

$paste_id = $_GET['id'];

// Database connection
    $conn = new mysqli('localhost', 'danceltk_name', 'qwerty123', 'danceltk_name', 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT `title`, `code`, `password` FROM `pastes` WHERE `id` = ?");
$stmt->bind_param("i", $paste_id);
$execval = $stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
  header('Location: 404.php');
    die("Paste not found!");
}

$stmt->bind_result($title, $code, $password);
$stmt->fetch();
$stmt->close();
$conn->close();

$password_required = !empty($password); // Check if a password is required for the paste

if ($password_required && !isset($_POST['password'])) {
    // Prompt for password
    ?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta property="og:image" content="https://lh3.googleusercontent.com/drive-viewer/AITFw-xTWYCdP76uj9HZ03HzC21IBK3PTssOT21Bs64K1UheBjbfu6DHqZyb40xkg2kTWYh39SyvT9p9RU5ngxVDYF2G4Gd0Zw=s2560">
    <title>INCRIPTED</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #333333;
            
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
        }

        .password-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .password-input {
            padding: 10px;
            width: 300px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .submit-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .submit-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">THE PASTE IS PROTECTED</h1>
        <form method="POST" action="" class="password-form">
            <input type="hidden" name="id" value="<?php echo $paste_id; ?>"> <!-- Add hidden input field -->
            <input type="pass" name="password" id="password" class="password-input" placeholder="Enter password">
            <input type="submit" value="Submit" class="submit-button">
        </form>
    </div>
</body>

</html>

    <?php
} elseif ($password_required && isset($_POST['password'])) {
    // Verify password
    $entered_password = $_POST['password'];
    if ($entered_password !== $password) {
        // Incorrect password, display alert message and redirect back
        echo "<script>alert('Incorrect password! try again'); window.history.back();</script>";
    } else {
        // Password correct, display paste content
        displayPasteContent($title, $code, $paste_id); // Pass the $paste_id to the displayPasteContent function
    }
} else {
    // No password required, display paste content
    displayPasteContent($title, $code, $paste_id); // Pass the $paste_id to the displayPasteContent function
}

function displayPasteContent($title, $code, $paste_id) {
    ?>

        <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:image" content="https://lh3.googleusercontent.com/drive-viewer/AITFw-xTWYCdP76uj9HZ03HzC21IBK3PTssOT21Bs64K1UheBjbfu6DHqZyb40xkg2kTWYh39SyvT9p9RU5ngxVDYF2G4Gd0Zw=s2560">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="view.css">
        <title><?php echo $title; ?></title>
        <style>
        	
            .share-buttons {
                display: flex;
                justify-content: space-between;
                width: 200px;
                margin-top: 10px;
            }
            
            .share-buttons img {
                width: 30px;
                height: 30px;
                border-radius: 50%;
            }
            svg {
            	width: 20px;
                height: 20px;
                border-radius: 50%;
            }
            .navbar {
  position: sticky;
  top: 0;
  z-index: 999;
}
nav a {
      margin: 0 10px;
      text-decoration: none;
      color: #333;
    }
    header {
      padding: 10px;
      position: sticky;
      top: 0;
    }
    .logo {
  width: 35px;
  border-radius: 5px;
  position: absolute;
  margin-top: 10px;
}
        </style>
    </head>
    <body>
        <div id="container">
            <header style="background-color:  #333333; ">
            	<nav class="navbar">
            	        	  <a style="font-size: 15px;" class="navbar-brand" href="https://dancelph.tk"><img class="logo" src="mypaste.ico"></a>
      <h1 style="color: white;";>ㅤㅤ<?php echo $title; ?></h1>
      <div  id="buttons">
                <p>ㅤcopy ㅤㅤorㅤㅤshare with</p>
                
                <div class="share-buttons">
                    <button id="copy-code" onclick="copyCode()"><svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" height="1.5em" width="1.5em"><path fill="currentColor" d="M15 37.95q-1.25 0-2.125-.875T12 34.95v-28q0-1.25.875-2.125T15 3.95h22q1.25 0 2.125.875T40 6.95v28q0 1.25-.875 2.125T37 37.95Zm0-3h22v-28H15v28Zm-6 9q-1.25 0-2.125-.875T6 40.95V12.3q0-.65.425-1.075Q6.85 10.8 7.5 10.8q.65 0 1.075.425Q9 11.65 9 12.3v28.65h22.2q.65 0 1.075.425.425.425.425 1.075 0 .65-.425 1.075-.425.425-1.075.425Zm6-37v28-28Z"/></svg></button>
                    <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode('https://dancelph.tk/view.php?id=' . $paste_id); ?>" target="_blank"><img src="ico/FB.jpg"></a>
                    <a href="fb-messenger://share?link=<?php echo urlencode('https://dancelph.tk/view.php?id=' . $paste_id); ?>" target="_blank"><img src="ico/messenger.jpg"></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://dancelph.tk/view.php?id=' . $paste_id); ?>" target="_blank"><img src="ico/twitter.png"></a>
                    <a href="https://www.reddit.com/submit?url=<?php echo urlencode('https://dancelph.tk/view.php?id=' . $paste_id); ?>" target="_blank"><img src="ico/reddit.png"></a>
                </div>
            </div>
    </nav>
            </header>
            <pre><code>
<?php echo htmlspecialchars($code); ?></code></pre>
        </div>
   <script>
    function copyCode() {
        var codeElement = document.querySelector("code");
        var codeText = codeElement.textContent;
        navigator.clipboard.writeText(codeText).then(function() {
            var copyButton = document.getElementById("copy-code");
            copyButton.textContent = "Copied!";
            copyButton.disabled = true;
        }).catch(function() {
            alert("Unable to copy code to clipboard.");
        });
    }
</script>

    </body>
    </html>
    <?php
}
?>
