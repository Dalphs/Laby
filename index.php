<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>pre{margin: 0px; font-size: 15px}</style>
    </head>
    <body>
        <?php
            if ($file = fopen("longLaby.txt", "r")) {
                //Saves board in a multidimensional array
                $board = [];
                while(!feof($file)) {
                    $line = fgets($file);
                    $chars = str_split($line);
                    $row = [];
                    foreach ($chars as $char){
                        array_push($row, $char);
                    }
                    array_push($board, $row);
                
                }
                //Prints board to website
                function printBoard($board){
                    foreach ($board as $row){
                        $line = "<pre>";
                        foreach ($row as $element){
                            $line .= $element;
                        }
                        $line .= "</pre>";
                        echo $line;
                    }
                    echo "<br>";
                }
                printBoard($board);
                
                
                //Finding start and end coodinates as well as width and height
                $start = [];
                $end = [];
                $i = 0;
                $j = 0;
                foreach ($board as $row){
                    $j = 0;
                    foreach ($row as $element){
                        if($element == "i"){
                            array_push($start, $j, $i);
                            
                        }else if($element == "u"){
                            array_push($end, $j, $i);
                        }
                        $j++;
                    }
                    $i++;
                }
                $width = $j;
                $height = $i;
                //echo "Start coordinate: (" . $start[0] . "," . $start[1] . ")<br>End coordinate: (" . $end[0] . "," . $end[1] . ")";
            
                
                //Finding way
                $usedLocations = $board;
                $routes[0] = [$start];
                
                function detectNewWays($usedLocations, $routes, $width, $height, $end){
                    $newRoutes = [];
                    $continue = true;
                    $finalRoute = [];
                    foreach ($routes as $route){
                        $maxIndex = sizeof($route) - 1;
                        $x = $route[$maxIndex][0];
                        $y = $route[$maxIndex][1];
                        $usedLocations[$y][$x] = "b";
                        //Finding ways to move
                        if($x + 1 < $width && ($usedLocations[$y][$x+1] == " " || $usedLocations[$y][$x+1] == "u")){
                            $usedLocations[$y][$x+1] = "n";
                            $temp = $route;
                            array_push($temp, [($x+1), $y]);
                            array_push($newRoutes, $temp);
                        }
                        if($x - 1 >= 0 && ($usedLocations[$y][$x-1] == " " || $usedLocations[$y][$x-1] == "u")){
                            $usedLocations[$y][$x-1] = "n";
                            $temp = $route;
                            array_push($temp, [($x-1), $y]);
                            array_push($newRoutes, $temp);
                        }
                        if($y + 1 < $height && ($usedLocations[$y+1][$x] == " " || $usedLocations[$y+1][$x] == "u")){
                            $usedLocations[$y+1][$x] = "n";
                            $temp = $route;
                            array_push($temp, [$x, ($y+1)]);
                            array_push($newRoutes, $temp);
                        }
                        if($y - 1 >= 0 && ($usedLocations[$y-1][$x] == " " || $usedLocations[$y-1][$x] == "u")){
                            $usedLocations[$y-1][$x] = "n";
                            $temp = $route;
                            array_push($temp, [$x, ($y-1)]);
                            array_push($newRoutes, $temp);
                        }
                        echo $x . "," . $y . "-" . $end[0] . "," . $end[1] . " ------------";
                        if($x == $end[0] && $y == $end[1]){
                            $continue = false;
                            array_push($finalRoute, $route);
                            break;
                            
                        }
                    }
                    return array(
                        "board" => $usedLocations,
                        "routes" => $newRoutes,
                        "continue" => $continue,
                        "finalRoute" => $finalRoute
                    );
                }
                $run = true;
                $finalRoute;
                while ($run){
                    $newWays = detectNewWays($usedLocations, $routes, $width, $height, $end);
                    $run = $newWays["continue"];
                    if($run){
                        $usedLocations = $newWays["board"];
                        $routes = $newWays["routes"];
                        printBoard($usedLocations);
                    }else
                        $finalRoute = $newWays["finalRoute"];
                }
                
                function showWay($board, $route, $start){
                    print_r($route);
                    $lastX = $start[0];
                    foreach ($route as $point){
                        $board[$point[1]][$point[0]] = ($lastX != $point[1]) ? "|" : "-";
                        $lastX = $point[1];
                    }
                    return $board;
                }
                $finalBoard = showWay($board, $finalRoute[0], $start);
                printBoard($finalBoard);
                
                fclose($file);
}
        ?>
    </body>
</html>
