// cross-browser preventdefault function
function MyPreventDefault(ev){
  if (ev.preventDefault) { ev.preventDefault(); } else { ev.returnValue = false; }
}

// get delta date in sec,min,day,month,year
function getDeltaDate(from, to){
    var dFrom = new Date(from);
    var dTo = new Date(to);

    var year = 0;
    var month = 0;
    var hours = 0;
    var days = 0;
    var minutes = 0;
    var seconds = 0;

    seconds = dTo.getSeconds() - dFrom.getSeconds();
    if( seconds < 0 ){
        seconds += 60;
        minutes--;
    }
    minutes += dTo.getMinutes() - dFrom.getMinutes();
    if( minutes < 0 ){
        minutes += 60;
        hours--;
    }
    hours += dTo.getHours() - dFrom.getHours();
    if( hours < 0 ){
        hours += 24;
        days--;
    }
    days += dTo.getDate() - dFrom.getDate();
    if( days < 0 ){
        days += 30; //посчитать количество дней в месяце From
        month--;
    }
    month += dTo.getMonth() - dFrom.getMonth();
    if( month < 0 ){
        month += 24;
        year--;
    }
    year += dTo.getFullYear() - dFrom.getFullYear();
    
    var res =
        (
            (year > 0)?
                year+( (year>4)?'л ':'г ' )
                :''
        ) + (
            (month > 0)?
                month+'м '
                :''
        ) + (
            (days > 0)?
                days+'д '
                :''
        ) + hours + ':' + minutes;

    return res;
}

/* 
$.preloadImages(["first_image.jpg","second_image.jpg"], function () {
    alert('Обе картинки загружены.');
}); 
*/
$.preloadImages = function () {
    if (typeof arguments[arguments.length - 1] == 'function') {
        var callback = arguments[arguments.length - 1];
    } else {
        var callback = false;
    }
    if (typeof arguments[0] == 'object') {
        var images = arguments[0];
        var n = images.length;
    } else {
        var images = arguments;
        var n = images.length - 1;
    }
    var not_loaded = n;
    for (var i = 0; i < n; i++) {
        jQuery(new Image()).attr('src', images[i]).load(function() {
            if (--not_loaded < 1 && typeof callback == 'function') {
                callback();
            }
        });
    }
}
