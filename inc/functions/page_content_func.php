<?php 

function get_item_html($item) {
	$output = "<li><img src='" 
			. $item["src"] . "' alt='" 
			. $item["img_id"] . "' />" 
			. "<button>remove</button>"
			. "</li>";
	return $output;
}
function get_user_photo_array() {
	include("config/connect.php");

	try {
		$result = $conn->prepare(
			"SELECT img_id, src, user_id 
			FROM user_img"
		);
		$result->execute();
		$catalog = $result->setFetchMode(PDO::FETCH_ASSOC);
		$catalog = $result->fetchAll();
	} catch (PDOException $e) {
		echo "Unable to retrieved results";
		exit;
	}
	return $catalog;
}

?>
