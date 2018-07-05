<?php

function most_frequent_words($inputString, $stopWords, $limit) {
    $inputString = strtolower($inputString);

    //get all words in string
    $words = str_word_count($inputString, 1);
    //remove stopWords from string before processing
    $words = array_diff($words, $stopWords);
    //count no. of times words were repeated
    $words = array_count_values($words);
    //sort array based on no. of times they were repeated, so higher frequency words get in start of array
    arsort($words);
    //get only required no. of top words
    $result = array_slice($words, 0, $limit);

    return $result;
}

//since we need to automate let's allow any number of files as input
$fileName = "post";
$fileExt = ".md";
$stopWordsFile = "stopwords.txt";

$i = 1;
$inputString = "";
//checking if file exists
while (file_exists($fileName . $i . $fileExt)) {
    $file = $fileName . $i . $fileExt;
    $i++;
    $handle = fopen($file, "r");
    $inputString .= fread($handle, filesize($file));
}

$handle = fopen($stopWordsFile, "r");
$stopWords = fread($handle, filesize($stopWordsFile));
//get array of stopWords by exploding it
$stopWords = explode("\r\n", $stopWords);

$topWords = most_frequent_words($inputString, $stopWords, 10);

$i = 1;
foreach($topWords as $topWord => $count) {
    echo "#" . $i . " " . $topWord . " appears " . $count . " times<br>";
    $i++;
}