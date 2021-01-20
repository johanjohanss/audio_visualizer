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
                    $presetGradient2 = $row["gradient1"];

                    $presetLayout = $row["layout"];
                    $presetBandStyle = $row["bandStyle"];
                    $presetInfoCheck = $row["infoCheck"];
                    $presetSongTitle = $row["songTitle"];
                    $presetArtistName = $row["artistName"];
                    $presetTextColor = $row["textColor"];
                    $presetParticleCheck = $row["particleCheck"];
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

            if(isset($_POST["presetName"])){
                $presetName = $_POST["presetName"];
            }else{
                $valid = false;
            }

            if($valid){
            updateData($dbh,
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
                $presetName,
                $user
                );
                $updateMessage = "Preset Updated";
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
    <link rel="stylesheet" href="style.css">
    <!--<script src="https://cdn.jsdelivr.net/npm/kute.js@2.0.16/dist/kute.min.js"></script>-->
</head>

<body>
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
                <label>Kick Range</label>
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

        <svg id="toolbar-x" xmlns="http://www.w3.org/2000/svg" width="3rem" fill="currentColor" class="bi bi-x"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
        </svg>

    </div>

    <div id="layout-1" class="layout layout-margin">
        <div class="container album-cover" id="album-cover">
            <img src="<?php 
                if(isset($presetPresetName)){
                    
                    if($presetPresetName == "Default"){
                        echo("preset_cover.jpg");
                    }else if($presetPresetName == "Green Tea"){
                        echo("../svg/grad-cover.svg");
                    }
                    else{
                        echo("../svg/wb-cover.svg");
                    }  
                }else{
                    echo("preset_cover.jpg");
                }
            ?>" id="album-upload" width="30%">
            <div id="songinfo" class="hide-layout">
                <p id="songtitle">TITLE</p>
                <!--<input id="songtitle" type="text" value="TITLE">-->
                <p id="songartist">ARTIST</p>
            </div>
        </div>
    </div>
    <div id="layout-2" class="layout layout-margin hide-layout">
        <div class="container album-cover" id="album-cover-2">
            <img src="<?php 
                if(isset($presetPresetName)){
                    
                    if($presetPresetName == "Default"){
                        echo("preset_cover.jpg");
                    }else if($presetPresetName == "Green Tea"){
                        echo("../svg/grad-cover.svg");
                    }
                    else{
                        echo("../svg/wb-cover.svg");
                    }  
                }else{
                    echo("preset_cover.jpg");
                }
            ?>" class="cover-layout-2" id="album-upload-2" width="30%">
            <img src="vinyl.svg" class="vinyl-layout-2" width="28%">

        </div>
        <div id="songinfo-2" class="hide-layout">
            <p id="songtitle-2">TITLE</p>

            <svg id="song-dot" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-dot" viewBox="0 0 16 16">
                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
            </svg>
            <p id="songartist-2">ARTIST</p>
        </div>
    </div>

    <div class="container" id="bardiv-container">
        <div id="barDiv"></div>
    </div>
    
    

    <div id="controls" class="controls">
        <div>
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
                >Layout 1</option>
                <option value="layout2"
                    <?php
                        if(isset($presetLayout)){
                            if($presetLayout == "layout2"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>
                >Layout 2</option>
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
                >Style 1</option>
                <option value="style2"
                <?php
                        if(isset($presetBandStyle)){
                            if($presetBandStyle == "style2"){
                                echo(" selected='selected'");
                            }  
                        }
                    ?>
                >Style 2</option>
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
                <div class="tab"><h2 >Equaliser</h2></div>
                <div class="tab"><h2 >Particles</h2></div>
                <div class="tab"><h2 >Background</h2></div>
                <div class="tab"><h2 >Fonts</h2></div>
        </div>
        <div class="adv-all-groups">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <!--<div class="adv-group">
                    <label>Empty</label>
                    <input type="range" min="0" max="100" value="30" id="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>-->
                <div class="adv-group">
                    <label>Band Width</label>

                    <div class="radioGroup">
                        <label for="bandWidth">L</label>
                        <input type="radio" value="64" class="bandWidth" name="bandWidth">
                    </div>

                    <div class="radioGroup">
                        <label for="bandWidth">M</label>
                        <input type="radio" value="128" class="bandWidth" name="bandWidth" checked="checked">
                    </div>

                    <div class="radioGroup">
                        <label for="bandWidth">S</label>
                        <input type="radio" value="256" class="bandWidth" name="bandWidth">
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
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
                    <input type="range" min="1" max="100" value="4" id="particles-variation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <label>Particle Spawn Rate</label>
                    <input type="range" min="3" max="800" value="200" id="range-rate">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <label>Particle Opacity</label>
                    <input type="range" min="0" max="1" step="0.1" value="0.8" id="adv-particle-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <label>Particle Speed X</label>
                    <input type="range" min="1" max="80000" value="200" id="particle-speedX">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <label>Particle Speed Y</label>
                    <input type="range" min="1" max="80000" value="10000" id="particle-speedY">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
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
                </div>
            </div>
            <div class="adv-group-container"> <!-- BACKGROUND -->
                <div class="adv-group">
                    <div class="adv-label-col">
                        <label>Set Image</label>
                        <input type="file" id="adv-setimage">
                     </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <div class="adv-label-col">
                        <label>Set Video</label>
                        <input type="file" id="adv-setvideo">
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <label>Set Opacity</label>
                    <input type="range" min="0" max="100" value="30" id="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
            </div>
            <div class="adv-group-container"> <!-- FONTS -->
                <div class="adv-group">
                    <label>Title Font</label>
                    <select>
                        <option>FONT A</option>
                        <option>FONT B</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
                <div class="adv-group">
                    <label>Artist Font</label>
                    <select>
                        <option>FONT A</option>
                        <option>FONT B</option>
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1.5rem" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                </div>
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
    </form>
    <script src="script.js" async defer></script>
</body>

</html>