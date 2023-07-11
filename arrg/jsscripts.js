
    function playError() {
        var audioElement = document.createElement('audio');
        audioElement.setAttribute('src', 'assets/error1.mp3');
        audioElement.setAttribute('autoplay', 'autoplay');
        audioElement.load();
        audioElement.play();
    }
    function playGood() {
        var audioElement = document.createElement('audio');
        audioElement.setAttribute('src', 'assets/good.mp3');
        audioElement.setAttribute('autoplay', 'autoplay');
        audioElement.load();
        audioElement.play();
    }
