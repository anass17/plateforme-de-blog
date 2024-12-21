<?php
    

    function format_datetime($date) {
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $arr = explode(' ', $date);
        $date_parts = explode('-', $arr[0]);
        $formated_date = $date_parts[2] . ' ' . $months[$date_parts[1] - 1] . ' ' . $date_parts[0];
        $time = substr($arr[1], 0, 5);

        $formated_datetime = $formated_date . ' - ' . $time;

        return $formated_datetime;
    } 

    function format_date($date) {
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        $arr = explode(' ', $date);
        $date_parts = explode('-', $arr[0]);
        $formated_date = $date_parts[2] . ' ' . $months[$date_parts[1] - 1] . ' ' . $date_parts[0];

        return $formated_date;
    } 