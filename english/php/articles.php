<?php include("include_header.php");?>
<main class="cd-main-content">
		<div class="cd-scrolling-bg cd-color-2">
			<div class="cd-container">
				<h1 class="clr1">Articles</h1>
 				<div class="alphabet gapBelowSmall gapAboveSmall">
					<span class="letter"><a href="articles.php?letter=a">A</a></span>
					<span class="letter"><a href="articles.php?letter=b">B</a></span>
					<span class="letter"><a href="articles.php?letter=c">C</a></span>
					<span class="letter"><a href="articles.php?letter=d">D</a></span>
					<span class="letter"><a href="articles.php?letter=e">E</a></span>
					<span class="letter"><a href="articles.php?letter=f">F</a></span>
					<span class="letter"><a href="articles.php?letter=g">G</a></span>
					<span class="letter"><a href="articles.php?letter=h">H</a></span>
					<span class="letter"><a href="articles.php?letter=i">I</a></span>
					<span class="letter"><a href="articles.php?letter=j">J</a></span>
					<span class="letter"><a href="articles.php?letter=k">K</a></span>
					<span class="letter"><a href="articles.php?letter=l">L</a></span>
					<span class="letter"><a href="articles.php?letter=l">M</a></span>
					<span class="letter"><a href="articles.php?letter=n">N</a></span>
					<span class="letter"><a href="articles.php?letter=o">O</a></span>
					<span class="letter"><a href="articles.php?letter=p">P</a></span>
					<span class="letter"><a href="articles.php?letter=q">Q</a></span>
					<span class="letter"><a href="articles.php?letter=r">R</a></span>
					<span class="letter"><a href="articles.php?letter=s">S</a></span>
					<span class="letter"><a href="articles.php?letter=t">T</a></span>
					<span class="letter"><a href="articles.php?letter=u">U</a></span>
					<span class="letter"><a href="articles.php?letter=v">V</a></span>
					<span class="letter"><a href="articles.php?letter=w">W</a></span>
					<span class="letter"><a href="articles.php?letter=x">X</a></span>
					<span class="letter"><a href="articles.php?letter=y">Y</a></span>
					<span class="letter"><a href="articles.php?letter=z">Z</a></span>
					<span class="letter"><a href="articles.php?letter=other">#</a></span>
				</div>
<?php

include("connect.php");
require_once("common.php");

if(isset($_GET['letter']))
{
 	$letter=$_GET['letter'];
	
 	if(!(isValidLetter($letter)))
 	{
 		echo '<span class="aFeature clr2">Invalid URL</span>';
 		echo '</div> <!-- cd-container -->';
 		echo '</div> <!-- cd-scrolling-bg -->';
 		echo '</main> <!-- cd-main-content -->';
 		include("include_footer.php");

         exit(1);
 	}
	
 	($letter == '') ? $letter = 'A' : $letter = $letter;
 }
 else
 {
 	$letter = 'A';
 }
if($letter == 'other')
{
	$query = "SELECT * FROM article WHERE title REGEXP '^[A-Za-z]'";
}
else
{
	$query = "select * from article where title like '$letter%'  union select * from article where title like '\"$letter%' union select * from article where title like '\'$letter%' order by TRIM(BOTH '\'' FROM TRIM(BOTH '\"' FROM title))";
}

$result = $db->query($query); 
$num_rows = $result ? $result->num_rows : 0;

if($num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$query3 = 'select feat_name from feature where featid=\'' . $row['featid'] . '\'';
		$result3 = $db->query($query3); 
		$row3 = $result3->fetch_assoc();
		$titleid = $row['titleid'];
		$dpart = preg_replace("/^0/", "", $row['part']);
		$dpart = preg_replace("/\-0/", "-", $dpart);
		$info = '';
		if($row['month'] != '')
		{
			$info = $info . getMonth($row['month']);
		}
		if($row['year'] != '')
		{
			$info = $info . ' <span class="font_size">' . $row['year'] . '</span>';
		}
		if($row['maasa'] != '')
		{
			$info = $info . ', ' . $row['maasa'] . '&nbsp;Maasa';
		}
		if($row['samvatsara'] != '')
		{
			$info = $info . ', ' . $row['samvatsara'] . '&nbsp;Samvatsara';
		}
		$info = preg_replace("/^,/", "", $info);
		$info = preg_replace("/^ /", "", $info);
		
		$sumne = preg_split('/-/' , $row['page']);
		$row['page'] = $sumne[0];
		if($result3){$result3->free();}

		echo '<div class="article">';
		echo '	<div class="gapBelowSmall">';
		echo ($row3['feat_name'] != '') ? '		<span class="aFeature clr2"><a href="feat.php?feature=' . urlencode($row3['feat_name']) . '&amp;featid=' . $row['featid'] . '">' . $row3['feat_name'] . '</a></span> | ' : '';
		echo '		<span class="aIssue clr5"><a href="toc.php?vol=' . $row['volume'] . '&amp;part=' . $row['part'] . '">Volume ' . intval($row['volume']) . ', Part ' . $dpart . ' <span class="font_resize">(' . $info . ')</span></a></span>';
		echo '	</div>';
		//~ echo '	<span class="aTitle"><a target="_blank" href="bookReader.php?volume=' . $row['volume'] . '&amp;part=' . $row['part'] . '&amp;page=' . $row['page'] . '">' . $row['title'] . '</a></span>';
		//~ DJVU link
		echo '	<span class="aTitle"><a target="_blank" href="../Volumes/pdf/' . $row['volume'] . '/' . $row['part'] . '/index.pdf#page=' . intval($row['page']) . '">' . $row['title'] . '</a></span>';
		if($row['authid'] != 0) {

			echo '<br /><span class="aAuthor">&nbsp;&nbsp;&mdash;';
			$authids = preg_split('/;/',$row['authid']);
			$authornames = preg_split('/;/',$row['authorname']);
			$a=0;
			foreach ($authids as $aid) {

				echo '<a class="delim" href="auth.php?authid=' . $aid . '&amp;author=' . urlencode($authornames[$a]) . '">' . $authornames[$a] . '</a> ';
				$a++;
			}
			
			echo '	</span>';
		}
		echo '<br/><span class="downloadspan"><a target="_blank" href="downloadPdf.php?titleid='.$titleid.'">Download Pdf</a></span>';
		echo '</div>';
	}
}
else
{
	echo '<span class="sml">No Article found starting from letter  \'' . $letter . '\'';
}

if($result){$result->free();}
$db->close();

?>
			</div> <!-- cd-container -->
		</div> <!-- cd-scrolling-bg -->
	</main> <!-- cd-main-content -->
<?php include("include_footer.php");?>
