<?php
    
    /**
     * @author Arzhanov Andrey <arzhan87@gmail.com>
     */
    final class Init
    {

        private $db;
        private $resultArr;
        private $sriptNameArr;
        
        /**
         * Constructor
         * @param resource $DBH Connection to the DataBase
         * 
         * @return void
         */
        function __construct($DBH) 
        {
            $this->db = $DBH;
            $this->resultArr = array(
                0 => "normal",
                1 => "illegal",
                2 => "failed",
                3 => "success",
            );
            
            $this->scriptNameArr = array(
                0 => "script1",
                1 => "script2",
                2 => "script3",
                3 => "script4",
                4 => "script5",
                5 => "script6",
                6 => "script7",
            );
            $this->create();
            $this->fill();
        }
        
        /**
         * Destructor
         * 
         * @return void
         */
        function __destruct()
        {
            unset($this->db);
            unset($this->resultArr);
            unset($this->scriptNameArr);
        }
        
        /**
         * pirivate create() create table test
         * 
         * @return void
         */
        private function create()
        {
            try {
                $createTableQuery = $this->db->prepare(
                    "CREATE TABLE IF NOT EXISTS `test` (
                        `id` int(11) auto_increment not NULL,
                        `script_name` varchar(25) not NULL,
                        `start_time` int(11),
                        `end_time` int(11),
                        `result` varchar(7),
                        UNIQUE KEY `id`  (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
                    ");
                $createTableQuery->execute();
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        
        
        /**
         * private fill() fill table `test` with random values
         * 
         * @return void
         */
        private function fill()
        {
            try {
                $insertQuery = $this->db->prepare(
                "INSERT INTO `test`
                    (`script_name`,
                    `start_time`,
                    `end_time`,
                    `result`) 
                VALUES
                    (:scriptName,
                    UNIX_TIMESTAMP(NOW()),
                    UNIX_TIMESTAMP(NOW()),
                    :result
                    );
                ");
                $insertQuery->execute(array(":scriptName" => $this->scriptNameArr[rand(0, sizeof($this->scriptNameArr) - 1)], ":result" => $this->resultArr[rand(0, sizeof($this->resultArr))]));
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        
        /**
         * public get() return data with normal and success `result`
         * 
         * @return array $returnArr
         */
        public function get()
        {
            try {
                $selectQuery = $this->db->prepare("
                SELECT
                    `script_name`,
                    `start_time`,
                    `end_time`,
                    `result`
                FROM
                    `test`
                WHERE `result` IN ('normal', 'success')
                ;");
                $selectQuery->execute();
                if($selectQuery->rowCount() > 0) {
                    $i = 0;
                    while($row = $selectQuery->fetch()) {
                        $returnArr[$i]['script_name'] = $row['script_name'];
                        $returnArr[$i]['start_time'] = $row['start_time'];
                        $returnArr[$i]['end_time'] = $row['end_time'];
                        $returnArr[$i]['result'] = $row['result'];
                        $i++;
                    }
                    return $returnArr;
                }
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
