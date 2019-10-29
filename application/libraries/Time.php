<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Time {

	function relative_format($ts) {
	    if(!ctype_digit($ts)) {
	        $ts = strtotime($ts);
	    }
	    $diff = time() - $ts;
	    if($diff == 0) {
	        return 'now';
	    } elseif($diff > 0) {
	        $day_diff = floor($diff / 86400);
	        if($day_diff == 0) {
	            if($diff < 60) return 'baru saja';
	            if($diff < 120) return '1 menit yang lalu';
	            if($diff < 3600) return floor($diff / 60) . ' menit yang lalu';
	            if($diff < 7200) return '1 jam yang lalu';
	            if($diff < 86400) return floor($diff / 3600) . ' jam yang lalu';
	        }
	        if($day_diff == 1) { return 'kemarin'; }
	        if($day_diff < 7) { return $day_diff . ' hari yang lalu'; }
	        if($day_diff < 31) { return ceil($day_diff / 7) . ' minggu yang lalu'; }
	        if($day_diff < 60) { return 'bulan lalu'; }
	        return date('F Y', $ts);
	    } else {
	        $diff = abs($diff);
	        $day_diff = floor($diff / 86400);
	        if($day_diff == 0) {
	            if($diff < 120) { return 'sebentar lagi'; }
	            if($diff < 3600) { return 'dalam ' . floor($diff / 60) . ' menit'; }
	            if($diff < 7200) { return 'dalam satu jam'; }
	            if($diff < 86400) { return 'dalam ' . floor($diff / 3600) . ' jam'; }
	        }
	        if($day_diff == 1) { return 'besok'; }
	        if($day_diff < 4) { return date('l', $ts); }
	        if($day_diff < 7 + (7 - date('w'))) { return 'minggu depan'; }
	        if(ceil($day_diff / 7) < 4) { return 'dalam ' . ceil($day_diff / 7) . ' minggu'; }
	        if(date('n', $ts) == date('n') + 1) { return 'bulan depan'; }
	        return date('F Y', $ts);
	    }
	}

	function readable_date($str){
		return $this->get_day($str).', '.date('d', strtotime($str)).' '.$this->get_month($str).' '.date('Y H:i', strtotime($str));
	}

	function get_day($str){
		switch (date('l', strtotime($str))) {
			case 'Sunday': $day = "Minggu"; break;
			case 'Monday': $day = "Senin"; break;
			case 'Tuesday': $day = "Selasa"; break;
			case 'Wednesday': $day = "Rabu"; break;
			case 'Thursday': $day = "Kamis"; break;
			case 'Friday': $day = "Jumat"; break;
			case 'Saturday': $day = "Sabtu"; break;
		}
		return $day;
	}

	function get_month($str){
		switch (date('n', strtotime($str))) {
			case '1': $day = "Januari"; break;
			case '2': $day = "Februari"; break;
			case '3': $day = "Maret"; break;
			case '4': $day = "April"; break;
			case '5': $day = "Mei"; break;
			case '6': $day = "Juni"; break;
			case '7': $day = "Juli"; break;
			case '8': $day = "Agustus"; break;
			case '9': $day = "September"; break;
			case '10': $day = "Oktober"; break;
			case '11': $day = "November"; break;
			case '12': $day = "Desember"; break;
		}
		return $day;
	}
}