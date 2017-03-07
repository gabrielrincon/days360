class Days360{
   
    public function test(){
        
        $date1 = new \DateTime('2016-10-10');
        $date2 = new \DateTime('2021-10-09');
        for($i = 0; $i < 390; $i++)
        {
            $dias = $this->days360_US($date1, $date2);
            echo "fecha " . $date2->format('Y-m-d') . " dias360 " . $dias . "</br>";
            $date2->setDate($date2->format('Y'), $date2->format('m'), $date2->format('d')-1);
        }
        
    }
    
    public function days360_US(DateTime $date_a, DateTime $date_b, $preserve_excel_compatibility = true) {
        
        $day_a = $date_a->format('d');
        $day_b = $date_b->format('d');
        
        // Step 1 must be skipped to preserve Excel compatibility
        // (1) If both date A and B fall on the last day of February, 
        // then date B will be changed to the 30th.
        
        if($this->isLastDayOfFebruary($date_a) && $this->isLastDayOfFebruary($date_b) && !$preserve_excel_compatibility)
        {
            $day_b = 30;
        }
        
        // (2) If date A falls on the 31st of a month or last day of February, 
        // then date A will be changed to the 30th.
        if($day_a == 31 || $this->isLastDayOfFebruary($date_a))
        {
            $day_a = 30;
        }
        
        // (3) If date A falls on the 30th of a month after applying (2) 
        // above and date B falls on the 31st of a month, then date B 
        // will be changed to the 30th.
        if($day_a == 30 && $day_b == 31)
        {
            $day_b = 30;
        }
        
        $days = ($date_b->format('Y') - $date_a->format('Y')) * 360 
                + ($date_b->format('m') - $date_a->format('m')) * 30
                +  ($day_b - $day_a);
        
        return $days;

    }
    
    public function isLastDayOfFebruary(\DateTime $date)
    {
        $last_february_day_in_given_year = new DateTime();
        $last_february_day_in_given_year->setDate($date->format('Y'), 3, -1);
        if($date == $last_february_day_in_given_year)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}
