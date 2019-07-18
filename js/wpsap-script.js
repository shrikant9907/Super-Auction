
setInterval(wpsap_diff_counter,1000); 
function wpsap_diff_counter() {
var date = 'January 22, 2017 03:14:07';
var getDate = new Date(date);
var nowDate = new Date();
var timeDiff = (getDate.getTime() - nowDate.getTime())/ 1000 / 60 / 60 / 24;

if(timeDiff>0) {
	var Years = Math.floor(timeDiff / 365.25);
	jQuery('.counter .years').html(Years);
	timeDiff =  timeDiff - Years*365.25;
}
if(timeDiff>0) {
	var Months = Math.floor(timeDiff / 30.4375);
	jQuery('.counter .months').html(Months);
	timeDiff =  timeDiff - Months*30.4375;
}
if(timeDiff>0) {
	var Weeks = Math.floor(timeDiff / 7);
	jQuery('.counter .weeks').html(Weeks);
	timeDiff =  timeDiff - Weeks*7;
}
if(timeDiff>0) {
	var Days = Math.floor(timeDiff);
	jQuery('.counter .days').html(Days);
	timeDiff =  timeDiff - Days;
}
if(timeDiff>0) {
	var Hours = Math.floor(timeDiff * 24);
	jQuery('.counter .hours').html(Hours);
	timeDiff =  timeDiff - Hours/24;
}
if(timeDiff>0) {
	var Minutes = Math.floor(timeDiff * 24 * 60);
	jQuery('.counter .minutes').html(Minutes);
	timeDiff =  timeDiff - Minutes / 24 / 60;
}
if(timeDiff>0) {
	var Seconds = Math.floor(timeDiff * 24 * 60 * 60);
	jQuery('.counter .seconds').html(Seconds);
}

if(timeDiff<=0){
	setInterval(wpsap_diff_counter); 
	jQuery('.wpsap_counter_wr').html('<b>Bidding Closed.</b>');	
}

}

