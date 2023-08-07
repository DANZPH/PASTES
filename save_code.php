<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:image" content="https://i.ibb.co/8ddzQhd/fake-smile.jpg">
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
        </style>
    </head>
    <body>
        
<div style="margin: 0 auto; width: fit-content;">
    <?php
    $title = $_POST['title'];
    $code = $_POST['code'];
    $password = $_POST['password'];
    // Database connection
    //$conn = new mysqli('localhost', 'root', '', 'test');
    $conn = new mysqli('localhost', 'danceltk_name', 'qwerty123', 'danceltk_name', 3306);
    

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO `pastes`(`title`, `code`, `password`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $code, $password);
    $execval = $stmt->execute();

    if ($execval) {
        $paste_id = $stmt->insert_id;
        $view_url = "view.php?id=" . $paste_id;
        echo "ㅤSave success<br>";
        echo "<button style='background-color: #1affa3' data-paste-id='$paste_id' onclick='redirectToView(this)'>View</button>";
        echo "<button style='background-color: #1affa3' data-paste-url='$view_url' onclick='copyLink(this)'>Copy Link</button>";
    } else {
        echo "Error saving code!";
    }
    $stmt->close();
    $conn->close();
    ?>
</div>
        <div id="container" style="margin: 0 auto; width: fit-content;">
            <header>
                <!--<h1 style="margin: 0 auto; width: fit-content;"><?php echo $title; ?></h1> -->
            </header>
            <div id="buttons">
            <p style="margin: 0 auto; width: fit-content;">or</p>
                <p style="margin: 0 auto; width: fit-content;">share with</p>
                
                <div class="share-buttons">
                   <a href="https://www.facebook.com/sharer.php?u=<?php echo urlencode('https://pa.hstn.me/view.php?id=' . $paste_id); ?>" target="_blank"><img src="ico/FB.jpg"></a>
                    <a href="fb-messenger://share?link=<?php echo urlencode('https://pa.hstn.me/view.php?id=' . $paste_id); ?>" target="_blank"><img src="ico/messenger.jpg"></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://pa.hstn.me/view.php?id=' . $paste_id); ?>" target="_blank"><img src="ico/twitter.png"></a>
                    <a href="https://www.reddit.com/submit?url=<?php echo urlencode('https://pa.hstn.me/view.php?id=' . $paste_id); ?>" target="_blank"><img src="ico/reddit.png"></a>
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

