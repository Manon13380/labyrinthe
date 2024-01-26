<?php
session_start();
$_SESSION['randomMap'] = rand(1, 2);

if ($_SESSION['randomMap'] === 1) {
    if (!isset($_SESSION["map"])) {
        $_SESSION["map"] = [
            [3, 0, 0, 0, 0],
            [1, 1, 0, 1, 1],
            [0, 0, 0, 1, 2],
            [0, 1, 1, 1, 0],
            [0, 0, 0, 0, 0]
        ];
        $_SESSON['fogMap'] = [
            [4, 4, 4, 4, 4],
            [4, 4, 4, 4, 4],
            [4, 4, 4, 4, 4],
            [4, 4, 4, 4, 4],
            [4, 4, 4, 4, 4],
        ];
    }
} else {
    if (!isset($_SESSION["map"])) {
        $_SESSION["map"] = [
            [3, 0, 0, 0],
            [1, 1, 0, 0],
            [1, 1, 0, 1],
            [1, 1, 0, 0],
            [1, 1, 1, 0],
            [0, 0, 0, 0],
            [0, 0, 0, 1],
            [1, 0, 0, 1],
            [1, 1, 0, 0],
            [2, 0, 0, 1]
        ];
        $_SESSON['fogMap'] = [
            [4, 4, 4, 4],
            [4, 4, 4, 4],
            [4, 4, 4, 4],
            [4, 4, 4, 4],
            [4, 4, 4, 4],
            [4, 4, 4, 4],
            [4, 4, 4, 4],
            [4, 4, 4, 4],
            [4, 4, 4, 4],
            [4, 4, 4, 4],
        ];
    }
}

define("SHIFTRIGHTANDDOWN", +1);
define("SHIFTLEFTANDUP", -1);

function displayMap($map)
{
    foreach ($map as $value) {
        echo "<div class='line'>";
        foreach ($value as $value1) {
            switch ($value1) {
                case '4':
                    echo "<div class='cell'><img src='./assets/images/brouillard.png' width='50'></div>";
                    break;
                case '3':
                    echo "<div class='cell'><img src='./assets/images/totoro.png' width='50'></div>";
                    break;
                case '2':
                    echo "<div class='cell'><img src='./assets/images/gland.png' width='25'></div>";
                    break;
                case '1':
                    echo "<div class='cell'><img src='./assets/images/buisson.png' width='50'></div>";
                    break;
                default:
                    echo "<div class='cell'></div>";
            }
        }
        echo "</div>";
    }
}
;
function shift($map, $shiftRD, $shiftLU)
{
    for ($i = 0; $i < count($map); $i++) {
        for ($j = 0; $j < count($map[$i]); $j++) {
            if ($shiftRD != 0 && ($j + $shiftRD) >= 0 && ($j + $shiftRD) < count($map[$i])) {
                if ($map[$i][$j] === 3 && $map[$i][$j + $shiftRD] === 0) {
                    $map[$i][$j] = 0;
                    $map[$i][$j + $shiftRD] = 3;
                    return $map;
                }
            }
            if ($shiftLU != 0 && ($i + $shiftLU) >= 0 && ($i + $shiftLU) < count($map)) {
                if ($map[$i][$j] === 3 && $map[$i + $shiftLU][$j] === 0) {
                    $map[$i][$j] = 0;
                    $map[$i + $shiftLU][$j] = 3;
                    return $map;
                }
            }
        }
    }
    return $map;
}
;


if (isset($_POST["right"])) {
    $_SESSION["map"] = shift($_SESSION["map"], SHIFTRIGHTANDDOWN, 0);
}
;
if (isset($_POST["left"])) {
    $_SESSION["map"] = shift($_SESSION["map"], SHIFTLEFTANDUP, 0);
}
;
if (isset($_POST["up"])) {
    $_SESSION["map"] = shift($_SESSION["map"], 0, SHIFTLEFTANDUP);
}
;
if (isset($_POST["down"])) {
    $_SESSION["map"] = shift($_SESSION["map"], 0, SHIFTRIGHTANDDOWN);
}
;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labyrinthe</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <header>
        <h1>Labyrinthe <br> My Neighbor Totoro</h1>
    </header>
    <main>
        <div class="gameContainer">
            <div class="labyrinthe">
                <?php
                displayMap($_SESSION["map"]);
                ?>
            </div>
            <div class="buttonContainer">
                <form method="POST">
                    <input type="hidden" name="up" value="haut">
                    <button type="submit"> <img src="./assets/images/haut.png" width="50" alt=""></button>
                </form>
                <div class="buttonGD">
                    <form method="POST">
                        <input type="hidden" name="left" value="gauche">
                        <button type="submit"> <img src="./assets/images/gauche.png" width="50" alt=""></button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="right" value="droite">
                        <button type="submit"> <img src="./assets/images/droite.png" width="50" alt=""></button>
                    </form>
                </div>
                <form method="POST">
                    <input type="hidden" name="down" value="bas">
                    <button type="submit"> <img src="./assets/images/bas.png" width="50" alt=""></button>
                </form>
            </div>
            <div class="sentence">
                <p></p>
            </div>
        </div>
    </main>
</body>

</html>