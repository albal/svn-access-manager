<?php 
      outputMessage($tMessage, $tMessageType);
      
      if(count($tUserMessages) > 0) {
          
          print '<div class="jumbotron">';
          print '<h3>'._("Important messages").'</h3>';
          
          foreach($tUserMessages as $message) {
          
            $order   = array("\r\n", "\n", "\r");
            $message = str_replace($order, "<br/>", $message);
            print '<p>'.$message.'</p>';
            
          }
          
          print '</div>';
      }

?>
<div>
&nbsp;
</div>
