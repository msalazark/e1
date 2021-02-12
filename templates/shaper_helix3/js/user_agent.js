// Create cookie
function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name+"="+value+expires+";path=/";
}

// Read cookie
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) === 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
    return null;
}

// Erase cookie
function eraseCookie(name) {
    createCookie(name,"",-1);
}

var user_agent_uuid = readCookie('user_agent_uuid');

function fillFormsWithUserAgentUUID(ua_uuid) {
    if (window.jQuery) {  
        // jQuery is loaded
        var subs_user_agent_uuid = $('#subs_user_agent_uuid').val();
        if (subs_user_agent_uuid == '') {
			$('#subs_user_agent_uuid').val(ua_uuid);
        }
    }	
}

function createUserAgent() {
	var create_url = document.location.href;
	if (create_url.indexOf('?') > -1) {
		create_url = create_url.split('?')[0];
	}
	axios.post('https://staging.esanbackoffice.com/websites/user-agent/', {
	    user_agent: window.navigator.userAgent,
	    vendor: window.navigator.vendor,
	    platform: window.navigator.platform,
	    website: document.location.origin,
	    url: create_url,
	  })
	  .then(function (response) {
	    createCookie('user_agent_uuid', response.data.user_agent_uuid, 360*3)
	    user_agent_uuid = readCookie('user_agent_uuid');
	    console.log('User Agent created:');
	    verifyUserAgent(user_agent_uuid);
	  })
	  .catch(function (error) {
	    console.log(error);
	  });
}

function verifyUserAgent(withUUID) {
	axios.get('https://staging.esanbackoffice.com/websites/user-agent/' + withUUID + '/verify/')
	  .then(function (response) {
		  if (response.data.verified) {
		    console.log('User Agent verified');
		    createCookie('user_agent_uuid', withUUID, 360*3)
			fillFormsWithUserAgentUUID(withUUID);
			registerClick(withUUID);
		  }		
	  })
	  .catch(function (error) {
		if (error.response) {
	      // The request was made and the server responded with a status code
	      // that falls out of the range of 2xx
	      console.log(error.response.data);
			if (error.response.status == 403) {
			    eraseCookie('user_agent_uuid');
			    console.log('Removed User Agent Cookies')
			} else {
		      console.log(error.response.status);
			}
	      console.log(error.response.headers);
	    } else if (error.request) {
	      // The request was made but no response was received
	      // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
	      // http.ClientRequest in node.js
	      console.log(error.request);
	    } else {
	      // Something happened in setting up the request that triggered an Error
	      console.log('Error', error.message);
	    }
	    console.log(error.config);		  
	  });
}

var page_load_datetime = new Date();

function registerClick(forUUID) {
	
	var referrer_url = String(document.referrer);
	if ((referrer_url.indexOf('?') > -1) && (referrer_url.indexOf(document.location.origin) > -1)) {
		referrer_url = referrer_url.split('?')[0];
	}
	
	var url = null;
	try {
		url = new URL(referrer_url);	
	}
	catch {
		console.log('No referrer')
	}
	
	var destination = document.location.href;
	if (destination.indexOf('?') > -1) {
		destination = destination.split('?')[0];
	}
	
	var urlParams = new URLSearchParams(window.location.search);
	var src_url_param = '';
	if (urlParams.has('src')) {
		src_url_param = urlParams.get('src');
	};
	if (src_url_param == '') {
		src_url_param = 'direct';
		if (referrer_url.indexOf(document.location.origin) > -1) {
			src_url_param = 'internal';
		} else if ((referrer_url != null) && (referrer_url != '')) {
			src_url_param = 'referral';
		}
	}
	
	var click_interaction = {
	    "timestamp": page_load_datetime.toJSON(),
	    "touchpoint": {
	        "code": src_url_param,
	        "name": document.title,
	        "url": referrer_url,
	        "destination": destination,
		},
	    "action": {
	        "code": "click",
	        "name": "Click",
	    },
	    "user_agent_uuid": user_agent_uuid,
	}
	
	axios.post(
		'https://staging.esanbackoffice.com/websites/user-agent/' + forUUID + '/new-click/',
		click_interaction
	)
	  .then(function (response) {
	    console.log('Click Registered');
		if (response.data.opportunity != null) {
			console.log('Opportunity!');
			if(typeof ebo_user_info !== "undefined") {
				ebo_user_info.opportunity = response.data.opportunity;
			}			
		}
	  })
	  .catch(function (error) {
	    console.log(error);
	  });
}


if (user_agent_uuid == null) {
	createUserAgent();
} else {
	verifyUserAgent(user_agent_uuid);
}

var did_read = false;
var did_scroll = false;
function registerPageRead(forUUID) {
	if (did_read==false) {
		var now = new Date();
		
		var url = document.location.href;
		if (url.indexOf('?') > -1) {
			url = url.split('?')[0];
		}
		
		var read_interaction = {
		    "timestamp": now.toJSON(),
		    "touchpoint": {
		        "code": 'webpage',
		        "name": document.title,
		        "url": url,
			},
		    "action": {
		        "code": "page_read",
		        "name": "Página Leída",
		    },
		    "user_agent_uuid": user_agent_uuid,
		}
		
		axios.post(
			'https://staging.esanbackoffice.com/websites/user-agent/' + forUUID + '/page-read/',
			read_interaction
		)
			.then(function (response) {
				console.log('Page Read');
				if (response.data.opportunity != null) {
					var program_abbreviation = response.data.opportunity.program.abbreviation
					if (typeof fbq === "function") { 
						fbq('track', 'ProgramaOportunidad', {
						    value: program_abbreviation,
						});
					}
					if (typeof ga === "function") {
						ga('send', 'event', 'ProgramaOportunidad', 'inicio_oportunidad', program_abbreviation, 1);
						ga('set', 'dimension5', program_abbreviation);
					}
				}

			})
			.catch(function (error) {
				console.log(error);
			});
		
		did_read = true;
	}
}
window.addEventListener('scroll', function() {
	did_scroll = true;
});
setTimeout(function () {
	if (user_agent_uuid != null) {
		if (did_scroll) {
			registerPageRead(user_agent_uuid);
		} else {
			$(window).scroll(function() {
				did_scroll = true;
				registerPageRead(user_agent_uuid);
			});
		}
	}
}, 30000);


window.onload = function() {
    if (window.jQuery) {  
        // jQuery is loaded
    } else {
        console.log('jQuery is not loaded');
    }
}

var ebo_user_info = {
  opportunityInternal: null,
  opportunityListener: function(val) {},
  set opportunity(val) {
    this.opportunityInternal = val;
    this.opportunityListener(val);
  },
  get opportunity() {
    return this.opportunityInternal;
  },
  registerListener: function(listener) {
    this.opportunityListener = listener;
  }
}
