<?php

if (!function_exists('shortName')) {
     function shortName($string){
        $words = explode(" ", $string); // Split the string into words

        $firstLetters = "";
        foreach ($words as $word) {
            $firstLetters .= strtoupper($word[0]); // Get the first letter of each word and convert it to uppercase
        }
        return $firstLetters;
    }
}