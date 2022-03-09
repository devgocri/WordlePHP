<?php

/*
 * devgocri 2022
 * Made for Hebsi#3789
 *
 * The code written here is very bad
 * Please try not to copy, my code will prolly wreck your project
 */

$word = "";
$words = [];
$lines = [
    "-----",
    "-----",
    "-----",
    "-----",
    "-----",
    "-----"
];
$eliminated = [];

game();

function game(): void
{
    printLines();
    GLOBAL $word, $words, $lines;
    if($word === "") randomWord();
    if($lines[5] !== "-----"){
        echo "\033[31m You lost! The word was:\033[32m $word \033[0m \n";
        exit();
    }
    echo alphabet() . "\n";
    $input = readline("Enter your guess: ");
    if(strlen($input) !== 5){
        echo "\033[31m The guess must be a word with 5 letters. \033[0m \n";
        sleep(2);
        game();
    }
    if(!ctype_alpha($input)){
        echo "\033[31m Invalid guess. \033[0m \n";
        sleep(2);
        game();
    }
    if(!in_array($input, $words)){
        echo "\033[31m Enter a valid word. \033[0m \n";
        sleep(2);
        game();
    }
    process($input);
    if($input === $word){
        echo "\033[32m You won! \033[0m \n";
        exit();
    }
    game();
}

function printLines(): void
{
    GLOBAL $lines;
    foreach ($lines as $line){
        echo "\033[97m $line \033[0m \n";
    }
}

function randomWord(): void
{
    $api = file_get_contents("https://raw.githubusercontent.com/tabatkins/wordle-list/main/words");
    GLOBAL $words;
    $words = explode("\n", str_replace('"', "", $api));
    GLOBAL $word;
    $word = $words[array_rand($words)];
}

function process(string $guess): void
{
    GLOBAL $word, $lines, $eliminated;
    $gletters = str_split($guess);
    $wletters = str_split($word);

    $line = [];

    foreach($gletters as $key => $letter){
        if($letter === $wletters[$key]){
            $line[] = "\033[32m $letter \033[0m";
        }else if(in_array($letter, $wletters)){
            $line[] = "\033[33m $letter \033[0m";
        }
        if(!in_array($letter, $wletters)){
            $line[] = "\033[90m $letter \033[0m";
            $eliminated[] = $letter;
        }
    }

    $completeLine = implode("", $line);

    foreach($lines as $key => $wordleline){
        if($wordleline === "-----"){
            $lines[$key] = $completeLine;
            return;
        }
    }
}

function alphabet(): string
{
    GLOBAL $eliminated;
    $alphabet = str_split("qwertyuiopasdfghjklzxcvbnm");
    foreach($alphabet as $key => $letter){
        if(in_array($letter, $eliminated)){
            $alphabet[$key] = "\033[90m$letter\033[0m";
        }
    }
    return implode(" ", $alphabet);
}
