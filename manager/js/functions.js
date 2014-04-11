function elFinderBrowser (field_name, url, type, win) {
    var elfinder_url = Etomite.db_url + 'manager/lib/elfinder2_0/elfinder.html';    // use an absolute path!
    tinyMCE.activeEditor.windowManager.open({
      file: elfinder_url,
      title: 'File Manager',
      width: 900,  
      height: 450,
      resizable: 'yes',
      inline: 'yes',    // This parameter only has an effect if you use the inlinepopups plugin!
      popup_css: false, // Disable TinyMCE's default popup CSS
      close_previous: 'no'
        }, {
          window: win,
          input: field_name
        });
    return false;
}

var spinner = null;

function spin(stop) {
	if (stop !== false) {
		if (spinner !== null) {
			spinner.spin();
		} else {
			var target = document.getElementById('adminbody');
			spinner = new Spinner(
				{
					lines: 12, // The number of lines to draw
					length: 18, // The length of each line
					width: 7, // The line thickness
					radius: 23, // The radius of the inner circle
					color: '#000', // #rgb or #rrggbb
					speed: 1.5, // Rounds per second
					trail: 60, // Afterglow percentage
					shadow: false // Whether to render a shadow
				}
			).spin(target);
		}
	} else {
		spinner.stop();
	}
}

function revert(){
    var is_sure = window.confirm("Are you sure you want to revert the document back to this version? You will not be able to revert back to the current version!");
    if(is_sure==true){
        return true;
    }else{ return false; }
}

//<!-- password geneator -->
//<!-- Original:  ataxx@visto.com -->

//<!-- This script and many more are available free online at -->
//<!-- The JavaScript Source!! http://javascript.internet.com -->

//<!-- Begin
function getRandomNum(lbound, ubound) {
    return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
}
function getRandomChar(number, lower, upper, other, extra) {
    var numberChars = "0123456789";
    var lowerChars = "abcdefghijklmnopqrstuvwxyz";
    var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var otherChars = "~!@#$%^&*()-_=+[{]}|;:'\",<.>?";
    var charSet = extra;
    if (number == true)
        charSet += numberChars;
    if (lower == true)
        charSet += lowerChars;
    if (upper == true)
        charSet += upperChars;
    if (other == true)
        charSet += otherChars;
    return charSet.charAt(getRandomNum(0, charSet.length));
}
function getPassword(length, extraChars, firstNumber, firstLower, firstUpper, firstOther,
    latterNumber, latterLower, latterUpper, latterOther) {
    length = 10;
    var rc = "";
    if (length > 0){
        rc = rc + getRandomChar(true, true, true, true, '');
        for (var idx = 1; idx < length; ++idx) {
            rc = rc + getRandomChar(true, true, true, true, '');
        }
    }
    return rc;
}

// function to activate the dashboard boxes sortable, tooltip and collapse after an ajax welcome page load
function activateCollapse() {
	$(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    $(".box-header, .nav-tabs").css("cursor","move");
	
	//Activate tooltips
    $("[data-toggle='tooltip']").tooltip();

    /*     
     * Add collapse and remove events to boxes
     */
    $("[data-widget='collapse']").click(function() {
        //Find the box parent        
        var box = $(this).parents(".box").first();
        //Find the body and the footer
        var bf = box.find(".box-body, .box-footer");
        if (!box.hasClass("collapsed-box")) {
            box.addClass("collapsed-box");
            bf.slideUp();
        } else {
            box.removeClass("collapsed-box");
            bf.slideDown();
        }
    });
}
