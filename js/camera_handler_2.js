/*
** The width and height of the captured photo. We will set the
** width to the value defined here, but the height will be
** calculated based on the aspect ratio of the input stream.
*/
var width = 640;
var height = 0;

/*
** |streaming| indicates whether or not we're currently streaming
** video from the camera. Obviously, we start at false.
*/
var streaming = false;

/*
** The various HTML elements we need to configure or control. These
** will be set by the startup() function.
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
var downloadPhoto = document.getElementById('download');
// var photoMontages = document.getElementsByClassName('demo')[0];

/*
** When user click on remove button in user photo gallery
** below script will remove photo from page and from database on server
*/
ul.addEventListener('click', (e) => {
	if (e.target.tagName === 'BUTTON') {
		const button = e.target;
		const li = button.parentNode;
		const ul = li.parentNode;
		const img_alt = li.childNodes[0].alt;
		const img = "img_id=" + img_alt;
		const xhr = new XMLHttpRequest();

		xhr.open("POST", "../inc/edit_photo/delete_photo.php", true);
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				console.log(xhr.responseText);
				ul.removeChild(li);
			}
		};
		xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
		xhr.send(img);
	}
});

/*
** Main function in this file.
** It will be activated after page load.
*/
function startup() {
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
		video.pause();
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

}

function turnOnCamera() {
	canvas.style.display = 'none';
	startVideo.style.display = 'none';
	uploadForm.style.display = 'none';
	video.style.display = '';
	navigator.getMedia = ( navigator.getUserMedia ||
							navigator.webkitGetUserMedia ||
							navigator.mozGetUserMedia ||
							navigator.msGetUserMedia);

	navigator.getMedia(
		{
			video: true,
			audio: false
		},
		function(stream) {
			if (navigator.mozGetUserMedia) {
				video.mozSrcObject = stream;
			} else {
				// video.srcObject = stream;
				var vendorURL = window.URL || window.webkitURL;
				video.src = vendorURL.createObjectURL(stream);
			}
			video.play();
		},
		function(err) {
			console.log("An error occured! " + err);
		}
	);

	video.addEventListener('canplay', function(ev){
		if (!streaming) {
			height = video.videoHeight / (video.videoWidth/width);
		
			// Firefox currently has a bug where the height can't be read from
			// the video, so we will make assumptions if this happens.
		
			if (isNaN(height)) {
				height = width / (4/3);
			}
		
			video.setAttribute('width', width);
			video.setAttribute('height', height);
			canvas.setAttribute('width', width);
			canvas.setAttribute('height', height);
			streaming = true;
		}
	}, false);
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
	if (width && height) {
		canvas.width = width;
		canvas.height = height;
		context.drawImage(video, 0, 0, width, height);
	}
}

/*
** Activate this function when user click on frame or picture button
** user wants to add on canvas
*/
// function addFilterOnPhoto(path) {
// 	var context = canvas.getContext('2d');

// 	var img = new Image();   // Create new img element
// 	img.addEventListener('load', function() {
// 		context.drawImage(img, 0, 0, 640, 480);
// 	}, false);
// 	img.src = path; // Set source path
// 	savePhoto.removeAttribute('disabled');
// }

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
	** Create new img tag on page.
	** This img will make a preview of effects on video stream.
	*/
	const img_copy = createIMG(path);
	snapPhoto.removeAttribute('disabled');
	cameraDiv.appendChild(img_copy);
	/*
	** start drag and drop listener
	*/
	dragAndDrop(img_copy);
}

/*
** Add listener for remove last effect button
*/
removeLast.addEventListener("click", function(ev) {
	var imgs = document.getElementsByClassName('temp');
	var imgsLenght = cameraDiv.querySelectorAll(".temp").length;

	if (imgsLenght > 0) {
		imgs[imgsLenght - 1].parentNode.removeChild(imgs[imgsLenght - 1]);
	}
	ev.preventDefault();
});

/*
** Add listener for increase effect size button
*/
sizeUpEffect.addEventListener("click", function(ev) {
	var imgs = document.getElementsByClassName('temp');
	var imgsLenght = cameraDiv.querySelectorAll(".temp").length;
	var target;
	var width;
	var height;

	if (imgsLenght > 0) {
		target = imgs[imgsLenght - 1];
		width = target.offsetWidth;
		height = target.offsetHeight;
		if (width < 400 && height < 400) {
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
	var imgsLenght = cameraDiv.querySelectorAll(".temp").length;
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

// need to fix script bellow

// downloadPhoto.addEventListener("click", function(ev) {
// 	this.href = canvas.toDataURL();
// 	this.download = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
// 	ev.preventDefault();
// });

// photoMontages.onclick = function(event) {
// 	var target = event.target;
// 	if (target.className === 'demo cursor' && target.tagName == 'IMG') {
// 		var img_copy = target.cloneNode(true);
// 		snapPhoto.removeAttribute('disabled');
// 		img_copy.classList.add('temp');
// 		document.getElementById('camera').appendChild(img_copy);
// 		dragAndDrop(img_copy);
// 	}

// 	function dragAndDrop(img) {
// 		var mousePosition;
// 		var offset = [0,0];
// 		var isDown = false;

// 		img.addEventListener('mousedown', function(e) {
// 		    isDown = true;
// 		    offset = [
// 		        img.offsetLeft - e.clientX,
// 		        img.offsetTop - e.clientY
// 		    	];
// 		}, true);

// 		document.addEventListener('mouseup', function() {
// 		    isDown = false;
// 		}, true);

// 		document.addEventListener('mousemove', function(event) {
// 		    event.preventDefault();
// 		    if (isDown) {
// 		        mousePosition = {

// 		            x : event.clientX,
// 		            y : event.clientY

// 		        };
// 		        image.style.left = (mousePosition.x + offset[0]) + 'px';
// 		        image.style.top  = (mousePosition.y + offset[1]) + 'px';
// 		    }
// 		}, true);
		
// 	}
// }

/*
** This function add captured photo to side block woth user saved photo
** also it will add photo
*/
function saveImage() {
	var xhr = new XMLHttpRequest();
	var imgFromCanvas = canvas.toDataURL("image/png");
	var img = "img=" + imgFromCanvas;
	xhr.open("POST", "../inc/edit_photo/save_photo.php", true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			console.log(xhr.responseText);
			// photo.setAttribute('src', imgFromCanvas);
			var li = createLI(imgFromCanvas);
			ul.appendChild(li);
		}
	};
	xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
	xhr.send(img);

	function createLI(src) {
		function createElement(elementName, property, value) {
			const element = document.createElement(elementName);
			element[property] = value; 
			return element;
		}
		
		function appendToLI(elementName, property, value) {
			const element = createElement(elementName, property, value);
			li.appendChild(element);
			return element;
		}
		
		const li = document.createElement('li');
		appendToLI('img', 'src', src);
		appendToLI('button', 'textContent', 'remove');
		return li;
	}
}

/*
** Set up our event listener to run the startup process
** once loading is complete.
*/
window.addEventListener('load', startup, false);
