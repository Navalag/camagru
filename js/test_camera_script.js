var video = document.querySelector("#videoElement");
var context = canvas.getContext('2d');
var images = document.getElementsByClassName('images')[0];
var localstream;

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

function camera() {
  document.getElementsByClassName('profile_background')[0].style.display = 'block';
  document.getElementsByClassName('camera')[0].style.display = 'block';
  cameraOn();
  get_user_photo();
}

function get_user_photo() {
  var xhr = new XMLHttpRequest();
  var data = new FormData();

  data.append('user_photo', '1');
  xhr.open('POST','../config/load_img.php', true);
  xhr.onreadystatechange = function () {
      if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        var obj = JSON.parse(xhr.responseText);
        remove_previous_photos();
        show_user_photos(obj);
      };
  };
  xhr.send(data);
}

function show_user_photos(obj) {
  var i = 0;
  var start = document.getElementById('user_photos');

  while (i < obj.length) {
    var user_photos = document.createElement('img');
    var photo_id = obj[i]['src'].split('.');

    user_photos.src = "../photos/" + obj[i]['src'];
    user_photos.id = "photos_" + photo_id[0];
    user_photos.className = 'img_user_photos';
    user_photos.setAttribute("onclick", "remove_photos();");
    start.appendChild(user_photos);
    i++;
  }
}

function remove_photos() {
  var find_id = event.target.id;
  var get_id = find_id.split('_');
  var xhr = new XMLHttpRequest();
  var data = new FormData();

  data.append('remove_user_photo', get_id[1]);
  xhr.open('POST','../config/load_img.php', true);
  xhr.onreadystatechange = function () {
      if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        document.getElementById(get_id[1]).remove();
        document.getElementById(find_id).remove();
        //get_img_from_table();
      };
  };
  xhr.send(data);
}

function remove_previous_photos() {
  var remove_div = document.getElementById('user_photos').getElementsByClassName('img_user_photos');
  var photos = document.getElementsByClassName('img_user_photos');
  var i = 0;

  while (i < photos.length)
  {
    remove_div[i].parentNode.removeChild(remove_div[i]);
  }
}

document.getElementById("cameraOn").addEventListener("click", cameraOn);

function closeCamera() {
  document.getElementsByClassName('profile_background')[0].style.display = 'none';
  document.getElementsByClassName('camera')[0].style.display = 'none';
  cameraOff();
}

function cameraOn() {
  creat_video_element();
  if (navigator.getUserMedia) {
      if (document.getElementById('videoElement').style.display == 'none')
      {
        context.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('videoElement').style.display = 'block';
        document.getElementById('canvas').style.display = 'none';
      }
      navigator.getUserMedia({video: true}, handleVideo, videoError);

      document.getElementById('uploadPhoto').style.display = 'block';
      document.getElementById('increase').style.display = 'block';
      document.getElementById('decrease').style.display = 'block';
      document.getElementById('savePhoto').style.display = 'none';
      document.getElementById('downloadPhoto').style.display = 'none';
      document.getElementById('savePhoto').style.display = 'none';
      document.getElementById('remove').style.display = 'block';
      document.getElementById("cameraOn").innerHTML = "Clear img";
  }
}

function creat_video_element() {
  var video = document.getElementById('videoElement');

  if (video.nodeName == 'IMG') {
    var parentDiv = document.getElementById('video_container');
    var newVideo = document.createElement('video');

    video.parentNode.removeChild(video);
    newVideo.setAttribute('autoplay', 'true');
    newVideo.setAttribute('id', 'videoElement');
    parentDiv.appendChild(newVideo);
  }
}

function handleVideo(stream) {
    localstream = stream;
    video.srcObject = stream;
}

function videoError(e) {
    // do something
}

function cameraOff() {
  video.pause();
  video.src = "";
  document.getElementById('makePhoto').style.display = 'none';
  document.getElementById('savePhoto').style.display = 'none';
  document.getElementById('increase').style.display = 'none';
  document.getElementById('decrease').style.display = 'none';
  document.getElementById('remove').style.display = 'none';
  document.getElementById("cameraOn").innerHTML = "Camera on";
  localstream.getTracks()[0].stop();
  remove_previous_photos();
}

// Trigger photo take
document.getElementById("makePhoto").addEventListener("click", makePhoto);

function makePhoto(e) {
  var canvas = document.getElementById('canvas');
  var video = document.getElementById('videoElement');
  var get_img = document.getElementById("video_container").querySelectorAll(".img_style");
  var get_img_lenght = document.getElementById("video_container").querySelectorAll(".img_style").length;
  var parentPos = document.getElementById('videoElement').getBoundingClientRect();
  var i = 0;

  document.getElementById('videoElement').style.display = 'none';
  document.getElementById('savePhoto').style.display = 'block';
  document.getElementById('increase').style.display = 'none';
  document.getElementById('decrease').style.display = 'none';
  document.getElementById('canvas').style.display = 'block';
  document.getElementById('makePhoto').style.display = 'none';
  document.getElementById('remove').style.display = 'none';
  document.getElementById('uploadPhoto').style.display = 'none';
  document.getElementById('downloadPhoto').style.display = 'block';


  if (video.nodeName == 'IMG')
  {
    width = video.width;
    height = video.height;
  } else {
    width = video.videoWidth;
    height = video.videoHeight;
  }
  canvas.height = height;
  canvas.width = width;

  context.drawImage(video, 0, 0, width, height);
  while (get_img_lenght > i)
  {
    width = get_img[i].width;
    height = get_img[i].height;

    var  childrenPos = get_img[i].getBoundingClientRect(),
        relativePos = {};

    relativePos.top = childrenPos.top - parentPos.top,
    relativePos.left = childrenPos.left - parentPos.left;
    context.drawImage(get_img[i], relativePos.left, relativePos.top, width, height);
    i++;
  }
  //video.pause();
  video.src = "";
  localstream.getTracks()[0].stop();
  removeElementsByClass('remove');
}

function removeElementsByClass(className)
{
  var elements = document.getElementsByClassName(className);
  while(elements.length > 0){
    elements[0].parentNode.removeChild(elements[0]);
  }
}

images.onclick = function(event) {
  var target = event.target;
  if (validations(target) == 1)
  {
    var copy = target.cloneNode(true);

    document.getElementById('makePhoto').style.display = 'block';
    copy.classList.add("remove");
    set_position();
    document.getElementById("video_container").appendChild(copy);
    move_image(copy);
    movu_touch(copy);
  }

  function set_position()
  {
    copy.style.position = 'absolute';
    copy.style.top = '50px';
    copy.style.left = '50px';
  }

  function validations(target)
  {
    var tmp = document.getElementById('videoElement');
    if (target.className == 'img_style' && target.tagName == 'IMG')
    {
        return (1);
    }
    return (0);
  }
}

function move_image(image)
{
  var mousePosition;
  var offset = [0,0];
  var isDown = false;


  image.addEventListener('mousedown', function(e) {
      isDown = true;
      offset = [
          image.offsetLeft - e.clientX,
          image.offsetTop - e.clientY
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
          image.style.left = (mousePosition.x + offset[0]) + 'px';
          image.style.top  = (mousePosition.y + offset[1]) + 'px';
      }
  }, true);
}

function movu_touch(box2) {
 var boxleft, // left position of moving box
 startx, // starting x coordinate of touch point
 dist = 0, // distance traveled by touch point
 touchobj = null // Touch object holder

 box2.addEventListener('touchstart', function(e){
  touchobj = e.changedTouches[0] // reference first touch point
  boxleft = parseInt(box2.style.left) // get left position of box
  startx = parseInt(touchobj.clientX) // get x coord of touch point
  e.preventDefault() // prevent default click behavior
 }, false)

 box2.addEventListener('touchmove', function(e){
  touchobj = e.changedTouches[0] // reference first touch point for this event

  var dist = parseInt(touchobj.clientX) - startx // calculate dist traveled by touch point
 // move box according to starting pos plus dist
 // with lower limit 0 and upper limit 380 so it doesn't move outside track:
  box2.style.left = ( (boxleft + dist > 380)? 380 : (boxleft + dist < 0)? 0 : boxleft + dist ) + 'px'
  e.preventDefault()
 }, false)
};


document.getElementById("remove").addEventListener("click", remove_image);

function remove_image(){
  var elements = document.getElementsByClassName('remove');
  var get_img_lenght = document.getElementById("video_container").querySelectorAll(".img_style").length;
  if (get_img_lenght > 0)
    elements[get_img_lenght - 1].parentNode.removeChild(elements[get_img_lenght - 1]);
  if (elements.length == 0)
    document.getElementById('makePhoto').style.display = 'none';
}


document.getElementById("increase").addEventListener("click", increase_image);

function increase_image(){
  var elements = document.getElementsByClassName('remove');
  var get_img_lenght = document.getElementById("video_container").querySelectorAll(".img_style").length;
  var target;
  var w;

  if (get_img_lenght > 0)
  {
    target = elements[get_img_lenght - 1];
    w = target.offsetWidth;
    if (w < 320)
        w = w + 5;
    target.style.height = w + "px";
    target.style.width = w + "px";
  }
}

document.getElementById("decrease").addEventListener("click", decrease_image);

function decrease_image(){
  var elements = document.getElementsByClassName('remove');
  var get_img_lenght = document.getElementById("video_container").querySelectorAll(".img_style").length;
  var target;
  var w;

  if (get_img_lenght > 0)
  {
    target = elements[get_img_lenght - 1];

    w = target.width;
    if (w > 40)
        w = w - 5;
    target.style.height = w + "px";
    target.style.width = w + "px";
  }
}

document.getElementById("downloadPhoto").addEventListener("click", download_photo);

function download_photo() {
  this.href = document.getElementById('canvas').toDataURL();
  this.download = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
}

document.getElementById("savePhoto").addEventListener("click", save_photo);

function save_photo() {
  var c = document.getElementById("canvas");
  var img = c.toDataURL("image/png");
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../config/save.php", true);
  var data = "img=" + img;
  xhr.onreadystatechange = function () {
      if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        cameraOn();
      };
  };
  xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded');
  xhr.send(data);
}

document.getElementById('uploadPhoto').addEventListener('change', upload_img, false);

function upload_img() {

  var file = document.querySelector('input[type=file]').files[0];
  var parentDiv = document.getElementById('video_container');
  var newImg = document.createElement('img');
  var reader = new FileReader();

  if (file) {
    reader.readAsDataURL(file);
  }
  reader.onloadend = function () {

      var videoTag = document.getElementById('videoElement');
      parentDiv.removeChild(videoTag);
      newImg.setAttribute('src', reader.result);
      newImg.setAttribute('id', 'videoElement');
      newImg.className = 'upload_img';
    parentDiv.appendChild(newImg);
  }
}
