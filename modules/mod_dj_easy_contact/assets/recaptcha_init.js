window.DJEasyContactInitCaptcha = function() {
	var items = document.getElementsByClassName("djeasycontact-g-recaptcha"), item, options;
	for (var i = 0, l = items.length; i < l; i++) {
		item = items[i];
		options = item.dataset ? item.dataset : {
			sitekey: item.getAttribute("data-sitekey"),
			theme:   item.getAttribute("data-theme"),
			size:    item.getAttribute("data-size"),
			callback: item.getAttribute("data-callback")
		};
		grecaptcha.render(item.getAttribute("id"), options);
	}
	
	// support for other extensions
	var items = document.getElementsByClassName('g-recaptcha'), item, options;
	for (var i = 0, l = items.length; i < l; i++) {
		item = items[i];
		options = item.dataset ? item.dataset : {
			sitekey: item.getAttribute('data-sitekey'),
			theme:   item.getAttribute('data-theme'),
			size:    item.getAttribute('data-size')
		};
		grecaptcha.render(item, options);
	}
};
