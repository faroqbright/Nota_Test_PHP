<?php

/**
 * Class TableCreator
 *
 * This class is responsible for creating and managing a table named 'Test'.
 * It provides methods to create the table, fill it with random data, and retrieve data based on criteria.
 */
final class TableCreator
{
   private $pdo; // Database connection

   /**
    * Constructor
    *
    * This method initializes the class, creates the 'Test' table, and fills it with random data.
    */
   public function __construct()
   {
      $host = 'localhost';
      $dbname = 'test';
      $username = 'root';
      $password = '';

      try {
         $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
         die("Database connection failed: " . $e->getMessage());
      }

      // Create the 'Test' table
      $this->create();

      // Fill the 'Test' table with random data
      $this->fill();
   }

   /**
    * Create a 'Test' table
    *
    * This method creates the 'Test' table with specific fields and constraints.
    */
   private function create()
   {
      $query = "CREATE TABLE IF NOT EXISTS Test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            script_name VARCHAR(25),
            start_time DATETIME,
            end_time DATETIME,
            result ENUM('normal', 'illegal', 'failed', 'success')
        )";

      $this->pdo->exec($query);
   }

   /**
    * Fill the 'Test' table with random data
    *
    * This method generates random data and inserts it into the 'Test' table.
    */
   private function fill()
   {
      // Generate and insert random data (you can use any method to generate data)
      $scriptNames = ["ScriptA", "ScriptB", "ScriptC"];
      $results = ["normal", "illegal", "failed", "success"];

      for ($i = 0; $i < 10; $i++) {
         $scriptName = $scriptNames[array_rand($scriptNames)];
         $startTime = date("Y-m-d H:i:s", rand(0, time()));
         $endTime = date("Y-m-d H:i:s", rand(strtotime($startTime), time()));
         $result = $results[array_rand($results)];

         $query = "INSERT INTO Test (script_name, start_time, end_time, result)
                      VALUES (:script_name, :start_time, :end_time, :result)";
         $stmt = $this->pdo->prepare($query);
         $stmt->bindParam(':script_name', $scriptName, PDO::PARAM_STR);
         $stmt->bindParam(':start_time', $startTime, PDO::PARAM_STR);
         $stmt->bindParam(':end_time', $endTime, PDO::PARAM_STR);
         $stmt->bindParam(':result', $result, PDO::PARAM_STR);
         $stmt->execute();
      }
   }

   /**
    * Get data from the 'Test' table based on the 'result' criteria
    *
    * This method retrieves data from the 'Test' table where the 'result' column matches
    * the provided values ('normal' and 'success').
    *
    * @return array The selected data.
    */
   public function get()
   {
      $query = "SELECT * FROM Test WHERE result IN ('normal', 'success')";
      $stmt = $this->pdo->query($query);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   }
}


$createTable = new TableCreator();
print_r($createTable->get());
