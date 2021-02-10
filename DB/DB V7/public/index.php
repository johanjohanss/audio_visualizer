<?php 
    include("../db.php");


    $loggedIn = false;
    $user = "default";
    session_start();

    //LOG OUT
    if(isset($_POST["logout"])){

        // Unset all of the session variables
        $_SESSION = array();
        
        // Destroy the session.
        session_destroy();

    }

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        $loggedIn = true;
        $user = $_SESSION["username"];
    }

    try {
        $dbh = openDBConnection();

        if(isset($_POST["deletePreset"]) && $loggedIn){
        
            if(isset($_POST["presetSelect"])){
    
                if($_POST["presetSelect"] != "Choose Preset"){
                    $selectValue = $_POST["presetSelect"];
                    $dbh = openDBConnection();
                    $data = fetchPreset($dbh, $selectValue, $user); //CHANGELOG - unneeded line?
                    deleteData($dbh, $selectValue, $user);
                    $deleteMessage = "Preset Deleted";
                }else{
                    //$valid = false;
                    $deleteMessage = "Preset not deleted";            }
            }
        }

        //POSTING PRESET
        if(isset($_POST["submitForm"]) && $loggedIn){

            //VALIDATING DATA

            $valid = true;
            //$saveMessage = "Default";

            if(isset($_POST["kickRange"])){
                if(is_numeric($_POST["kickRange"])){
                    $kickRange = $_POST["kickRange"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["barColor"])){
                if(strlen($_POST["barColor"]) == 7){
                        $barColor = $_POST["barColor"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["particleColor"])){
                if(strlen($_POST["particleColor"]) == 7){
                        $particleColor = $_POST["particleColor"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["bgColor"])){
                if(strlen($_POST["bgColor"]) == 7){
                        $bgColor = $_POST["bgColor"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["gradient1"])){
                if(strlen($_POST["gradient1"]) == 7){
                        $gradient1 = $_POST["gradient1"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["gradient2"])){
                if(strlen($_POST["gradient2"]) == 7){
                        $gradient2 = $_POST["gradient2"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["layout-select"])){
                $layout = $_POST["layout-select"]; 
            }else{
                $valid = false;
            }

            if(isset($_POST["band-select"])){
                $bandStyle = $_POST["band-select"]; 
            }else{
                $valid = false;
            }

            if(isset($_POST["infocheck"])){
                $infoCheck = $_POST["infocheck"];
            }else{
                $infoCheck = "off";
            }

            $songTitle = $_POST["song-title"];
            $artistName = $_POST["artist-name"];
            
            if(isset($_POST["text-color"])){
                if(strlen($_POST["text-color"]) == 7){
                        $textColor = $_POST["text-color"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["particlecheck"])){
                $particleCheck = $_POST["particlecheck"];
            }else{
                $particleCheck = "off";
            }

            if(isset($_POST["range-eq"])){
                if(is_numeric($_POST["range-eq"])){
                    $EQWidth = $_POST["range-eq"];
                }else{
                    $valid = false;
                }
            }

            //Update 2 Data
            if(isset($_POST["bandWidth"])){
                if(is_numeric($_POST["bandWidth"])){
                    $bandWidth = $_POST["bandWidth"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["sizeVar"])){
                if(is_numeric($_POST["sizeVar"])){
                    $sizeVar = $_POST["sizeVar"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["spawnRate"])){
                if(is_numeric($_POST["spawnRate"])){
                    $spawnRate = $_POST["spawnRate"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["pOpacity"])){
                if(is_numeric($_POST["pOpacity"])){
                    $pOpacity = $_POST["pOpacity"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["speedX"])){
                if(is_numeric($_POST["speedX"])){
                    $speedX = $_POST["speedX"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["speedY"])){
                if(is_numeric($_POST["speedY"])){
                    $speedY = $_POST["speedY"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["repeatCheck"])){
                $repeatCheck = $_POST["repeatCheck"];
            }else{
                $repeatCheck = "off";
            }

            if(isset($_POST["coverCheck"])){
                $coverCheck = $_POST["coverCheck"];
            }else{
                $coverCheck = "off";
            }

            if(isset($_POST["bgColorOpacity"])){
                if(is_numeric($_POST["bgColorOpacity"])){
                    $bgColorOpacity = $_POST["bgColorOpacity"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["font-title"])){
                if(is_numeric($_POST["font-title"])){
                    $fontTitle = $_POST["font-title"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["font-artist"])){
                if(is_numeric($_POST["font-artist"])){
                    $fontArtist = $_POST["font-artist"];
                }else{
                    $valid = false;
                }
            }

            //---

            //Movement Check
            if(isset($_POST["moveCheck"])){
                $moveCheck = $_POST["moveCheck"];
            }else{
                $moveCheck = "off";
            }
            

            if(isset($_POST["presetName"])){
                $presetName = $_POST["presetName"];
            }else{
                $valid = false;
            }
             
            if($valid){
                $dataInserted = insertData($dbh,
                $kickRange,
                $barColor, 
                $particleColor,
                $bgColor,
                $gradient1,
                $gradient2,
                $layout,
                $bandStyle,
                $infoCheck,
                $songTitle,
                $artistName,
                $textColor,
                $particleCheck, 
                $EQWidth,
                $bandWidth,
                $sizeVar,
                $spawnRate,
                $pOpacity,
                $speedX,
                $speedY,
                $repeatCheck,
                $coverCheck,
                $bgColorOpacity,
                $fontTitle,
                $fontArtist,
                $moveCheck,
                $presetName,
                $user
                );

                if($dataInserted){
                    $saveMessage = "Preset Saved";
                }else{
                    $saveMessage = "Max preset limit reached!";
                }
                
            }else{
                $saveMessage = "Preset Could Not Be Saved. Check that the data is correct.";
            }
        }

        //LOADING IN ALL PRESETS
        //Should be run every time to display presets in Select element
        $data = fetchData($dbh, $user);
        
        if(count($data) !== 0) {
            
            $presetSelect = "<select name='presetSelect' id='presetSelect'><option>Choose Preset</option>";
    
            foreach($data as $row) {
                $id = $row["id"];
                $presetNameIn = $row["presetName"];
                $presetName = $presetNameIn;//"Preset " .  $id;
                $name="preset" . $id;
                $presetSelect .= "<option value='$id'";
                
                if(isset($_POST["loadPreset"])){
                    if($_POST["presetSelect"] == $id){
                        //echo("selected='selected'")
                        $presetSelect .= " selected='selected'";
                    }
                }

                //Making preset load on update
                if(isset($_POST["updatePreset"])){
                    if($_POST["presetSelect"] == $id){
                        //echo("selected='selected'")
                        $presetSelect .= " selected='selected'";
                    }
                }
                
                $presetSelect .= ">$presetName</option>";

                //Values from DB
                $barColor = $row["barColor"];
            }
            $presetSelect .= "</select>";
                
        } else {
            $msg = "<p>No presets available</p>";
        }

    } catch( PDOException $e) {
        $msg = $e->getMessage();
    }

    closeDBConnection($dbh);

    //LOADING PRESET
    if(isset($_POST["loadPreset"])){
        
        if(isset($_POST["presetSelect"])){

            if($_POST["presetSelect"] != "Choose Preset"){
                $selectValue = $_POST["presetSelect"];
                $dbh = openDBConnection();
                $data = fetchPreset($dbh, $selectValue, $user);
                
                foreach($data as $row) {
                    //Values from DB
                    $id = $row["id"];
                    $presetKickRange = $row["kickRange"];
                    $presetBarColor = $row["barColor"];
                    $presetParticleColor = $row["particleColor"];
                    $presetBgColor = $row["bgColor"];
                    $presetGradient1 = $row["gradient1"];
                    $presetGradient2 = $row["gradient2"];

                    $presetLayout = $row["layout"];
                    $presetBandStyle = $row["bandStyle"];
                    $presetInfoCheck = $row["infoCheck"];
                    $presetSongTitle = $row["songTitle"];
                    $presetArtistName = $row["artistName"];
                    $presetTextColor = $row["textColor"];
                    $presetParticleCheck = $row["particleCheck"];
                    $bandWidth = $row["bandWidth"];
                    $sizeVar = $row["sizeVar"];
                    $spawnRate = $row["spawnRate"];
                    $pOpacity = $row["pOpacity"];
                    $speedX = $row["speedX"];
                    $speedY = $row["speedY"];
                    $repeatCheck = $row["repeatCheck"];
                    $coverCheck = $row["coverCheck"];
                    $bgColorOpacity = $row["bgColorOpacity"];
                    $fontTitle = $row["fontTitle"];
                    $fontArtist = $row["fontArtist"];
                    $fontArtist = $row["fontArtist"];
                    $presetMoveCheck = $row["moveCheck"];
                    $presetEQWidth = $row["EQWidth"];
                    $presetPresetName = $row["presetName"];
                }
            }else{
                //Show error message - select a preset first
            }
            
        }
        closeDBConnection($dbh);
    }

    if(isset($_POST["updatePreset"]) && $loggedIn){

            $dbh = openDBConnection();

            //VALIDATING DATA

            $valid = true;
            if(isset($_POST["presetSelect"])){
                $id = $_POST["presetSelect"];
            }else{
                $valid = false;
            }
            

            if(isset($_POST["kickRange"])){
                if(is_numeric($_POST["kickRange"])){
                    $kickRange = $_POST["kickRange"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["barColor"])){
                if(strlen($_POST["barColor"]) == 7){
                        $barColor = $_POST["barColor"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["particleColor"])){
                if(strlen($_POST["particleColor"]) == 7){
                        $particleColor = $_POST["particleColor"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["bgColor"])){
                if(strlen($_POST["bgColor"]) == 7){
                        $bgColor = $_POST["bgColor"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["gradient1"])){
                if(strlen($_POST["gradient1"]) == 7){
                        $gradient1 = $_POST["gradient1"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["gradient2"])){
                if(strlen($_POST["gradient2"]) == 7){
                        $gradient2 = $_POST["gradient2"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["layout-select"])){
                $layout = $_POST["layout-select"]; 
            }else{
                $valid = false;
            }

            if(isset($_POST["band-select"])){
                $bandStyle = $_POST["band-select"]; 
            }else{
                $valid = false;
            }

            if(isset($_POST["infocheck"])){
                $infoCheck = $_POST["infocheck"];
            }else{
                $infoCheck = "off";
            }

            $songTitle = $_POST["song-title"];
            $artistName = $_POST["artist-name"];
            
            if(isset($_POST["text-color"])){
                if(strlen($_POST["text-color"]) == 7){
                        $textColor = $_POST["text-color"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["particlecheck"])){
                $particleCheck = $_POST["particlecheck"];
            }else{
                $particleCheck = "off";
            }

            if(isset($_POST["range-eq"])){
                if(is_numeric($_POST["range-eq"])){
                    $EQWidth = $_POST["range-eq"];
                }else{
                    $valid = false;
                }
            }

            //Update 2 Data
            if(isset($_POST["bandWidth"])){
                if(is_numeric($_POST["bandWidth"])){
                    $bandWidth = $_POST["bandWidth"];
                }else{
                    $valid = false;
                }
            }
            //echo $valid;

            if(isset($_POST["sizeVar"])){
                if(is_numeric($_POST["sizeVar"])){
                    $sizeVar = $_POST["sizeVar"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["spawnRate"])){
                if(is_numeric($_POST["spawnRate"])){
                    $spawnRate = $_POST["spawnRate"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["pOpacity"])){
                if(is_numeric($_POST["pOpacity"])){
                    $pOpacity = $_POST["pOpacity"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["speedX"])){
                if(is_numeric($_POST["speedX"])){
                    $speedX = $_POST["speedX"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["speedY"])){
                if(is_numeric($_POST["speedY"])){
                    $speedY = $_POST["speedY"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["repeatCheck"])){
                $repeatCheck = $_POST["repeatCheck"];
            }else{
                $repeatCheck = "off";
            }

            if(isset($_POST["coverCheck"])){
                $coverCheck = $_POST["coverCheck"];
            }else{
                $coverCheck = "off";
            }

            if(isset($_POST["bgColorOpacity"])){
                if(is_numeric($_POST["bgColorOpacity"])){
                    $bgColorOpacity = $_POST["bgColorOpacity"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["font-title"])){
                if(is_numeric($_POST["font-title"])){
                    $fontTitle = $_POST["font-title"];
                }else{
                    $valid = false;
                }
            }

            if(isset($_POST["font-artist"])){
                if(is_numeric($_POST["font-artist"])){
                    $fontArtist = $_POST["font-artist"];
                }else{
                    $valid = false;
                }
            }

            //---
            //Movement Check
            if(isset($_POST["moveCheck"])){
                $moveCheck = $_POST["moveCheck"];
            }else{
                $moveCheck = "off";
            }

            //Movement Check
            if(isset($_POST["moveCheck"])){
                $moveCheck = $_POST["moveCheck"];
            }else{
                $moveCheck = "off";
            }

            if(isset($_POST["presetName"])){
                $presetName = $_POST["presetName"];
            }else{
                $valid = false;
            }

            if($valid){
            $validUpdate = updateData($dbh,
                $id,
                $kickRange,
                $barColor, 
                $particleColor,
                $bgColor,
                $gradient1,
                $gradient2,
                $layout,
                $bandStyle,
                $infoCheck,
                $songTitle,
                $artistName,
                $textColor,
                $particleCheck, 
                $EQWidth,
                $bandWidth,
                $sizeVar,
                $spawnRate,
                $pOpacity,
                $speedX,
                $speedY,
                $repeatCheck,
                $coverCheck,
                $bgColorOpacity,
                $fontTitle,
                $fontArtist,
                $moveCheck,
                $presetName,
                $user
                );
                if($validUpdate){
                    $updateMessage = "Preset Updated";
                }else{
                    $updateMessage = "No Updates Were Made";
                }
                
            }else{
                $updateMessage = "Preset Could Not Be Saved. Check that the data is correct.";
            }

            
            //Making preset load on update        
            $selectValue = $_POST["presetSelect"];
            $dbh = openDBConnection();
            $data = fetchPreset($dbh, $selectValue, $user);
            
            foreach($data as $row) {
                //Values from database
                $id = $row["id"];
                $presetKickRange = $row["kickRange"];
                $presetBarColor = $row["barColor"];
                $presetParticleColor = $row["particleColor"];
                $presetBgColor = $row["bgColor"];
                $presetGradient1 = $row["gradient1"];
                $presetGradient2 = $row["gradient2"];
    
                $presetLayout = $row["layout"];
                $presetBandStyle = $row["bandStyle"];
                $presetInfoCheck = $row["infoCheck"];
                $presetSongTitle = $row["songTitle"];
                $presetArtistName = $row["artistName"];
                $presetTextColor = $row["textColor"];
                $presetParticleCheck = $row["particleCheck"];
                $bandWidth = $row["bandWidth"];
                $sizeVar = $row["sizeVar"];
                $spawnRate = $row["spawnRate"];
                $pOpacity = $row["pOpacity"];
                $speedX = $row["speedX"];
                $speedY = $row["speedY"];
                $repeatCheck = $row["repeatCheck"];
                $coverCheck = $row["coverCheck"];
                $bgColorOpacity = $row["bgColorOpacity"];
                $fontTitle = $row["fontTitle"];
                $fontArtist = $row["fontArtist"];
                $presetMoveCheck = $row["moveCheck"];
                $presetEQWidth = $row["EQWidth"];
                $presetPresetName = $row["presetName"];
            }   

            closeDBConnection($dbh);
    }
    
?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Audio Visualiser</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/pixi.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Nanum+Myeongjo:wght@400;700&family=Bungee+Inline&family=Bungee+Shade&family=New+Rocker&family=Bungee+Hairline&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        <?php 
            if(isset($presetPresetName)){
                if($presetPresetName == "MDRN"){
                    echo(
                        "#bodyBackgroundImg{
                            background-image: url('img/mdrn.jpg');
                        }"
                    );
                }
            }
        ?>
        
    </style>
    <!--<script src="https://cdn.jsdelivr.net/npm/kute.js@2.0.16/dist/kute.min.js"></script>-->
</head>

<body>
<div id="bodyBackgroundImg">
</div>
<div id="distort" class="hide-layout">
</div>
<div id="bodyBackground">
</div>
<form id="formWrap" action="<?php echo($_SERVER["PHP_SELF"]);?>" method="post">
    <?php
        if(isset($saveMessage)){
            echo("<div id='saveMessage'>
                <p>$saveMessage</p>
            </div>");
        }
    ?>
    <?php
        if(isset($updateMessage)){
            echo("<div id='updateMessage'>
                <p>$updateMessage</p>
            </div>");
        }
    ?>
    <?php
        if(isset($deleteMessage)){
            echo("<div id='deleteMessage'>
                <p>$deleteMessage</p>
            </div>");
        }
    ?>
    <div id="toolBar" class="toolbar">

        <div class="form-group-left">
            <label for="audio-file">Upload Audio</label>
            <input type="file" id="audio-file" accept="audio/*" />
        </div>

        <div class="form-group-left">
            <label for="img-file">Upload Album Cover</label>
            <input type="file" id="img-file" accept="image/png, image/jpeg, image/svg, image/*" />
        </div>

        
        <svg id=playButton xmlns="http://www.w3.org/2000/svg" width="3rem" fill="white" class="bi bi-play-fill" viewBox="0 0 16 16">
            <path d="M11.596 8.697l-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
        </svg>

        <svg id=pauseButton xmlns="http://www.w3.org/2000/svg" class="hide-layout" width="3rem" fill="white" class="bi bi-pause-fill" viewBox="0 0 16 16">
            <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z"/>
        </svg>
        

        <!--<canvas id="canvas"></canvas>-->
        <audio id="audio" controls></audio>
        <div id="toolbar-right">
            <div class="form-group">
                <label>Audio sensitivity</label>
                <input type="range" id="kickRange" name="kickRange" min="0" max="257" value="<?php
                    if(isset($presetKickRange)){
                        echo($presetKickRange);
                    }else{
                        echo("240");
                    }?>">
            </div>
            <div class="form-group">
                <label>Bar Color</label>
                <input type="color" id="bar-color" value="<?php
                    if(isset($presetBarColor)){
                        echo($presetBarColor);
                    }else{
                        echo("#ffffff");
                    }
                ?>" name="barColor">
            </div>
            <!-- old place for particle-color -->
            <div class="form-group">
                <label>Background Color</label>
                <input type="color" id="bg-color" name="bgColor" value="<?php 
                    if(isset($presetBgColor)){
                        echo($presetBgColor);
                    }else{
                        echo("#111111");
                    } ?>">
            </div>
            <div class="form-group">
                <label>Gradient Background</label>
                <div class="row-group">
                    <input type="color" id="gradient-1" value="<?php 
                        if(isset($presetGradient1)){
                            echo($presetGradient1);
                        }else{
                            echo("#1C1B27");
                        } 
                    ?>" name="gradient1">
                    <input type="color" id="gradient-2" value="<?php 
                        if(isset($presetGradient2)){
                            echo($presetGradient2);
                        }else{
                            echo("#121212");
                        } 
                    ?>" name="gradient2">
                </div>
            </div>
        </div>


        <!--<div class="form-group">
            <label>Transition Speed</label>
            <input type="range" id="transitionRange" min="0.5" max="2">
        </div>-->

        <!--<svg id="toolbar-x" xmlns="http://www.w3.org/2000/svg" width="3rem" fill="currentColor" class="bi bi-x"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
        </svg>-->

    </div>

    <div id="layout-1" class="layout layout-margin">
        <div class="container album-cover" id="album-cover">
            <img src="<?php 
                if(isset($presetPresetName)){
                    
                    if($presetPresetName == "Default"){
                        echo("preset_cover.jpg");
                    }
                    else{
                        echo("../svg/wb-cover.svg");
                    }  
                }else{
                    echo("../svg/wb-cover.svg");
                }
            ?>" id="album-upload" width="30%">
            <div id="songinfo" class="hide-layout">
                <p id="songtitle" class="song-title font1">TITLE</p>
                <!--<input id="songtitle" type="text" value="TITLE">-->
                <p id="songartist" class="song-artist afont1">ARTIST</p>
            </div>
        </div>
    </div>
    <div id="layout-2" class="layout layout-margin hide-layout">
        <div class="container album-cover" id="album-cover-2">
            <img src="<?php 
                if(isset($presetPresetName)){
                    
                    if(isset($presetLayout) && $presetLayout == "layout2"){
                        echo("../svg/grad-cover.svg");
                    }

                    if($presetPresetName == "Default"){
                        echo("preset_cover.jpg");
                    }
                    /*else{
                        echo("../svg/wb-cover.svg");
                    }  */
                    

                }else{
                    echo("../svg/grad-cover.svg");
                }
            ?>" class="cover-layout-2" id="album-upload-2" width="30%">
            <img src="img/vinyl-record-2.png" class="vinyl-layout-2" width="28%">
            <img src="../svg/curve-white.svg" class="vinyl-center" width="9.68%">

        </div>
        <div id="songinfo-2" class="hide-layout">
            <p id="songtitle-2" class="song-title font1">TITLE</p>

            <svg id="song-dot" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-dot" viewBox="0 0 16 16">
                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
            </svg>
            <p id="songartist-2" class="song-artist afont1">ARTIST</p>
        </div>
    </div>

    <!-- IG STORY 1 -->
    <div id="layout-3" class="layout hide-layout">
        <div class="container album-cover" id="album-cover-3">
            <img src="<?php 
                if(isset($presetPresetName)){

                    echo("../svg/wb-cover.svg");
                }
                
            ?>" class="cover-layout-3" id="album-upload-3" width="60%">

        </div>
        <div id="songinfo-3" class="">
            <p id="songtitle-3" class="song-title font1">TITLE</p>
            <p id="songartist-3" class="song-artist afont1">ARTIST</p>
        </div>
    </div>

    <div class="container" id="bardiv-container">
        <div id="barDiv"></div>
    </div>
    
    <div id="helpSection" class="">
        <svg id="help-x" xmlns="http://www.w3.org/2000/svg" width="3rem" fill="currentColor" class="bi bi-x"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
        </svg>
        <div class="helpDiv">
            <h2>Need some help?</h2>

            <h3>How it works</h3>
            <p>Upload your audio and album cover and create a visualisation for your song.
                Play around with all the different settings to customize your visualisation.
                Enable full screen in your browser and record your screen to save the visualisation. 
                You can create an account to save your own presets and load them later.
                Make sure to look at the keyboard shortcuts to get the most out of the application.
            </p>

            <h3>Keyboard Shortcuts</h3>
            <p>Use these keyboard shortcuts to use the application.</p>
            <p>Press <span class="shortcut">1</span> on the keyboard to start the visualisation. Some setting should be set before starting the visualisation so if 
            something doesn't work, try to set it before starting.
            </p>
            <p>Press <span class="shortcut">2</span> on the keyboard to show and hide the top menu.</p>
            <p>Press <span class="shortcut">3</span> on the keyboard to show and hide the bottom menu.</p>
            <p>Press <span class="shortcut">4</span> on the keyboard to show more options. These option give you more customization for your visualisation.</p>
            <p>Press <span class="shortcut">5</span> on the keyboard to show or hide the cursor.</p>

            <h3>Contact</h3>
            <p>Email for contact or feedback: johan96johansson@gmail.com</p>
            <p></p>
        </div>
        <div class="helpDiv">
            <img id="helpImg" src="../svg/wb-cover.svg" width="80%">
        </div>
    </div>

    <div id="controls" class="controls">
        
        <!--<svg xmlns="http://www.w3.org/2000/svg" width="3rem" fill="currentColor" class="bi bi-keyboard" viewBox="0 0 16 16">
            <path d="M14 5a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h12zM2 4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H2z"/>
            <path d="M13 10.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5zm0-2a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5zm-5 0A.25.25 0 0 1 8.25 8h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5A.25.25 0 0 1 8 8.75v-.5zm2 0a.25.25 0 0 1 .25-.25h1.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-1.5a.25.25 0 0 1-.25-.25v-.5zm1 2a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5zm-5-2A.25.25 0 0 1 6.25 8h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5A.25.25 0 0 1 6 8.75v-.5zm-2 0A.25.25 0 0 1 4.25 8h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5A.25.25 0 0 1 4 8.75v-.5zm-2 0A.25.25 0 0 1 2.25 8h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5A.25.25 0 0 1 2 8.75v-.5zm11-2a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5zm-2 0a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5zm-2 0A.25.25 0 0 1 9.25 6h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5A.25.25 0 0 1 9 6.75v-.5zm-2 0A.25.25 0 0 1 7.25 6h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5A.25.25 0 0 1 7 6.75v-.5zm-2 0A.25.25 0 0 1 5.25 6h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5A.25.25 0 0 1 5 6.75v-.5zm-3 0A.25.25 0 0 1 2.25 6h1.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-1.5A.25.25 0 0 1 2 6.75v-.5zm0 4a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5zm2 0a.25.25 0 0 1 .25-.25h5.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-5.5a.25.25 0 0 1-.25-.25v-.5z"/>
        </svg>-->
        <svg id="helpBtn" xmlns="http://www.w3.org/2000/svg" width="2rem" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
        </svg>
        <div>
            <!--<p>Keyboard Shortcuts</p>-->
            <p>1: Play</p>
            <p>2: Toggle Top Toolbar</p>
            <p>3: Toggle Bottom Toolbar</p>
            <p>4: Advanced Options</p>
            <p>5: Hide Cursor</p>
        </div>
        <div id="preset-selector" class="form-col">
            <!--<label for="preset-select">Load Preset</label>-->
            
                <!--if(isset($_SESSION["loggedin"])){
                    echo(
                        

                    );
                }else{
                    echo("not logged in");
                }-->

                        
                <?php
                    //if($loggedIn){
                        echo("<button type='submit' name='loadPreset' id='loadPresetButton'>Load Preset</button>");

                        if(isset($presetSelect)){        
                            echo($presetSelect);
                        }else{
                            echo("<p id='no-preset'>No presets yet</p>");
                        }
                    //}
                    /*else{
                        echo("
                            <p>Log in to load presets</p>
                            <a href='login.php' class='logInBtn'>Log In</a>
                        ");
                    }*/
                    
                    
                ?>
                
            
            
            <!--<select id="preset-select" name="layout-select">
                <option value="layout1" name="preset1"></option>
                <option value="layout2" name="preset1"></option>
            </select>-->

            <!-- GET TEST -->
            <!--<a id="loadPresetButton" href="index.php?action=load&id=$id&barColor=$barColor">Load</a>-->
            
        </div>
        
        <div id="layout-selector" class="form-col">
            <label for="layout-select">Select Layout</label>
            <select id="layout-select" name="layout-select">
                <option value="layout1" 
                    <?php
                        if(isset($presetLayout)){
                            if($presetLayout == "layout1"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>
                >Default</option>
                <option value="layout2"
                    <?php
                        if(isset($presetLayout)){
                            if($presetLayout == "layout2"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>
                >Vinyl</option>
                <option value="layout3"
                    <?php
                        if(isset($presetLayout)){
                            if($presetLayout == "layout3"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>
                >IG Story</option>
            </select>
        </div>
        <div id="band-selector" class="form-col">
            <label for="band-select">Select Band Style</label>
            <select id="band-select" name="band-select">
                <option value="style1"
                    <?php
                        if(isset($presetBandStyle)){
                            if($presetBandStyle == "style1"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>
                >Default</option>
                <option value="style2"
                <?php
                        if(isset($presetBandStyle)){
                            if($presetBandStyle == "style2"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>
                >Dots</option>
            </select>
        </div>
        <div class="c-group">
            <div>
                <label for="infocheck">Show Song Information</label>
                <input type="checkbox" name="infocheck" id="infocheck"
                    <?php
                        if(isset($presetInfoCheck)){
                            if($presetInfoCheck == "on"){
                                echo(" checked");
                            }  
                        }else{
                            echo("checked");
                        }
                    ?>
                >
            </div>
            <div id="title-artist" class="hide-layout">
                <div class="space-between">
                    <label for="song-title">Title: </label>
                    <input type="text" class="textfield" id="song-title" name="song-title" value="<?php
                            if(isset($presetSongTitle)){
                                echo($presetSongTitle);
                            }else{
                                echo("TITLE");
                            }
                        ?>">
                </div>
                <div class="space-between">
                    <label for="artist-name">Artist: </label>
                    <input type="text" class="textfield" id="artist-name" name="artist-name" value="<?php
                            if(isset($presetArtistName)){
                                echo($presetArtistName);
                            }else{
                                echo("ARTIST");
                            }
                        ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Text Color</label>
            <input type="color" id="text-color" value="<?php
                if(isset($presetTextColor)){
                    echo($presetTextColor);
                }else{
                    echo("#ffffff");
                }
            ?>" 
            name="text-color">
        </div>
        <div>
            <label for="particlecheck">Enable Particle Effects</label>
            <input type="checkbox" name="particlecheck" id="particlecheck"
                <?php
                        if(isset($presetParticleCheck)){
                            if($presetParticleCheck == "on"){
                                echo(" checked");
                            }  
                        }
                    ?>
            >
        </div>
        <div class="form-group">
                <label>Particle Color</label>
                <input type="color" id="particle-color" value="<?php 
                    if(isset($presetParticleColor)){
                        echo($presetParticleColor);
                    }else{
                        echo("#FDCF2B");
                    } ?>" name="particleColor">
            </div>
    </div>

    <div id="adv-options" class="hide-layout">
        <!--<div id="adv-links">
            <h3>Useful links</h3>
            <a href="https://pixabay.com/" target="_blank">Pixabay - Background images and videos</a>

        </div>-->
        <svg id="adv-x" xmlns="http://www.w3.org/2000/svg" width="3rem" fill="currentColor" class="bi bi-x"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
        </svg>
        <div id="preset-div"> <!--$loggedIn -->
            <div id="presetNameDiv">
                <?php
                if($loggedIn){
                    echo("<label for='presetName'>");

                    if(isset($presetPresetName)){ 
                        echo("Current Preset:");  
                    }else{
                        echo("Name New Preset:");
                    }

                    echo("</label>");

                    echo("<input type='text' name='presetName' id='presetName' value='");
                    if(isset($presetPresetName)){ 
                        echo($presetPresetName);  
                    }
                    echo("'>");

                    

                }else{
                    echo("
                            <p>Log in to save your own presets</p>
                            <a href='login.php' class='logInBtn'>Log in / Create account</a>
                        ");
                }
                        
                ?>
            </div>
            <div id="updateDiv">
                <?php
                    if(isset($presetPresetName) && $loggedIn){ 
                        echo('<button type="submit" name="updatePreset" id="updateButton" class="formButton">Update Preset</button>
                        ');  
                    }

                ?>
            </div>
            <div id="saveDiv">
                <?php
                    if($loggedIn){
                        if(!isset($presetPresetName)){ 
                            echo('<button type="submit" name="submitForm" id="saveButton" class="formButton">Save Preset</button>
                            ');
                        }else{
                            echo('<button type="submit" name="submitForm" id="saveButton" class="formButton">Save As New</button>
                            ');
                        }
                    }
                ?>
                </div>
            <div id="deleteDiv">
                <?php
                    if(isset($presetPresetName) && $loggedIn){ 
                        echo('<button type="submit" name="deletePreset" id="deleteButton" class="formButton">Delete Preset</button>
                        ');  
                    }
                ?>
                </div>
                <div id="logOutDiv">
                    <?php 
                    if($loggedIn){
                        echo('<div id="logOutDiv">
                        <button type="submit" name="logout" class="formButton" id="logOutBtn">Log out</button>
                        </div>');
                    }   
                    ?>
                </div>
        </div>
        
        <div class="tabs">
                <div class="tab"><h2 >Background</h2></div>
                <div class="tab"><h2 >Equaliser</h2></div>
                <div class="tab"><h2 >Particles</h2></div>
                <div class="tab"><h2 >Fonts</h2></div>
        </div>
        <div class="adv-all-groups">
        <div class="adv-group-container"> <!-- BACKGROUND -->
            <div class="adv-group-ml">
                <div class="adv-label-col">
                    <label class="">Set Image</label>
                    <input type="file" id="adv-setimage" accept="image/*">
                </div>
            </div>
            <div class="adv-group-ml">
                <div class="adv-label-col">
                    <label class="">Set Video</label>
                    <input type="file" id="adv-setvideo" accept="video/*">
                </div>
            </div>                    
        
                <!--<div class="adv-group-tabs">
                    <div class="link-tab tab-active" id="link-tab-1">
                        <p>Upload Files</p>
                    </div>
                    <div class="link-tab" id="link-tab-2">
                        <p>Link Files</p>
                    </div>
                </div>
                <div class="bg-tabs">
                    <div id="bg-tab-1" class="">
                        
                        <div class="adv-group">
                            <div class="adv-label-col">
                                <label class="">Set Image</label>
                                <input type="file" id="adv-setimage" accept="image/*">
                            </div>
                        </div>
                        <div class="adv-group">
                            <div class="adv-label-col">
                                <label class="">Set Video</label>
                                <input type="file" id="adv-setvideo" accept="video/*">
                            </div>
                        </div>
                    </div>
                    <div id="bg-tab-2" class="hide-layout">
                        
                        <div class="adv-group">
                            <div class="adv-label-col">
                                <label class="">Link Image</label>
                                <input type="text" name="link-bgimage" id="imgLinkInput">
                                <div id="setImageLink" class="linkButton"><p>Set Image</p></div>
                            </div>
                        </div>
                        <div class="adv-group">
                            <div class="adv-label-col">
                                <label class="">Link Video</label>
                                <input type="text" name="link-video" id="videoLinkInput">
                                <div id="setVideoLink" class="linkButton"><p>Set Video</p></div>
                            </div>
                        </div>
                    </div>
                </div>-->
                
                <!--<div class="adv-group">
                    <div class="adv-label-col-end">
                        <label class="">Or link your image to save it in your preset</label>
                        <input type="text" id="adv-linkimage">
                     </div>
                    
                </div>-->
                <div class="adv-group">
                    
                    <label>Background Repeat</label>
                    <input type="checkbox" id="repeatCheck" <?php
                        if(isset($repeatCheck)){
                            if($repeatCheck === "on"){
                                echo "checked='checked'";
                            }   
                        }
                    ?> name="repeatCheck">
                
                </div>
                <div class="adv-group">
                    
                    <label>Background Cover</label>
                    <input type="checkbox" id="coverCheck" <?php
                        if(isset($coverCheck)){
                            if($coverCheck === "on"){
                                echo "checked='checked'";
                            }   
                        }else{
                            echo "checked='checked'";
                        }
                    ?> name="coverCheck">
                   
                </div>
                <div class="adv-group">
                    
                    <label>Background Movement</label>
                    <input type="checkbox" id="movementCheck" name="moveCheck" <?php
                        if(isset($presetMoveCheck)){
                            if($presetMoveCheck === "on"){
                                echo "checked='checked'";
                            }   
                        }
                    ?> name="movementCheck">
                     
                  
                </div>
                
                <div class="adv-group">
                    
                    <label for="bgColorOpacity">Background Color Opacity</label>
                    <input type="range" min="0" max="1" step="0.05" value="<?php
                        if(isset($bgColorOpacity)){
                            echo $bgColorOpacity;
                        }else{
                            echo "1";
                        }
                    ?>" name="bgColorOpacity" id="bgColorOpacity">
                    
                </div>
                
            </div>
            <div class="adv-group-container"> <!-- EQ -->
                <div class="adv-group">
                    <label>Equaliser Width (%)</label>
                    <input type="range" min="0" max="100" value="<?php
                        if(isset($presetEQWidth)){
                            echo($presetEQWidth);
                        }else{
                            echo("50");
                        }
                    ?>" 
                    id="range-eq" name="range-eq">
                    
                </div>
                
                <div class="adv-group">
                    <label>Band Width</label>

                    <div class="radioGroup">
                        <label for="bandWidth">L</label>
                        <input type="radio" value="64" class="bandWidth" name="bandWidth" <?php
                        if(isset($bandWidth)){
                            if($bandWidth == "64"){
                                echo "checked='checked'";
                            }
                        }
                    ?>>
                    </div>

                    <div class="radioGroup">
                        <label for="bandWidth">M</label>
                        <input type="radio" value="128" class="bandWidth" name="bandWidth" <?php
                        if(isset($bandWidth)){
                            if($bandWidth == "128"){
                                echo "checked='checked'";
                            }
                        }else{
                            echo "checked='checked'";
                        }
                    ?>>
                    </div>

                    <div class="radioGroup">
                        <label for="bandWidth">S</label>
                        <input type="radio" value="256" class="bandWidth" name="bandWidth" <?php
                        if(isset($bandWidth)){
                            if($bandWidth == "256"){
                                echo "checked='checked'";
                            }
                        }
                    ?>>
                    </div>

                </div>
                <div class="adv-group">
                    <p class="notification notify-warning">Set band width before pressing play</p>
                </div>
                <div class="adv-group">
                    <label>Frequency range</label>
                    <input type="range" min="0" max="50" value="<?php
                        if(isset($presetFreqRange)){
                            echo($presetFreqRange);
                        }else{
                            echo("0");
                        }
                    ?>" 
                    id="range-freq" name="range-freq">
                    
                </div>
                <div class="adv-group">
                    <p class="notification"><span class="notify-warning">Set frequency range before pressing play.</span><br> This setting is useful if your equaliser doesn't 
                        look centered. Raise the value to remove high frequencies and balance the EQ.
                    </p>
                </div>
                
            </div>
            <div class="adv-group-container"> <!-- PARTICLES -->
                <!--<div class="adv-group">
                    <label>Particle Size</label>
                    <input type="range" min="0" max="1000" value="5" id="range-particles">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>-->
                <div class="adv-group">
                    <label>Particle Size Variation</label>
                    <input type="range" min="1" max="100" value="<?php
                        if(isset($sizeVar)){
                            echo $sizeVar;
                        }else{
                            echo "4";
                        }
                    ?>" id="particles-variation" name="sizeVar">
                    
                </div>
                <div class="adv-group">
                    <label>Particle Spawn Rate</label>
                    <input type="range" min="-750" max="-1" value="<?php
                        if(isset($spawnRate)){
                            echo $spawnRate;
                        }else{
                            echo "-200";
                        }
                    ?>" id="range-rate" name="spawnRate"> <!-- min="2" max="750" -->
                    
                </div>
                <div class="adv-group">
                    <label>Particle Opacity</label>
                    <input type="range" min="0" max="1" step="0.1" value="<?php
                        if(isset($pOpacity)){
                            echo $pOpacity;
                        }else{
                            echo "0.8";
                        }
                    ?>" id="adv-particle-opacity" name="pOpacity">
                    
                </div>
                <div class="adv-group">
                    <label>Particle Speed X</label>
                    <input type="range" min="1" max="80000" value="<?php
                        if(isset($speedX)){
                            echo $speedX;
                        }else{
                            echo "20000";
                        }
                    ?>" id="particle-speedX" name="speedX">
                    
                </div>
                <div class="adv-group">
                    <label>Particle Speed Y</label>
                    <input type="range" min="1" max="80000" value="<?php
                        if(isset($speedY)){
                            echo $speedY;
                        }else{
                            echo "20000";
                        }
                    ?>" id="particle-speedY" name="speedY">
                    
                </div>
                <!--<div class="adv-group">
                    <label>Particle Appearance</label>
                    <select>
                        <option>Circle</option>
                        <option>Circle Outline</option>
                        <option>Square</option>
                        <option>Square Outline</option>
                        <option>Triangle</option>
                        <option>Triangle Outline</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <label>Particle Rotation</label>
                    <input type="checkbox" id="particle-rotation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>-->
            </div>
            
            <div class="adv-group-container"> <!-- FONTS -->
                <div class="adv-group">
                    <label>Title Font</label>
                    <select id="font-title" name="font-title">
                        <option value="1"<?php
                        if(isset($fontTitle)){
                            if($fontTitle == "1"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 1</option>
                        <option value="2" <?php
                        if(isset($fontTitle)){
                            if($fontTitle == "2"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 2</option>
                        <option value="3" <?php
                        if(isset($fontTitle)){
                            if($fontTitle == "3"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 3</option>
                        <option value="4" <?php
                        if(isset($fontTitle)){
                            if($fontTitle == "4"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 4</option>
                        <option value="5" <?php
                        if(isset($fontTitle)){
                            if($fontTitle == "5"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 5</option>
                        <option value="6" <?php
                        if(isset($fontTitle)){
                            if($fontTitle == "6"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 6</option>
                        <option value="7" <?php
                        if(isset($fontTitle)){
                            if($fontTitle == "7"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 7</option>
                        <option value="8" <?php
                        if(isset($fontTitle)){
                            if($fontTitle == "8"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 8</option>
                    </select>
                    
                </div>
                <div class="adv-group">
                    <label>Artist Font</label>
                    <select id="font-artist" name="font-artist">
                        <option value="1" <?php
                        if(isset($fontArtist)){
                            if($fontArtist == "1"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 1</option>
                        <option value="2" <?php
                        if(isset($fontArtist)){
                            if($fontArtist == "2"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 2</option>
                        <option value="3" <?php
                        if(isset($fontArtist)){
                            if($fontArtist == "3"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 3</option>
                        <option value="4" <?php
                        if(isset($fontArtist)){
                            if($fontArtist == "4"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 4</option>
                        <option value="5" <?php
                        if(isset($fontArtist)){
                            if($fontArtist == "5"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 5</option>
                        <option value="6" <?php
                        if(isset($fontArtist)){
                            if($fontArtist == "6"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 6</option>
                        <option value="7" <?php
                        if(isset($fontArtist)){
                            if($fontArtist == "7"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 7</option>
                        <option value="8" <?php
                        if(isset($fontArtist)){
                            if($fontArtist == "8"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>>FONT 8</option>
                    </select>
                    
                </div>
                <!--<div class="adv-group">
                    <label>Title Font Size</label>
                    <input type="range" id="titleFontSize" name="titleFontSize" min="1" max="30"  value="7">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <label>Artist Font Size</label>
                    <input type="range" id="artistFontSize" name="artistFontSize" min="1" max="30"  value="4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>-->
                <!--<div class="adv-group">
                    <label>Color</label>
                    <input type="color">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>-->
            </div>
        </div>
    </div>


    <!--SVG for morph-->
    <!--
    
    <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
    <svg  id="morph-svg" width="100%" viewBox="0 0 15164 4744" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
        <path id="morph1" d="M15163.1,0l-15163.1,0l0,4743.75l15163.1,0l0,-4743.75Z" style="fill:#1a1923;"/>
        <g ><path id="morph2" style="visibility:hidden"; d="M41.798,1929.41c0,0 958.549,-1173.2 3081.05,-1291.81c2122.5,-118.604 3100.09,1014.61 5869.34,-212.954c2769.25,-1227.56 3185.44,545.493 6024.69,1057.92c69.176,12.485 -17.279,257.35 43.977,270.323c32.422,6.867 57.744,750.256 51.217,776.748c-271.678,1102.76 273.107,2309.82 -445.46,2225.48c-900.015,-105.632 -9568.96,46.343 -9568.96,46.343l-5061.22,67.373l5.373,-2939.43Z" style="fill:#c63e5e;"/><path d="M26.01,1717.24c0,0 747.187,-848.061 2869.69,-966.664c2122.5,-118.604 3100.09,1014.61 5869.34,-212.954c2769.25,-1227.56 3541.59,580.024 6400.74,966.277c53.509,7.229 2.355,1824.3 6.262,1874.27c20.682,264.555 -28.513,736.812 -152.971,1065.14c-79.942,210.891 -17.451,362.401 -334.409,361.344c-136.633,-0.456 -419.984,40.919 -490.838,44.494c-853.121,43.043 -14191,6.436 -14191,6.436l-2.826,-1870.66l26.01,-1267.67Z" style="fill:#1a1923;"/></g>
    </svg>

    <svg  id="morph-svg-top" width="100%" viewBox="0 0 15164 4744" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
        <path id="morph-top-1" d="M15163.1,0l-15163.1,0l0,4743.75l15163.1,0l0,-4743.75Z" style="fill:#1a1923;"/>
        <g><path id="morph-top-2" style="visibility:hidden"; d="M15148.6,2940.69c0,0 -958.55,1173.2 -3081.05,1291.81c-2122.5,118.603 -3100.09,-1014.61 -5869.34,212.953c-2769.25,1227.56 -3185.44,-545.493 -6024.69,-1057.92c-69.175,-12.485 17.28,-257.35 -43.977,-270.323c-32.421,-6.866 -57.743,-750.256 -51.217,-776.747c271.678,-1102.76 -273.107,-2309.82 445.46,-2225.48c900.015,105.632 9568.97,-46.344 9568.97,-46.344l5061.22,-67.372l-5.373,2939.43Z" style="fill:#c63e5e;"/><path d="M15164.3,3152.86c0,0 -747.186,848.061 -2869.69,966.665c-2122.5,118.603 -3100.09,-1014.61 -5869.34,212.954c-2769.25,1227.56 -3541.59,-580.025 -6400.74,-966.277c-53.509,-7.229 -2.356,-1824.3 -6.262,-1874.27c-20.683,-264.555 28.512,-736.813 152.971,-1065.14c79.942,-210.892 17.45,-362.402 334.409,-361.345c136.633,0.456 419.983,-40.918 490.837,-44.493c853.121,-43.044 14191,-6.437 14191,-6.437l2.827,1870.66l-26.011,1267.67Z" style="fill:#1a1923;"/></g>
    </svg>


    
    <svg  id="morph-svg-2" width="100%" viewBox="0 0 15164 4744" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
        <path id="morph2-1" d="M41.798,1929.41c0,0 958.549,-1173.2 3081.05,-1291.81c2122.5,-118.604 3100.09,1014.61 5869.34,-212.954c2769.25,-1227.56 3185.44,545.493 6024.69,1057.92c69.176,12.485 -17.279,257.35 43.977,270.323c32.422,6.867 57.744,750.256 51.217,776.748c-271.678,1102.76 273.107,2309.82 -445.46,2225.48c-900.015,-105.632 -9568.96,46.343 -9568.96,46.343l-5061.22,67.373l5.373,-2939.43Z" style="fill:#c63e5e;"/>
        <path id="morph2-2" style="visibility:hidden"; d="M5.373,1560c0,0 933.751,-1525.42 3081.05,-1291.81c3468.11,377.311 2870.17,-490.009 6017.58,62.354c2667.07,468.064 3058.37,-1466.37 5876.45,782.609c54.943,43.846 -17.279,257.35 43.977,270.323c32.422,6.866 57.744,750.255 51.217,776.747c-271.677,1102.76 273.107,2309.82 -445.46,2225.48c-900.014,-105.632 -9568.96,46.344 -9568.96,46.344l-5061.22,67.372l5.373,-2939.43Z" style="fill:#c63e5e;"/>
    </svg>

    
    <svg  id="morph-svg-2-top" width="100%" viewBox="0 0 15164 4744" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
        <path id="morph-top-2-1" d="M15148.6,2940.69c0,0 -958.55,1173.2 -3081.05,1291.81c-2122.5,118.603 -3100.09,-1014.61 -5869.34,212.953c-2769.25,1227.56 -3185.44,-545.493 -6024.69,-1057.92c-69.175,-12.485 17.28,-257.35 -43.977,-270.323c-32.421,-6.866 -57.743,-750.256 -51.217,-776.747c271.678,-1102.76 -273.107,-2309.82 445.46,-2225.48c900.015,105.632 9568.97,-46.344 9568.97,-46.344l5061.22,-67.372l-5.373,2939.43Z" style="fill:#c63e5e;"/>
        <path id="morph-top-2-2" style="visibility:hidden"; d="M15071.3,2939.43c0,0 -933.751,1525.42 -3081.05,1291.81c-3468.11,-377.31 -2870.17,490.01 -6017.58,-62.354c-2667.07,-468.064 -3058.37,1466.37 -5876.45,-782.609c-54.943,-43.846 17.279,-257.349 -43.978,-270.323c-32.421,-6.866 -57.743,-750.255 -51.217,-776.747c271.678,-1102.76 -273.106,-2309.82 445.461,-2225.48c900.014,105.632 9568.96,-46.344 9568.96,-46.344l5061.22,-67.372l-5.373,2939.43Z" style="fill:#c63e5e;"/>
    </svg>
-->

    <!--<script src="Wave.js-master/src/index.js"></script>-->
    <video src="<?php
        if(isset($presetPresetName)){
            if($presetPresetName == "Synthwave"){
                echo("video/neon.mp4");
            }
        }
        if(isset($presetPresetName)){
            if($presetPresetName == "Ink"){
                echo("video/ink.mp4");
            }
        }
        if(isset($presetPresetName)){
            if($presetPresetName == "Skull"){
                echo("video/skull.mp4");
            }
        }
        if(isset($presetPresetName)){
            if($presetPresetName == "Waves"){
                echo("video/waves.mp4");
            }
        }
        if(isset($presetPresetName)){
            if($presetPresetName == "Electro"){
                echo("video/galaxy.mp4");
            }
        }
    ?>" width="100%" muted autoplay loop>
        Your browser does not support the video tag.
    </video>
    <div id="hovertop"></div>
    <div id="hoverbottom"></div>
    </form>
    <script src="script.js" async defer></script>
    <script type="text/javascript">

        window.addEventListener("load", function(){
            let movementCheck = document.getElementById("movementCheck");
            let distort = document.getElementById("distort");
            movementCheck.addEventListener("change", function(){
                checkMove(movementCheck, distort);
            });
            checkMove(movementCheck, distort);
        });
        
        function checkMove(checkbox, distort){
            
            if(checkbox.checked){
                distort.classList.remove("hide-layout");
                let advImage = document.getElementById("adv-setimage");
                //advImage.addEventListener("change", moveImgSetup);
                //function moveImgSetup(){
                let src = "img/mdrn.jpg"
                let imgFile = advImage.files;
                if(imgFile[0] != null){
                    src = URL.createObjectURL(imgFile[0]);
                }else{
                    src = "img/mdrn.jpg";
                }
                
                moveBackground(src);
                //}
            }else{
                distort.classList.add("hide-layout");
            }
        }

        function moveBackground(src){

            let ds = document.getElementById("distort");
            let winWidth = window.innerWidth *2;
            let winHeight = window.innerHeight *2; 
            
            const app = new PIXI.Application({
                width: winWidth,
                height: winHeight,
                backgroundColor: 0xffffff,
                resolution: window.devicePixelRatio || 1,
            });
            ds.appendChild(app.view);
            

            app.stage.interactive = true;

            let container = new PIXI.Container();
            app.stage.addChild(container);

            let containerSize = {
                x: window.innerWidth,
                y: window.innerHeight
            };

            //Add src link from file input here
            let bg = new PIXI.Sprite.from(src); //img/bg.jpg
            container.addChild(bg);
            bg.x = 0; //-10
            bg.y = 0; //-10
            bg.scale.set(1.48, 1.48); //-1.1, 1.1

            let displacementSprite = PIXI.Sprite.from('img/map.jpg');

            displacementSprite.texture.baseTexture.wrapMode = PIXI.WRAP_MODES.REPEAT;
            let displacementFilter = new PIXI.filters.DisplacementFilter(displacementSprite);
            displacementFilter.padding = 10;

            displacementSprite.position = bg.position;
            displacementSprite.scale.set(2, 2);


            app.stage.addChild(displacementSprite);

            bg.filters = [displacementFilter];

            displacementFilter.scale.x = 120; //500 looks cool, standard:120
            displacementFilter.scale.y = 120; //500 looks cool, standard:120

            app.ticker.add(() => {
                displacementSprite.y += 1;
                if (displacementSprite.y > displacementSprite.height) {
                    displacementSprite.y = 0;
                }
            });
        }
        

    </script>
</body>

</html>