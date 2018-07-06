<?php

namespace OldElephant\Engines\Speaker;

use \OLS\Display\SpeakerLayout\LayoutAssignments;

class Speak {
    use LayoutAssignments;
    


    public function throw_error($content){
        include_once($this->place_of_layouts.$this->layouts['error'].'.phtml');
        die();
    } 
}
