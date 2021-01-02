window.onload = function () {


    //DECLARING VARIABLES
    const file = document.getElementById("audio-file");
    const audio = document.getElementById("audio");
    let layouts = document.querySelectorAll(".layout");

    //Uploading images
    const imgFile = document.getElementById("img-file");
    imgFile.addEventListener("change", updateImage);

    function updateImage() {
        let imgFiles = imgFile.files;
        let src = URL.createObjectURL(imgFiles[0]);

        let albumImage = document.getElementById("album-upload");
        albumImage.setAttribute("src", src);

        //console.log(src);
    }
    //-----

    //KICK SENSITIVITY
    let kickSensitivity = 254.5;
    let kickRange = document.getElementById("kickRange");
    kickRange.addEventListener("change", updateKickSens);

    function updateKickSens() {
        kickSensitivity = kickRange.value;
        console.log(kickSensitivity);
    }
    //---

    //TOOLBAR SETUP
    const toolbar = document.getElementById("toolBar");

    //TOGGLE MENU
    const toolbarX = document.getElementById("toolbar-x");
    toolbarX.addEventListener("click", toggleMenu);

    function toggleMenu() {
        console.log("menu toggled")
        toolbar.classList.toggle("toolbarShow");
    }

    window.addEventListener("keydown", function (e) {
        console.log("keydown: " + e.keyCode);

        if (e.keyCode === 67) { //C
            toggleControls();
        }
        if (e.keyCode === 77) { //M
            toggleMenu();
        }
        if (e.keyCode === 80) { //P
            startMusic();
        }
    });

    //SWITCHING LAYOUTS
    let layoutSelect = document.getElementById("layout-select");
    layoutSelect.addEventListener("change", switchLayout);

    //SWITCHING BAND STYLE
    let bandSelect = document.getElementById("band-select");
    layoutSelect.addEventListener("change", switchBands);

    //CHANGE COLORS
    let barInput = document.getElementById("bar-color");
    barInput.addEventListener("input", changeBarColor);

    let bgInput = document.getElementById("bg-color");
    bgInput.addEventListener("input", changeBgColor);


    function changeBarColor() {
        let bandClass = null;
        if (bandSelect.value == "style1") {
            bandClass = ".band";
        }
        if (bandSelect.value == "style2") {
            bandClass = ".band2";
        }
        let bands = document.querySelectorAll(bandClass);
        bands.forEach(band => {
            band.style.backgroundColor = barInput.value;
        });
    }
    function changeBgColor() {
        document.body.style.backgroundColor = bgInput.value;
    }

    //GRADIENT BACKGROUND

    let gradient1 = document.getElementById("gradient-1");
    let gradient2 = document.getElementById("gradient-2");

    gradient1.addEventListener("input", setGradientBg);
    gradient2.addEventListener("input", setGradientBg);

    function setGradientBg() {
        console.log(gradient1.value);
        console.log(gradient2.value);
        document.body.style.background = "linear-gradient(to right," + gradient1.value + "," + gradient2.value + ")";
    }
    //-------

    /*let transitionRange = document.getElementById("transitionRange");
    kickRange.addEventListener("change", updateTransLength);
    
    function updateTransLength() {
    
    }*/

    let albumCover = document.getElementById("album-cover");

    file.onchange = function () {

        //getting the file and setting the source
        var files = this.files;
        audio.src = URL.createObjectURL(files[0]);
        audio.load();

    };

    function startMusic() {

        if (layoutSelect.value == "layout2") {
            let vinylLayout2 = document.querySelector(".vinyl-layout-2");
            vinylLayout2.classList.add("vinyl-spin");
        }

        audio.play();
        var context = new AudioContext();
        var src = context.createMediaElementSource(audio);
        var analyser = context.createAnalyser();

        //var canvas = document.getElementById("canvas");
        //canvas.width = window.innerWidth;
        //canvas.height = window.innerHeight;
        //var ctx = canvas.getContext("2d");

        src.connect(analyser);
        analyser.connect(context.destination);

        analyser.fftSize = 256; //64 to give it less bands, or anything to the power of 2

        var bufferLength = analyser.frequencyBinCount;
        console.log(bufferLength);

        //barDivs
        let barDiv = document.getElementById("barDiv");
        let barDiv2 = document.getElementById("barDiv2");

        let storeBand = null;

        for (var i = 0; i < bufferLength; i++) {

            let band = document.createElement("div");

            if (bandSelect.value == "style1") {
                band.classList.add("band");
                storeBand = ".band";
            }
            if (bandSelect.value == "style2") {
                band.classList.add("band2");
                storeBand = ".band2";
            }

            barDiv.appendChild(band);
        }

        //storeBand has the right class
        let bands = document.querySelectorAll(storeBand);

        var dataArray = new Uint8Array(bufferLength);

        var WIDTH = document.body.clientWidth;
        //var HEIGHT = canvas.height;

        var barWidth = (WIDTH / bufferLength) * 2.5;
        var barHeight;
        var x = 0;

        let leftPos = -5;

        bands.forEach(band => {
            if (bandSelect.value == "style1") {
                band.style.width = barWidth + "px";
            }
            if (bandSelect.value == "style2") {
                band.style.width = 100 + "px";
                band.style.position = "absolute";
                band.style.left = leftPos + "%";
                leftPos += 1.2;
                console.log(bandSelect.value);
            }
        });

        function renderFrame() {
            requestAnimationFrame(renderFrame);

            x = 0;

            analyser.getByteFrequencyData(dataArray);

            //ctx.fillStyle = "#000";
            //ctx.fillRect(0, 0, WIDTH, HEIGHT);

            for (var i = 0; i < bufferLength; i++) {


                //detecting kick hits
                if (i == 1) {
                    if (dataArray[i] > kickSensitivity) { //change sensitivity
                        console.log("kick hit");

                        albumCover.classList.add("grow");
                    }
                    if (dataArray[i] < kickSensitivity) {
                        albumCover.classList.remove("grow");
                    }
                }
                //---


                //Height
                barHeight = (dataArray[i] / 0.5) /// 1.5;
                bands.item(i).style.height = barHeight + "px";

                //Setting dynamic width of bands 
                if (bandSelect.value == "style2") {

                    barHeight = (dataArray[i] / 0.75) /// 1.5; 
                    bands.item(i).style.height = barHeight + "px";

                    barWidth = 300 - dataArray[i];
                    bands.item(i).style.width = barWidth + "px";//barHeight + "px";
                }


                //Colors
                /*var r = 255//barHeight + (25 * (i / bufferLength));
                var g = 253//250 * (i / bufferLength);
                var b = 100//barHeight + (25 * (i / bufferLength));//50;
    
                bands.item(i).style.backgroundColor = "rgb(" + r + "," + g + "," + b + ")";
                */
                //ctx.fillStyle = "rgb(" + r + "," + g + "," + b + ")";
                //ctx.fillRect(x, HEIGHT - barHeight, barWidth, barHeight);

                x += barWidth + 1;
            }
        }

        audio.play();
        renderFrame();
    }

    function toggleControls() {
        let controls = document.getElementById("controls");
        controls.classList.toggle("hide-controls");
    }

    function switchLayout() {

        layouts.forEach(layout => {
            layout.classList.add("hide-layout");
        });

        if (layoutSelect.value == "layout1") {
            layouts.item(0).classList.remove("hide-layout");
        }
        if (layoutSelect.value == "layout2") {
            layouts.item(1).classList.remove("hide-layout");
        }
        if (layoutSelect.value == "layout3") {
            layouts.item(2).classList.remove("hide-layout");
        }
    }

    function switchBands() {
        bands.forEach(band => {
            band.className = "";
        });
    }

};

