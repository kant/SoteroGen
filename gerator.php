<?php
header ('Content-type: text/html; charset=UTF-8');

$dir = "posts";
$dirMainSite = "site";
$nameYourBlog = "Luis Araujo";

createInitialDirectories();
copyAllImagens();

//call getfile of directory
getAllFileInDir($dir);

//read all file in folder
function getAllFileInDir($dir){
	$arrFiles = array();
	//if director exists		
	if (file_exists($dir)){
    //open directory		
    if ($handle = opendir($dir)) {
    	  //while has file
        while (false !== ($file = readdir($handle))) {
        	   //exception
            if ($file != "." && $file != "..") {
				    //explode for know extension of file                
                $nameFile = explode(".",$file);
                //encode content
                $file = utf8_encode($file);
					 //if file has extension and is txt file                
                if((count($nameFile) > 1) && ($nameFile[1] == "txt" )){                		
							      
                		$files[ filemtime($dir."/".$file) ] = $file;
                	  
                	  //insert file in arrFile and replace "../" in dir for /filename
                    //array_push($arrFiles,  str_replace("../", "", $dir)."/".$file);
                }
            }
        	}
			//close dir
      	closedir($handle);
   	 }
   	 
   	 //sort in growing order
    	 krsort($files);
    	     	 
    	 foreach($files as $file) {
        $lastModified = date('F d Y, H:i:s', filemtime($dir."/".$file)  );
        array_push($arrFiles,  str_replace("../", "", $dir)."/".$file);
       //echo "<tr><td><input type=\"checkbox\" name=\"box[]\"></td><td><a href=\"$file\" target=\"_blank\">$file</a></td><td>$lastModified</td></tr>";
       
    }	 
	}
	
	
	
	//call open files
	openAllFiles($arrFiles);
	
	
}


//open all files searched
function openAllFiles($arrFiles){
	
	//create object of posts
	$posts = (object) array('title' => array(), 'tags' => array(), 'date' => array(), 'abstract' => array(), 'content' => array());

	//turn all files 
	for($i = 0; $i < count($arrFiles); $i++){
   	//open file
		$myfile = fopen($arrFiles[$i], "r") or die("Unable to open file!");
		
		//this is string about content post
	   $joincontent = "";
	   //flag for signing if is in season of post
	   $contenttrue = false;
	   
	   //turn file to end
		while(!feof($myfile)) {
			//get line
			$linha = fgets($myfile);
			//explode for separate tag of content tag
			$taglinha = explode(":",$linha);
			
			//if has content
		   if(count($taglinha ) > 1){
		   	
		   	//tag is equal title
				if($taglinha[0] == "title" ){
					   array_push($posts->title, utf8_encode($taglinha[1]) );
				//tag is equal tag
				}else if($taglinha[0] == "tag" ){
					   array_push($posts->tags, utf8_encode($taglinha[1]) );   
				//tag is equal date			
				}else if($taglinha[0] == "date" ){
					   array_push($posts->date, utf8_encode($taglinha[1]) );
					   //tag is equal date			
				}else if($taglinha[0] == "abstract" ){
					   array_push($posts->abstract, utf8_encode($taglinha[1]) );
				//tag is equal content	     
				}else if( ($taglinha[0] == "content") && (!$contenttrue) ){
   				  $contenttrue = true;				    
				     $joincontent = utf8_encode($taglinha[1]);					
				}else if($contenttrue){
					  $joincontent.= utf8_encode($taglinha[1]);		
				}
			} 
  			
		}
		
		//insert content in posts
		array_push($posts->content, $joincontent);			
		
		//create post of last insert in post
		
		 
		createPostFiles( (object) array('title' => $posts->title[count($posts->title) - 1], 
		 'tags' => $posts->tags[count($posts->tags) - 1], 
		 'date' => $posts->date[count($posts->date) - 1],
		  'abstract' => $posts->abstract[count($posts->abstract) - 1],
		 'content' => $posts->content[count($posts->content) - 1])
		 );
	
		//close file			
		fclose($myfile);

	}
	
	createHomePage($posts);
	createPageTags(getAllTags());
	createPageAbout();
	
	
}


function createInitialDirectories(){
	global $dirMainSite; 
	
	if (!file_exists($dirMainSite)) {
		//create all directory
		mkdir ($dirMainSite, 0700);
    	mkdir ($dirMainSite."/posts/", 0700);
    	mkdir ($dirMainSite."/images/", 0700);
    	mkdir ($dirMainSite."/style/", 0700);
    	mkdir ($dirMainSite."/about/", 0700);    	
    	mkdir ($dirMainSite."/posts/style/", 0700);	
	}	
}

function createHomePage($posts){
	global $dirMainSite; 
	
		//create home file
		$filename = $dirMainSite."/index.html";	
		$myfile = fopen($filename, "w");
		fwrite($myfile, createContentHome($posts) );
		fclose($myfile);  
}

function copyAllImagens(){
		global $dirMainSite;  
	
		
		//copy style files    	
    	copy('style/style_home.css', $dirMainSite."/style/style_home.css");
		copy('style/style_pages.css', $dirMainSite."/posts/style/style_pages.css");
		
		
		//copy all imagens		
		$arrFiles = array();
		//if director exists		
		if (file_exists("images")){
    	//open directory		
    	if ($handle = opendir("images")) {
    	echo "ok";
    	  //while has file
        while (false !== ($file = readdir($handle))) {
        	   //exception
            if ($file != "." && $file != "..") {
				    //explode for know extension of file                
                $nameFile = explode(".",$file);
                //encode content
                $file = utf8_encode($file);
					 //if file has extension and is jpg, png, gif file                
                if((count($nameFile) > 1) && ( ($nameFile[1] == "jpg" ) || ($nameFile[1] == "png" ) || ($nameFile[1] == "gif" )) ){
      					copy("images/".$file, $dirMainSite."/images/".$file);              
                }
            	}
        		}
				//close dir
      		closedir($handle);
   		 }
		}
}

//create post
function createPostFiles($post){
	
	global $dirMainSite;  
	

	$url = getURLReplaced($post->title, $post->tags);
	$newtag =  getStringReplaced($post->tags);
	
	
	//verify and create (if need) directory of tag 
	if (!file_exists( $dirMainSite."/posts/".$newtag )) {
		mkdir ($dirMainSite."/posts/".$newtag."/", 0700);
	}	
	
	//create a file name
	$filename = $dirMainSite."/posts/".$url.".html";
	//create file	
			
	$myfile = fopen($filename, "w");
	//write in file
	fwrite($myfile, createContentPostHTML($post) );
	//close file
	fclose($myfile);

}


function getURLReplaced($title, $tag){
	//get title post
	$newtitle = str_replace(" ", "_",$title);
	$newtitle = str_replace(PHP_EOL, "", $newtitle);
	//get tag post
	$newtag = str_replace(" ", "_",$tag);
	$newtag = str_replace(PHP_EOL, "", $newtag);
	
	return $newtag."/".$newtitle;
}

function getStringReplaced($str){
	
	$str = str_replace(" ", "",$str);
	$str = str_replace(PHP_EOL, "", $str);
	return $str;
}




function createContentPostHTML($post) {
	global $nameYourBlog;
$cont = '<html>
<head>
    <meta charset="utf-8">
    <title>'.$nameYourBlog.' | '.$post->title.'</title>
    <link href="../style/style_pages.css" rel="stylesheet">
	
	<meta property="og:locale" content="pt_BR">
	<meta property="og:url" content="http://luisaraujo.github.io/blog/post/js/instalando_jsdoc.html">
	<meta property="og:title" content="'.$post->title.'">
	<meta property="og:site_name" content="'.$nameYourBlog.'">
	<!-- need set abstract -->
	<meta property="og:description" content="Aprenda a instalar e usar a API de documentação JsDoc para o seu projeto em javascript.">
	
	<meta property="og:image" content="https://luisaraujo.github.io/blog/img/capa.jpg">
	<meta property="og:image:type" content="image/jpeg">
	<meta property="og:image:width" content="800"> 
	<meta property="og:image:height" content="600"> 
	
</head>

<body>
<header>
<div class="delimiter">
   <a href="../../index.html"> Luis Araujo </div></a>
</div>

<div class="bt"><a href="../../about/index.html">sobre</div>

</header>
<article>

<div class="post">
	<div class="title">
	<a href="#">'.$post->title.'</a>
	</div>
	
	<div class="meta-data">
	   Postado em:  '.$post->date.'| Tags: <a class="link" href="index.html" >'.$post->tags.'</a>
	</div>
	
	<div class="abstract">
		'.$post->content.'
	</div>
</div>

</article>

<footer>
	<div class="info">
		Developmented with SoteroGen by Luis Araujo - 2017
		<div class="icons">
		<a href="https://www.facebook.com/canalLuis4raujo/?fref=ts" target="blank" class="icon-litte"><img src="../../images/fb-icon.png"></a>
		<a href="https://www.youtube.com/user/Luis4raujo"  target="blank"  class="icon-litte"><img src="../../images/youtube-icon.png"></a>
		<a href="https://github.com/LuisAraujo"  target="blank"  class="icon-litte"><img src="../../images/git-icon.png"></a>
		</div>
	</div>
</footer>

</body>
</html>';

return $cont;

}


function createContentHome($posts){
	global $nameYourBlog;
	
	
	$cont='<html>
	   <head>
		<meta charset="utf-8">
		<title>'.$nameYourBlog.' | Blog</title>
		<link href="style/style_home.css" rel="stylesheet">
		
		<meta property="og:locale" content="pt_BR">
		<meta property="og:url" content="http://luisaraujo.github.io/blog/post/js/instalando_jsdoc.html">
		<meta property="og:title" content="'.$nameYourBlog.' | Blog">
		<meta property="og:site_name" content="'.$nameYourBlog.'">
		<meta property="og:description" content="Seu blog sobre programação! Aprenda sobre web e games. Tudo sobre APIs, frameworks, padrões e programação no mesmo lugar!">
		
		<meta property="og:image" content="https://luisaraujo.github.io/blog/img/capa.jpg">
		<meta property="og:image:type" content="image/jpeg">
		<meta property="og:image:width" content="800"> 
		<meta property="og:image:height" content="600">
		
	</head>

<body>
<header>
<div class="delimiter">
   <a href="index.html"> Luis Araujo </a>
</div> 
</div>
<div class="bt"><a href="about/index.html">sobre <a/> </div>

</header>
<article>'; 

$limit = (count($posts->title) >  5) ? 5 : count($posts->title);
echo $limit;

for($i = 0; $i < $limit ;  $i++){
$cont.='<div class="post">
	<div class="title">
	<a href="posts/'.getURLReplaced($posts->title[$i], $posts->tags[$i]).'.html">'.$posts->title[$i].'</a>
	</div>
	<div class="meta-data">
	   Postado em:  '.$posts->date[$i].'
	</div>
	<div class="abstract">
		'.$posts->abstract[$i].'
	</div>
	<span ><a class="tags" href="posts/'.$posts->tags[$i].'">'.$posts->tags[$i].' </a>
</div>
';
}

$cont.='</article><footer>
	<div class="info">
		Developmented with SoteroGen by Luis Araujo - 2017
		<div class="icons">
		<a href="https://www.facebook.com/canalLuis4raujo/?fref=ts" target="blank" class="icon-litte"><img src="images/fb-icon.png"></a>
		<a href="https://www.youtube.com/user/Luis4raujo"  target="blank"  class="icon-litte"><img src="images/youtube-icon.png"></a>
		<a href="https://github.com/LuisAraujo"  target="blank"  class="icon-litte"><img src="images/git-icon.png"></a>
		</div>
		
	</div>
</footer>

</body>
</html>';

	return $cont;


}


function getAllTags(){

global $dirMainSite;
$arrayTags = array();


$arrFiles = array();
	//if director exists		
	if (file_exists($dirMainSite.'/posts')){
    //open directory		
    if ($handle = opendir($dirMainSite.'/posts')) {
    	  //while has file
        while (false !== ($file = readdir($handle))) {
        	       //explode for know extension of file                
                $nameFile = explode(".",$file);
                //encode content
                $file = utf8_encode($file);
					 //only folder             
                if((count($nameFile) == 1) && ($nameFile[0] != "style" )){
                	 //save name folders in array      
				   	 array_push($arrayTags, $file);
            	}
        	}
			//close dir
      	closedir($handle);
   	 }

	}
	
	return $arrayTags;
}


function createContentPageTags($tag){
global $nameYourBlog;
global $dirMainSite;


$cont = '<html>
<head>
    <meta charset="utf-8">
    <title>'.$nameYourBlog.' | Blog</title>
    <link href="../style/style_pages.css" rel="stylesheet">
</head>

<body>
<header>
<div class="delimiter">
   <a href="../../index.html"> '.$nameYourBlog.' </div></a>
</div>

<div class="bt"><a href="../../about/index.html">sobre</div>

</header>
<article>

<div class="post">
	<div class="title">
	<a href="#">'.ucfirst($tag).'</a>
	</div>
	
	<div class="meta-data">
	   Todos os posts sobre '.$tag.'
	</div>
	
	<div class="abstract">';
	   
	   $dir = $dirMainSite."/posts/".$tag;
	   
	   if ($handle = opendir($dir)) {
    	  //while has file
        while (false !== ($file = readdir($handle))) {
        	   //exception
            if ($file != "." && $file != "..") {
				    //explode for know extension of file                
                $nameFile = explode(".",$file);
                //encode content
                $file = utf8_encode($file);
					 //if file has extension and is txt file                
                if((count($nameFile) > 1) && ($nameFile[1] == "html" ) && ($nameFile[0] != "index" )){                		
							$cont.='<ul>
								<li class="listpost"> <a class="link" href="'.$file.'">'.ucfirst( explode(".", str_replace("_", " ",$file))[0] ).'</a></li>
							</ul>'
							               		;	
                }
            }
        	}
			//close dir
      	closedir($handle);
   	 }
	
	
	
	
	

$cont.='
	</div>
</div>

</article>

<footer>
	<div class="info">
		Developmented with SoteroGen by Luis Araujo - 2017
		<div class="icons">
		<a href="https://www.facebook.com/canalLuis4raujo/?fref=ts" target="blank" class="icon-litte"><img src="../../images/fb-icon.png"></a>
		<a href="https://www.youtube.com/user/Luis4raujo"  target="blank"  class="icon-litte"><img src="../../images/youtube-icon.png"></a>
		<a href="https://github.com/LuisAraujo"  target="blank"  class="icon-litte"><img src="../../images/git-icon.png"></a>
		</div>
	</div>
</footer>

</body>
</html>';


return $cont;

}


function createPageTags($tags){
		global $dirMainSite; 
		
		
		for($i=0; $i < count($tags); $i++){
			//create home file
			$filename = $dirMainSite."/posts/".$tags[$i]."/index.html";	
			$myfile = fopen($filename, "w");
			fwrite($myfile, createContentPageTags($tags[$i]));
			fclose($myfile);  
		}
}

function createPageAbout(){	
	global $dirMainSite; 

	//open file
	$myfile = fopen('pagesfixed/about.txt', "r") or die("Unable to open file!");
	$content = "";	   
	//turn file to end
	while(!feof($myfile)) {
			//get line
			$linha = fgets($myfile);
			$content .= utf8_encode($linha);  			
	}
		
	fclose($myfile);
		
		
	$filename = $dirMainSite."/about/index.html";	
	$myfile = fopen($filename, "w");
	fwrite($myfile, createContentPageAbout($content));
	fclose($myfile);  
	
}	



function createContentPageAbout($content){
global $nameYourBlog;


$cont='<html>
<head>
    <meta charset="utf-8">
    <title>'.$nameYourBlog.' | Blog</title>
    <link href="../posts/style/style_pages.css" rel="stylesheet">
</head>

<body>
<header>
<div class="delimiter">
   <a href="../index.html"> '.$nameYourBlog.' </div></a>
</div>

<div class="bt"><a href="../../about/index.html">sobre</div>

</header>
<article>

<div class="post">
	<div class="title">
	<a href="#">Sobre</a>
	</div>
	
	<img class="profile" src="../images/perfil.png">
	
	<div class="abstract">'. $content. '</article>

<footer>
	<div class="info">
		Desenvolvido por Luis Araujo - 2017
		<div class="icons">
		<a href="https://www.facebook.com/canalLuis4raujo/?fref=ts" target="blank" class="icon-litte"><img src="../images/fb-icon.png"></a>
		<a href="https://www.youtube.com/user/Luis4raujo"  target="blank"  class="icon-litte"><img src="../images/youtube-icon.png"></a>
		<a href="https://github.com/LuisAraujo"  target="blank"  class="icon-litte"><img src="../images/git-icon.png"></a>
		</div>
	</div>
</footer>

</body>
</html>';


return $cont;


}


?>

