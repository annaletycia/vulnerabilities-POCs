<?php

// Definindo cookies falsos e vulneraveis a acessos nao seguros (via JavaScript)
setcookie("FakeSessionID", "123456789", time() + 3600); // Cookie de sessao falso com expiracao de 1 hora, sem as flags HttpOnly e Secure
setcookie("UserPreferences", "darkmode=true", time() + 3600);
 
// Start output buffering to handle form input and comments
ob_start();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vulnerable Comment Section</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f4;
    }
    h1 {
        color: #333;
    }
    form {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }
    textarea {
        width: 50%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Ensures padding and border are included in width */
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin-top: 10px;
        cursor: pointer;
        border-radius: 4px;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    h2 {
        margin-top: 20px;
        color: #333;
    }
    .comment {
        background: #fff;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
</style>
</head>
<body>
<h1>Vulnerable Comment Section</h1>
 
    <!-- Form to submit comments -->
<form method="POST" action="">
<label for="comment">Deixe um comentário:</label><br>
<textarea name="comment" id="comment" rows="4" cols="50"></textarea><br><br>
<input type="submit" value="Enviar">
</form>
 
    <h2>Comentários:</h2>
<div>
<?php
        // Store and display comments
        $comments_file = 'comments.txt';
 
        // If a comment is submitted, append it to the file
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
            $comment = $_POST['comment'] . "\n";
            file_put_contents($comments_file, $comment, FILE_APPEND);
        }
 
        // Display comments
        if (file_exists($comments_file)) {
            $comments = file($comments_file, FILE_IGNORE_NEW_LINES);
            foreach ($comments as $comment) {
                // Vulnerable to XSS since no sanitization is done
                echo "<p>$comment</p>";
            }
        }
        ?>
</div>
 
<script>
document.getElementById('comment').addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault(); // Prevents the default behavior (new line in textarea)
        this.form.submit(); // Submits the form
    }
});
</script>


</body>
</html>