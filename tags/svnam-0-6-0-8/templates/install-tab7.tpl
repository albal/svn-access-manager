<?php
    if( count($tErrors) > 0 ) {
    
        print "<div class=\"form-group\">\n";      
        print "\t<h3 class=\"page-header\">"._("Hints and errors")."</h3>\n";
        print "</div>\n";
        print "<div class=\"form-group\">\n";
        print "\t<p>"._("Please take care about the following warnings and errors.")."</p>\n";
        print "\t<p class=\"form-control-static\">\n";
        print "\t\t<ul>\n";
        
        if( is_array( $tErrors ) ) {
            
            foreach( $tErrors as $tMessage ) {
            
                print "\t\t\t<li>".$tMessage."</li>";
            
            } 
        }
        
        print "\t\t</ul>\n";
        print "\t</p>\n";
        print "</div>\n";
    }
?>
