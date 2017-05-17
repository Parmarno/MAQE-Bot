<?php 
    // class botClass {
    //     public function hello($args){
    //         return $args;
    //     }

    //     public function direction(){

    //     }

    //     public function position(){

    //     }
    // }

    do {
        $selection = fgetc(STDIN);
        do {
           
        } while ( trim($selection) == '' );
        
        echo $selection;
        
    } while ( $selection != 'exit' );
    
    exit(0);
?>