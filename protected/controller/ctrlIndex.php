<?php

class ctrlIndex extends ctrl {


	
public function __construct(){
	parent::__construct();

}
	
public function index() {

	$this->out('about.php');
	exit();
	}
	
// public function page($page) {
// 	$this->page = $page;
// 	if ($page == 'about') { $this->out('about.php'); exit();}
	
// 	if ((int)$page <= 0 || (int)$page > $this->pages){ $this->page($this->pages); exit;}
// 		$offset = ($page-1)*5;
// 		$res = $this->db->query("SELECT * FROM post ORDER BY create_time DESC LIMIT 5 OFFSET ?", array($offset), 'i');
// 		$this->posts = $this->db->getArray($res);
// 		$this->out('posts.php');

// 	}

// public function search($needle=NULL){
// 	if(isset($_POST['needle'])){
// 		$needle = $_POST['needle'];
// 	}
// 	if (isset($needle)){	
// 		$this->page = 'search';
// 		$res = $this->db->query("SELECT DISTINCT post.id, post.title, post.content, post.create_time, post.author_id	
// 										 FROM post 
// 										 LEFT JOIN tag_post
// 	                                     ON post.id = tag_post.post_id
// 										 LEFT JOIN tag 
// 										 ON tag_post.tag_id = tag.id 
// 										 WHERE UPPER(post.title) LIKE ?
// 										 OR UPPER(post.content) LIKE ?
// 										 OR UPPER(tag.name) LIKE ? ",
// 										array('%'.strtoupper($needle).'%', '%'.strtoupper($needle).'%', '%'.strtoupper($needle).'%'), 
// 										'sss');
//         $this->posts = $this->db->getArray($res);												
// 		$this->out('posts.php');
// 	}
// 	else $this->index();
// }

// public function tag($tagname){
// 	if (isset($tagname)){
// 		$this->page = 'search';
// 		$res = $this->db->query("SELECT DISTINCT post.id, post.title, post.content, post.create_time, post.author_id 
// 								 FROM post
// 								 WHERE post.id IN 
// 								 (
// 								 SELECT post_id 
// 								 FROM tag_post 
// 								 INNER JOIN tag
// 								 ON tag_post.tag_id = tag.id
// 								 WHERE tag.name = ? 
// 								 )",
// 								 array($tagname),
// 								 's');
// 		$this->posts = $this->db->getArray($res);
// 		$this->out('posts.php');
// 	}
// 	else
// 		$this->index();
// }
	
// public function getPosts(){
// 	return $this->posts;	
// }

// public function getTagCloud(){
// 	return $this->tags;
// }

// public function getLastComments(){
// 	return $this->lastComments;
// }

// public function getPage(){
// 	return $this->page;
// }

// public function getNumPages(){
// 	return $this->pages;
// }

// public function next(){
// 	return ($this->page+1);
// }

// public function previous(){
// 	return ($this->page-1);
// }
}



