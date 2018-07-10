/*
** Add listener to load all comments from database after page load
*/
window.addEventListener('load', submitComment, false);

/*
** Add likes
*/
document.addEventListener('DOMContentLoaded', () => {
	const like_unlike = document.querySelector('.fa-heart');

	like_unlike.addEventListener('click', function(ev) {
		like_unlike_photo();
		ev.preventDefault();
	}, false);

	// unlike.addEventListener('click', function(ev) {
	// 	unlike_photo();
	// 	ev.preventDefault();
	// }, false);

	function like_unlike_photo() {
		const xhr = new XMLHttpRequest();
		const url = "../inc/comments_likes/likes.php";
		const img_id = like_unlike.getAttribute('data-id');
		const request = "like=1&img_id="+img_id;
		if (like_unlike.className === 'liked far fa-heart') {
			console.log("check 2");
			const request = "unlike=1&img_id="+img_id;
		} else {
			console.log(img_id);
			const request = "like=1&img_id="+img_id;
			console.log(request);
		}
		xhr.open("POST", url, true);
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				const likes_amount = xhr.responseText;
				document.querySelector(".likes_count").innerHTML = likes_amount + " likes";
				if (like_unlike.className === 'liked far fa-heart') {
					like_unlike.classList.remove('liked');
					like_unlike.classList.add('unliked');
				} else {
					like_unlike.classList.remove('unliked');
					like_unlike.classList.add('liked');
				}
			}
		}
		console.log(request);
		xhr.send(request);
	}

	// function like_photo() {
	// 	var request = new XMLHttpRequest();
	// 	var url = "../inc/comments_likes/likes.php";
	// 	var img_id = document.querySelector("data-id").value;
	// 	var vars = "liked=1&img_id="+img_id;
	// 	request.open("POST", url, true);
	// 	request.onreadystatechange= function() {
	// 		if (request.readyState == 4 && request.status == 200) {
	// 			var return_data = request.responseText;
	// 			document.querySelector(".likes_count").innerHTML = return_data + " likes";
	// 			like.addClass('hide');
	// 			unlike.removeClass('hide');
	// 		}
	// 	}
	// 	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	// 	request.send(vars);
	// }
	// function unlike_photo() {
	// 	var request = new XMLHttpRequest();
	// 	var url = "../inc/comments_likes/likes.php";
	// 	var img_id = document.querySelector("data-id").value;
	// 	var vars = "unliked=1&img_id="+img_id;
	// 	request.open("POST", url, true);
	// 	request.onreadystatechange= function() {
	// 		if (request.readyState == 4 && request.status == 200) {
	// 			var return_data = request.responseText;
	// 			document.querySelector(".likes_count").innerHTML = return_data + " likes";
	// 			like.removeClass('hide');
	// 			unlike.addClass('hide');
	// 		}
	// 	}
	// 	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	// 	request.send(vars);
	// }
});

/*
** Add coment to database and if success add on page without reload
*/
function submitComment() {
	var request = new XMLHttpRequest();
	var url= "../inc/comments_likes/comments.php";
	var username= document.getElementById("name_entered").value;
	var usercomment= document.getElementById("comment_entered").value;
	var vars= "name="+username+"&comment="+usercomment;
	request.open("POST", url, true);
	request.onreadystatechange= function() {
		if (request.readyState == 4 && request.status == 200) {
			var return_data = request.responseText;
			document.getElementById("showcomments").innerHTML = return_data;
		}
	}
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	request.send(vars);
}
