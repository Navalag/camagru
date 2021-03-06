/*
** The width and height of the captured photo. We will set the
** width to the value defined here, but the height will be
** calculated based on the aspect ratio of the input stream.
*/
var width = 640;
var height = 480;
/*
** |streaming| indicates whether or not we're currently streaming
** video from the camera. Obviously, we start at false.
**
** |localstream| will hold video steam connection.
** We will need it lates to turn off camera.
*/
var streaming = false;
var localstream;
/*
** The various HTML elements we need to configure or control.
** These will be set by the startup() function.
*/
var video = null;
var canvas = null;
var photo = null;
var startVideo = null;
var snapPhoto = null;
var savePhoto = null;
var cameraDiv = null;

var ul = document.getElementById('photo');
var removeLast = document.getElementById('removeLast');
var sizeUpEffect = document.getElementById('sizeUp');
var sizeDownEffect = document.getElementById('sizeDown');
var uploadIMG = document.getElementById('uploadImage');

/*
** Below is the main function in this file.
** Run the startup process once page loading is complete.
*/
window.addEventListener('load', function() {
	video = document.getElementById('video');
	canvas = document.getElementById('canvas');
	startVideo = document.getElementById('startVideo');
	snapPhoto = document.getElementById('snapPhoto');
	uploadForm = document.getElementById('upload-form');
	savePhoto = document.getElementById('save');
	cameraDiv = document.getElementById('cameraDiv');
	/*
	** Listener for TurnOn Camera button
	*/
	startVideo.addEventListener('click', turnOnCamera);
	/*
	** Listener for TakePhoto button
	*/
	snapPhoto.addEventListener('click', function(ev) {
		video.style.display = 'none';
		canvas.style.display = '';
		takePicture();
		ev.preventDefault();
	}, false);
	/*
	** Listener for SavePhoto button
	*/
	savePhoto.addEventListener('click', function(ev){
		saveImage();
		ev.preventDefault();
	}, false);

}, false);

function turnOnCamera() {
	canvas.style.display = 'none';
	startVideo.style.display = 'none';
	uploadForm.style.display = 'none';
	video.style.display = '';
	navigator.getMedia = ( navigator.getUserMedia ||
							navigator.webkitGetUserMedia ||
							navigator.mozGetUserMedia ||
							navigator.msGetUserMedia );

	navigator.getMedia(
		{
			video: true,
			audio: false
		},
		function(stream) {
			localstream = stream;
			video.srcObject = stream;
		},
		function(err) {
			console.log("An error occured! " + err);
		}
	);
}

/*
** Capture a photo by fetching the current contents 
** of the video and drawing it into a canvas,
** then converting that to a PNG format data URL.
**
** By drawing it on an offscreen canvas and then
** drawing that to the screen, we can change its size
** and/or apply other changes before drawing it.
*/
function takePicture() {
	var context = canvas.getContext('2d');
	var imgs = cameraDiv.querySelectorAll(".temp");
	var uploadedIMG = cameraDiv.querySelector('.uploaded');
	var imgsLenght = imgs.length;
	/*
	** getBoundingClientRect() method returns the size
	** of an element and its position relative to the viewport.
	*/
	var parentPos = canvas.getBoundingClientRect();
	var i = 0;

	if (uploadedIMG) {
		width = uploadedIMG.width;
		height = uploadedIMG.height;
		canvas.width = width;
		canvas.height = height;
		context.drawImage(uploadedIMG, 0, 0, width, height);
		addMontagesOnCanvas();
	} else if ((width && height) && !uploadedIMG) {
		// first add on canvas picture from video stream
		context.drawImage(video, 0, 0, width, height);
		addMontagesOnCanvas();
	}
	function addMontagesOnCanvas() {
		// than add on canvas all superposable images
		while (i < imgsLenght) {
			width = imgs[i].width;
			height = imgs[i].height;

			var childrenPos = imgs[i].getBoundingClientRect(),
				relativePos = {};

			relativePos.top = childrenPos.top - parentPos.top;
			relativePos.left = childrenPos.left - parentPos.left;
			context.drawImage(imgs[i], relativePos.left, relativePos.top, width, height);
			i++;
		}
		/*
		** At the end turn off video stream
		** and remove all superposable images from page, 
		** means leave it only as effects on img we created above.
		** Than turn on save photo button.
		*/
		if (video.src) {
			video.src = "";
			localstream.getTracks()[0].stop();
		} else if (uploadedIMG) {
			uploadedIMG.parentNode.removeChild(uploadedIMG);
		}
		while (imgsLenght > 0) {
			imgs[imgsLenght - 1].parentNode.removeChild(imgs[imgsLenght - 1]);
			imgsLenght--;
		}
		savePhoto.removeAttribute('disabled');
		/*
		** set width and height to null to prevent double call function
		** takePicture()
		*/
		width = null;
		height = null;
		uploadedIMG = null;
	}
}

/*
** Activate script when user clicks on one of the superposable images
*/
function addFilterOnPhoto(path) {
	function createIMG(src) {
		const img = document.createElement('img');
		img.setAttribute('src', src);
		img.setAttribute('class', 'temp');
		return img;
	}
	function dragAndDrop(img) {
		var mousePosition;
		var offset = [0,0];
		var isDown = false;

		img.addEventListener('mousedown', function(e) {
			isDown = true;
			offset = [
					  img.offsetLeft - e.clientX,
					  img.offsetTop - e.clientY
					 ];
		}, true);

		document.addEventListener('mouseup', function() {
			isDown = false;
		}, true);

		document.addEventListener('mousemove', function(event) {
			event.preventDefault();
			if (isDown) {
				mousePosition = {

					x : event.clientX,
					y : event.clientY

				};
				img.style.left = (mousePosition.x + offset[0]) + 'px';
				img.style.top = (mousePosition.y + offset[1]) + 'px';
			}
		}, true);
	}
	/*
	** This script will work only if video stream is activated.
	** Create new img tag on page.
	** This img will make a preview of effects on video stream.
	*/
	if (video.style.display !== 'none' || document.querySelector('.uploaded')) {
		const img_copy = createIMG(path);
		cameraDiv.appendChild(img_copy);

		snapPhoto.removeAttribute('disabled');
		removeLast.removeAttribute('disabled');
		sizeUpEffect.removeAttribute('disabled');
		sizeDownEffect.removeAttribute('disabled');
		/*
		** start drag and drop listener
		*/
		dragAndDrop(img_copy);
	}
}

/*
** Add listener for remove last effect button
*/
removeLast.addEventListener("click", function(ev) {
	var imgs = document.getElementsByClassName('temp');
	var imgsLenght = imgs.length;

	if (imgsLenght > 0) {
		imgs[imgsLenght - 1].parentNode.removeChild(imgs[imgsLenght - 1]);
	}
	// disable take photo button when user removed all effects
	if (imgsLenght == 1) {
		snapPhoto.setAttribute('disabled', '');
	}
	ev.preventDefault();
});

/*
** Add listener for increase effect size button
*/
sizeUpEffect.addEventListener("click", function(ev) {
	var imgs = document.getElementsByClassName('temp');
	var imgsLenght = imgs.length;
	var target;
	var width;
	var height;

	if (imgsLenght > 0) {
		target = imgs[imgsLenght - 1];
		width = target.offsetWidth;
		height = target.offsetHeight;
		if (width < 640 && height < 480) {
			width += 5;
			height += 5;
			target.style.height = height + 'px';
			target.style.width = width + 'px';
		}
	}
	ev.preventDefault();
});

/*
** Add listener for decrease effect size button
*/
sizeDownEffect.addEventListener("click", function(ev) {
	var imgs = document.getElementsByClassName('temp');
	var imgsLenght = imgs.length;
	var target;
	var width;
	var height;

	if (imgsLenght > 0) {
		target = imgs[imgsLenght - 1];
		width = target.offsetWidth;
		height = target.offsetHeight;
		if (width > 40 && height > 40) {
			width -= 5;
			height -= 5;
			target.style.height = height + 'px';
			target.style.width = width + 'px';
		}
	}
	ev.preventDefault();
});

uploadIMG.addEventListener("click", function() {
	var file = document.querySelector('input[type=file]').files[0];
	var parentDiv = document.getElementById('cameraDiv');
	var newImg = document.createElement('img');
	var reader = new FileReader();

	if (file) {
		reader.readAsDataURL(file);
	}
	reader.onloadend = function () {
		startVideo.style.display = 'none';
		uploadForm.style.display = 'none';
		newImg.setAttribute('src', reader.result);
		newImg.setAttribute('class', 'uploaded');
		parentDiv.appendChild(newImg);
	}
});

function saveImage() {
	var xhr = new XMLHttpRequest();
	var imgFromCanvas = canvas.toDataURL("image/png");
	var img = "img=" + imgFromCanvas;
	xhr.open("POST", "../inc/php-ajax/save_photo.php", true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			console.log(xhr.responseText);
			location.reload();
		}
	};
	xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
	xhr.send(img);
}
