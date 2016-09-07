<?php

    $swap_times = 959595;//set how many times you want to swap; 
    $cards = array();

    //init cards
    for ($i = 0; $i < 52; $i++) {
        array_push($cards, $i);
    }

    //show original cards set
    echo "Original cards set:\n";
    show_card($cards);

    //shuffle cards set
    for ($i = 0; $i < $swap_times; $i++) {
        $time = (int)time();
        swap_card($cards, base_convert($time * $i, 10, 8) % 52, $time * $i % 52);
    }

    echo "\nAfter suffle:\n";
    show_card($cards);
    echo "\n";

    //convert to cards graph by numbers
    function show_card($cards) {
        for ($i = 0; $i < 52; $i++) {
            switch ($cards[$i] % 13 + 1) {
                case 1:
                    echo str_pad("A", 2, ' ', STR_PAD_LEFT);
                    break;
                case 11:
                    echo str_pad("J", 2, ' ', STR_PAD_LEFT);
                    break;
                case 12:
                    echo str_pad("Q", 2, ' ', STR_PAD_LEFT);
                    break;
                case 13:
                    echo str_pad("K", 2, ' ', STR_PAD_LEFT);
                    break;
                default:
                    echo str_pad($cards[$i] % 13 + 1, 2, ' ', STR_PAD_LEFT);
                    break;
            }
            switch ((int)($cards[$i] / 13)) {
                case 0:
                    echo "♣ ";
                    break;
                case 1:
                    echo "♦ ";
                    break;
                case 2:
                    echo "♥ ";
                    break;
                case 3:
                    echo "♠ ";
                    break;
                default:
                    break;
            }
            if ($i % 13 == 12) {
                echo "\n";
            }
        }
    }

    //swap card 
    function swap_card(&$array, $key1, $key2) {
        $temp = $array[$key1];
        $array[$key1] = $array[$key2];
        $array[$key2] = $temp;
    }
?>