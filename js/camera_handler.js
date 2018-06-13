// var canvas = document.getElementById('canvas');
// var context = canvas.getContext('2d');
// var video = document.getElementById('video');
// var mediaConfig =  { video: true };

const mediaDiv = document.getElementById('media');

const videoTag = document.createElement('video');
videoTag.setAttribute("id", "video");
videoTag.setAttribute("width", "640");
videoTag.setAttribute("height", "480");
videoTag.setAttribute("autoplay", "");
const video = document.getElementById('video');
const mediaConfig = { video: true };

const canvasTag = document.createElement('canvas');
canvasTag.setAttribute("id", "canvas");
canvasTag.setAttribute("width", "640");
canvasTag.setAttribute("height", "480");
const canvas = document.getElementById('canvas');

// var sampleImage = document.getElementById("ringoImage");
// var	canvasFromImg = convertImageToCanvas(sampleImage);
/*
** PUT VIDEO LISTENERS INTO PLASE
*/
document.getElementById('start-video').addEventListener('click', function() {
	var errBack = function(e) {
		console.log('An error has occurred!', e)
	};
	mediaDiv.appendChild(videoTag);
	/*
	** PUT VIDEO LISTENERS INTO PLASE
	*/
	window.addEventListener("DOMContentLoaded", function() {
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
	}, false);
	// canvas.style.display = 'none';
	// video.style.display = 'block';
});
/*
** SNAP PHOTO
*/
document.getElementById('snap').addEventListener('click', function() {
	video.pause();
	mediaDiv.removeChild(videoTag);
	mediaDiv.appendChild(canvasTag);
	let context = canvas.getContext('2d');

	// video.style.display = 'none';
	// canvas.style.display = 'block';
	context.drawImage(video, 0, 0, 640, 480);
});
/*
** SAVE PHOTO TO SERVER VIA AJAX
*/
document.getElementById('save').addEventListener('click', function() {
	var xhr = new XMLHttpRequest();
	var img_from_canvas = canvas.toDataURL("image/png");
	var img = "img=" + img_from_canvas;
	xhr.open("POST", "../inc/camera-photo/save_photo.php", true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			console.log(xhr.responseText);
			document.getElementById("pngHolder").appendChild(convertCanvasToImage(canvas));
		}
	};
	xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
	xhr.send(img);
});
/*
** ADD FRAME OR OTHER EFFECT
*/
document.getElementById('effect').addEventListener('click', function() {
	// var canvas_copy = document.getElementById('canvas-copy');
	// var context_copy = canvas_copy.getContext('2d');
	// context.drawImage(loadImage('../img/frame1.png', main), 0, 0, 640, 480);
	// console.log("check");

	let img1 = loadImage('../img/frame1.png', main);
	let context = canvas.getContext('2d');
	// var img2 = loadImage('', main);

	let imagesLoaded = 0;
	function main() {
		imagesLoaded += 1;
		// if(imagesLoaded == 2) {
			context.drawImage(img1, 0, 0, 640, 480);
			// ctx.globalAlpha = 0.5;
			// ctx.drawImage(img2, 0, 0);
		// }
	}

	function loadImage(src, onload) {
		var img = new Image();
		img.onload = onload;
		img.src = src;

		return img;
	}
});

// document.getElementById("canvasHolder").appendChild(canvas);

// var canvas = document.getElementById("canvas");
// var ctx = canvas.getContext("2d");



// Converts canvas to an image
function convertCanvasToImage(canvas) {
	var image = new Image();
	image.src = canvas.toDataURL("image/png");

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
