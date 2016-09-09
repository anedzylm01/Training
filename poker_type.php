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
            [5] => 牌型
            [6] => 牌組分數
        牌組分數計算 (牌型 + 點數 * 花色)
            牌型
                同花順 Straight Flush : 1700
                鐵扇 Four of a Kind : 1500
                葫蘆 Full house : 1300
                同花 Flush : 1100
                順子 Straight : 900
                三條 Three of a kind : 700
                兩對 Two Pairs : 500
                一對 One Pair : 300
                高牌 High card : 100
            點數
                A : 14 (同花順和順子中A如果配上2345時當做1點。)
                K : 13
                Q : 12
                J : 11
                10 : 10
                9 : 9
                8 : 8
                7 : 7
                6 : 6
                5 : 5
                4 : 4
                3 : 3
                2 : 2   
            花色
                ♠ : 4
                ♥ : 3
                ♦ : 2
                ♣ : 1

    */

    $cards = array();
    $suffled_cards = array();
    $game_cards_set = array();
    $players = -1;

    start_game_info($players);
    shuffle_cards($cards, $suffled_cards);
    deal($suffled_cards, $players, $game_cards_set);
    card_type($players, $game_cards_set);
    show_game($players, $game_cards_set);
    game_rank($players, $game_cards_set);

    //according to game_cards_set grades to sort
    function sort_by_grades($a, $b) {
        if ($a[6] == $b[6]) {
            return 0;
        }
        return ($a[6] > $b[6]) ? -1 : 1;
    }

    //rank play in game
    function game_rank($players, $game_cards_set){
        uasort($game_cards_set, 'sort_by_grades');
        echo "Game Rank:\n";
        foreach ($game_cards_set as $key => $value) {
            echo "Player" . str_pad($key + 1, 3, ' ') . "\n";
        }
    }

    //show players cards
    function show_game($players, $game_cards_set){
        for ($i = 0; $i < $players; $i++) {
            echo "Player" . str_pad($i + 1, 3, ' ');
            for ($j = 0; $j < 5; $j++) {
                show_card($game_cards_set[$i][$j]);
            }
            echo " {$game_cards_set[$i][5]}\n";
        }
    }

    //compute grades by suit
    function count_suit(&$player_cards_set){
        for ($i = 0; $i < 5; $i++) {
            $points = $player_cards_set[$i] % 13;
            //specil case for Straight & Straight Flush
            if ($player_cards_set[5] == "Straight Flush" || $player_cards_set[5] == "Straight") {
                if ($points == 0) {
                    $points = 1;
                }
            }
            else{
                if ($points == 0) {
                    $points = 14;
                }
            }
            $player_cards_set[6] = $player_cards_set[6] + $points * ((int)($player_cards_set[$i] / 13) + 1);
        }
    }

    //Four of a Kind & Full house & Three of a kind & Two Pairs & One Pairwill & High card will be found here
    function pair_or_more(&$player_cards_set){
        $cards_without_suit = array();
        foreach ($player_cards_set as $key => $value) {
            $cards_without_suit[$key] = $value % 13;
        }
        $player_cards_set_unique = array_unique($cards_without_suit);
        switch (count($player_cards_set_unique)) {
                case 2:
                    $max_same_cards_num = max(array_count_values($cards_without_suit));
                    if ($max_same_cards_num == 3) {
                        array_push($player_cards_set, "Full house");
                        array_push($player_cards_set, 1300); //Full house
                    }
                    if ($max_same_cards_num == 4) {
                        array_push($player_cards_set, "Four of a Kind");
                        array_push($player_cards_set, 1500); //Four of a Kind
                    }
                    break;
                case 3:
                    $max_same_cards_num = max(array_count_values($cards_without_suit));
                    if ($max_same_cards_num == 3) {
                        array_push($player_cards_set, "Three of a kind");
                        array_push($player_cards_set, 700); //Three of a kind
                    }
                    if ($max_same_cards_num == 2) {
                        array_push($player_cards_set, "Two Pairs");
                        array_push($player_cards_set, 500); //Two Pairs
                    }
                    break;
                case 4:
                    array_push($player_cards_set, "One Pair");
                    array_push($player_cards_set, 300); //One Pair
                    break;
                default:
                    array_push($player_cards_set, "High card");
                    array_push($player_cards_set, 100); //High card
                    break;
        }
    }

    // check flush
    function bool_flush($player_cards_set){
        $is_flush = -1;
        for ($i = 0; $i < 4; $i++) {
            if ((int)($player_cards_set[$i + 1] / 13) == (int)($player_cards_set[$i] / 13)) {
            }
            else{
                $is_flush = 0;
                return false;
            }
        }
        if ($is_flush != 0) {
            return true;
        }
    }

    //Straight & Straight Flush will be found here
    function straight(&$player_cards_set){
        $is_straight = -1;
        //straight
        for ($i = 0; $i < 4; $i++) { 
            if (($player_cards_set[$i + 1] % 13 - $player_cards_set[$i] % 13) == 1) {
            }
            else
            {
                $is_straight = 0;
                break;
            }
        }
        if ($is_straight != 0) {
            if (bool_flush($player_cards_set)) {
                array_push($player_cards_set, "Straight Flush");
                array_push($player_cards_set, 1700); //Straight Flush
            }
            else{
                array_push($player_cards_set, "Straight");
                array_push($player_cards_set, 900); //Straight
            }
        }
    }

    //compute what type of cards and compute cards' grades
    function card_type($players, &$game_cards_set){
        for ($i = 0; $i < $players; $i++) { 
            straight($game_cards_set[$i]);
            if (bool_flush($game_cards_set[$i])) {
                array_push($player_cards_set, "Flush");
                array_push($player_cards_set, 1100);//Flush
            }
            pair_or_more($game_cards_set[$i]);
            count_suit($game_cards_set[$i]);
        }
    }

    //deal cards to every player
    function deal(&$suffled_cards, $players, &$game_cards_set){
        //prepare game_cards_set space for players
        for ($i = 0; $i < $players; $i++) {
            $game_cards_set[$i] = array();
        }

        //eveyone has 5 cards
        for ($i = 0; $i < 5; $i++) { 
            for ($j = 0; $j < $players; $j++) {
                array_push($game_cards_set[$j], array_pop($suffled_cards));
                sort($game_cards_set[$j]);
            }
        }
    }

    //shuffle cards
    function shuffle_cards(&$cards, &$suffled_cards){
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

    //convert to cards graph by numbers
    function show_cards($cards) {
        for ($i = 0; $i < 52; $i++) {
            show_card($cards[$i]);
            if ($i % 13 == 12) {
                echo "\n";
            }
        }
    }

    //When start game, show info to user what to do 
    function start_game_info(&$players){
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
