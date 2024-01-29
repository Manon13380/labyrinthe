<?php
session_start();
$_SESSION['randomMap'] = rand(1, 2);
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = ['mur' => '', 'map' => '', 'win' => "C'est gagné !!!"];
}
if ($_SESSION['randomMap'] === 1) {
    if (!isset($_SESSION["map"])) {
        $_SESSION["map"] = [
            [3, 0, 0, 0, 0],
            [1, 1, 0, 1, 1],
            [0, 0, 0, 1, 2],
            [0, 1, 1, 1, 0],
            [0, 0, 0, 0, 0]
        ];
        $_SESSION["playerPos"] = [0, 0];
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
        $_SESSION["playerPos"] = [0, 0];
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
            if ($shiftRD != 0) {
                if (($j + $shiftRD) >= 0 && ($j + $shiftRD) < count($map[$i])) {
                    if ($map[$i][$j] === 3) {
                        if ($map[$i][$j + $shiftRD] === 0) {
                            $map[$i][$j] = 0;
                            $map[$i][$j + $shiftRD] = 3;
                            $_SESSION["playerPos"] = [$i, $j + $shiftRD];
                            $_SESSION['error']['mur'] = "";
                            $_SESSION['error']['map'] = "";
                            return $map;
                        }
                        if ($map[$i][$j + $shiftRD] === 1) {
                            $_SESSION['error']['map'] = "";
                            $_SESSION['error']['mur'] = "Attention il y a un mur !";
                        }
                    }
                } else {
                    $_SESSION['error']['mur'] = "";
                    $_SESSION['error']['map'] = "Attention il n'y a pas de chemin par ici !";
                }
            }
            if ($shiftLU != 0) {
                if (($i + $shiftLU) >= 0 && ($i + $shiftLU) < count($map)) {
                    if ($map[$i][$j] === 3) {
                        if ($map[$i + $shiftLU][$j] === 0) {
                            $map[$i][$j] = 0;
                            $map[$i + $shiftLU][$j] = 3;
                            $_SESSION["playerPos"] = [$i + $shiftLU, $j];
                            $_SESSION['error']['mur'] = "";
                            $_SESSION['error']['map'] = "";
                            return $map;
                        }
                        if ($map[$i + $shiftLU][$j] === 1) {
                            $_SESSION['error']['map'] = "";
                            $_SESSION['error']['mur'] = "Attention il y a un mur !";
                        }
                    }
                }
            }
        }
        if (($i + $shiftLU) < 0 || ($i + $shiftLU) > count($map)) {
            $_SESSION['error']['mur'] = "";
            $_SESSION['error']['map'] = "Attention il n'y a pas de chemin par ici !";
        }
    }
    return $map;
}
;
function fogMap()
{
    $fogMap = $_SESSION["map"];
    $pos = $_SESSION["playerPos"];
    for ($i = 0; $i < count($fogMap); $i++) {
        for ($j = 0; $j < count($fogMap[$i]); $j++) {
            if (
                !(($i == $pos[0] && $j == $pos[1])
                    || ($i == $pos[0] && $j == $pos[1] - 1)
                    || ($i == $pos[0] && $j == $pos[1] + 1)
                    || ($i == $pos[0] - 1 && $j == $pos[1])
                    || ($i == $pos[0] + 1 && $j == $pos[1])
                )
            ) {
                $fogMap[$i][$j] = 4;
            }
        }
    }
    return $fogMap;
}
function win()
{
    $map = $_SESSION["map"];
    $pos = $_SESSION["playerPos"];
    if (
        ($pos[1] + 1 < count($map[$pos[0]]) && $map[$pos[0]][$pos[1] + 1] === 2)
        || ($pos[1] - 1 >= 0 && $map[$pos[0]][$pos[1] - 1] === 2)
        || ($pos[0] + 1 < count($map) && $map[$pos[0] + 1][$pos[1]] === 2)
        || ($pos[0] - 1 >= 0 && $map[$pos[0] - 1][$pos[1]] === 2)
    ) {
        echo $_SESSION['error']['win'];
    }
}
function sentence()
{
    if ($_SESSION['error']['mur'] != "") {
        echo $_SESSION['error']['mur'];
    }
    if ($_SESSION['error']['map'] != "") {
        echo $_SESSION['error']['map'];
    }
    win();
}

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
if(isset($_POST["reset"])){
    session_destroy();
    header("Refresh:0");
}
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
                displayMap(fogMap());
                ?>
                <div class="sentenceContainer">
                    <p id="sentence">
                        <?php sentence() ?>
                    </p>
                </div>
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
            <div>
                <form method="POST">
                    <input type="hidden" name="reset" value="reset">
                    <button class="resetButton" type="submit">Recommencer</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>