/*
** Add likes
*/
function like_unlike_photo(id) {
	const like_unlike = document.querySelector('#img' + id);
	const xhr = new XMLHttpRequest();
	const url = "../inc/php-ajax/likes.php";
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

// fix bug when click on show comments buttom activates submit comment function

// function submitComment(id) {
// 	console.log('check1');
// 	var input = document.getElementById("comment_entered" + id);
// 	var usercomment = input.value;
// 	var request = new XMLHttpRequest();
// 	var url = "../inc/comments_likes/comments.php";
// 	var vars = "comment="+usercomment+"&img_id="+id;

// 	// Execute a function when the user releases a key on the keyboard
// 	input.addEventListener("keyup", function(event) {
// 		// event.preventDefault();
// 		console.log('check2');
// 		// Number 13 is the "Enter" key on the keyboard
// 		if (event.keyCode === 13) {
// 			console.log('check3');
// 			request.open("POST", url, true);
// 			request.onreadystatechange = function() {
// 				if (request.readyState == 4 && request.status == 200) {
// 					console.log('check4');
// 					var return_data = request.responseText;
// 					var i = return_data.indexOf(" ");
// 					var comments_amount = return_data.substr(0, i);
// 					var comments_body = return_data.substr(i);
// 					document.getElementById("comment_count" + id).innerHTML = comments_amount + " comments";
// 					document.getElementById("showcomments" + id).innerHTML = comments_body;
// 					usercomment = "";
// 					console.log(comments_amount);
// 					console.log(comments_body);
// 					// console.log(request.responseText);
// 				}
// 			}
// 			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
// 			request.send(vars);
// 		}
// 	});
// }

function submitComment(id) {
	var request = new XMLHttpRequest();
	var url = "../inc/php-ajax/comments.php";
	var usercomment = document.getElementById("comment_entered" + id).value;
	var img_id = id;
	var vars= "comment="+usercomment+"&img_id="+img_id;
	request.open("POST", url, true);
	request.onreadystatechange = function() {
		if (request.readyState == 4 && request.status == 200) {
			var return_data = request.responseText;
			var i = return_data.indexOf(" ");
			var comments_amount = return_data.substr(0, i);
			var comments_body = return_data.substr(i);
			document.getElementById("comment_count" + id).innerHTML = comments_amount + " comments";
			document.getElementById("showcomments" + id).innerHTML = comments_body;
			document.getElementById("comment_entered" + id).value = "";

		}
	}
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send(vars);
}
