<?php

$directory = './datafiles';
$pattern = '/^[a-zA-Z0-9]+\.ixt$/';
$matchingFiles = [];


if ($handle = opendir($directory)) {
   while (false !== ($file = readdir($handle))) {

      if (preg_match($pattern, $file)) {
         $matchingFiles[] = $file;
      }
   }
   closedir($handle);
}


sort($matchingFiles);


echo "Matching files in $directory:<br>";
foreach ($matchingFiles as $key => $filename) {
   echo $key . "=>" . $filename . "<br>";
}
