<?php
include 'common.php';

function is_holiday($pdate) {
    $not_holiday  = new DateTime("2017-9-30");
    $diff = date_diff($not_holiday, $pdate);
    if ( 0 == intval($diff->format("%a"))) {
        return false;
    }
    $when = strtotime($pdate->format('Y-m-d H:i:s'));
    $what_day = date("N", $when);
    if ($what_day > 5)
        return true;
    else
        return false;
}

function getWorkingDays($startDate, $endDate)
{
    $begin = strtotime($startDate->format('Y-m-d H:i:s'));
    $end   = strtotime($endDate->format('Y-m-d H:i:s'));
    if ($begin > $end) {
        return 0;
    } else {
        $no_days  = 0;
        $weekends = 0;
        while ($begin <= $end) {
            $no_days++; // no of days in the given interval
            $what_day = date("N", $begin);
            if ($what_day > 5) { // 6 and 7 are weekend days
                $weekends++;
            };
            $begin += 86400; // +1 day
        };
        $working_days = $no_days - $weekends;
        $not_holiday  = new DateTime("2017-9-30");
        $diff = date_diff($not_holiday, $endDate);
        $datediff = intval($diff->format("%R%a"));
        if( $datediff >= 0)
            $working_days++;
            
        return $working_days;
    }
}

function dateDifference($start_date)
{
    $today = new DateTime('now');
    $interval = date_diff($today, $start_date);
    return $interval->format("%d");
}

function generate_class($today_now)
{
    global $url;
    global $classoffset;
    $start_date  = new DateTime("2017-8-31");
    $offset =  getWorkingDays($start_date, $today_now);
    
    $url1 = $url . "images/" . $offset . "_class_1.png";
    $url2 = $url . "images/" . $offset . "_class_2.png";
    $file_morning = "./images/" . $offset . "_class_1.png";
    $file_afternoon = "./images/" . $offset . "_class_2.png";
    
    if ( !file_exists($file_morning) ) {
        $im = imagecreatefrompng('./images/class_schedule.png');
        $center = $classoffset[$offset];
        $im_morning = imagecrop($im, ['x' => 80, 'y' => $center-150, 'width' => 1560, 'height' => 300]);
        if ($im_morning !== FALSE) {
            imagepng($im_morning, $file_morning);
        }
        $im_after = imagecrop($im, ['x' => 1640, 'y' => $center-150, 'width' => 1580, 'height' => 300]);
        if ($im_after !== FALSE) {
            imagepng($im_after, $file_afternoon);
        }
    }
    return array($url1, $url2);
}

$today = new DateTime('now');

?>
