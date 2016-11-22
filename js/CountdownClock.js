addEvent(window, 'load', function() {
    var eDays = document.getElementById('days');
    var eHours = document.getElementById('hours');
    var eMinutes = document.getElementById('minutes');
    var eSeconds = document.getElementById('seconds');
    var timer;
    timer = setInterval(function() {
        var vDays = parseInt(eDays.innerHTML, 10);
        var vHours = parseInt(eHours.innerHTML, 10);
        var vMinutes = parseInt(eMinutes.innerHTML, 10);
        var vSeconds = parseInt(eSeconds.innerHTML, 10);

    vSeconds--;
    if(vSeconds < 0) {
        vSeconds = 59;
        vMinutes--;
        if(vMinutes < 0) {
            vMinutes = 59;
            vHours--;
            if(vHours < 0) {
                vHours = 23;
                vDays--;
            }
        }
    } else {
    if(vSeconds == 0 &&
       vMinutes == 0 &&
       vHours == 0 &&
       vDays == 0) {
        clearInterval(timer);
        }
    }
    eSeconds.innerHTML = vSeconds;
    eMinutes.innerHTML = vMinutes;
    eHours.innerHTML = vHours;
    eDays.innerHTML = vDays;
    }, 1000);
});


function addEvent( obj, type, fn ) {
    if ( obj.attachEvent ) {
        obj['e'+type+fn] = fn;
        obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
        obj.attachEvent( 'on'+type, obj[type+fn] );
    } else
    obj.addEventListener( type, fn, false );
}
