// Put event listeners into place
window.addEventListener("DOMContentLoaded", function() {
	// Grab elements, create settings, etc.
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');
	var video = document.getElementById('video');
	var mediaConfig =  { video: true };
	// var sampleImage = document.getElementById("ringoImage");
	// var	canvasFromImg = convertImageToCanvas(sampleImage);
	var errBack = function(e) {
		console.log('An error has occurred!', e)
	 };

	// Put video listeners into place
	if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
		navigator.mediaDevices.getUserMedia(mediaConfig).then(function(stream) {
				video.src = window.URL.createObjectURL(stream);
				video.play();
		});
	} else if(navigator.getUserMedia) { // Standard
		navigator.getUserMedia(mediaConfig, function(stream) {
			video.src = stream;
			video.play();
		}, errBack);
	} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
		navigator.webkitGetUserMedia(mediaConfig, function(stream){
			video.src = window.webkitURL.createObjectURL(stream);
			video.play();
		}, errBack);
	} else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
		navigator.mozGetUserMedia(mediaConfig, function(stream){
			video.src = window.URL.createObjectURL(stream);
			video.play();
		}, errBack);
	}

	// Trigger photo take
	document.getElementById('snap').addEventListener('click', function() {
		context.drawImage(video, 0, 0, 640, 480);
		sendFile(convertCanvasToImage(canvas));
	});

}, false);

// document.getElementById("canvasHolder").appendChild(canvas);

// var canvas = document.getElementById("canvas");
// var ctx = canvas.getContext("2d");

// var img1 = loadImage('img/treats.svg', main);
// var img2 = loadImage('img/city-logo.svg', main);

// var imagesLoaded = 0;
// function main() {
//     imagesLoaded += 1;

//     if(imagesLoaded == 2) {
//         // composite now
//         ctx.drawImage(img1, 0, 0);

//         ctx.globalAlpha = 0.5;
//         ctx.drawImage(img2, 0, 0);
//     }
// }

function sendFile(file) {
  var uri = '../inc/camera.php';
  var xhr = new XMLHttpRequest();
  var fd = new FormData();

  xhr.open('POST', uri, true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      console.log(xhr.responseText);
      document.getElementById("pngHolder").appendChild(convertCanvasToImage(canvas));
      //do what you want with the image name returned
      //e.g update the interface
    }
  };
  fd.append('fileToUpload', file, 'chris1.png');
  xhr.send(fd);
}

function loadImage(src, onload) {
    var img = new Image();

    img.onload = onload;
    img.src = src;

    return img;
}

// Converts canvas to an image
function convertCanvasToImage(canvas) {
	var image = new Image();
	image.src = canvas.toDataURL("image/png");
	console.log(image);

	return image;
}

// Converts image to canvas; returns new canvas element
function convertImageToCanvas(image) {
	var canvas = document.createElement("canvas");
	canvas.width = image.width;
	canvas.height = image.height;
	canvas.getContext("2d").drawImage(image, 0, 0);

	return canvas;
}
