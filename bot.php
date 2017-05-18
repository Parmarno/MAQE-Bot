<?php 
    if( !class_exists('botClass') ) :
        class botClass {
            private $axis_x, $axis_y, $running ,$walk, $count_walk, $direction, $status;

            public function __construct( $command ){
                $this->command = strtoupper($command);
                $this->init();
            }

            private function init(){
                
                $this->reset();
                $this->validate();
                $this->process();
                $this->response();
                $this->loop();
            }

            private function reset(){
                $this->walk = 0;
                $this->axis_x = 0;
                $this->axis_y = 0;    
                $this->direction = "North";
                $this->running = 'Stop';
                $this->count_walk = 0;
                $this->status = 1;
                $this->exit = false;
                $this->validate = false;
                $this->debug = true;
            }

            private function validate(){

                if( !( preg_match("/^[LRW0-9]+$/", $this->command) == 1 ) ) :
                    if( $this->debug ) :
                        echo "Invalid character.\n"; 
                    endif;
                else:
                    if( preg_match_all('/W+/', $this->command, $matches_walk) ) :
                        if( preg_match_all('/\d+/', $this->command, $matches_count_walk) ) :
                            $this->count_walk = $matches_count_walk[0];
                            if( count($matches_walk[0]) == count($this->count_walk)  ) :
                                $this->validate = true;
                            else :
                                if( $this->debug ) :
                                    echo "Error type walk but specify number step.\n";
                                endif;
                                
                            endif;
                        else :
                            if( $this->debug ) :
                                echo "Error type walk but specify number step.\n";
                            endif;
                        endif;
                    elseif( preg_match_all('/\d+/', $this->command) ):
                        if( $this->debug ) :
                            echo "ณือฟสรก .\n"; 
                        endif;
                    else :
                        $this->validate = true;
                    endif;
                endif; 
            }

            private function process(){

                if( $this->validate ) :
                    if( $this->debug ) :
                        echo "From Command : ".$this->command."\n";
                        echo "===============================\n";
                    endif;

                    foreach ( str_split($this->command) as $state_value) :
                        if ( in_array($state_value, ['R', 'L']) ) :
                            $this->running = $state_value;
                            $this->direction($state_value);

                        elseif( $state_value == "W" ):
                            $this->walk();
                        endif;
                    endforeach;
                endif;
            }

            private function direction(){
                if( $this->debug ) :
                    echo $this->status.".) Turne ".$this->running." Move Direction From ".$this->direction;
                endif;

                if( $this->running === "R" ) :
                    switch ($this->direction) :
                        case 'North':
                            $this->direction = 'East';
                            break;
                        case 'East':
                            $this->direction = 'South';
                            break;
                        case 'South':
                            $this->direction = 'West';
                            break;
                        case 'West':
                            $this->direction = 'North';
                            break;
                        default:
                            break;
                    endswitch;
                else :
                    switch ($this->direction) :
                        case 'North':
                            $this->direction = 'West';
                            break;
                        case 'East':
                            $this->direction = 'North';
                            break;
                        case 'South':
                            $this->direction = 'East';
                            break;
                        case 'West':
                            $this->direction = 'South';
                            break;
                        default:
                            break;
                    endswitch;
                endif;
                if( $this->debug ) :
                    echo " to ".$this->direction."\n";
                endif;
                $this->status += 1;
            }

            private function walk(){
                switch ($this->direction) :
                    case 'North':
                        $this->axis_y += $this->count_walk[$this->walk];
                        break;
                    case 'East':
                        $this->axis_x += $this->count_walk[$this->walk];
                        break;
                    case 'South':
                        $this->axis_y -= $this->count_walk[$this->walk];
                        break;
                    case 'West':
                        $this->axis_x -= $this->count_walk[$this->walk];
                        break;
                    default:
                        break;
                endswitch;
                if( $this->debug ) :
                    echo $this->status.".) Walk ".$this->count_walk[$this->walk]." Step \n";
                endif;
                $this->walk += 1;
                $this->status += 1;
            }

            private function response(){
                if( $this->validate ) :
                    echo "\nX : ".$this->axis_x.' Y : '.$this->axis_y." \n";
                    echo 'Direction : '.$this->direction."\n";
                else :
                    echo "This command is syntax error.\n";
                endif;
            }

            private function loop(){
                do {
                    print "\nPlease type command, Or Type 'exit' to exit : ";
                    print "\nCommand : ";
                    $command = trim(fgets(STDIN));

                    if( $command == "exit" ) :
                        echo "\nGood bye !, See ya ...\n";
                        $this->exit = true;
                        exit(0);
                    endif;
                    
                    $this->command = strtoupper($command);
                    $this->init();
                } while(!$this->exit);
            }
        }
    endif; 
    
    
    $objBot = new botClass(isset($argv[1]) ? $argv[1] : '');
    

?>