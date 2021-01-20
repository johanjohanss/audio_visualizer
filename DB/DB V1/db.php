<?php

    function openDBConnection() {

        try {
            
            $dns = "mysql:host=localhost;dbname=audiovisual;charset=utf8";
            $userName = "root"; //Skapa gärna en egen användare med lösenord och viss rättighet.
            $password = "";
            $dbhOptions = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );

            $dbh = new PDO($dns, $userName, $password, $dbhOptions); 
            
            return $dbh;

        } catch(PDOException $e) {
            echo("DB ERROR");
            throw $e;
        }

    }

    function checkUploads($dbh, $user){
        try{

            $data = fetchUserData($dbh, $user);
            $rowCount = 0;

            foreach($data as $row) {
                $rowCount++;
            }

            if($rowCount < 10){ //User can upload 10 presets to database
                return true;
            }else{
                return false;
            }

        } catch(PDOException $e) {
            throw $e;
        }
    }

    function insertData($dbh, $kickRange, $barColor, $particleColor, $bgColor, $gradient1, $gradient2, $layout, $bandStyle, $infoCheck, $songTitle, $artistName, $textColor, $particleCheck, $EQWidth, $presetName, $user) {

        try {

            $userHasSpace = checkUploads($dbh, $user);

            if($userHasSpace){
                $sql = "INSERT INTO presets(kickRange, barColor, particleColor, bgColor, gradient1, gradient2, layout, bandStyle, infoCheck, songTitle, artistName, textColor, particleCheck, EQWidth, presetName, user) ";
                $sql .= "VALUES(:kickRange, :barColor, :particleColor, :bgColor, :gradient1, :gradient2, :layout, :bandStyle, :infoCheck, :songTitle, :artistName, :textColor, :particleCheck, :EQWidth, :presetName, :user);";

                $stmt = $dbh->prepare($sql);

                $stmt->bindValue(":kickRange", $kickRange);
                $stmt->bindValue(":barColor", $barColor);
                $stmt->bindValue(":particleColor", $particleColor);
                $stmt->bindValue(":bgColor", $bgColor);
                $stmt->bindValue(":gradient1", $gradient1);
                $stmt->bindValue(":gradient2", $gradient2);
                $stmt->bindValue(":layout", $layout);
                $stmt->bindValue(":bandStyle", $bandStyle);
                $stmt->bindValue(":infoCheck", $infoCheck);
                $stmt->bindValue(":songTitle", $songTitle);
                $stmt->bindValue(":artistName", $artistName);
                $stmt->bindValue(":textColor", $textColor);
                $stmt->bindValue(":particleCheck", $particleCheck);
                $stmt->bindValue(":EQWidth", $EQWidth);
                $stmt->bindValue(":presetName", $presetName);
                $stmt->bindValue(":user", $user);

                $stmt->execute();

                $stmt = null;

                return true;
            }else{
                return false;
            }
            

        } catch(PDOException $e) {
            throw $e;
        }
    }

    function fetchData($dbh, $user) {

        try {

            $sql = "SELECT * FROM presets WHERE user=:user OR user=:preset;";

            $stmt = $dbh->prepare($sql);

            $stmt->bindValue(":user", $user);
            $stmt->bindValue(":preset", "preset");

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;

            return $data;

        } catch(PDOException $e) {
            throw $e;
        }
    
    }

    function fetchUserData($dbh, $user) {

        try {

            $sql = "SELECT * FROM presets WHERE user=:user;";

            $stmt = $dbh->prepare($sql);

            $stmt->bindValue(":user", $user);

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;

            return $data;

        } catch(PDOException $e) {
            throw $e;
        }
    
    }

    function fetchPreset($dbh, $selectValue, $user){

        try {

            $sql = "SELECT * FROM presets WHERE id=:selectValue AND user=:user OR id=:selectValue AND user=:preset;";

            $stmt = $dbh->prepare($sql);

            
            $stmt->bindValue(":selectValue", $selectValue);
            $stmt->bindValue(":user", $user);
            $stmt->bindValue(":preset", "preset");

            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null;

            return $data;

        } catch(PDOException $e) {
            throw $e;
        }

    }

    function updateData($dbh, $id, $kickRange, $barColor, $particleColor, $bgColor, $gradient1, $gradient2, $layout, $bandStyle, $infoCheck, $songTitle, $artistName, $textColor, $particleCheck, $EQWidth, $presetName, $user) {

        try {

            $sql = "UPDATE presets SET kickRange=:kickRange, barColor=:barColor, particleColor=:particleColor, ";
            $sql .= "bgColor=:bgColor, gradient1=:gradient1, gradient2=:gradient2, layout=:layout, bandStyle=:bandStyle, infoCheck=:infoCheck, songTitle=:songTitle, artistName=:artistName, textColor=:textColor, particleCheck=:particleCheck, EQWidth=:EQWidth, presetName=:presetName, user=:user ";
            $sql .= "WHERE id=:id AND user=:user;";

            $stmt = $dbh->prepare($sql);

            $stmt->bindValue(":id", $id);
            $stmt->bindValue(":kickRange", $kickRange);
            $stmt->bindValue(":barColor", $barColor);
            $stmt->bindValue(":particleColor", $particleColor);
            $stmt->bindValue(":bgColor", $bgColor);
            $stmt->bindValue(":gradient1", $gradient1);
            $stmt->bindValue(":gradient2", $gradient2);
            $stmt->bindValue(":layout", $layout);
            $stmt->bindValue(":bandStyle", $bandStyle);
            $stmt->bindValue(":infoCheck", $infoCheck);
            $stmt->bindValue(":songTitle", $songTitle);
            $stmt->bindValue(":artistName", $artistName);
            $stmt->bindValue(":textColor", $textColor);
            $stmt->bindValue(":particleCheck", $particleCheck);
            $stmt->bindValue(":EQWidth", $EQWidth);
            $stmt->bindValue(":presetName", $presetName);
            $stmt->bindValue(":user", $user);

            $stmt->execute();

            $stmt = null;

            return true;

        } catch(PDOException $e) {
            throw $e;
        }
    }

    function deleteData($dbh, $id, $user) {

        try {

            $sql = "DELETE FROM presets WHERE id=:id AND user=:user;";

            $stmt = $dbh->prepare($sql);

            $stmt->bindValue(":id", $id);
            $stmt->bindValue(":user", $user);

            $stmt->execute();

            $stmt = null;

            return true;

        } catch(PDOException $e) {
            throw $e;
        }
    }

    function closeDBConnection(&$db) { 
        $db = null;
    }

?>