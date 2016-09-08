<?php
    $cards = array();
    $suffled_cards = array();
    $game_cards_set = array();
    $players = -1;

    echo "Game Start! Please tell me how many players want to play this game[0~10]: ";
    fscanf(STDIN, "%d\n", $players);
    while (!is_int($players) || $players < 0 || $players > 10) {
        echo "Please tell me a positive integer number[0~10]. How many players want to play this game: ";
        fscanf(STDIN, "%d\n", $players);
    }
    if ($players == 0) {
        echo "No one here. Have a nice day, Bye!\n";
        exit(0);
    }

    shuffle_cards($cards, $suffled_cards);
    deal($suffled_cards, $players, $game_cards_set);

    function deal(&$suffled_cards, $players, &$game_cards_set){
        //prepare game_cards_set space for players
        for ($i = 0; $i < $players; $i++) {
            $game_cards_set[$i] = array();
        }
        //eveyone has 5 cards
        for ($i = 0; $i < 5; $i++) { 
            for ($j = 0; $j < $players; $j++) {
                array_push($game_cards_set[$j], array_pop($suffled_cards));
            }
        }
        print_r($game_cards_set);
    }

    function shuffle_cards(&$cards, &$suffled_cards){
        //make sure array of cards is empty 
        $cards = array_diff($cards, $cards);

        //init cards
        for ($i = 0; $i < 52; $i++) {
            array_push($cards, $i);
        }

        //show original cards set
        echo "Original cards set:\n";
        show_cards($cards);

        //shuffle cards set
        for ($i = 51; $i >= 0; $i--) {
            $card_rand_key = rand(0, $i);
            $suffled_cards[$i] = $cards[$card_rand_key];
            $cards[$card_rand_key] = $cards[$i];
        }

        echo "\nAfter suffle:\n";
        show_cards($suffled_cards);
        echo "\n";
    }

    //convert to card graph by number
    function show_card($card) {
            switch ($card % 13 + 1) {
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
                    echo str_pad($card % 13 + 1, 2, ' ', STR_PAD_LEFT);
                    break;
            }
            switch ((int)($card / 13)) {
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
    }

    //convert to cards graph by numbers
    function show_cards($cards) {
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
?>
