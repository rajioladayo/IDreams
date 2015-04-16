<?php
	mysql_select_db($database_conn, $conn);
	$category_query = "select c.*, c2.name as parent_name from categories as c left join categories as c2 on c.parent = c2.code
where c.status and if(isnull(c.parent), true, c2.status) 
order by if(isnull(c.parent), 1, 2)";
	
	$category_query_result = mysql_query($category_query,$conn);
	
	if(mysql_num_rows($category_query_result)){
		
		$menuArray = array();
	
		while ($row = mysql_fetch_assoc($category_query_result)) {
			if(!$row['parent']){
				$menuArray["{$row['code']}=={$row['name']}"] = array();
			} else {
				$menuArray["{$row['parent']}=={$row['parent_name']}"][$row['code']] = $row['name'];
			}
		}
		
		echo "<ul class='sub-menu $code'>";
		foreach($menuArray as $code => $name){
			echo getMenuItemHtml($code, $name);
		}
		echo "</li>";
	}
	
	function getMenuItemHtml($code, $name){
		if(is_array($name)){
			$code = explode('==', $code);
			$html = "<li class='menu-main-item {$code[0]}'><a href='category.php?c={$code[0]}'>{$code[1]}</a><ul class='sub-menu $code'>";
			foreach($name as $k => $v){
				$html .= getMenuItemHtml($k, $v);
			}
			$html .= "</ul></li>";
			
		} else {
			$html = "<li class='menu-item $code'><a href='category.php?c=$code'>$name</a></li>";
		}
		
		return $html;
	}
	
	
?>							
					