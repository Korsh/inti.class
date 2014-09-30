<?php

    final class Init
    {

        private $db;
        private $resultArr;
        private $sriptNameArr;
        /*
        *
        *
        */
        function init($DBH)
        {
            $this->db = $DBH;
            $this->create();
            $this->fill();
            $this->resultArr = array(
                0 => "normal",
                1 => "illegal",
                2 => "failed",
                3 => "success",
            )
            
            $this->scriptNameArr = array(
                0 => "script1",
                1 => "script2",
                2 => "script3",
                3 => "script4",
                4 => "script5",
                5 => "script6",
                6 => "script7",
            )
        }
        
        /*
        *
        *
        */
        function private create()
        {
            try {
                $this->db->execute(
                    "CREATE TABLE `test` (
                        `id` int(11) auto_increment not NULL,
                        `script_name` varchar(25) not NULL,
                        `start_time` int(11),
                        `end_time` int(11),
                        `result` varchar(7),
                        UNIQUE KEY `id`  (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
                    ");            
            }
            catch(PDOException $e) {
                echo $e->getMessage();
                file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
            }
        }
        
        
        /*
        *
        *
        */
        function private fill()
        {
            try {
                $insertQuery = $this->db->prepare(
                "INSERT INTO `test`
                    (`script_name`,
                    `start_time`,
                    `end_time`
                    `result`) 
                VALUES
                    (:scriptName,
                    UNIX_TIMESTAMP(NOW()),
                    UNIX_TIMESTAMP(NOW()),
                    :result
                    );
                ");
                $insertQuery->execute(array(":scriptName" => $this->scriptNameArr[rand(0, sizeof($this->scriptNameArr))], ":result" => $this->resultArr[rand(0, sizeof($this->resultArr))]))
            }
            catch(PDOException $e) {
                echo $e->getMessage();
                file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
            }
        }
        
        /*
        *
        *
        */    
        function public get()
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
                WHERE `result` IN (:resultArr)
                ;");            
                $selectQuery->execute(array(':resultArr' => array("normal","success")));
                if($selectQuery->rowCount() > 0) {
                    $i = 0;
                    while($row = $selectQuery->fetch()) {
                        $returnArr[$i]['script_name'] = $row['script_name'];
                        $returnArr[$i]['start_time'] = $row['start_time'];
                        $returnArr[$i]['end_time'] = $row['end_time'];
                        $returnArr[$i]['result'] = $row['result'];
                        $i++
                    }
                    return $returnArr;
                }
            }
            catch(PDOException $e) {
                echo $e->getMessage();
                file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
            }
        }
    }
?>
