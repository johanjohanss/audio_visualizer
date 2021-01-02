window.onload = function () {




    var file = document.getElementById("thefile");
    var audio = document.getElementById("audio");

    let kickSensitivity = 254.5;
    let kickRange = document.getElementById("kickRange");
    kickRange.addEventListener("change", updateKickSens);

    function updateKickSens() {
        kickSensitivity = kickRange.value;
        console.log(kickSensitivity);
    }

    //TOOLBAR SETUP
    let toolbar = document.getElementById("toolBar");

    //TOGGLE MENU
    let toolbarX = document.getElementById("toolbar-x");
    toolbarX.addEventListener("click", toggleMenu);

    function toggleMenu() {
        console.log("menu toggled")
        toolbar.classList.toggle("toolbarShow");
    }

    window.addEventListener("keydown", function (e) {
        console.log("keydown");

        if (e.keyCode === 77) {
            toggleMenu();
        }
        //audio.play();
        //renderFrame();
    });

    //CHANGE COLORS
    let barInput = document.getElementById("bar-color");
    barInput.addEventListener("input", changeBarColor);

    let bgInput = document.getElementById("bg-color");
    bgInput.addEventListener("input", changeBgColor);

    function changeBarColor() {
        let bands = document.querySelectorAll(".band");
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

        //window.addEventListener("keydown", function () {



        var files = this.files;
        audio.src = URL.createObjectURL(files[0]);
        audio.load();
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

        analyser.fftSize = 256;

        var bufferLength = analyser.frequencyBinCount;
        console.log(bufferLength);

        let barDiv = document.getElementById("barDiv");

        for (var i = 0; i < bufferLength; i++) {

            let band = document.createElement("div");
            band.classList.add("band");
            barDiv.appendChild(band);

        }

        let bands = document.querySelectorAll(".band");

        var dataArray = new Uint8Array(bufferLength);

        var WIDTH = document.body.clientWidth;
        //var HEIGHT = canvas.height;

        var barWidth = (WIDTH / bufferLength) * 2.5;
        var barHeight;
        var x = 0;

        bands.forEach(band => {
            band.style.width = barWidth + "px";
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

        //});
    };

};

