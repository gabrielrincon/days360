<?php

/*
 * Days360 financial function
 */

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
    
    /*
     * 
     * This method uses the the US/NASD Method (30US/360) to calculate the days 
     * between two dates
     * 
     * NOTE: to use the reference calculation method 'preserve_excel_compatibility' must be set to false
     * The default is to preserve compatibility. This means results are comparable to those obtained with
     * Excel or Calc. This is a bug in Microsoft Office which is preserved for reasons of backward compatibility.
     * Open Office Calc also
     * choose to "implement" this bug to be MS-Excel compatible [1].
     *
     *   [1] http://wiki.openoffice.org/wiki/Documentation/How_Tos/Calc:_Date_%26_Time_functions#Financial_date_systems
     *
     *  Implementation as given by http://en.wikipedia.org/w/index.php?title=360-day_calendar&oldid=546566236 * 
     * 
     */
    public function days360_US_NASD(\DateTime $date_a, \DateTime $date_b)
    {
        return $this->days360_US($date_a, $date_b, FALSE);
    }
    
    /*
     * This method uses the the European method (30E/360) to calculate the days between two dates
     *
     * Implementation as given by http://en.wikipedia.org/w/index.php?title=360-day_calendar&oldid=546566236
     */
    public function days360_EU(\DateTime $date_a, \DateTime $date_b){
        
        $day_a = $date_a->format('d');
        $day_b = $date_b->format('d');
        
        // If either date A or B falls on the 31st of the month, that date will 
        // be changed to the 30th;
        if($day_a == 31)
        {
            $day_a = 30;
        }
        
        if($day_b == 31)
        {
            $day_b = 30;
        }
        
        $days = ($date_b->format('Y') - $date_a->format('Y')) * 360 
                + ($date_b->format('m') - $date_a->format('m')) * 30
                +  ($day_b - $day_a);
        
        return $days;

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
