document.addEventListener('DOMContentLoaded', () => {
	const like = document.querySelector('.like');
	const unlike = document.querySelector('.unlike');

	submitComment();

	function submitComment() {
		var request = new XMLHttpRequest();
		var url= "inc/comments_likes/comments.php";
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

	like.addEventListener('click', function(ev) {
		like_photo();
		ev.preventDefault();
	}, false);

	unlike.addEventListener('click', function(ev) {
		unlike_photo();
		ev.preventDefault();
	}, false);

	function like_photo() {
		var request = new XMLHttpRequest();
		var url = "inc/comments_likes/likes.php";
		var img_id = document.querySelector("data-id").value;
		var vars = "liked=1&img_id="+img_id;
		request.open("POST", url, true);
		request.onreadystatechange= function() {
			if (request.readyState == 4 && request.status == 200) {
				var return_data = request.responseText;
				document.querySelector(".likes_count").innerHTML = return_data + " likes";
				like.addClass('hide');
				unlike.removeClass('hide');
			}
		}
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.send(vars);
	}
	function unlike_photo() {
		var request = new XMLHttpRequest();
		var url = "inc/comments_likes/likes.php";
		var img_id = document.querySelector("data-id").value;
		var vars = "unliked=1&img_id="+img_id;
		request.open("POST", url, true);
		request.onreadystatechange= function() {
			if (request.readyState == 4 && request.status == 200) {
				var return_data = request.responseText;
				document.querySelector(".likes_count").innerHTML = return_data + " likes";
				like.removeClass('hide');
				unlike.addClass('hide');
			}
		}
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.send(vars);
	}


	// $(document).ready(function(){
	// 	// when the user clicks on like
	// 	$('.like').on('click', function(){
	// 		var postid = $(this).data('id');
	// 		    $post = $(this);

	// 		$.ajax({
	// 			url: 'index.php',
	// 			type: 'post',
	// 			data: {
	// 				'liked': 1,
	// 				'postid': postid
	// 			},
	// 			success: function(response){
	// 				$post.parent().find('span.likes_count').text(response + " likes");
	// 				$post.addClass('hide');
	// 				$post.siblings().removeClass('hide');
	// 			}
	// 		});
	// 	});

	// 	// when the user clicks on unlike
	// 	$('.unlike').on('click', function(){
	// 		var postid = $(this).data('id');
	// 	    $post = $(this);

	// 		$.ajax({
	// 			url: 'index.php',
	// 			type: 'post',
	// 			data: {
	// 				'unliked': 1,
	// 				'postid': postid
	// 			},
	// 			success: function(response){
	// 				$post.parent().find('span.likes_count').text(response + " likes");
	// 				$post.addClass('hide');
	// 				$post.siblings().removeClass('hide');
	// 			}
	// 		});
	// 	});
	// });

	// window.addEventListener('load', submitComment, false);
});
