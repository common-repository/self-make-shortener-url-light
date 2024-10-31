<?php
/*
Plugin Name: Self Make Shortener URL light
Plugin URI: https://you-1.tokyo/self-make-shortener-url-make-light/
Description: Generate abbreviated URL from CSV file.
Author: Kazuyoume
Version: 0.5
Author URI:https://you-1.tokyo
License:GPL2
*/
/*  Copyright 2019/03/01 kazuyoume (email:kazuyoume@you-1.tokyo)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	  published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
function kym_tan_DeleteTable($table_name) {
  global $wpdb;
  if ($wpdb->get_var("show tables like'" . $wpdb->prefix . $table_name . "'") == $wpdb->prefix . $table_name) {
      $wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix . $table_name);
	}
	wp_clear_scheduled_hook('cronjyobhook');
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
function kym_tan_MakeTable($table_name) {
		global $wpdb;
		if($table_name=="kym_tan_01"){
		$tname=$wpdb->prefix.$table_name;
    if ($wpdb->get_var("show tables like '" .$tname. "'") != $tname) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = "CREATE TABLE " . $wpdb->prefix . $table_name . "(
        id int not null auto_increment,
        name     varchar(200) NOT NULL,
        descr    text  not null,
        tag      int   not null,
        cate     int   not null,
        url      text  not null,
        tan      varchar(50) NOT NULL,
        rtype    int   not null,
				c0       int   not null,
				c1       int   not null,
				c2       int   not null,
				c3       int   not null,
				c4       int   not null,
				c5       int   not null,
				c6       int   not null,
				cA       int   not null,
				cdate    datetime not null,
				ldate    datetime not null,
				noindex  int   not null,
				nofollow int   not null,
				excol1 varchar(100) not null,
				excol2 varchar(100) not null,
				excol3 varchar(100) not null,
				excol4 varchar(100) not null,
				excol5 varchar(100) not null,
				exdat1 datetime not null,
				exdat2 datetime not null,
				exdat3 datetime not null,
				exdat4 datetime not null,
				exdat5 datetime not null,
				exint1 int      not null,
				exint2 int      not null,
				exint3 int      not null,
				exint4 int      not null,
				exint5 int      not null,
				extxt1 text     not null,
				extxt2 text     not null,
				extxt3 text     not null,
				extxt4 text     not null,
				extxt5 text     not null,
				primary key(id),
				UNIQUE KEY tan  (tan),
				UNIQUE KEY name (name)
        );";
        dbDelta($sql);
		}
	}

	if($table_name=="kym_tan_02"){
		$tname=$wpdb->prefix.$table_name;
    if ($wpdb->get_var("show tables like '" .$tname. "'") != $tname) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = "CREATE TABLE " . $wpdb->prefix . $table_name . "(
        id int not null auto_increment,
        tan                     varchar(50) NOT NULL,
        REMOTE_ADDR             varchar(200) NOT NULL,
        HTTP_ACCEPT_LANGUAGE    varchar(200) NOT NULL,
        HTTP_USER_AGENT         varchar(200) NOT NULL,
				ldate                   datetime not null,
				excol1 varchar(100) not null,
				excol2 varchar(100) not null,
				excol3 varchar(100) not null,
				excol4 varchar(100) not null,
				excol5 varchar(100) not null,
				exdat1 datetime not null,
				exdat2 datetime not null,
				exdat3 datetime not null,
				exdat4 datetime not null,
				exdat5 datetime not null,
				exint1 int      not null,
				exint2 int      not null,
				exint3 int      not null,
				exint4 int      not null,
				exint5 int      not null,
				extxt1 text     not null,
				extxt2 text     not null,
				extxt3 text     not null,
				extxt4 text     not null,
				extxt5 text     not null,
				primary key(id)
        );";
        dbDelta($sql);
		}
	}
		if ( !wp_next_scheduled("cronjyobhook") ) {
			wp_schedule_event( time(),'daily','cronjyobhook');
		}
}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
	function kym_tan_redirect(){
		global $wpdb;
		$tname01=$wpdb->prefix."kym_tan_01";
		$tname02=$wpdb->prefix."kym_tan_02";
		if (is_404()) {
      $rui=str_replace("/","",$_SERVER['REQUEST_URI']);
			$sqlt= "select * from  ".$tname01." where tan='%s'";
			$sql = $wpdb->prepare($sqlt,$rui);
			$results = $wpdb->get_results($sql,ARRAY_A);
			$ret=$results[0];
			     if($ret['noindex']<>1 and $ret['nofollow']<>1){ header("X-Robots-Tag:noindex,nofollow", true);}
			else if($ret['noindex']<>0 and $ret['nofollow']==0){ header("X-Robots-Tag:noindex", true);}
			else if($ret['noindex']==0 and $ret['nofollow']<>0){ header("X-Robots-Tag:nofollow", true);}
			if($ret['tan']==$rui){
					header('Location: ' .$ret['url'], true, $ret['rtype']);
					$sqlt= "update  ".$tname01." set c0=c0+1,cA=CA+1,ldate='".date('Y-m-d H:i:s', strtotime('+9hour'))."' where tan='%s'";
					$sql = $wpdb->prepare($sqlt,$rui);
					$results = $wpdb->get_results($sql,ARRAY_A);
					$sqlt= "insert into  ".$tname02." (tan,REMOTE_ADDR,HTTP_ACCEPT_LANGUAGE,HTTP_USER_AGENT,ldate)  values ('%s','%s','%s','%s','%s')";
					$sql = $wpdb->prepare($sqlt,$rui,$_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_ACCEPT_LANGUAGE'],$_SERVER['HTTP_USER_AGENT'],date('Y-m-d H:i:s', strtotime('+9hour')));
					$results = $wpdb->get_results($sql);
		 	   exit;
				} 
		}
		
	}
	//------------------------------------------------------------------------------
	//------------------------------------------------------------------------------
	function kym_tan_top_menu(){add_menu_page("S_URL_light", "S_URL_light", "edit_plugins",  __FILE__, "kym_tan_sub_menu1");	}
		function kym_tan_sub_menu1(){
		global $wpdb;
		$tname=$wpdb->prefix."kym_tan_01";
		//------------//
		echo "<h1>Self Make Shortener URL light</h1>";
		echo '<img src="' . plugin_dir_url( __FILE__ ) . 'images/img.png" alt="Self Make Shortener URL Make light" />';
		//------------//
    echo "<h1>Only One Step</h1>";
		echo "Make CSV File and Upload!!";
		echo "<a href=\"https://you-1.tokyo/self-make-shortener-url-make-light/\" target=\"_blank\">HowToMake</a><br>";
		$dog = 'Upload';
		$msg = <<< EOM
		Select CSV File and Push【{$dog}】Button<br>
		<form method="post" action="" enctype="multipart/form-data">
		<input type="file" name="csv" /><br> 
		<input type="submit" value="{$dog}">
		</form>
EOM;
		echo $msg;
		//------------//
	   if (is_uploaded_file($_FILES["csv"]["tmp_name"])) {
		 $file_tmp_name = $_FILES["csv"]["tmp_name"];
		 $file_name = $_FILES["csv"]["name"];
		 echo "Upload Successfull<br>";	
		//------------//
		if (pathinfo($file_name, PATHINFO_EXTENSION) != 'csv') {
			echo "Supported format is CSV file only<br>";
			echo "The extension of the uploaded file is set to<<".pathinfo($file_name, PATHINFO_EXTENSION).">><br>";
			echo "Check the file and re-upload.<br>";
			
		} else {
					//------------//
			if (move_uploaded_file($file_tmp_name, "./" . $file_name)) {
						//------------//
				chmod("./" . $file_name, 0644);
				$msg = $file_name . "is uploaded";
				$file = './'.$file_name;
				if( ($fp = fopen($file,"r"))=== false ){
					echo  "Failed!!";
				}
			//------------//
				echo "<table border=\"1\">";
				while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
					  if(!array_diff($data, array(''))){
						continue;
						}
						echo "<tr>";
						for($i = 0; $i < count($data); ++$i ){
							$tpri = mb_convert_encoding($data[$i], 'UTF-8', 'SJIS');
							$tpri = $tpri === "" ?  "&nbsp;" : $tpri;
							echo("<td>".$tpri."</td>");
						}
						echo "</tr>";
						$arr[] = $data;
				}
				echo "</table>";
				fclose($fp);
						//------------//
				unlink('./'.$file_name);
				foreach($arr as $key => $val){
						if($key==0){continue;} //SkipFirstRow
						$sqlt= "insert into ".$tname." (name,descr,url,tan,rtype,cdate,noindex,nofollow) values ('%s','%s','%s','%s','%s','%s','%d','%d') ON DUPLICATE KEY UPDATE name='%s', descr='%s',url='%s',tan='%s',rtype='%s',noindex='%d',nofollow='%d'";
						$sql = $wpdb->prepare($sqlt,mb_convert_encoding($val[0],'UTF-8','SJIS'),
																				mb_convert_encoding($val[1],'UTF-8','SJIS'),
																				mb_convert_encoding($val[2],'UTF-8','SJIS'),
																				mb_convert_encoding($val[3],'UTF-8','SJIS'),
																				mb_convert_encoding($val[4],'UTF-8','SJIS'),
																				date('Y-m-d H:i:s', strtotime('+9hour')),
																				mb_convert_encoding($val[5],'UTF-8','SJIS'),
																				mb_convert_encoding($val[6],'UTF-8','SJIS'),
																				mb_convert_encoding($val[0],'UTF-8','SJIS'),
																				mb_convert_encoding($val[1],'UTF-8','SJIS'),
																				mb_convert_encoding($val[2],'UTF-8','SJIS'),
																				mb_convert_encoding($val[3],'UTF-8','SJIS'),
																				mb_convert_encoding($val[4],'UTF-8','SJIS'),
																				mb_convert_encoding($val[5],'UTF-8','SJIS'),
																				mb_convert_encoding($val[6],'UTF-8','SJIS'));
           	$results= $wpdb->get_results($sql);
				}


			} else {
				echo  "Can not uploaded file";
			}
		}
	} else {
		echo   "";
	}
		
		//----- Current setting value-------//
		echo "<hr>";
		echo "<h1>Current setting value</h1>";
 $sqlt= "
SELECT name,descr,tag,cate,url,tan,rtype,noindex,nofollow,c0,c1,c2,c3,c4,c5,c6,cA,
date_format(cdate, '%m/%d') as cdate,
date_format(ldate, '%m/%d') as ldate,
DATEDIFF(now(),cdate) as ddiff
FROM ".$tname." order by c0 desc,cA desc limit 30
 ";
 $results = $wpdb->get_results($sqlt);
 echo "<hr>";
 echo "※The meaning of the numbers is the date of the past (ex:1 -> yesterday)<br>";
 echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
 echo "<tr bgcolor=\"#ffeebb\"><th>Name</th><th>Description</th><th>Sname</th><th>Rtype</th><th>noindex</th><th>nofollow</th><th>Total</th><th>Today</th>";
 echo "<th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th>";
 echo "<th>RegDay</th><th>LastClick</th><th>PassDay</th><th>URL</th></font></tr>";
 foreach( $results as $key => $val){
	echo "<tr>";
	echo "<td>".$val->name."</td>";
	echo "<td>".$val->descr."</td>";
	echo "<td><a href=\"".esc_url(get_home_url())."\\".$val->tan."\" target=\"_blank\">".$val->tan."</td>";
	echo "<td>".$val->rtype."</td>";
	echo "<td>";
	if($val->noindex<>0){echo "<font color=\"red\"><strong>ON</strong></font>";} elseif($val->noindex==0){echo "<font color=\"blue\"><strong>OFF</strong></font>";}
	echo "</td>";
	echo "<td>";
	if($val->nofollow<>0){echo "<font color=\"red\"><strong>ON</strong></font>";} elseif($val->nofollow==0){echo "<font color=\"blue\"><strong>OFF</strong></font>";}
	echo "</td>";
	echo "<td>".$val->cA."</td>";
	echo "<td>".$val->c0."</td>";
	echo "<td>".$val->c1."</td>";
	echo "<td>".$val->c2."</td>";
	echo "<td>".$val->c3."</td>";
	echo "<td>".$val->c4."</td>";
	echo "<td>".$val->c5."</td>";
	echo "<td>".$val->c6."</td>";
	//echo "<td>".date('Y-m-d',$val->cdate)."</td>";
	echo "<td>".$val->cdate."</td>";
	echo "<td>".$val->ldate."</td>";
	echo "<td>".$val->ddiff."</td>";
	echo "<td>";
  if(strlen($val->url)<40) {echo $val->url;} else{echo substr($val->url, 0, 40)."・・・";}
	echo "</td>";
	echo "</tr>";
  }
 echo "</table>";


		//-----Simple analysis------//
		$tname=$wpdb->prefix."kym_tan_02";
		echo "<hr>";
		echo "<h1>Simple analysis(the past two days)</h1>";
 $sqlt= "
 select count(XX.tan) as cnt,XX.tan as tan ,XX.lang as lang ,XX.ua as ua FROM(
	SELECT tan,SUBSTRING_INDEX(HTTP_ACCEPT_LANGUAGE,',',1) as lang ,
	(case 
	 when HTTP_USER_AGENT like '%Android%' then 'Android'
	 when HTTP_USER_AGENT like '%iPhone%'  then 'iPhone'
	 when HTTP_USER_AGENT like '%iPad%'    then 'iPad'
	 when HTTP_USER_AGENT like '%Windows%' then 'Windows'
	 when HTTP_USER_AGENT like '%Mac%'     then 'Mac'
	 else 'other' end) as ua
	FROM ".$tname.")XX
	group by XX.tan,XX.lang,XX.ua order by count(XX.tan) desc
 ";
 $results = $wpdb->get_results($sqlt);
 echo "<hr>";
 echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
 echo "<tr bgcolor=\"#ffeebb\"><th>Count</th><th>Sname</th><th>languege</th><th>UsarAgent</th></tr>";
 foreach( $results as $key => $val){
	echo "<tr>";
	echo "<td>".$val->cnt."</td>";
	echo "<td>".$val->tan."</td>";
	echo "<td>".$val->lang."</td>";
	echo "<td>".$val->ua."</td>";
	echo "</tr>";
  }
 echo "</table>";


		//----Last 10 access------//
		$tname=$wpdb->prefix."kym_tan_02";
		echo "<hr>";
		echo "<h1>Last 10 access</h1>";
 $sqlt= "
 SELECT id,date_format(ldate, '%m/%d %H:%i') as ldate,tan,REMOTE_ADDR,HTTP_USER_AGENT FROM ".$tname." order by ldate desc limit 10
 ";
 $results = $wpdb->get_results($sqlt);
 echo "<hr>";
 echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
 echo "<tr bgcolor=\"#ffeebb\"><th>LastAccess</th><th>Sname</th><th>Address</th><th>ua</th></tr>";
 foreach( $results as $key => $val){
	echo "<tr>";
	echo "<td>".$val->ldate."</td>";
	echo "<td>".$val->tan."</td>";
	echo "<td>".$val->REMOTE_ADDR."</td>";
	echo "<td>".$val->HTTP_USER_AGENT."</td>";
	echo "</tr>";
  }
 echo "</table>";


}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------	
function kym_tan_analyze(){
	global $wpdb;
	$tname=$wpdb->prefix."kym_tan_01";
	$wpdb->get_results("update  ".$tname." set c6=c5");
	$wpdb->get_results("update  ".$tname." set c5=c4");
	$wpdb->get_results("update  ".$tname." set c4=c3");
	$wpdb->get_results("update  ".$tname." set c3=c2");
	$wpdb->get_results("update  ".$tname." set c2=c1");
	$wpdb->get_results("update  ".$tname." set c1=c0");
	$wpdb->get_results("update  ".$tname." set c0=0");

	$tname=$wpdb->prefix."kym_tan_02";
	$wpdb->get_results("delete from ".$tname." WHERE ldate < NOW() - INTERVAL 2 day");
	}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
function kym_tan_add_files(){
	wp_register_style('style', plugins_url('css/style.css', __FILE__));
	wp_enqueue_style('style');
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
function kym_tan_sc($kym_tan_args) {
	global $wpdb;
	$tname=$wpdb->prefix."kym_tan_01";
	$sqlt= "select tan from ".$tname." where tan='%s' limit 1";
	$sql = $wpdb->prepare($sqlt,$kym_tan_args['tan']);
	$results = $wpdb->get_results($sql,ARRAY_A);
	$ex=$results[0]['tan'];
	return "<a href=\"".esc_url(get_home_url())."\\".$results[0]['tan']."\" target=\"_blank\">".$kym_tan_args['word']."</a>";
}


//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
function kym_tan_activate()   {	kym_tan_MakeTable('kym_tan_01');kym_tan_MakeTable('kym_tan_02');}
function kym_tan_stop()       {	kym_tan_DeleteTable('kym_tan_01');kym_tan_DeleteTable('kym_tan_02');}
function kym_tan_click404()           {	kym_tan_redirect();}
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
  add_action('template_redirect','kym_tan_click404',9 );
	add_action('admin_menu', 'kym_tan_top_menu');
	add_action('cronjyobhook','kym_tan_analyze');
	//add_action('admin_init', 'kym_tan_add_files');
	add_shortcode('tan','kym_tan_sc');
	register_deactivation_hook(__FILE__, 'kym_tan_stop');
	register_activation_hook(__FILE__, 'kym_tan_activate');
?>