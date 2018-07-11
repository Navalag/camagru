/*
** Add listener to load all comments from database after page load
*/
window.addEventListener('load', submitComment, false);

/*
** Add likes
*/
function like_unlike_photo(id) {
	const like_unlike = document.querySelector('#img' + id);
	const xhr = new XMLHttpRequest();
	const url = "../inc/comments_likes/likes.php";
	const img_id = id;
	if (like_unlike.className === 'far fa-heart liked') {
		var request = "unlike=1&img_id="+img_id;
	} else {
		var request = "like=1&img_id="+img_id;
	}
	xhr.open("POST", url, true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			const likes_amount = xhr.responseText;
			document.querySelector("#likes_count" + id).innerHTML = likes_amount + " likes";
			if (like_unlike.className === 'far fa-heart liked') {
				like_unlike.classList.remove('liked');
				like_unlike.classList.add('unliked');
			} else {
				like_unlike.classList.remove('unliked');
				like_unlike.classList.add('liked');
			}
		}
	}
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(request);
}

/*
** Add coment to database and if success add on page without reload
*/
function submitComment() {
	var request = new XMLHttpRequest();
	var url = "../inc/comments_likes/comments.php";
	var username = document.getElementById("name_entered").value;
	var usercomment = document.getElementById("comment_entered").value;
	var vars= "name="+username+"&comment="+usercomment;
	request.open("POST", url, true);
	request.onreadystatechange = function() {
		if (request.readyState == 4 && request.status == 200) {
			var return_data = request.responseText;
			document.getElementById("showcomments").innerHTML = return_data;
		}
	}
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send(vars);
}
