window.onload = function () {
  console.log(
    "%cAudio Visualiser",
    "background-color: rgb(34, 34, 34) ; color: rgb(255, 255, 66); padding: 5px; font-size: 1.2rem;"
  );
  console.log("Built by Johan Johansson");
  console.log(
    "If you have any feedback or suggestions, or you want to contribute to this project, contact me here:"
  );

  console.log("%cjohan96johansson@gmail.com", "color: rgb(28, 191, 255);");

  //DECLARING VARIABLES
  const file = document.getElementById("audio-file");
  const audio = document.getElementById("audio");
  const layouts = document.querySelectorAll(".layout");
  const vinylLayout2 = document.querySelector(".vinyl-layout-2");
  const vinylCenter = document.querySelector(".vinyl-center");
  const playButton = document.getElementById("playButton");
  const pauseButton = document.getElementById("pauseButton");
  const helpButton = document.getElementById("helpBtn");

  var BW = 128;
  var removeFreq = 0;
  var particleInterval;
  var particleOpacity = 0.7;
  var particleSpeedX = 8200;
  var particleSpeedXMin = 8000;
  var particleSpeedY = 2200;
  var particleSpeedYMin = 2000;

  playButton.addEventListener("click", startMusic);
  pauseButton.addEventListener("click", stopMusic);
  helpButton.addEventListener("click", showHelp);

  let advX = document.getElementById("adv-x");
  advX.addEventListener("click", showAdvancedOptions);

  let helpX = document.getElementById("help-x");
  helpX.addEventListener("click", showHelp);

  //HOVER TO SHOW MENU
  let hovertop = document.getElementById("hovertop");
  hovertop.addEventListener("mouseenter", function () {
    let toolbar = document.getElementById("toolBar");
    if (toolbar.classList.contains("toolbarShow")) {
      toggleMenu();
    }
  });

  let hoverbottom = document.getElementById("hoverbottom");
  hoverbottom.addEventListener("mouseenter", function () {
    let controls = document.getElementById("controls");
    if (controls.classList.contains("hide-controls")) {
      toggleControls();
    }
  });

  //CHECK IF PLAYING
  var isPlaying = false;

  let particlecheck = document.getElementById("particlecheck");
  particlecheck.addEventListener("click", function () {
    if (isPlaying) {
      spawnParticles();
    }
  });

  //SETTING BAND WIDTH
  const bandWidthRadios = document.querySelectorAll(".bandWidth");
  bandWidthRadios.forEach((radio) => {
    radio.addEventListener("click", function (e) {
      let BWValue = e.currentTarget.value;
      BW = BWValue;
    });
  });

  //UPDATE BANDWIDTH ON INIT
  function updateBandWidth() {
    let BWWidthRadios = document.querySelectorAll(".bandWidth");
    BWWidthRadios.forEach((radio) => {
      if (radio.checked) {
        BW = radio.value;
      }
    });
  }

  //Gradient
  let gradient1 = document.getElementById("gradient-1");
  let gradient2 = document.getElementById("gradient-2");
  gradient1.addEventListener("input", setGradientBg);
  gradient2.addEventListener("input", setGradientBg);

  //SHOW SONG INFO
  let songinfo = document.getElementById("songinfo");
  let songinfo2 = document.getElementById("songinfo-2");
  let songinfo3 = document.getElementById("songinfo-3");
  let infocheck = document.getElementById("infocheck");
  infocheck.addEventListener("click", toggleSongInfo);
  let titleArtist = document.getElementById("title-artist");
  let songTitle = document.getElementById("song-title");
  let artistName = document.getElementById("artist-name");
  songTitle.addEventListener("input", updateTitle);
  artistName.addEventListener("input", updateArtist);

  //text color
  let textColor = document.getElementById("text-color");
  textColor.addEventListener("input", changeTextColor);

  //SWITCHING LAYOUTS
  let layoutSelect = document.getElementById("layout-select");
  layoutSelect.addEventListener("change", switchLayout);

  //CHANGE EQ WIDTH
  let rangeEQ = document.getElementById("range-eq");
  rangeEQ.addEventListener("input", changeEQWidth);

  let rangeFreq = document.getElementById("range-freq");
  rangeFreq.addEventListener("input", changeFreqWidth);

  //CHANGE PARTICLE WIDTH + SPAWN RATE
  let particleVariation = document.getElementById("particles-variation");
  var particleVar = particleVariation.value;

  let spawnRange = document.getElementById("range-rate");
  var spawnRate = Math.abs(spawnRange.value);

  particleVariation.addEventListener("input", updateParticleValues);
  spawnRange.addEventListener("input", updateSpawnRate);
  function updateParticleValues() {
    particleVar = particleVariation.value;
  }

  function updateSpawnRate() {
    if (isPlaying) {
      clearInterval(particleInterval);
      spawnRate = Math.abs(spawnRange.value);
      spawnParticles();
    } else {
      spawnRate = Math.abs(spawnRange.value);
    }
  }

  //PARTICLE OPACITY
  let pOpacityRange = document.getElementById("adv-particle-opacity");
  pOpacityRange.addEventListener("input", function () {
    particleOpacity = pOpacityRange.value;
  });

  //PARTICLE SPEED
  let speedXSlider = document.getElementById("particle-speedX");
  let speedYSlider = document.getElementById("particle-speedY");
  speedXSlider.addEventListener("input", updateParticleSpeeds);
  speedYSlider.addEventListener("input", updateParticleSpeeds);
  function updateParticleSpeeds() {
    particleSpeedX = speedXSlider.value;
    particleSpeedY = speedYSlider.value;
  }

  //KICK SENSITIVITY
  let kickSensitivity = 254.5;
  let kickRange = document.getElementById("kickRange");
  kickRange.addEventListener("change", updateKickSens);

  //CHANGING FONTS
  var titleFont = document.getElementById("font-title");
  var artistFont = document.getElementById("font-artist");
  var songTitles = document.querySelectorAll(".song-title");
  var songArtists = document.querySelectorAll(".song-artist");
  titleFont.addEventListener("input", updateTitleFont);
  artistFont.addEventListener("input", updateArtistFont);

  function updateTitleFont() {
    let fontValue = titleFont.value;

    songTitles.forEach((title) => {
      title.className = "song-title";
      let currentFont = "font" + fontValue;
      title.classList.add(currentFont);
    });
  }
  function updateArtistFont() {
    let fontValue = artistFont.value;

    songArtists.forEach((artist) => {
      artist.className = "song-artist";
      let currentFont = "afont" + fontValue;
      artist.classList.add(currentFont);
    });
  }

  //UPLOAD BACKGROUND IMAGE
  let advImage = document.getElementById("adv-setimage");
  advImage.addEventListener("change", setBGImage);
  let repeatCheck = document.getElementById("repeatCheck");
  let coverCheck = document.getElementById("coverCheck");
  repeatCheck.addEventListener("change", setBGSettings);
  coverCheck.addEventListener("change", setBGSettings);

  function setBGImage() {
    let imgFile = advImage.files;
    let src = URL.createObjectURL(imgFile[0]);

    let bgIMG = document.getElementById("bodyBackgroundImg");
    bgIMG.style.backgroundImage = "url('" + src + "')";
  }

  function setBGSettings() {
    let repeatCheck = document.getElementById("repeatCheck");
    let coverCheck = document.getElementById("coverCheck");
    let bgIMG = document.getElementById("bodyBackgroundImg");
    if (repeatCheck.checked) {
      bgIMG.style.backgroundRepeat = "repeat";
    } else {
      bgIMG.style.backgroundRepeat = "no-repeat";
    }
    if (coverCheck.checked) {
      bgIMG.style.backgroundSize = "cover";
      console.log(bgIMG.style.backgroundSize);
    } else {
      bgIMG.style.backgroundSize = "contain";
      console.log(bgIMG.style.backgroundSize); //DOES NOT WORK
    }
  }

  //UPLOAD BACKGROUND VIDEO
  let advVideo = document.getElementById("adv-setvideo");
  advVideo.addEventListener("change", setBGVideo);

  function setBGVideo() {
    let videoFile = advVideo.files;
    let src = URL.createObjectURL(videoFile[0]);
    let bgURL = document.getElementById("bgURL");
    bgURL.setAttribute("value", src);

    let videoTag = document.querySelector("video");
    videoTag.setAttribute("src", src);
  }

  //CHANGE FONT SIZE
  /*let titleFontSize = document.getElementById("titleFontSize");
  let artistFontSize = document.getElementById("artistFontSize");
  titleFontSize.addEventListener("input", changeFontSize);
  artistFontSize.addEventListener("input", changeFontSize);

  function changeFontSize() {
    let songTitle1 = document.getElementById("songtitle");
    let songTitle2 = document.getElementById("songtitle-2");
    let songArtist1 = document.getElementById("songartist");
    let songArtist2 = document.getElementById("songartist-2");

    songTitle1.style.fontSize = titleFontSize.value + "rem";
    songTitle2.style.fontSize = titleFontSize.value / 2 + "rem";
    songArtist1.style.fontSize = artistFontSize.value + "rem";
    songArtist2.style.fontSize = titleFontSize.value / 2 + "rem";
  }*/

  //BACKGROUND COLOR OPACITY
  var bgColorOpacity = document.getElementById("bgColorOpacity");
  var bodyBG = document.getElementById("bodyBackground");
  bgColorOpacity.addEventListener("input", changeBGOpacity);
  function changeBGOpacity() {
    bodyBG.style.opacity = bgColorOpacity.value;
  }

  //CHANGE COLORS
  let barInput = document.getElementById("bar-color");
  barInput.addEventListener("input", changeBarColor);

  //CHANGE TABS LINK/UPLOAD
  /*let linkTab1 = document.getElementById("link-tab-1");
  let linkTab2 = document.getElementById("link-tab-2");
  linkTab1.addEventListener("click", function () {
    showUploadTab();
  });
  linkTab2.addEventListener("click", function () {
    showLinkTab();
  });*/

  //FUNCTIONALITY FOR TABS
  /*function showUploadTab() {
    linkTab1.classList.add("tab-active");
    linkTab2.classList.remove("tab-active");
    let tab1 = document.getElementById("bg-tab-1");
    let tab2 = document.getElementById("bg-tab-2");
    tab1.classList.remove("hide-layout");
    tab2.classList.add("hide-layout");
  }
  function showLinkTab() {
    linkTab2.classList.add("tab-active");
    linkTab1.classList.remove("tab-active");
    let tab1 = document.getElementById("bg-tab-1");
    let tab2 = document.getElementById("bg-tab-2");
    tab2.classList.remove("hide-layout");
    tab1.classList.add("hide-layout");
  }*/

  //LINK BACKGROUND IMAGE FUNCTIONALITY
  /*let setImageLinkBtn = document.getElementById("setImageLink");
  setImageLinkBtn.addEventListener("click", setLinkedBG);
  function setLinkedBG() {
    let imgLinkInput = document.getElementById("imgLinkInput");
    let bg = document.getElementById("bodyBackgroundImg");
    bg.style.backgroundImage = "url(" + imgLinkInput.value + ")";
  }*/

  //INIT FUNCTION
  function init() {
    setGradientBg();
    toggleSongInfo();
    changeTextColor();
    updateTitle();
    updateArtist();
    switchLayout();
    changeEQWidth();
    updateBandWidth();
    updateKickSens();
    updateTitleFont();
    updateArtistFont();
    setBGSettings();
    changeBGOpacity();
    changeBorderColor();
  }
  init();

  //Uploading images
  const imgFile = document.getElementById("img-file");
  imgFile.addEventListener("change", updateImage);

  function updateImage() {
    let imgFiles = imgFile.files;
    let src = URL.createObjectURL(imgFiles[0]);

    let albumImage = document.getElementById("album-upload");
    albumImage.setAttribute("src", src);

    let albumImage2 = document.getElementById("album-upload-2");
    albumImage2.setAttribute("src", src);

    let albumImage3 = document.getElementById("album-upload-3");
    albumImage3.setAttribute("src", src);

    let vinylImage = document.querySelector(".vinyl-center");
    vinylImage.setAttribute("src", src);
  }
  //-----

  function updateKickSens() {
    kickSensitivity = kickRange.value;
  }
  //---

  //TOOLBAR SETUP
  const toolbar = document.getElementById("toolBar");

  //TOGGLE MENU
  /*const toolbarX = document.getElementById("toolbar-x");
  toolbarX.addEventListener("click", toggleMenu);*/

  function toggleMenu() {
    toolbar.classList.toggle("toolbarShow");
  }

  window.addEventListener("keydown", function (e) {
    if (e.keyCode === 52) {
      //A
      showAdvancedOptions();
    }
    if (e.keyCode === 51) {
      //C
      toggleControls();
    }
    if (e.keyCode === 53) {
      //H
      toggleCursor();
    }
    if (e.keyCode === 50) {
      //M
      toggleMenu();
    }
    if (e.keyCode === 49) {
      //P
      startMusic();
    }
  });

  function toggleSongInfo() {
    if (infocheck.checked) {
      songinfo.classList.remove("hide-layout");
      songinfo2.classList.remove("hide-layout");
      songinfo3.classList.remove("hide-layout");
      titleArtist.classList.remove("hide-layout");
    } else {
      songinfo.classList.add("hide-layout");
      songinfo2.classList.add("hide-layout");
      songinfo3.classList.add("hide-layout");
      titleArtist.classList.add("hide-layout");
    }
  }

  //SWITCHING BAND STYLE
  var bandSelect = document.getElementById("band-select");
  //bandSelect.addEventListener("change", switchBands);

  let bgInput = document.getElementById("bg-color");
  bgInput.addEventListener("input", changeBgColor);

  let particleInput = document.getElementById("particle-color");
  particleInput.addEventListener("input", changeParticleColor);

  function changeBarColor() {
    let bandClass = null;
    if (bandSelect.value == "style1") {
      bandClass = ".band";
    }
    if (bandSelect.value == "style2") {
      bandClass = ".band2";
    }
    let bands = document.querySelectorAll(bandClass);

    bands.forEach((band) => {
      if (bandSelect.value == "style1") {
        band.style.backgroundColor = barInput.value;
      }
      if (bandSelect.value == "style2") {
        band.querySelector("div").style.backgroundColor = barInput.value;
      }
    });

    let barDivBorder = document.getElementById("barDiv");
    barDivBorder.style.borderBottomColor = barInput.value;
  }

  function changeBorderColor() {
    let barDivBorder = document.getElementById("barDiv");
    barDivBorder.style.borderBottomColor = barInput.value;
  }

  function changeBgColor() {
    let bodyBG = document.getElementById("bodyBackground");
    bodyBG.style.background = "none";
    bodyBG.style.backgroundColor = bgInput.value;
  }

  //GRADIENT BACKGROUND
  function setGradientBg() {
    let bodyBG = document.getElementById("bodyBackground");
    bodyBG.style.background =
      "linear-gradient(to right," +
      gradient1.value +
      "," +
      gradient2.value +
      ")";
  }
  //-------

  function changeParticleColor() {
    let particles = document.querySelectorAll(".particle");
    particles.forEach((particle) => {
      particle.style.backgroundColor = particleInput.value;
    });
  }

  let albumCover = document.getElementById("album-cover");

  file.onchange = function () {
    //getting the file and setting the source
    var files = this.files;
    audio.src = URL.createObjectURL(files[0]);
    audio.load();
  };

  async function startMusic() {
    isPlaying = true;
    playButton.classList.add("hide-layout");
    pauseButton.classList.remove("hide-layout");

    //Particle effects
    if (particlecheck.checked) {
      spawnParticles();
    }

    //---

    if (layoutSelect.value == "layout2") {
      let vinylLayout2 = document.querySelector(".vinyl-layout-2");
      vinylLayout2.classList.add("vinyl-spin");
      vinylCenter.classList.add("vinyl-spin");
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

    analyser.fftSize = BW; //64 to give it less bands, or anything to the power of 2

    var bufferLength = analyser.frequencyBinCount;

    //barDivs
    let barDiv = document.getElementById("barDiv");
    //let barDiv2 = document.getElementById("barDiv2");

    let storeBand = null;

    for (var i = 0; i < bufferLength - removeFreq; i++) {
      let band = document.createElement("div");

      if (bandSelect.value == "style1") {
        band.classList.add("band");
        storeBand = ".band";
      }
      if (bandSelect.value == "style2") {
        band.classList.add("band2");
        storeBand = ".band2";

        //adding point to band 2
        let point = document.createElement("div");
        point.classList.add("point");
        band.appendChild(point);
      }

      barDiv.appendChild(band);
    }

    if (bandSelect.value == "style2") {
      //Getting all points
      var points = document.querySelectorAll(".point");
    }

    //storeBand has the right class
    let bands = document.querySelectorAll(storeBand);

    changeBarColor();

    var dataArray = new Uint8Array(bufferLength);

    var WIDTH = document.body.clientWidth;
    //var HEIGHT = canvas.height;

    var barWidth = (WIDTH / bufferLength) * 2.5;
    var barHeight;
    var x = 0;

    let leftPos = -5;

    bands.forEach((band) => {
      band.style.width = barWidth + "px";
    });

    function renderFrame() {
      requestAnimationFrame(renderFrame);

      x = 0;

      analyser.getByteFrequencyData(dataArray);

      //ctx.fillStyle = "#000";
      //ctx.fillRect(0, 0, WIDTH, HEIGHT);

      for (var i = 0; i < bufferLength - removeFreq; i++) {
        //detecting kick hits
        if (i == 1) {
          if (dataArray[i] > kickSensitivity) {
            //change sensitivity
            albumCover.classList.add("grow");
          }
          if (dataArray[i] < kickSensitivity) {
            albumCover.classList.remove("grow");
          }
        }
        //---

        //Height
        //if (i < 10) {
        barHeight = dataArray[i] / 2.7; /// 1.5;
        bands.item(i).style.height = barHeight + "px";
        /*} else {
          barHeight = dataArray[i] / 2.7; /// 1.5;
          bands.item(i).style.height = barHeight + "px";
        }*/

        //Setting dynamic width of bands
        if (bandSelect.value == "style2") {
          barHeight = dataArray[i] / 1.95; /// 1.5;
          bands.item(i).style.height = barHeight + "px";
        }

        if (layoutSelect.value == "layout3") {
          barHeight = dataArray[i] / 4.3; /// 1.5;
          bands.item(i).style.height = barHeight + "px";
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

  function stopMusic() {
    audio.pause();

    pauseButton.classList.add("hide-layout");
    playButton.classList.remove("hide-layout");
    vinylLayout2.classList.remove("vinyl-spin");
    vinylCenter.classList.remove("vinyl-spin");
    clearInterval(particleInterval);
  }

  function toggleControls() {
    let controls = document.getElementById("controls");
    controls.classList.toggle("hide-controls");
  }

  function switchLayout() {
    layouts.forEach((layout) => {
      layout.classList.add("hide-layout");
    });

    if (layoutSelect.value == "layout1") {
      layouts.item(0).classList.remove("hide-layout");
    }
    if (layoutSelect.value == "layout2") {
      layouts.item(1).classList.remove("hide-layout");
      if (isPlaying) {
        vinylLayout2.classList.add("vinyl-spin");
        vinylCenter.classList.add("vinyl-spin");
      }
    }
    if (layoutSelect.value == "layout3") {
      layouts.item(2).classList.remove("hide-layout");
      let barDivRef = document.getElementById("bardiv-container");
      barDivRef.style.bottom = "13%";
    } else {
      let barDivRef = document.getElementById("bardiv-container");
      barDivRef.style.bottom = "5%";
    }
  }

  function spawnParticles() {
    particleInterval = setInterval(function () {
      let randomLeft = Math.random() * 101;
      let randomTop = Math.random() * 101;

      //random variation value and particle size
      let randWidth = Math.random() * particleVar;
      //randWidth = randWidth + particleWidth.trim();

      let randBorderRadius = 100; //Math.random() * 50 + 30;
      //let randHeight = Math.random() * 6;

      let particle = document.createElement("div");
      particle.classList.add("particle");
      particle.style.left = randomLeft + "%";
      particle.style.top = randomTop + "%";
      particle.style.width = randWidth + "px";
      particle.style.height = randWidth + "px";
      particle.style.borderRadius = randBorderRadius + "%";
      particle.style.backgroundColor = particleInput.value;
      document.body.appendChild(particle);
      move(particle, particleSpeedX, particleSpeedY);
    }, spawnRate);
  }

  /*function getSpawnRate() {
    return spawnRate;
  }*/

  function move(element, speedX, speedY) {
    //variables
    let randomRotation = Math.floor(Math.random() * 150);
    let endRotation = Math.floor(Math.random() * 100);

    let endPositionX = Math.floor(Math.random() * speedX); //+8000
    let endPositionY = Math.floor(Math.random() * speedY); //+2000

    let randomOpacity = Math.random() * particleOpacity; //+ 0.2;

    let keyframes = [
      {
        transform:
          "rotate(" + randomRotation + "deg) translateX(0%) translateY(0%)",
        opacity: 0,
      },
      {
        opacity: randomOpacity,
      },
      {
        transform:
          "rotate(" +
          endRotation +
          "deg) translateX(" +
          endPositionX +
          "%) translateY(" +
          endPositionY +
          "%)",
        opacity: 0,
      },
    ];

    let options = {
      duration: 9000,
      iterations: 1,
      fill: "forwards",
      easing: "ease",
    };

    element.animate(keyframes, options);

    setTimeout(function () {
      element.remove();
    }, 10000);
  }

  function toggleCursor() {
    document.body.classList.toggle("hidecursor");
  }

  //UPDATE TITLE AND ARTIST
  function updateTitle() {
    let titleText = document.getElementById("songtitle");
    let titleText2 = document.getElementById("songtitle-2");
    let titleText3 = document.getElementById("songtitle-3");
    titleText.innerText = songTitle.value;
    titleText2.innerText = songTitle.value;
    titleText3.innerText = songTitle.value;
  }
  function updateArtist() {
    let artistText = document.getElementById("songartist");
    let artistText2 = document.getElementById("songartist-2");
    let artistText3 = document.getElementById("songartist-3");
    artistText.innerText = artistName.value;
    artistText2.innerText = artistName.value;
    artistText3.innerText = artistName.value;
  }

  //SHOW ADVANCED OPTIONS
  function showAdvancedOptions() {
    let advOptions = document.getElementById("adv-options");
    advOptions.classList.toggle("hide-layout");
  }

  //CHANGE EQ WIDTH
  function changeEQWidth() {
    let eq = document.getElementById("barDiv");
    eq.style.width = rangeEQ.value + "%";
  }

  //CHANGE FREQUENCY WIDTH
  function changeFreqWidth() {
    removeFreq = rangeFreq.value;
  }

  //CHANGE TEXT COLOR
  function changeTextColor() {
    let t1 = document.getElementById("songtitle");
    let t2 = document.getElementById("songtitle-2");
    let t3 = document.getElementById("songtitle-3");
    let a1 = document.getElementById("songartist");
    let a2 = document.getElementById("songartist-2");
    let a3 = document.getElementById("songartist-3");
    let dot = document.getElementById("song-dot");

    t1.style.color = textColor.value;
    t2.style.color = textColor.value;
    t3.style.color = textColor.value;
    a1.style.color = textColor.value;
    a2.style.color = textColor.value;
    a3.style.color = textColor.value;
    dot.style.color = textColor.value;
  }

  function showHelp() {
    let helpSection = document.getElementById("helpSection");
    helpSection.classList.toggle("helpSectionShow");
  }

  /*function switchBands() {
    let bandClass = null;
    if (bandSelect.value == "style1") {
      bandClass = ".band";
    }
    if (bandSelect.value == "style2") {
      bandClass = ".band2";
    }

    let bands = document.querySelectorAll(bandClass);

    bands.forEach((band) => {
      if (bandSelect.value == "style1") {
        band.classList.add("band");
        band.classList.remove("band2");
        band.querySelector(".point").remove();
        band.style.backgroundColor = barInput.value;
      }
      if (bandSelect.value == "style2") {
        band.classList.add("band2");
        band.classList.remove("band");
        band.style.backgroundColor = "red !important";
        let point = document.createElement("div");
        point.classList.add("point");
        band.appendChild(point);
        //band.querySelector("div").style.backgroundColor = barInput.value;
      }
    });
  }*/

  //KUTE JS MORPH
  /*var tween = KUTE.fromTo(
    "#morph1",
    { path: "#morph1" },
    { path: "#morph2" },
    {
      // options
      delay: 500,
      easing: "easingCubicInOut",
      yoyo: false,
      repeat: 0,
      duration: 1250,
      //complete: animComplete(),
    }
  ).start();

  var tween = KUTE.fromTo(
    "#morph-top-1",
    { path: "#morph-top-1" },
    { path: "#morph-top-2" },
    {
      // options
      delay: 500,
      easing: "easingCubicInOut",
      yoyo: false,
      repeat: 0,
      duration: 1250,
      //complete: animComplete(),
    }
  ).start();

  var tween = KUTE.fromTo(
    "#morph2-1",
    { path: "#morph2-1" },
    { path: "#morph2-2" },
    {
      // options
      delay: 500,
      easing: "easingCubicInOut",
      yoyo: false,
      repeat: 0,
      duration: 1400,
      //complete: animComplete(),
    }
  ).start();

  var tween = KUTE.fromTo(
    "#morph-top-2-1",
    { path: "#morph-top-2-1" },
    { path: "#morph-top-2-2" },
    {
      // options
      delay: 500,
      easing: "easingCubicInOut",
      yoyo: false,
      repeat: 0,
      duration: 1400,
      //complete: animComplete(),
    }
  ).start();*/
};
