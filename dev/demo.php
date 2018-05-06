<?php

/**
 * Minimal class autoloader
 *
 * @param string $class Full qualified name of the class
 */
function miniAutoloader($class)
{
    $class = str_replace('\\', '/', $class);
    require __DIR__ . '/../src/' . $class . '.php';
}

// If the Composer autoloader exists, use it. If not, use our own as fallback.
$composerAutoloader = __DIR__.'/../vendor/autoload.php';
if (is_readable($composerAutoloader)) {
    require $composerAutoloader;
} else {
    spl_autoload_register('miniAutoloader');
}

$defaultText = '[quote][quote]He said[/quote]she said[/quote]';
$text = isset($_POST['text']) ? $_POST['text'] : null;

$bbCode = new ChrisKonnertz\BBCode\BBCode();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BBCode Demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/framy/latest/css/framy.min.css">
    <style>
        body { padding: 20px }
        h1 { margin-bottom: 40px }
        h4 { margin-top: 40px }
        textarea { resize: vertical; }
        blockquote { margin-left: 0; margin-right: 0; }
        footer { color: #aaa }
        div.success { border: 1px solid #4ce276; margin-top: 20px; padding: 10px; border-top-width: 10px }
        div.error { border: 1px solid #f36362; margin-top: 20px; padding: 10px; border-top-width: 10px }
        .form-select { max-width: 100px }
        .button-group { margin-bottom: 20px }
        .content { margin-bottom: 20px; padding: 20px; box-shadow: 0 1px 3px 0 #c8c8c8; }
    </style>
</head>
<body>
    <h1>BBCode Demo</h1>

    <div class="content">
        <form method="POST">
            <div class="form-element">
                <label for="text">Text:</label>
                <textarea id="text" class="form-field" name="text" rows="4"><?php echo $text !== null ? $text : $defaultText ?></textarea>
            </div>

            <input type="submit" value="Render" class="button">
        </form>

        <div class="block result">
            <?php

            if ($text !== null) {
                try {
                    $result = $bbCode->render($text);

                    echo '<div class="success">Rendered: <pre><b>' . htmlspecialchars($result) . '</b></pre></div>';
                } catch (\Exception $exception) {
                    echo '<div class="error">'.$exception->getMessage().'</div>';
                }
            }

            ?>
        </div>
    </div>

    <footer class="block">
        <small>
            Version <?php echo ChrisKonnertz\BBCode\BBCode::VERSION ?>.
        </small>
    </footer>
</body>
</html>