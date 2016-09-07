<?php
    for ($i = 1; $i < 10; $i++) {
        for ($j = 1; $j < 10; $j++) {
            echo "{$j}*{$i} = " . str_pad($i * $j, 2, ' ') . " ";
        }
        echo "\n";
    }
?>