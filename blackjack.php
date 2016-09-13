<?php
    /*poker_type.php
    目的:使用者輸入遊戲人數，程式發出洗好的牌給各位玩家，並判斷玩家的牌型與牌組排名
    說明:
        玩家牌組資料結構
            [0] => 第一張牌
            [1] => 第二張牌
            [2] => 第三張牌
            [3] => 第四張牌
            [4] => 第五張牌
            [5] => 總共幾張牌
            [6] => 牌組分數
            [7] => special case for 1
        牌組分數計算 (點數和)
            點數(points)
                A : 1 or 11
                K : 10
                Q : 10
                J : 10
                10 : 10
                9 : 9
                8 : 8
                7 : 7
                6 : 6
                5 : 5
                4 : 4
                3 : 3
                2 : 2

    */

    $cards = array();
    $suffled_cards = array();
    $game_cards_set = array();
    $players = -1;

    start_game_info($players);
    shuffle_cards($cards, $suffled_cards);
    game_ini($suffled_cards, $players, $game_cards_set);
    hit($suffled_cards, $game_cards_set, $players);
    show_game($players, $game_cards_set);
    find_winner($game_cards_set);

    //rank player in game
    function find_winner($game_cards_set) {
        echo "Game Winner :\n";
        $have_winner = 0;
        for ($i = 21; $i > 17; $i--) {
            foreach ($game_cards_set as $key => $value) {
                if ($game_cards_set[$key][6] == $i || $game_cards_set[$key][7] == $i) {
                    $player_num = $key + 1;
                    echo "Player" . $player_num . "\n";
                    $have_winner = 1;
                }
            }
            if ($have_winner == 1) {
                break;
            }
        }
        if ($have_winner == 0) {
            echo "No winner.\n";
        }
    }

    //show players cards
    function show_game($players, $game_cards_set) {
        for ($i = 0; $i < $players; $i++) {
            echo "Player" . str_pad($i + 1, 2, ' ');
            for ($j = 0; $j < $game_cards_set[$i][5]; $j++) {
                show_card($game_cards_set[$i][$j]);
            }
            echo  "Total :";
            if ($game_cards_set[$i][7] > 21) {
                echo  $game_cards_set[$i][6] . "\n";
            } else {
                echo  $game_cards_set[$i][7] . "\n";
            }
        }
    }

    //compute points
    function count_points(&$player_cards_set) {
        $player_cards_set[6] = 0;
        $player_cards_set[7] = 0;
        $special_case = 0;
        for ($i = 0; $i < $player_cards_set[5]; $i++) {
            $points = ($player_cards_set[$i] % 13);
            if ($points == 10 || $points == 11 || $points == 12) {
                $points = 9;
            }
            $player_cards_set[6] = $player_cards_set[6] + ($points + 1);
            $player_cards_set[7] = $player_cards_set[7] + ($points + 1);
            if ($points == 0) {
                $player_cards_set[7] = $player_cards_set[7] + 10;
            }
        }
    }

    //compute players need to hit or not 
    function hit(&$suffled_cards, &$game_cards_set, $players) {
        for ($i = 0; $i < $players; $i++) {
             count_points($game_cards_set[$i]);
             while ((($game_cards_set[$i][7] < 17 && $game_cards_set[$i][6] < 17) || ($game_cards_set[$i][7] >21 && $game_cards_set[$i][6] < 17)) && $game_cards_set[$i][5] < 5) {
                deal($suffled_cards, $game_cards_set, $i);
                count_points($game_cards_set[$i]);
            }
        }
    }
   
    //deal cards 
    function deal(&$suffled_cards, &$game_cards_set, $player) {
        $game_cards_set[$player][5]++; 
        $game_cards_set[$player][$game_cards_set[$player][5] - 1] = array_pop($suffled_cards);
    }

    //initial game setting
    function game_ini(&$suffled_cards, $players, &$game_cards_set) {
        //prepare game_cards_set space for players
        for ($i = 0; $i < $players; $i++) {
            $game_cards_set[$i] = array();
            for ($j = 0; $j < 8; $j++) {
                array_push($game_cards_set[$i], -1);
            }
            $game_cards_set[$i][5] = 0;
            $game_cards_set[$i][6] = (int)0;

        }
        //when game start, deal 2 cards to every players.
        for ($i = 0; $i < 2; $i++) { 
            for ($j = 0; $j < $players; $j++) {
                deal($suffled_cards, $game_cards_set, $j);
            }
        }
    }

    //shuffle cards
    function shuffle_cards(&$cards, &$suffled_cards) {
        //make sure array of cards is empty 
        $cards = array_diff($cards, $cards);
        //init cards
        for ($i = 0; $i < 52; $i++) {
            array_push($cards, $i);
        }
        //shuffle cards set
        for ($i = 51; $i >= 0; $i--) {
            $card_rand_key = rand(0, $i);
            $suffled_cards[$i] = $cards[$card_rand_key];
            $cards[$card_rand_key] = $cards[$i];
        }
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

    //When start game, show info to user what to do 
    function start_game_info(&$players) {
        $connect_times = 0;
        echo "Game Start! Please tell me how many players want to play this game[0~10]: ";
        fscanf(STDIN, "%d\n", $players);
        while ((!is_int($players) || $players < 0 || $players > 10) && $connect_times < 3) {
            if ($connect_times == 2) {
                echo "Warning! Too many illegal words in attempts. See you.\n";
                exit(0);
            }
            echo "Please tell me a positive integer number[0~10]. \nHow many players want to play this game: ";
            fscanf(STDIN, "%d\n", $players);
            $connect_times++;
        }
        if ($players == 0) {
            echo "No one here. Have a nice day, Bye!\n";
            exit(0);
        }
    }
?>


