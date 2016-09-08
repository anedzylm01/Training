<?php
    $cards = array();
    $suffled_cards = array();

    //init cards
    for ($i = 0; $i < 52; $i++) {
        array_push($cards, $i);
    }

    //show original cards set
    echo "Original cards set:\n";
    show_card($cards);

    //shuffle cards set
    for ($i = 51; $i >= 0; $i--) {
        $card_rand_key = rand(0, $i);
        $suffled_cards[$i] = $cards[$card_rand_key];
        $cards[$card_rand_key] = $cards[$i];
    }

    echo "\nAfter suffle:\n";
    show_card($suffled_cards);
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
    function swap_card(&$cards, $key1, $key2) {
        $temp = $cards[$key1];
        $cards[$key1] = $cards[$key2];
        $cards[$key2] = $temp;
    }
?>
