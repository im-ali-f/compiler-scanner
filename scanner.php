<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner</title>
    <link rel="stylesheet" href="scanner.css">
</head>

<body>
    <div class="wholeScanner">
        <div class="scannerInput">
            <form action="scanner.php" method="post" enctype="multipart/form-data">
                <input type="file" name="file" id="fileFromHtml" class="font">
                <button value="Scan !" class="scanBTN font" id="scanBTN">Scan!</button>
            </form>
        </div>
    </div>
</body>

</html>
<?php
error_reporting(E_ALL ^ E_WARNING);
if (isset($_FILES["file"])) {
    $lines = explode("\n", file_get_contents($_FILES['file']['tmp_name']));
    foreach ($lines as $line) {
        $wordBySpace[] = explode(" ", $line);
    }


    $tokenList = [];
    $op = [";", ">", ")"];
    foreach ($wordBySpace as $wordArray) {
        foreach ($wordArray as $word) {
            $strCounter = 0;
            $tempToken = "";
            $regex = '/[A-Za-z0-9]*\w+/i';
            while ($strCounter < strlen($word)) {
                if (ord($word[$strCounter]) != 13 && ord($word[$strCounter]) != 9) {
                    if (!preg_match($regex, $word[$strCounter])) {
                        if ($tempToken != "" && $tempToken != " " && ord($tempToken) != 13 && ord($tempToken) != 9) {
                            $tokenList[] = $tempToken;
                            $tempToken = "";
                        }
                        $newOpToken = $word[$strCounter];
                        $opCounter = 1;
                        $nextChar = $word[$strCounter + $opCounter];
                        while (!preg_match($regex, $nextChar) && $nextChar != " " && $nextChar != "" && ord($nextChar) != 13 && ord($nextChar) != 9 && !in_array($nextChar, $op)) {
                            $newOpToken .= $nextChar;
                            $opCounter += 1;
                            $nextChar = $word[$strCounter + $opCounter];
                            $strCounter += 1;
                            if ($newOpToken == "--" || $newOpToken == "//") {
                                break 3;
                            }
                        }
                        if ($newOpToken != "" && $newOpToken != " " && ord($newOpToken) != 13) {
                            $tokenList[] = $newOpToken;
                        }
                    } else {
                        $tempToken .= $word[$strCounter];
                    }
                }
                $strCounter += 1;
            }
            if ($tempToken != "" && $tempToken != " " && ord($tempToken) != 13 && ord($tempToken) != 9) {
                $tokenList[] = $tempToken;
            }
        }
    }
    $lastTokenList = [];
    foreach ($tokenList as $token) {
        if ($token != "" && $token != " " && ord($token) != 13 && ord($token) != 9) {
            $lastTokenList[] = $token;
        }
    }
}

?>
<div class="scannerOutput font">
    <div class="diffFont">Token List</div>
    <hr>
    <?php
    foreach ($lastTokenList as $token) {
        echo "<br>" . $token;
    }
    ?>
</div>