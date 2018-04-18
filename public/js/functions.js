$(document).ready(function () {
	//custom validator
	jQuery.validator.addMethod("full_url", function (val, elem) {
		// if no url, don't do anything
		if (val.length == 0) {
			return true;
		}
		// if user has not entered http:// https:// or ftp:// assume they mean http://
		if (!/^(https?|ftp):\/\//i.test(val)) {
			val = 'http://' + val; // set both the value
			$(elem).val(val); // also update the form element
		}
		return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(val);
	});
	
	// Animate Hamburger Menu
	$('#nav-icon, #nav-icon2').click(function(){
		$('#nav-icon').toggleClass('open');
	});
	
	
	//validate url fields and autofill http
	$('#newurl').validate({
		rules: {
			url: {
				required: true,
				full_url: true
			}
		},
		messages: {

			"url": {
				full_url: "Please enter a valid URL."
			}

		},
		errorLabelContainer: '.errortxt',
		errorElement: 'div',
		onkeyup: true
	});
	
	if($('.stats-area').length){
		if (window.addEventListener) {
			window.addEventListener('resize', resize);

		}
		else {
			window.attachEvent('onresize', resize);

		}
	}

});

function showSearch() {
	if ($('#searchFiltersWrap').css('display') == 'none') {
		$('#searchFiltersWrap').show('fast');
	} else {
		$('#searchFiltersWrap').hide('fast');
	}
}
function resize () {
    // On resize, recalculate graphps
	if($('#stat_line_24').length){
		yourls_graphstat_line_24();	
	}
    if($('#stat_line_all').length){
		yourls_graphstat_line_all();
	}
	if($('#stat_line_7').length){
		yourls_graphstat_line_7();
	}
	if($('#visualization_stat_tab_source_ref').length){
		yourls_graphstat_tab_source_ref();
	}
	if($('#visualization_stat_tab_source_direct').length){
		yourls_graphstat_tab_source_direct();
	}
	
	
	
}
