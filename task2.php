<?php

/**
 * Script for downloading a page, extracting content from sections, and saving it to a database table.
 *
 * This script does the following:
 * 1. Establishes a database connection.
 * 2. Downloads the content of a specified page.
 * 3. Parses the HTML content to extract headings, abstracts, pictures, and links from sections.
 * 4. Saves the extracted data into the 'wiki_sections' database table.
 * 5. Closes the database connection.
 *
 * 
 */


$host = 'localhost';
$dbname = 'test';
$username = 'root';
$password = '';

try {
   $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
   die("Database connection failed: " . $e->getMessage());
}

// URL
// $url = 'http://localhost/test_task/php/task2html.php';   //test url with section
$url = 'https://www.wikipedia.org/';



$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0');
$html = curl_exec($curl);


if (curl_errno($curl)) {
   die("cURL error: " . curl_error($curl));
}
curl_close($curl);

// Create a new DOMDocument
$dom = new DOMDocument;
libxml_use_internal_errors(true);
$dom->loadHTML($html);
libxml_use_internal_errors(false);






// Extracting headings, abstracts, pictures, and links from sections
foreach ($dom->getElementsByTagName('section') as $section) {
   $title = $section->getElementsByTagName('h2')->item(0)->textContent;
   $abstract = $section->getElementsByTagName('p')->item(0)->textContent;
   $img = $section->getElementsByTagName('img')->item(0)->getAttribute('src');
   $link = $section->getElementsByTagName('a')->item(0)->getAttribute('href');
   $date = date('Y-m-d H:i:s');

   // Save the data in the database
   $sql = "INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (:date_created, :title, :url, :picture, :abstract)";
   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(':date_created', $date, PDO::PARAM_STR);
   $stmt->bindParam(':title', $title, PDO::PARAM_STR);
   $stmt->bindParam(':url', $link, PDO::PARAM_STR);
   $stmt->bindParam(':picture', $img, PDO::PARAM_STR);
   $stmt->bindParam(':abstract', $abstract, PDO::PARAM_STR);
   $stmt->execute();
}

echo "Data extracted and saved to the database.";


$pdo = null;
