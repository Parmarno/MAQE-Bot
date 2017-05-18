<?php 
    class botClass {
        public $axis_x, $axis_y, $running ,$walk, $count_walk, $direction, $status;

        public function __construct(){
            $this->walk = 0;
            $this->axis_x = 0;
            $this->axis_y = 0;    
            $this->direction = "North";
            $this->running = 'Stop';
            $this->count_walk = 0;
            $this->status = 1;
        }

        public function init( $command ){
            $this->command = strtoupper($command);

            echo "===============================\n";

            if( $this->validate() ) :
                $this->process();
                $this->response();
            else :
                $this->error();
            endif;
            
            echo "===============================\n";
        }

        public function validate(){
            if( strpos($this->command, 'W') ) :
                if( preg_match_all('/\d+/', $this->command, $matches) ) :
                    $this->count_walk = $matches;
                    if( substr_count( $this->command, "W" ) != count( $this->count_walk[0] ) ) : 
                        echo "Error type walk but specify number step\n";
                        return false;
                    endif;
                endif;
            endif;
            return true;
        }

        public function direction(){
            echo $this->status.".) Turne ".$this->running." Move Direction From ".$this->direction;

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

            echo " to ".$this->direction."\n";;
            $this->status += 1;
        }

        public function walk(){
            switch ($this->direction) :
                case 'North':
                    $this->axis_y += $this->count_walk[0][$this->walk];
                    break;
                case 'East':
                    $this->axis_x += $this->count_walk[0][$this->walk];
                    break;
                case 'South':
                    $this->axis_y -= $this->count_walk[0][$this->walk];
                    break;
                case 'West':
                    $this->axis_x -= $this->count_walk[0][$this->walk];
                    break;
                default:
                    break;
            endswitch;

            echo $this->status.".) Walk ".$this->count_walk[0][$this->walk]." Step \n";
            $this->walk += 1;
            $this->status += 1;
        }

        public function process(){

            echo "From Command : ".$this->command."\n";
            echo "===============================\n";

            foreach ( str_split($this->command) as $state_value) :
                if ( in_array($state_value, ['R', 'L']) ) :
                    $this->running = $state_value;
                    $this->direction($state_value);

                elseif( $state_value == "W" ):
                    $this->walk();
                endif;
            endforeach;
        }

        public function response(){

            echo 'X : '.$this->axis_x.' Y : '.$this->axis_y." \n";
            echo 'Direction : '.$this->direction."\n";
        }

        public function error(){
            echo "This command is error syntax.\n";
        }

    }
    
    $objBot = new botClass();
    $objBot->init(isset($argv[1]) ? $argv[1] : '');
    exit(0);
?>