<?php
echo "<div class='wrapper'>
	<ul class='right'>";
		$topmenu2 = $this->model_utama->view_where_ordering_limit('menu',array('position' => 'Top','aktif' => 'Ya'),'urutan','ASC',0,5);
			foreach ($topmenu2->result_array() as $row) {
			echo "<li><a href='$row[link]'>$row[nama_menu]</a></li>";
		}
	echo "</ul>
	<p>&copy; ".date('Y')." Copyright <b>Blackexpo Media</b>. All Rights reserved.<br/>Develop by &nbsp;<a href='https://401xd.com/' target='_blank'>MCP</a>. Powered by&nbsp;<a href='https://401xd.com/' target='_blank'>401XD Group</a>.</p>
</div>";