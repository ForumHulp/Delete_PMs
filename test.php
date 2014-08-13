<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

// functions*

function unix_days_ago($pocet_dni)
   {
   return time() - (60*60*24*(int)$pocet_dni);
   }
// functions*

if ($user->data['user_id'] == ANONYMOUS)
{
   die ("<b>User is not logged in.</b>");
}

else
{
    echo '<fieldset><legend>Purge PMs</legend><b>Logged user: ' . $user->data['username_clean'] . '.</b><br>';
   if ($auth->acl_get('a_') || ($auth->acl_get('m_')))
   {
   echo"<form action=\"purge_pm.php\" method=\"post\">";
   echo"Find private messages older than <input name=\"pocet_dni\" type=\"text\"> days.<br>";
   echo"<input type=\"hidden\" name=\"action\" value=\"vypis\"><input type=\"submit\" value=\"OK\"></form>";
   }
   else die("<b>You do not have a permission.</b>");
}

if ((!($auth->acl_get('a_'))) && (!($auth->acl_get('m_')))) die();

switch ($_POST["action"])
   {
   case "vypis":
      if ($_POST["pocet_dni"]!=="") $pocet_dni = $_POST["pocet_dni"]; else $pocet_dni="0";
      $timestamp = unix_days_ago($pocet_dni);
      
      $sql = 'SELECT msg_id,message_time,message_subject,message_attachment FROM phpbb_privmsgs where message_time < '.$timestamp.' order by message_time';
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrowset($result);
      $size = sizeof($row);
      echo "Found <b>".$size. "</b> messages older than <b>".$pocet_dni."</b> days.<br>";
      //print_r ($row);
   
      if ($size!==0)
      {
         echo"<form name=\"test\" action=\"purge_pm.php\" method=\"post\"><table border=1><tr><td><input type=\"checkbox\"></td><td>ID </td><td>Date and time</td><td>Subject</td><td>Attachment</td></tr>";
         foreach ($row as $row2) 
         {
            echo"<tr>";
            echo "<td><input type=\"checkbox\" name=\"id[]\" value=\"".$row2['msg_id']."\"></td>";
            echo "<td>".$row2['msg_id']."</td>";
            $pole_id[] = $row2['msg_id'];
            $sprava_cas = date("Y-m-d h:i:s A",$row2['message_time']);
            echo "<td>".$sprava_cas."</td>";
            echo "<td>".$row2['message_subject']."</td>";
               if ($row2['message_attachment']=="1") 
               {
                  $sql2 = 'SELECT physical_filename FROM phpbb_attachments where post_msg_id = '.$row2['msg_id'].'';
                  $result = $db->sql_query($sql2);
                  $row2 = $db->sql_fetchrowset($result);
                  $size2 = sizeof($row2);
                  echo "<td>Y - ".$size2 ."</td>"; 
               }
               else echo "<td>N</td>";
            
            echo"</tr>\n";
         }
         echo"</table><br>";
         echo"<a href=\"#\" onclick=\"marklist('test', 'id', true); return false;\">Mark all</a> &bull; <a href=\"#\" onclick=\"marklist('test', 'id', false); return false;\">Unmark all</a><br>";
         echo"<input type=\"hidden\" name=\"action\" value=\"delete\"><input type=\"submit\" value=\"Remove selected\"></form><br> * After clicking the button all marked messages will be completely removed from database.";
      }      
      $db->sql_freeresult($result);
   break;
   
   case "delete":
      $id = $_POST['id']; 
      $pocet = sizeof($id);
      echo "Preparation for deletion of <b>$pocet</b> private messages. <br><br>";
         for ($i=0;$i<$pocet;$i++)
            {
            echo"<hr>&bull; Message id <b>$id[$i]</b> - deletion in progress.<br>";
            $sql2 = 'SELECT physical_filename FROM phpbb_attachments where post_msg_id = '.$id[$i].'';
            $result = $db->sql_query($sql2);
            $row2 = $db->sql_fetchrowset($result);
            $size2 = sizeof($row2);
            if ($size2!=="0")
               {
                  foreach ($row2 as $hodnota) 
                  {
                  echo"&nbsp;&nbsp;&nbsp;Attachment found. [file <b>files/".$hodnota['physical_filename']."</b>, was removed.]<br>";
                  unlink ("files/".$hodnota['physical_filename']);
                  $sql = 'DELETE FROM phpbb_attachments where post_msg_id = '.$id[$i].'';
                  $result = $db->sql_query($sql);
                  echo"&nbsp;&nbsp;&nbsp; Information about attachment removed from table <b>phpbb_attachments</b>.<br>";
                  }
               }

      $sql = 'DELETE phpbb_privmsgs AS t1, phpbb_privmsgs_to AS t2 FROM phpbb_privmsgs AS t1 INNER JOIN phpbb_privmsgs_to AS t2
WHERE t1.msg_id=t2.msg_id AND t1.msg_id='.$id[$i].'';
      $result = $db->sql_query($sql);
      echo"&nbsp;&nbsp;&nbsp; Table <b>phpbb_privmsgs_to</b> purged.<br>";
      echo"&nbsp;&nbsp;&nbsp; Deletion of private message id.<b>$id[$i]</b> completed.";
      $db->sql_freeresult($result);
      }      
   
   break;
   }
echo"</fieldset>";

?>
<script>
function marklist(id, name, state)
{
   var parent = document.getElementById(id);
   if (!parent)
   {
      eval('parent = document.' + id);
   }

   if (!parent)
   {
      return;
   }

   var rb = parent.getElementsByTagName('input');
   
   for (var r = 0; r < rb.length; r++)
   {   
      if (rb[r].name.substr(0, name.length) == name)
      {
         rb[r].checked = state;
      }
   }
}
</script>