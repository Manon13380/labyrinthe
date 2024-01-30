<?php
session_start();
$_SESSION['randomMap'] = rand(1, 3);
$_SESSION['randoLine'] = rand(3, 6);
$_SESSION['randCell'] = rand(3, 10);
$_SESSION['randomNuts'] = [rand(2, ($_SESSION['randoLine'] - 1)), rand(2, ($_SESSION['randCell'] - 1))];

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
} elseif ($_SESSION['randomMap'] === 2) {
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
} else {
    if (!isset($_SESSION["map"])) {
        $_SESSION["playerPos"] = [0, 0];
        $_SESSION["map"] = path(randomMap());
    }
}
define("SHIFTRIGHTANDDOWN", +1);
define("SHIFTLEFTANDUP", -1);

function randomMap()
{
    $randomNuts = $_SESSION['randomNuts'];
    $map = array();
    for ($i = 0; $i < $_SESSION['randoLine']; $i++) {
        $map[$i] = array();
        for ($j = 0; $j < $_SESSION['randCell']; $j++) {
            if ($i == 0 && $j == 0) {
                $map[$i][$j] = 3;

            } elseif ($i == $randomNuts[0] && $j == $randomNuts[1]) {
                $map[$i][$j] = 2;
            } else {
                $map[$i][$j] = 1;
            }
        }
    }
    return $map;
}

function path($map, $pos = [0, 0])
{
    $cell = rand(1, 4);

    if ($pos[1] + 1 < count($map[$pos[0]])) {
        if ($map[$pos[0]][$pos[1] + 1] === 2) {
            $map[0][0] = 3;
            $map[$pos[0]][$pos[1]] = 0;
            return $map;
        }
    }
    if ($pos[1] - 1 >= 0) {
        if ($map[$pos[0]][$pos[1] - 1] === 2) {
            $map[0][0] = 3;
            $map[$pos[0]][$pos[1]] = 0;
            return $map;
        }
    }
    if ($pos[0] + 1 < count($map)) {
        if ($map[$pos[0] + 1][$pos[1]] === 2) {
            $map[0][0] = 3;
            $map[$pos[0]][$pos[1]] = 0;
            return $map;
        }
    }
    if ($pos[0] - 1 >= 0) {
        if ($map[$pos[0] - 1][$pos[1]] === 2) {
            $map[0][0] = 3;
            $map[$pos[0]][$pos[1]] = 0;
            return $map;
        }
    }
    $map[$pos[0]][$pos[1]] = 0;
    switch ($cell) {
        case '1':
            $pos[0] = max(0, $pos[0] - 1);
            break;
        case '2':
            $pos[1] = min($pos[1] + 1, (count($map[$pos[0]]) - 1));
            break;
        case '3':
            $pos[0] = min($pos[0] + 1, (count($map) - 1));
            break;
        default:
            $pos[1] = max(0, $pos[1] - 1);
    }
    return path($map, $pos);
}


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
        if (($i + $shiftLU) < 0 || ($i + $shiftLU) >= count($map) || ($j + $shiftRD) < 0 || ($j + $shiftRD) >= count($map[$i])) {
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

if (isset($_POST["direction"])) {
    switch ($_POST["direction"]) {
        case 'right':
            $_SESSION["map"] = shift($_SESSION["map"], SHIFTRIGHTANDDOWN, 0);
            break;
        case 'left':
            $_SESSION["map"] = shift($_SESSION["map"], SHIFTLEFTANDUP, 0);
            break;
        case 'up':
            $_SESSION["map"] = shift($_SESSION["map"], 0, SHIFTLEFTANDUP);
            break;
        case 'down':
            $_SESSION["map"] = shift($_SESSION["map"], 0, SHIFTRIGHTANDDOWN);
            break;
    }
}
if (isset($_POST["reset"])) {
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
            <form method="POST">
                <div class="buttonContainer">
                    <button type="submit" name="direction" value="up"> <img src="./assets/images/haut.png" width="50"
                            alt=""></button>
                    <div class="buttonGD">
                        <button type="submit" name="direction" value="left"> <img src="./assets/images/gauche.png"
                                width="50" alt=""></button>

                        <button type="submit" name="direction" value="right"> <img src="./assets/images/droite.png"
                                width="50" alt=""></button>
                    </div>
                    <button type="submit" name="direction" value="down"> <img src="./assets/images/bas.png" width="50"
                            alt=""></button>
                </div>
            </form>
            <div>
                <form method="POST">
                    <button class="resetButton" name="reset" value="reset" type="submit">Recommencer</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>