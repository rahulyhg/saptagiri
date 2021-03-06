<?php include("include_header.php");?>
<main class="cd-main-content">
		<div class="cd-scrolling-bg cd-color-2">
			<div class="cd-container">
<?php

include("connect.php");
require_once("common.php");

if(isset($_GET['authid'])){$authid = $_GET['authid'];}else{$authid = '';}
if(isset($_GET['author'])){$authorname = $_GET['author'];}else{$authorname = '';}

echo '<h1 class="clr1 gapBelowSmall">लेखक : ' . $authorname . '</h1>';

$authorname = entityReferenceReplace($authorname);

if(!(isValidAuthid($authid) && isValidAuthor($authorname)))
{
	echo '<span class="aFeature clr2">Invalid URL</span>';
	echo '</div> <!-- cd-container -->';
	echo '</div> <!-- cd-scrolling-bg -->';
	echo '</main> <!-- cd-main-content -->';
	include("include_footer.php");

    exit(1);
}

$query = 'select * from article where authid like \'%' . $authid . '%\'';

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
			$info = $info . ' <span style="font-size: 0.95em">' . toKannada(intval($row['year'])) . '</span>';
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
		echo '		<span class="aIssue clr5"><a href="toc.php?vol=' . $row['volume'] . '&amp;part=' . $row['part'] . '">वर्ष ' . toKannada(intval($row['volume'])) . ', अंक ' . toKannada($dpart) . ' <span class="font_resize">(' . $info . ')</span></a></span>';
		echo '	</div>';
		echo '	<span class="aTitle"><a target="_blank" href="../Volumes/pdf/' . $row['volume'] . '/' . $row['part'] . '/index.pdf#page=' . intval($row['page']) . '">' . $row['title'] . '</a></span>';
		echo '<br/><span class="downloadspan"><a target="_blank" href="downloadPdf.php?titleid='.$titleid.'">Download Pdf</a></span>';
		echo '</div>';
	}
}

if($result){$result->free();}
$db->close();

?>
			</div> <!-- cd-container -->
		</div> <!-- cd-scrolling-bg -->
	</main> <!-- cd-main-content -->
<?php include("include_footer.php");?>
