////////////////////////////////////////////////////////////////////////////////
//  This file is part of eAnalytics
//
//  Copyright (C) 2011 by EXAConsult GmbH and the contributors
//
//  Complete list of developers available at our web site:
//
//       http://www.eanalytics.de
//
//  eAnalytics is free software: you can redistribute it and/or modify
//  it under the terms of the GNU Affero General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  eAnalytics is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU Affero General Public License for more details.
//
//  You should have received a copy of the GNU Affero General Public License
//  along with this program.  If not, see http://www.gnu.org/licenses/.
////////////////////////////////////////////////////////////////////////////////
// eAnalyticsTag - external JavaScript - File (eat_v1_2_1.js)
////////////////////////////////////////////////////////////////////////////////
// Version: 1.2.1
// change 2014-09-27; by Integrated Analytics:
//             "&" in title and topics is replaced by "%26" instead of "&#37;26"
////////////////////////////////////////////////////////////////////////////////

var eat_async = eat_async || [];

var eAT = eAT || ( function() {
		/////////////////////////////////////////////////////////////////
		// Variables
		/////////////////////////////////////////////////////////////////

		var eat_domainID = 100;

		var eat_clientID = 1000;
		
		// last field is running field
		var eat_source = 'utm_source|eatso';
		var eat_campaign = 'utm_campaign|eatca';
		var eat_content = 'utm_content|eatco';
		var eat_channel = 'utm_medium|eatch';
		var eat_glc = 'eatglc';
		var eat_term = 'utm_term|eatte';
		var eat_recipient = 'eatre';
		////////////////////////////////////////////////////////////////////////////////
		var eat_ipMask = 1;
		// 1= anonymous IP  0= No IP
		////////////////////////////////////////////////////////////////////////////////
		var eat_debug = 0;
		// 0= no debug   1= debug (tag request deactivated)    2= debug (tag request activated)
		////////////////////////////////////////////////////////////////////////////////
		// system parameter definition
		var eat_eatStatus = 'request';
		// request|off(shutdown Eat)
		var eat_cookieStatus = 'full';
		// temp(only tmp-session_tag no PUTS)|full(all)|off(no cookie setting)
		var eat_pluginStatus = 'full';
		// identify all Plugins of Client
		var eat_thirdParty = 'off';
		// Set 3rd-Party-Cookie on/off
		var eat_req ;;
		// URI-Stem of Eat
		var eat_sendBrowserInfo = true;
		// get Browserinfo Language, Java enabled, Cookie status
		var eat_pluginFlash = true;
		// get Browserinfo Flashplayer
		var eat_pluginWMedia = false;
		// get Browserinfo WindowsMediaplayer
		var eat_nonPage = 'no';

		var eat_QueryParameter = new Array();
		// contains QueryParameters which are set on webpage
		var eat_event = new Array();
		// contains Events which are set on webpage

		var eat_request_method = "GET";
		// the mthod used for sending the request. Values: GET, POST
		eat_event[0] = '';
		// initialize Array Event
		var eat_protocol_value;
		// protocol of eat
		var eat_marv = '';
		var eat_yplt = '';
		var eat_session_timeout = 7200000;
		// Session Timeout
		var eat_put_timeout = 31536000000;
		// PUT Timeout
		var eat_rvct = 15;
		// number of digit for generate a random value for cookie   rvct= random value cookie tag
		var eat_ptnm = 'eEatID';
		// EAT generated Session-Tag
		var eat_ppnm = 'ePEatID';
		// EAT generated PUT
		var eat_doNotTr = 'eat_doNotTrack';
		// EAT generated DoNotTrack Cookie
		var eat_eatV = 'eat_1.2.1';
		// Version of Eat
		var eat_debugstr = 'debug mode activated:\n';

		var eat_sessionID;
		// Session id
		var eat_pageTitle;
		// contains the page title
		var eat_topic_level_1;
		// contains topic level 1
		var eat_topic_level_2;
		// contains topic level 2
		var eat_topic_level_3;
		// contains topic level 3
		var eat_clientSpec;
		// contains client specific data
		var eat_page_attribute_1;
		var eat_page_attribute_2;
		var eat_page_attribute_3;

		var eat_ignEvent = 'no';
		// ignore the automatic event generation for special URI-Parameter; Values: yes, no
		var eat_page_load_start;
		// load start time of the page
		var eat_page_load_end;
		// load end time of the page

		var eat_lastCallTstmp = new Date(); // time of the last tag request
		var eat_rqst_diff = 200; //milliseconds to wait before an unload  

		////////////////////////////////////////////////////////////////////////////////
		// private Methods
		///////////////////////////////////////////////////////////////////////////////

		/*
		* Add an event
		*
		*/
		function addEvent() {
			var nextIndex = eat_event.length;
			if (eat_event[0] == '') {
				nextIndex = 0;
			}
			var value = '';
			for (var i = 0; i < arguments.length; i++) {
				if (i > 0) {
					value += '|';
				}
				value += arguments[i];

			}
			eat_event[nextIndex] = value;
		}

		/*
		 * Add query parameter.
		 */
		function addQueryParameter() {

			var nextIndex = eat_QueryParameter.length;
			if (eat_QueryParameter[0] == '') {
				nextIndex = 0;
			}
			for (var i = 0; i < arguments.length; i++) {
				eat_QueryParameter[nextIndex] = arguments[i];
                nextIndex++;
			}
			
		}

		/* create Eat Image HTML-Tag */
		function eat_getTagParams(eat_Info0, eat_Info1) {
			var eat_eEat = eat_protocol_value;
			var eat_ryid = Math.floor(Math.random() * 100000000);
			var eat_rytd = new Date();
			var eat_ryts = eat_rytd.getTime();
			eat_marv = "&x=" + eat_ryid + eat_ryts;
			// x_id is a unique ID
			var eat_plsG0 = encodeURI(eat_Info0);
			var eat_plsG1 = eat_Info1;
			var eat_plsG2 = encodeURI(eat_marv + "&z=" + eat_eatV);

			return eat_plsG0 + eat_plsG1 + eat_plsG2;

		}

		/* Call eat */
		function featC() {
			var eat_eCkV = eat_fgCO(eat_doNotTr);
			// check if cookie do not track exist

			if (eat_eCkV == null) {
				if (eat_eatStatus == "full" || eat_eatStatus == "request") {

					eat_fnbOb();
					var eat_PPdt = new Array();
					eat_PPdt = eat_getParameter();

					var reqParam = eat_getTagParams(eat_PPdt[0], eat_PPdt[1]);
					send(reqParam);
					eat_lastCallTstmp = new Date() + eat_rqst_diff;
				}
			}
		}

		/*
		 * Send the image or xmlhttprequest depending on the request method.
		 */
		function send(reqParam) {
			eat_setProtocol();
			if (eat_debug == 0 || eat_debug == 2) {
				if (eat_debug == 2) {
					eat_debugstr += "\n\ntag request activated";
					alert(eat_debugstr);
				}

				// send the data
				if (eat_request_method == 'POST') {
					setXMLHttpRequestCall(reqParam);
				} else {
					setImageTagCall(reqParam);
				}
			} else {
				eat_debugstr += "\n\ntag request  deactivated";
				alert(eat_debugstr);
			}
			eat_debugstr = 'debug mode activated:\n';
		}

		/*
		 Create XMLHttpRequest to transmit the data via POST.
		 */
		function setXMLHttpRequestCall(reqParam) {

			var eat_request;

			if ( typeof XDomainRequest != "undefined") {// code for IE8
				try {
					eat_request = new XDomainRequest();
					eat_request.open("POST", eat_protocol_value);
					eat_request.onreadystatechange = function() {
						if (eat_request.readyState == 4 && eat_request.status != 200) {
						    alert('status: ' + eat_request.status);
							setImageTagCall(reqParam);
						}
					};
					eat_request.send(reqParam);
				} catch(e) {
					alert('error: ' + e);
					setImageTagCall(reqParam);
				}
			} else {
				eat_request = false;
				try {
					if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
						eat_request = new XMLHttpRequest();
					} else {// code for IE6, IE5

						eat_request = new ActiveXObject("Microsoft.XMLHTTP");
					}
				} catch(e) {
					alert('error: ' + e);
					setImageTagCall(reqParam);
				}

				try {
					if (eat_request) {
						eat_request.open("POST", eat_protocol_value, true);
						eat_request.onreadystatechange = function() {
							if (eat_request.readyState == 4 && eat_request.status != 200) {
								alert('status: ' + eat_request.status);
								setImageTagCall(reqParam);
							}
						};
						eat_request.send(reqParam);
					}
				} catch(e) {
					alert('error: ' + e);
					setImageTagCall(reqParam);
				}
			}

		}

		/*
		 Create an image to transmit the data via GET
		 */
		function setImageTagCall(reqParam) {
				var img = new Image(1, 1);
				img.onload = function() {};
				img.src = eat_protocol_value + "?" + reqParam;
		}

		/* function called if visitor do not want do be tracked */
		function eat_doNotTrack() {
			eat_generateCookieValue(eat_doNotTr, eat_rvct, eat_put_timeout);
		}

		/* function called if visitor want do be tracked */
		function eat_doTrack() {
			var eat_exp = new Date();
			eat_exp.setTime(eat_exp.getTime() - 1);
			eat_doTrack_val = 1;
			window.document.cookie = eat_doNotTr + "=" + escape(eat_doTrack_val) + "; expires=" + eat_exp.toGMTString() + "; path=/";
		}

		/* check for protocol */
		function eat_setProtocol() {
			eat_protocol_value = window.location.protocol;

			var s = '.gif';
			/* When use the Eat with Third-Party-Cookie */
			if (eat_thirdParty == "on") {
				s = '.php';
			}
			var url = eat_req + '/' + eat_clientID + '/eat' + eat_ipMask + s;

			if (eat_protocol_value == "http:") {
				eat_protocol_value = "http://" + url;
			} else if (eat_protocol_value == "https:") {
				eat_protocol_value = "https://" + url;
			} else {
				eat_protocol_value = "http://" + url;
			}
		}

		/* check browser if cookie is enable to set */
		function eat_fnbOb() {
			if (eat_sendBrowserInfo == true) {
				if (navigator.cookieEnabled == true && eat_cookieStatus != "off") {
					var eat_CIda = eat_fgCO(eat_ptnm);
					if (eat_CIda == null) {
						eat_sBnf = true;
						eat_generateCookieValue(eat_ptnm, eat_rvct, eat_session_timeout);
						// generate Session-Cookie
					} else {
						eat_sBnf = false;
					}
				} else {
					eat_sBnf = true;
				}
			} else {
				eat_sBnf = false;
			}
		}

		/* get Cookie if exist */
		function eat_fgCO(eat_CkNa) {
			if (document.cookie) {
				var eat_DoCk = document.cookie;
				var eat_cnme = eat_CkNa + "=";
				var eat_clen = eat_DoCk.length;
				var eat_cbgn = 0;
				while (eat_cbgn < eat_clen) {
					var eat_vbgn = eat_cbgn + eat_cnme.length;
					if (eat_DoCk.substring(eat_cbgn, eat_vbgn) == eat_cnme) {
						var eat_vend = eat_DoCk.indexOf(";", eat_vbgn);
						if (eat_vend == -1)
							eat_vend = eat_clen;
						return unescape(eat_DoCk.substring(eat_vbgn, eat_vend));
					}
					eat_cbgn = eat_DoCk.indexOf(" ", eat_cbgn) + 1;
					if (eat_cbgn == 0) {
						break;
					}
				}
			}
			return null;
		}

		/* generate Cookie Values */
		function eat_generateCookieValue(eat_nmea, eat_nmeb, eat_nmec) {
			var eat_eCkV = eat_fgCO(eat_nmea);
			var eat_rdnv = "";
			var eat_datr = new Date();
			var eat_ttme = eat_datr.getTime() + eat_nmec;
			eat_datr.setTime(eat_ttme);
			if (eat_eCkV == null) {
				for (var eat_geni = 0; eat_geni < eat_nmeb; eat_geni++) {
					var eat_rdmv = Math.floor(Math.random() * 10);
					if (eat_rdmv == 10)
						eat_rdmv -= 1;
					eat_rdnv += eat_rdmv;
				}
				var eat_brtd = new Date();
				var eat_brts = eat_brtd.getTime();
				eat_rdnv += eat_brts;
				eat_setCookie(eat_nmea, eat_rdnv, eat_datr);
				eat_eCkV = eat_rdnv;
			} else {
				eat_setCookie(eat_nmea, eat_eCkV, eat_datr);
			}
			return eat_eCkV;
			// Cookie Value
		}

		/* set Cookie */
		function eat_setCookie(eat_nmfa, eat_nmfb, eat_nmfc) {
			if (!eat_nmfc)
				eat_nmfc = new Date();
			var eat_nmfd = eat_getDomain();
			if (eat_nmfd != '') {
				eat_nmfd = '; domain=' + eat_nmfd;
			}
			// write cookie at user
			window.document.cookie = eat_nmfa + "=" + escape(eat_nmfb) + "; expires=" + eat_nmfc.toGMTString() + "; path=/";
		}

		/* get Domain */
		function eat_getDomain() {
			var eat_duri = window.document.URL;
			var eat_durr;
			if (eat_duri.indexOf('https://') > -1) {
				eat_durr = eat_duri.substr(8);
			} else if (eat_duri.indexOf('http://') > -1) {
				eat_durr = eat_duri.substr(7);
			} else {
				return "";
			}
			var eat_durt = eat_durr.substr(0, eat_durr.indexOf('/'));
			if (eat_durt.indexOf('.') == -1) {
				return '';
			} else if (eat_durt.indexOf('.') == eat_durt.lastIndexOf('.')) {
				return '.' + eat_durt;
			} else {
				if (eat_durt.match(/\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}/g)) {
					return eat_durt;
				} else {
					return eat_durt.substr(eat_durt.indexOf('.'));
				}
			}
		}

		/* get Parameter from website */
		function eat_getParameter() {
			var eat_paqe = '';
			var eat_paqeEvent = '';
			if (eat_isDefine(eat_sessionID)) {
				if (eat_sessionID != null && eat_sessionID != '') {
					eat_paqe += "a=" + eat_sessionID;

					eat_debugstr += "\nSessionID: a=" + eat_sessionID;

				}
			} else if (navigator.cookieEnabled == true && eat_cookieStatus != "off") {
				eat_paqe += "a=" + eat_generateCookieValue(eat_ptnm, eat_rvct, eat_session_timeout);
				// generate Session-Cookie
				eat_debugstr += "\nSessionID: a=" + eat_generateCookieValue(eat_ptnm, eat_rvct, eat_session_timeout);
			}
			if (navigator.cookieEnabled == true && eat_cookieStatus == "full") {
				eat_paqe += "&b=" + eat_generateCookieValue(eat_ppnm, eat_rvct, eat_put_timeout);
				// generate Persistant-Cookie PUT
				eat_debugstr += "\nPut: b=" + eat_generateCookieValue(eat_ppnm, eat_rvct, eat_put_timeout);
			}
			var eat_ttmp = '';

			if (eat_isDefine(eat_topic_level_1))// topic 1 of page
			{
				if (eat_topic_level_1 != '') {
					eat_ttmp += eat_topic_level_1.replace(/&/g, '%26') + "|;";
				} else {
					eat_ttmp += "|;";
				}
			} else {
				eat_ttmp += "|;";
			}
			if (eat_isDefine(eat_topic_level_2))// topic 2 of page
			{
				if (eat_topic_level_2 != '') {
					eat_ttmp += eat_topic_level_2.replace(/&/g, '%26') + "|;";
				} else {
					eat_ttmp += "|;";
				}
			} else {
				eat_ttmp += "|;";
			}

			if (eat_isDefine(eat_topic_level_3))// topic 3 of page
			{
				if (eat_topic_level_3 != '') {
					eat_ttmp += eat_topic_level_3.replace(/&/g, '%26') + "|;";
				} else {
					eat_ttmp += "|;";
				}
			} else {
				eat_ttmp += "|;";
			}

			if (eat_isDefine(eat_page_attribute_1))// attribute 1 of page
			{
				if (eat_page_attribute_1 != '') {
					eat_ttmp += eat_page_attribute_1.replace(/&/g, '%26') + "|;";
				} else {
					eat_ttmp += "|;";
				}
			} else {
				eat_ttmp += "|;";
			}

			if (eat_isDefine(eat_page_attribute_2))// attribute 2 of page
			{
				if (eat_page_attribute_2 != '') {
					eat_ttmp += eat_page_attribute_2.replace(/&/g, '%26') + "|;";
				} else {
					eat_ttmp += "|;";
				}
			} else {
				eat_ttmp += "|;";
			}

			if (eat_isDefine(eat_page_attribute_3))// attribute 3 of page
			{
				if (eat_page_attribute_3 != '') {
					eat_ttmp += eat_page_attribute_3.replace(/&/g, '%26');
				}
			}
			if (eat_ttmp != '|;|;') {
				eat_paqe += "&f=" + eat_ttmp;
				eat_debugstr += "\nTopics and Attributes: f=" + eat_ttmp;
			}
			var eat_smul = window.document.URL;
			var eat_ruri = '';
			var eat_rqSt = '';
			if (eat_smul.indexOf("?") == -1) {
				eat_ruri = eat_smul.replace(/#/g, '_');
			} else {
				var eat_rspu = eat_smul.split("?");
				eat_ruri = eat_rspu[0].replace(/#/g, '_');
				eat_rqSt = eat_rspu[1].replace(/#/g, '%26eat_anchor=');
			}
			eat_paqe += "&c=" + eat_ruri;
			eat_debugstr += "\nURL: c=" + eat_ruri;
			if (eat_isDefine(eat_QueryParameter)) {
				if (eat_QueryParameter.length > 0 && eat_QueryParameter[0] != '' && eat_rqSt != '') {
					var eat_dtmp = eat_getUriQueryParameter(eat_rqSt, eat_QueryParameter);
					if (eat_dtmp != '') {
						eat_paqe += "&d=" + eat_dtmp;
						eat_debugstr += "\nURLParameter: d=" + eat_dtmp;
					}
					eat_QueryParameter = new Array();
				}
			}
			if (eat_isDefine(eat_pageTitle)) {
				if (eat_pageTitle == '') {
					if (document.title.length > 127) {
						eat_paqe += "&e=" + (document.title).substr(0, 127).replace(/&/g, '%26');
						eat_debugstr += "Pagetitle: e=" + (document.title).substr(0, 127);
					} else {
						if (document.title == '') {
							eat_paqe += '';
						} else {
							eat_paqe += "&e=" + (document.title).replace(/&/g, '%26');
							eat_debugstr += "\nPagetitle: e=" + (document.title);
						}
					}
				} else {
					eat_paqe += "&e=" + eat_pageTitle.replace(/&/g, '%26');
					eat_debugstr += "\nPagetitle: e=" + eat_pageTitle;
				}
			} else {
				if (document.title.length > 127) {
					eat_paqe += "&e=" + (document.title).substr(0, 127).replace(/&/g, '%26');
					eat_debugstr += "\nPagetitle: e=" + (document.title).substr(0, 127);
				} else {
					if (document.title == '') {
						eat_paqe += '';
					} else {
						eat_paqe += "&e=" + (document.title).replace(/&/g, '%26');
						eat_debugstr += "\nPagetitle: e=" + (document.title);
					}
				}
			}

			if (eat_isDefine(eat_recipient)) {
				if (eat_recipient != '' && eat_rqSt != '') {
					var eat_mrtmp = '';
					var eat_array_mrRecipient = eat_recipient.split('|');

					var eat_isrecipient = '';
					var eat_recipient_is = '';

					for (var eat_ip = 0; eat_ip < eat_array_mrRecipient.length; eat_ip++) {
						var mySearchExpressionP = eval("/" + eat_array_mrRecipient[eat_ip] + "/");
						eat_isrecipient = eat_rqSt.search(mySearchExpressionP);

						if (eat_isrecipient != -1) {
							eat_recipient_is = eat_array_mrRecipient[eat_ip];
							eat_mrtmp = eat_getUriQueryParameterValue(eat_rqSt, eat_recipient_is);
						} else {
							eat_recipient_is = '';
						}

					}

					if (eat_mrtmp != '') {
						eat_paqe += "&mr=" + eat_mrtmp;
						eat_debugstr += "\nRecipient: mr=" + eat_mrtmp;
					}

				}
			}

			if (eat_isDefine(eat_term)) {
				if (eat_term != '' && eat_rqSt != '') {
					var eat_mttmp = '';
					var eat_array_mtTerm = eat_term.split('|');

					var eat_isterm = '';
					var eat_term_is = '';

					for (var eat_ip = 0; eat_ip < eat_array_mtTerm.length; eat_ip++) {
						var mySearchExpressionP = eval("/" + eat_array_mtTerm[eat_ip] + "/");
						eat_isterm = eat_rqSt.search(mySearchExpressionP);

						if (eat_isterm != -1) {
							eat_term_is = eat_array_mtTerm[eat_ip];
							eat_mttmp = eat_getUriQueryParameterValue(eat_rqSt, eat_term_is);
						} else {
							eat_term_is = '';
						}

					}

					if (eat_mttmp != '') {
						eat_paqe += "&mt=" + eat_mttmp;
						eat_debugstr += "\nTerm: mt=" + eat_mttmp;
					}

				}
			}

			if (eat_isDefine(eat_channel)) {
				if (eat_channel != '' && eat_rqSt != '') {
					var eat_mmtmp = '';
					var eat_array_mmChannel = eat_channel.split('|');

					var eat_ischannel = '';
					var eat_channel_is = '';

					for (var eat_ip = 0; eat_ip < eat_array_mmChannel.length; eat_ip++) {
						var mySearchExpressionP = eval("/" + eat_array_mmChannel[eat_ip] + "/");
						eat_ischannel = eat_rqSt.search(mySearchExpressionP);

						if (eat_ischannel != -1) {
							eat_channel_is = eat_array_mmChannel[eat_ip];
							eat_mmtmp = eat_getUriQueryParameterValue(eat_rqSt, eat_channel_is);
						} else {
							eat_channel_is = '';
						}

					}

					if (eat_mmtmp != '') {
						if (eat_mmtmp == "email" || eat_mmtmp == "sea" || eat_mmtmp == "som" || eat_mmtmp == "affn" || eat_mmtmp == "coop" || eat_mmtmp == "oda") {
							eat_paqe += "&mo=" + eat_mmtmp;
							eat_debugstr += "\nChannel: mo=" + eat_mmtmp;
						}
					}

				}
			}

			if (eat_isDefine(eat_source)) {
				if (eat_source != '' && eat_rqSt != '') {
					var eat_mtmp = '';
					var eat_array_msSource = eat_source.split('|');

					var eat_issource = '';
					var eat_source_is = '';

					for (var eat_ip = 0; eat_ip < eat_array_msSource.length; eat_ip++) {
						var mySearchExpressionP = eval("/" + eat_array_msSource[eat_ip] + "/");
						eat_issource = eat_rqSt.search(mySearchExpressionP);

						if (eat_issource != -1) {
							eat_source_is = eat_array_msSource[eat_ip];
							eat_mtmp = eat_getUriQueryParameterValue(eat_rqSt, eat_source_is);
						} else {
							eat_source_is = '';
						}

					}

					if (eat_mtmp != '') {
						eat_paqe += "&ms=" + eat_mtmp;
						eat_debugstr += "\nSource: ms=" + eat_mtmp;
					}

				}
			}
			if (eat_isDefine(eat_content)) {
				if (eat_content != '' && eat_rqSt != '') {
					var eat_ntmp = '';
					var eat_array_mnContent = eat_content.split('|');

					var eat_iscontent = '';
					var eat_content_is = '';

					for (var eat_ib = 0; eat_ib < eat_array_mnContent.length; eat_ib++) {
						var mySearchExpressionB = eval("/" + eat_array_mnContent[eat_ib] + "/");
						eat_iscontent = eat_rqSt.search(mySearchExpressionB);

						if (eat_iscontent != -1) {
							eat_content_is = eat_array_mnContent[eat_ib];
							eat_ntmp = eat_getUriQueryParameterValue(eat_rqSt, eat_content_is);
						} else {
							eat_content_is = '';
						}

					}

					if (eat_ntmp != '') {
						eat_paqe += "&mn=" + eat_ntmp;
						eat_debugstr += "\nContent: mn=" + eat_ntmp;
					}
				}
			}
			if (eat_isDefine(eat_glc)) {
				if (eat_glc != '' && eat_rqSt != '') {
					var eat_glctmp = '';
					var eat_array_glc = eat_glc.split('|');

					var eat_isglc = '';
					var eat_glc_is = '';

					for (var eat_ic = 0; eat_ic < eat_array_glc.length; eat_ic++) {
						var mySearchExpressionC = eval("/" + eat_array_glc[eat_ic] + "/");
						eat_isglc = eat_rqSt.search(mySearchExpressionC);

						if (eat_isglc != -1) {
							eat_glc_is = eat_array_glc[eat_ic];
							eat_glctmp = eat_getUriQueryParameterValue(eat_rqSt, eat_glc_is);
						} else {
							eat_glc_is = '';
						}

					}

					if (eat_glctmp != '') {
						eat_paqe += "&glc=" + eat_glctmp;
						eat_debugstr += "\nGenerated Link-Code: glc=" + eat_glctmp;
					}
				}
			}
			if (eat_isDefine(eat_campaign)) {
				if (eat_campaign != '' && eat_rqSt != '') {
					var eat_otmp = '';
					var eat_array_mcCampaign = eat_campaign.split('|');

					var eat_iscampaign = '';
					var eat_campaign_is = '';

					for (var eat_ic = 0; eat_ic < eat_array_mcCampaign.length; eat_ic++) {
						var mySearchExpressionC = eval("/" + eat_array_mcCampaign[eat_ic] + "/");
						eat_iscampaign = eat_rqSt.search(mySearchExpressionC);

						if (eat_iscampaign != -1) {
							eat_campaign_is = eat_array_mcCampaign[eat_ic];
							eat_otmp = eat_getUriQueryParameterValue(eat_rqSt, eat_campaign_is);
						} else {
							eat_campaign_is = '';
						}

					}

					if (eat_otmp != '') {
						eat_paqe += "&mc=" + eat_otmp;
						eat_debugstr += "\nCampaign: mc=" + eat_otmp;
					}
				}
			}

			/* get adspace Parameter from referrer query string */
			if (eat_rqSt != '' && eat_ignEvent == 'no') {
				var eat_add_tmp = '';
				var eat_is_addspace = '';

				var eat_check_addspace = 'eat_adspace';

				var mySearchExpressionC = eval("/" + eat_check_addspace + "/");
				eat_is_addspace = eat_rqSt.search(mySearchExpressionC);

				if (eat_is_addspace != -1) {
					eat_add_tmp = eat_getUriQueryParameterValue(eat_rqSt, eat_check_addspace);
					var eat_array_addspace = eat_add_tmp.split('_');

					for (var eat_ad_count = 0; eat_ad_count < 6; eat_ad_count++) {
						if ( typeof (eat_array_addspace[eat_ad_count]) == 'undefined') {
							eat_array_addspace[eat_ad_count] = '';
						}
					}

					if ( typeof (eat_array_addspace[4]) != 'undefined') {
						var eat_help_adspace = eat_array_addspace[4].toLowerCase();
						if (eat_help_adspace != 'product') {
							eat_array_addspace[5] = '';
						}
					}

					// make a adspace event with action-id = 2
					var array_counter = eat_array_addspace.length;
					if (array_counter < 7) {
						addEvent('11|' + eat_array_addspace[0] + '|' + eat_array_addspace[1] + '|' + eat_array_addspace[2] + '|' + eat_array_addspace[3] + '|' + eat_array_addspace[4] + '|' + eat_array_addspace[5] + '|2');
					}
				}
			}

			/* get ABTest Parameter from referrer query string */
			if (eat_rqSt != '' && eat_ignEvent == 'no') {
				var eat_abtest_tmp = '';
				var eat_is_abtest = '';

				var eat_check_abtest = 'eat_ABTest';

				var mySearchExpressionC = eval("/" + eat_check_abtest + "/");
				eat_is_abtest = eat_rqSt.search(mySearchExpressionC);

				if (eat_is_abtest != -1) {
					eat_abtest_tmp = eat_getUriQueryParameterValue(eat_rqSt, eat_check_abtest);
					var eat_array_abtest = eat_abtest_tmp.split('_');

					if ( typeof (eat_array_abtest[0]) == 'undefined') {
						eat_array_abtest[0] = '';
					}

					if ( typeof (eat_array_abtest[1]) == 'undefined') {
						eat_array_abtest[1] = '';
					}

					var array_counter = eat_array_abtest.length;
					if (array_counter < 3) {
						// make ABTest event
						addEvent('14|' + eat_array_abtest[0] + '|' + eat_array_abtest[1]);
					}
				}
			}

			/* get SiteSearch Parameter from referrer query string */
			if (eat_rqSt != '' && eat_ignEvent == 'no') {
				var eat_sitesearch_tmp = '';
				var eat_is_sitesearch = '';

				var eat_check_sitesearch = 'eat_sitesearch';

				var mySearchExpressionC = eval("/" + eat_check_sitesearch + "/");
				eat_is_sitesearch = eat_rqSt.search(mySearchExpressionC);

				if (eat_is_sitesearch != -1) {
					eat_sitesearch_tmp = eat_getUriQueryParameterValue(eat_rqSt, eat_check_sitesearch);
					var eat_array_sitesearch = eat_sitesearch_tmp.split('_');

					if ( typeof (eat_array_sitesearch[0]) == 'undefined') {
						eat_array_sitesearch[0] = '';
					}

					if ( typeof (eat_array_sitesearch[1]) == 'undefined') {
						eat_array_sitesearch[1] = '';
					}

					var array_counter = eat_array_sitesearch.length;
					if (array_counter < 3) {
						// make Site Search Result Click event
						addEvent('16|' + eat_array_sitesearch[0] + '|' + eat_array_sitesearch[1]);
					}
				}
			}

			if (eat_sBnf) {
				eat_paqe += "&" + eat_getBrowserInfo();
			}

			if (eat_isDefine(eat_domainID)) {
				if (eat_domainID != '') {
					eat_paqe += "&g=" + eat_domainID;
					eat_debugstr += "\nDomainID: g=" + eat_domainID;
				}
			} else {
				eat_paqe += "&g=000";
				eat_debugstr += "\nDomainID: g=000";
			}
			if (eat_isDefine(eat_event)) {
				var eat_pxEv = eat_getEatEvent();
				if (eat_pxEv != '') {
					//eat_paqe += "&h=" + eat_pxEv;
					eat_paqeEvent = "&h=" + eat_pxEv;
					eat_debugstr += "\nEvents: h=" + eat_pxEv;
				}
			}
			if (eat_isDefine(eat_clientSpec)) {
				if (eat_clientSpec != '') {
					eat_paqe += "&l=" + eat_clientSpec.replace(/&/g, '%26');
					eat_debugstr += "\nClient Specific: l=" + eat_clientSpec;
				}
			}
			if (eat_isDefine(eat_page_load_start) && eat_isDefine(eat_page_load_end)) {
				// get load time in milliseconds for loading page

				var eat_page_load_time = eat_page_load_end.getTime() - eat_page_load_start.getTime();

				eat_paqe += "&w=" + eat_page_load_time;
				eat_debugstr += "\nPage Loadtime: w=" + eat_page_load_time;
				eat_page_load_start = undefined;
				eat_page_load_end = undefined;
			}
			if (eat_isDefine(eat_nonPage)) {
				if (eat_nonPage != 'no') {
					eat_paqe += "&i=1";
					eat_debugstr += "\nNon Page: i=1";
				}
			}
			var eat_scif = eat_getScreenInformation();
			while (eat_scif.indexOf("undefined") > -1) {
				eat_scif = eat_scif.replace("undefined", "-1");
			}
			if (eat_scif != '') {
				eat_paqe += eat_scif;
			}
			var eat_refr = window.document.referrer;
			if (eat_refr != '') {
				var eat_refb = '';
				var eat_refc = '';
				if (eat_refr.indexOf("?") == -1) {
					eat_refb = eat_refr;
				} else {
					var spr = eat_refr.split("?");
					eat_refb = spr[0];
					eat_refc = spr[1];
				}
				eat_paqe += "&k=" + eat_refb;
				eat_debugstr += "\nReferrer: k=" + eat_refb;

				var eat_refd = eat_getReferrerQueryParameter(eat_refc);
				if (eat_refd != "") {
					eat_paqe += "&v=" + eat_refd;
					eat_debugstr += "\nReferrer Querystring: v=" + eat_refd;
				}
			}
			return [eat_paqe, eat_paqeEvent];
		}

		/* get screen information resolution(browser and client), Eatdepth, colordepth */
		function eat_getScreenInformation() {
			var eat_scit = "&j=" + screen.height + "|;" + screen.width + "|;";
			var eat_scbh = window.innerHeight;
			var eat_scbb = window.innerWidth;
			if ( typeof window.innerWidth == "undefined") {
				eat_scbh = screen.availHeight;
				eat_scbb = screen.availWidth;
			}
			var eat_scdh = document.documentElement.clientHeight;
			var eat_scdb = document.documentElement.clientWidth;
			if (eat_scdb == 0 && eat_scdh == 0) {
				eat_scdh = document.body.clientHeight;
				eat_scdb = document.body.clientWidth;
			}
			eat_scit += eat_scbh + "|;" + eat_scbb + "|;" + screen.EatDepth + "|;" + screen.colorDepth + "|;" + eat_scdh + "|;" + eat_scdb;
			eat_debugstr += "\nScreenInfo:" + eat_scit;
			return eat_scit;
		}

		/* check if value isDefine or not */
		function eat_isDefine(eat_vtvl) {
			var eat_iDeb = true;
			if ( typeof eat_vtvl == "undefined") {
				eat_iDeb = false;
			}
			if ( typeof eat_vtvl == "unknown") {
				eat_iDeb = false;
			}
			return eat_iDeb;
		}

		/* get URI Query Parameter */
		function eat_getUriQueryParameter(eat_qstb, eat_qspf) {
			var eat_qstd = '';
			var eat_qstf = 0;
			while (eat_qstf < eat_qspf.length && eat_qspf[eat_qstf] != '') {
				var eat_qsth = eat_qspf[eat_qstf];
				var eat_qstj = eat_qstb.indexOf(eat_qsth + "=");
				if (eat_qstj == -1) {
					eat_qstd += '';
				} else {
					if (eat_qstd != '')
						eat_qstd += '|;';
					eat_qstd += eat_qsth + "|;";
					var eat_qstl = eat_qsth.length + 1;
					var eat_qstn = eat_qstb.substring(eat_qstj + eat_qstl, eat_qstb.length);
					var eat_qstp = eat_qstn.indexOf("&");
					if (eat_qstp == -1) {
						eat_qstd += eat_qstn;
					} else {
						eat_qstd += eat_qstn.substring(0, eat_qstp);
					}
				}
				eat_qstf++;
			}
			return eat_qstd;
		}

		/* get Referrer Query Parameter */
		function eat_getReferrerQueryParameter(eat_rqpz) {
			var eat_rqpy = '';
			var eat_rqpx = 0;
			var eat_rqpw = eat_rqpz.split("&");
			while (eat_rqpx < eat_rqpw.length) {
				var eat_rqpv = eat_rqpw[eat_rqpx].split("=");
				if (eat_rqpx != 0) {
					eat_rqpy += "|;";
				}
				if (eat_rqpv.length == 2) {
					eat_rqpy += eat_rqpv[0] + "|;" + eat_rqpv[1];
				} else {
					eat_rqpy += eat_rqpv[0] + "|;" + "";
				}
				eat_rqpx++;
			}
			return eat_rqpy;
		}

		/* get URI Query Parameter Value */
		function eat_getUriQueryParameterValue(eat_qusy, eat_qusz) {
			var eat_qusp = '';
			var eat_quse = eat_qusy.indexOf(eat_qusz + "=");
			if (eat_quse == -1) {
				eat_qusp += '';
			} else {
				var eat_qusr = eat_qusz.length + 1;
				var eat_qusl = eat_qusy.substring(eat_quse + eat_qusr, eat_qusy.length);

				var eat_qusv = eat_qusl.indexOf("&");

				if (eat_qusv == -1) {
					eat_qusp += eat_qusl;
				} else {
					eat_qusp += eat_qusl.substring(0, eat_qusv);
				}
			}
			return eat_qusp;
		}

		/* get Eat Business-Event */
		function eat_getEatEvent() {
			var eat_valt = '';
			//var eat_ecti = 0;
			//while (eat_ecti < eat_event.length && eat_event[eat_ecti] != '') {
			while (eat_event.length > 0 && eat_event[0] != '') {
				if (eat_valt != '')
					eat_valt += '%7C;';
				var eat_enfo = eat_event.shift().split('|');
				var eat_ectj = 0;
				var eat_ecid = eat_enfo[0];
				while (eat_ectj < 20)// maximum 20 Events
				{
					if (eat_ecid == 1)// Search
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[2];
						}// Amount-Searchresult
						else if (eat_ectj == 2) {
							eat_valt += eat_enfo[3];
						}// Searchtype
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Search-String
						else if (eat_ectj == 3) {
							eat_valt += eat_enfo[4];
						}// Resultpage
						else if (eat_ectj == 4) {
							eat_valt += eat_enfo[5];
						}// maximum Results of Search
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[6].replace(/,/g, '&#37;2C'));
						}// Search-Category
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[7].replace(/,/g, '&#37;2C'));
						}// Search-Order Attribute
						else if (eat_ectj == 9) {
							eat_valt += eat_encode64(eat_enfo[8].replace(/,/g, '&#37;2C'));
						}
						// Search-Order Direction
					} else if (eat_ecid == 2)// Order
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[1];
						}// Action
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[2].replace(/,/g, '&#37;2C'));
						}// Order-ID
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// Basket-ID
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}// Customer-ID
						else if (eat_ectj == 9) {
							eat_valt += eat_encode64(eat_enfo[7].replace(/,/g, '&#37;2C'));
						}// Currency-ISO Alphacode
						else if (eat_ectj == 13) {
							eat_valt += eat_enfo[5].replace(/,/g, '');
						}// Sales
						else if (eat_ectj == 14) {
							eat_valt += eat_enfo[6].replace(/,/g, '');
						}// Sales net
						else if (eat_ectj == 15) {
							eat_valt += eat_enfo[8].replace(/,/g, '');
						}// Shipment
						else if (eat_ectj == 16) {
							eat_valt += eat_enfo[9].replace(/,/g, '');
						}
						// Discount
					} else if (eat_ecid == 3)// Basket
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[2];
						}// Action
						else if (eat_ectj == 2) {
							eat_valt += eat_enfo[8];
						}// Amount
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Basket-ID
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// Product-ID
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}// Productname
						else if (eat_ectj == 9) {
							eat_valt += eat_encode64(eat_enfo[5].replace(/,/g, '&#37;2C'));
						}// Product-Categorie 1
						else if (eat_ectj == 10) {
							eat_valt += eat_encode64(eat_enfo[6].replace(/,/g, '&#37;2C'));
						}// Product-Categorie 2
						else if (eat_ectj == 11) {
							eat_valt += eat_encode64(eat_enfo[7].replace(/,/g, '&#37;2C'));
						}// Product-Categorie 3
						else if (eat_ectj == 12) {
							eat_valt += eat_encode64(eat_enfo[11].replace(/,/g, '&#37;2C'));
						}// Currency-ISO Alphacode
						else if (eat_ectj == 13) {
							eat_valt += eat_enfo[9].replace(/,/g, '');
						}// Price per unit
						else if (eat_ectj == 14) {
							eat_valt += eat_enfo[10].replace(/,/g, '');
						}
						// Price per unit	net
					} else if (eat_ecid == 4)// Lead
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Lead Type
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[2].replace(/,/g, '&#37;2C'));
						}
						// Lead Type Category
					} else if (eat_ecid == 5)// Login
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[1];
						}// Action
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[2].replace(/,/g, '&#37;2C'));
						}// Login
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}
						// Customer-ID
					} else if (eat_ecid == 6)// Newsletter
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[2];
						}// Action
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Customer-ID
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// Newsletter-ID
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}
						// Newslettername
					} else if (eat_ecid == 7)// Service
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[2];
						}// Action
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Customer-ID
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// Service-ID
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}
						// Servicename
					} else if (eat_ecid == 8)// Productview
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Product-ID
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[2].replace(/,/g, '&#37;2C'));
						}// Productname
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// Product-Categorie 1
						else if (eat_ectj == 9) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}// Product-Categorie 2
						else if (eat_ectj == 10) {
							eat_valt += eat_encode64(eat_enfo[5].replace(/,/g, '&#37;2C'));
						}// Product-Categorie 3
						else if (eat_ectj == 11) {
							eat_valt += eat_encode64(eat_enfo[6].replace(/,/g, '&#37;2C'));
						}
						// Viewtype
					} else if (eat_ecid == 9)// Processes
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[1];
						}// Process-ID
						else if (eat_ectj == 2) {
							eat_valt += eat_enfo[3];
						}// Process-Step-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[2].replace(/,/g, '&#37;2C'));
						}// Processname
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}
						// Process-Step-Name
					} else if (eat_ecid == 10)// Formular-Tracking
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Formname
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[2];
						}// FormStepID
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// FormStep
						else if (eat_ectj == 2) {
							eat_valt += eat_enfo[4];
						}
						// FormStepType
					} else if (eat_ecid == 11 && eat_enfo[7] == 2)// Adspace-Targetpage
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Name
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[2].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Position
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Style
						else if (eat_ectj == 9) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Class
						else if (eat_ectj == 10) {
							eat_valt += eat_encode64(eat_enfo[5].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Type
						else if (eat_ectj == 11) {
							eat_valt += eat_encode64(eat_enfo[6].replace(/,/g, '&#37;2C'));
						}// product-id
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[7];
						}
						// Action-ID = 2
					} else if (eat_ecid == 11 && eat_enfo[1] == 1)// Adspace-MediumImpression
					{
						var eat_addspace_infos = eat_enfo[2].split('_');

						for (var eat_ad_count = 0; eat_ad_count < 6; eat_ad_count++) {

							if ( typeof (eat_addspace_infos[eat_ad_count]) == 'undefined') {
								eat_addspace_infos[eat_ad_count] = '';
							}
						}

						// product-id is empty when Promotionbanner-Type is not 'product'
						if ( typeof (eat_addspace_infos[4]) != 'undefined') {
							var eat_help_adspace_infos = eat_addspace_infos[4].toLowerCase();
							if (eat_help_adspace_infos != 'product') {
								eat_addspace_infos[5] = '';
							}
						}

						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						} else if (eat_ectj == 1)// Event-ID
						{
							eat_valt += eat_enfo[1];
						}// Action-ID = 1
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_addspace_infos[0].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Name
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_addspace_infos[1].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Position
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_addspace_infos[2].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Style
						else if (eat_ectj == 9) {
							eat_valt += eat_encode64(eat_addspace_infos[3].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Class
						else if (eat_ectj == 10) {
							eat_valt += eat_encode64(eat_addspace_infos[4].replace(/,/g, '&#37;2C'));
						}// Promotionbanner-Type
						else if (eat_ectj == 11) {
							eat_valt += eat_encode64(eat_addspace_infos[5].replace(/,/g, '&#37;2C'));
						}
						// product-id
					} else if (eat_ecid == 12)//http-status code
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[1];
						}
						// http-code
					} else if (eat_ecid == 13)//Videotracking
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[1];
						}// Video-Status
						else if (eat_ectj == 2) {
							eat_valt += eat_enfo[2];
						}// Video-Duration
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// Video-Name
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}
						// Video-Type
					} else if (eat_ecid == 14)//ABTesting
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Testname
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[2].replace(/,/g, '&#37;2C'));
						}
						// Testvalue
					} else if (eat_ecid == 15)//Viewed site section
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Site Section Name
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[2];
						}
						// CUSTOM-VALUE
					} else if (eat_ecid == 16)// Site Search Result Click
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Search term
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[2];
						}
						// Search position
					} else if (eat_ecid == 17)// Click Visitor Forwarding
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[1].replace(/,/g, '&#37;2C'));
						}// Target Name
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[2].replace(/,/g, '&#37;2C'));
						}// Target Domain
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[3].replace(/,/g, '&#37;2C'));
						}// Target URL
						else if (eat_ectj == 9) {
							eat_valt += eat_encode64(eat_enfo[4].replace(/,/g, '&#37;2C'));
						}
						// Target Category
					} else if (eat_ecid > 31)// clientspecific event
					{
						if (eat_ectj == 0) {
							eat_valt += eat_ecid;
						}// Event-ID
						else if (eat_ectj == 1) {
							eat_valt += eat_enfo[1];
						}// Integer 1
						else if (eat_ectj == 2) {
							eat_valt += eat_enfo[2];
						}// Integer 2
						else if (eat_ectj == 3) {
							eat_valt += eat_enfo[3];
						}// Integer 3
						else if (eat_ectj == 4) {
							eat_valt += eat_enfo[4];
						}// Integer 4
						else if (eat_ectj == 5) {
							eat_valt += eat_enfo[5];
						}// Integer 5
						else if (eat_ectj == 6) {
							eat_valt += eat_encode64(eat_enfo[6].replace(/,/g, '&#37;2C'));
						}// String 1
						else if (eat_ectj == 7) {
							eat_valt += eat_encode64(eat_enfo[7].replace(/,/g, '&#37;2C'));
						}// String 2
						else if (eat_ectj == 8) {
							eat_valt += eat_encode64(eat_enfo[8].replace(/,/g, '&#37;2C'));
						}// String 3
						else if (eat_ectj == 9) {
							eat_valt += eat_encode64(eat_enfo[9].replace(/,/g, '&#37;2C'));
						}// String 4
						else if (eat_ectj == 10) {
							eat_valt += eat_encode64(eat_enfo[10].replace(/,/g, '&#37;2C'));
						}// String 5
						else if (eat_ectj == 11) {
							eat_valt += eat_encode64(eat_enfo[11].replace(/,/g, '&#37;2C'));
						}// String 6
						else if (eat_ectj == 12) {
							eat_valt += eat_encode64(eat_enfo[12].replace(/,/g, '&#37;2C'));
						}// String 7
						else if (eat_ectj == 13) {
							eat_valt += eat_enfo[13];
						}// Decimal 1
						else if (eat_ectj == 14) {
							eat_valt += eat_enfo[14];
						}// Decimal 2
						else if (eat_ectj == 15) {
							eat_valt += eat_enfo[15];
						}
						// Decimal 3
					}
					if (eat_ectj != 20)
						eat_valt += ',';
					eat_ectj++;
				}
				//eat_ecti++;
			}
			return eat_valt;
		}

		/* get Flashplayer when MSIE is used by Client */
		function eat_getFlashMSIE() {
			var eat_fvei = '';
			var eat_fvev = '';
			try {
				var eat_fvep = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
				var eat_fve0 = eat_fvep.GetVariable("$version");
				var eat_fve1 = eat_fve0.split(" ");
				var eat_fve2 = eat_fve1[1].split(",");
				eat_fvev = eat_fve2[0];
				eat_fvei = 1;
			} catch(e) {
				eat_fvev = 0;
				eat_fvei = 0;
			}
			var eat_fveb = eat_fvei + "|;" + eat_fvev;
			return eat_fveb;
		}

		/* get WinMediaPlayer when MSIE is used by Client */
		function eat_getWinMediaMSIE() {
			var eat_plcd = '';
			var eat_ids2 = "22d6f312-b0f6-11d0-94ab-0080c74c7e95";
			if (document.body.addBehavior) {
				document.body.addBehavior("#default#clientCaps");
				if (document.body.isComponentInstalled("{" + eat_ids2 + "}", "ComponentID")) {
					var eat_rppp = document.body.getComponentVersion("{" + eat_ids2 + "}", "ComponentID");
					var eat_rpsp = eat_rppp.split(',');
					eat_plcd += "1|;" + eat_rpsp[0];
				} else {
					eat_plcd += "0|;0";
				}
			} else {
				eat_plcd += "0|;0";
			}
			return eat_plcd;
		}

		/* get WinMediaPlayer when other browser is used by Client */
		function eat_getWinMediaOther() {
			var eat_wmei = '';
			var eat_wmev = '';
			try {
				//var eat_wme0 = navigator.plugins["Windows Media Player Plug-in Dynamic Link Library"].name;
				eat_wmev = -1;
				eat_wmei = 1;
			} catch(e) {
				eat_wmev = 0;
				eat_wmei = 0;
			}
			var eat_wmeb = eat_wmei + "|;" + eat_wmev;
			return eat_wmeb;
		}

		/* get FlashPlayer when other browser is used by Client */
		function eat_getFlashOther() {
			var eat_fvei = '';
			var eat_fvev = '';
			try {
				var eat_fve0 = navigator.plugins["Shockwave Flash"].description;
				var eat_fve1 = eat_fve0.split(" ");
				var eat_fve2 = eat_fve1[2].split(".");
				eat_fvev = eat_fve2[0];
				eat_fvei = 1;
			} catch(e) {
				eat_fvev = 0;
				eat_fvei = 0;
			}
			var eat_fveb = eat_fvei + "|;" + eat_fvev;
			return eat_fveb;
		}

		/* get Browser Info: Flash, Windowsmediaplayer, Java, Language, Cookiestatus */
		function eat_getBrowserInfo() {
			var eat_flai = "r=";

			if (eat_pluginStatus == "full" || (eat_pluginStatus == "select" && eat_pluginFlash == true )) {
				if (navigator.appName == "Microsoft Internet Explorer") {
					eat_flai += eat_getFlashMSIE();
				} else {
					eat_flai += eat_getFlashOther();
				}
			} else {
				eat_flai += "0|;0";
			}
			if (eat_pluginStatus == "full" || (eat_pluginStatus == "select" && eat_pluginWMedia == true )) {
				if (navigator.appName == "Microsoft Internet Explorer") {
					eat_flai += "|;" + eat_getWinMediaMSIE();
				} else {
					eat_flai += "|;" + eat_getWinMediaOther();
				}
			} else {
				eat_flai += "|;0|;0";
			}
			var eat_brLa;
			if (navigator.appName == "Microsoft Internet Explorer") {
				eat_brLa = "p=" + navigator.browserLanguage;
			} else {
				eat_brLa = "p=" + navigator.language;
			}
			var eat_opsy = "q=" + navigator.platform;
			var eat_cost;
			if (navigator.cookieEnabled == true) {
				eat_cost = "s=1";
			} else if (navigator.cookieEnabled == false) {
				eat_cost = "s=0";
			} else {
				eat_cost = "s=-1";
			}
			var eat_jVEn;
			if (navigator.javaEnabled() == true) {
				eat_jVEn = "t=1";
			} else if (navigator.javaEnabled() == false) {
				eat_jVEn = "t=0";
			} else {
				eat_jVEn = "t=-1";
			}
			eat_flai += "&" + eat_brLa + "&" + eat_opsy + "&" + eat_jVEn + "&" + eat_cost + "&u=1";
			eat_debugstr += "\nBrowserPlattform: " + eat_flai;
			return eat_flai;
		}

		/* get Position of a Click in browser window (heatmap relevant) */
		function eat_getEatPosition(eat_posE) {
			if (eat_eatStatus == "full" || eat_eatStatus == "heatmap") {
				var eat_mapO = '';
				var eat_xCli = 0;
				var eat_yCli = 0;
				var eat_sidp = '';
				if (eat_isDefine(eat_sessionID)) {
					eat_sidp = "&a=" + eat_sessionID;
				}
				if (eat_isDefine(eat_mapID)) {
					if (eat_mapID != null && eat_mapID != '') {
						eat_mapO = "ym=" + eat_mapID + "&";
						eat_debugstr += "\nMapID: ym=" + eat_mapID;
					}
				}
				if (!eat_posE) {
					eat_posE = window.event;
				}
				if (navigator.appName == "Microsoft Internet Explorer") {
					eat_xCli = eat_posE.clientX + document.documentElement.scrollLeft;
					// get X-Coordinate
					eat_yCli = eat_posE.clientY + document.documentElement.scrollTop;
					// get Y-Coordinate
					eat_debugstr += "\nXY Heatmap: xx=" + eat_posE.clientX + document.documentElement.scrollLeft + "yy=" + eat_posE.clientY + document.documentElement.scrollTop;
				} else {
					eat_xCli = eat_posE.pageX;
					// get X-Coordinate
					eat_yCli = eat_posE.pageY;
					// get Y-Coordinate
					eat_debugstr += "\nXY Heatmap: xx=" + eat_xCli + "yy=" + eat_yCli;
				}

				// build 1x1 Eat IMG-Tag
				var eat_plps = eat_mapO + "xx=" + eat_xCli + eat_sidp + "&yy=" + eat_yCli + "&z=" + eat_eatV + eat_marv;

				if (eat_isDefine(eat_sendPosition)) {
					if (eat_sendPosition == true) {
						send(eat_plps);
					}
				}
			}
		}

		/* encode event char fields in base64 */
		function eat_encode64(eat_in) {
			var eat_key = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			// key for coding
			var eat_out = "";
			// encoded output
			var eat_char1, eat_char2, eat_char3;
			//
			var eat_encode1, eat_encode2, eat_encode3, eat_encode4;
			var eat_i = 0;

			eat_in = eat_utf8b64(eat_in);

			while (eat_i < eat_in.length) {
				eat_char1 = eat_in.charCodeAt(eat_i++);
				eat_char2 = eat_in.charCodeAt(eat_i++);
				eat_char3 = eat_in.charCodeAt(eat_i++);

				eat_encode1 = eat_char1 >> 2;
				eat_encode2 = ((eat_char1 & 3) << 4) | (eat_char2 >> 4);
				eat_encode3 = ((eat_char2 & 15) << 2) | (eat_char3 >> 6);
				eat_encode4 = eat_char3 & 63;

				if (isNaN(eat_char2)) {
					eat_encode3 = eat_encode4 = 64;
				} else if (isNaN(eat_char3)) {
					eat_encode4 = 64;
				}

				eat_out = eat_out + eat_key.charAt(eat_encode1) + eat_key.charAt(eat_encode2) + eat_key.charAt(eat_encode3) + eat_key.charAt(eat_encode4);
			}

			return eat_out;
		}

		/* for encoding event char fields help-function utf8 */
		function eat_utf8b64(eat_str) {
			eat_str = eat_str.replace(/\r\n/g, "\n");
			var eat_utf = "";

			for (var eat_i = 0; eat_i < eat_str.length; eat_i++) {
				var eat_chc = eat_str.charCodeAt(eat_i);

				if (eat_chc < 128) {
					eat_utf += String.fromCharCode(eat_chc);
				} else if ((eat_chc > 127) && (eat_chc < 2048)) {
					eat_utf += String.fromCharCode((eat_chc >> 6) | 192);
					eat_utf += String.fromCharCode((eat_chc & 63) | 128);
				} else {
					eat_utf += String.fromCharCode((eat_chc >> 12) | 224);
					eat_utf += String.fromCharCode(((eat_chc >> 6) & 63) | 128);
					eat_utf += String.fromCharCode((eat_chc & 63) | 128);
				}
			}

			return eat_utf;
		}

		/* call Eat when fill out a Field of a form */
		function setFormTrackingEvent(eat_FormName, eat_FormStepID, eat_FormStep, eat_FormStepType)// FormName, FormStepID, FormStep, FormStepType
		{
			addEvent('10|' + eat_FormName + '|' + eat_FormStepID + '|' + eat_FormStep + '|' + eat_FormStepType);
			eat_nonPage = 'yes';
			featC();
			eat_nonPage = 'no';
		}

		/* call Eat when clicked link */
		function visitForwarding(eat_TargetName, eat_TargetDomain, eat_TargetURL, eat_TargetCategory) {
			addEvent('17|' + eat_TargetName + '|' + eat_TargetDomain + '|' + eat_TargetURL + '|' + eat_TargetCategory);
			eat_nonPage = 'yes';
			featC();
			eat_nonPage = 'no';
			return true;
		}

		/* call Eat when clicked link */
		function clickSuccessEvent(eat_LeadType, eat_LeadTypeCategory) {
			addEvent('4|' + eat_LeadType + '|' + eat_LeadTypeCategory);
			eat_nonPage = 'yes';
			featC();
			eat_nonPage = 'no';
			return true;
		}

		function beforeUnload() {
			
			while (new Date().getTime() < eat_lastCallTstmp);
		}

		function addEvntLstnr(obj, evnt, fnct, useCptr) {
			if (obj.addEventListener) {
				obj.addEventListener(evnt, fnct, useCptr);
				return true;
			}

			if (obj.attachEvent) {
				return obj.attachEvent('on' + evnt, fnct);
			}

			obj['on' + evnt] = fnct;
		}

		addEvntLstnr(window, 'beforeunload', beforeUnload, false);
        addEvntLstnr(document, 'mousedown',eat_getEatPosition,false);
		
		


		
		return {
			eat_featC : featC,
			eat_setFormTrackingEvent : setFormTrackingEvent,
			eat_VisitForwarding : visitForwarding,
			eat_ClickSuccessEvent : clickSuccessEvent,
			eat_addEvent : addEvent,
			eat_doTrack : eat_doTrack,
			eat_doNotTrack : eat_doNotTrack,
			eat_addQueryParameter : addQueryParameter,
			eat_setRequestMethod : function(method) {
				eat_request_method = method;
			},
			eat_setNonPageFlag : function(nonPageFlag) {
				eat_nonPage = nonPageFlag;
			},
			eat_setClientSpecificEvent : function(value) {
				eat_clientSpec = value;
			},
			eat_setSessionID : function(value) {
				eat_sessionID = value;
			},
			eat_getSessionID : function() {
				return eat_sessionID;
			},
			eat_setDomainID : function(domainID) {
				eat_domainID = domainID;
			},
			eat_setClientID : function(clientID) {
				eat_clientID = clientID;
			},
			eat_setPageTitle : function(value) {
				eat_pageTitle = value;
			},
			eat_setPageTopicLevel1 : function(pageTopicLevel) {
				eat_topic_level_1 = pageTopicLevel;
			},
			eat_setPageTopicLevel2 : function(pageTopicLevel) {
				eat_topic_level_2 = pageTopicLevel;
			},
			eat_setPageTopicLevel3 : function(pageTopicLevel) {
				eat_topic_level_3 = pageTopicLevel;
			},
			eat_setPageAttribute1 : function(pageAttribute) {
				eat_page_attribute_1 = pageAttribute;
			},
			eat_setPageAttribute2 : function(pageAttribute) {
				eat_page_attribute_2 = pageAttribute;
			},
			eat_setPageAttribute3 : function(pageAttribute) {
				eat_page_attribute_3 = pageAttribute;
			},
			eat_setSource : function(source) {
				eat_source = source;
			},
			eat_setCampaign : function(campaign) {
				eat_campaign = campaign;
			},
			eat_setContent : function(content) {
				eat_content = content;
			},
			eat_setChannel : function(channel) {
				eat_channel = channel;
			},
			eat_setTerm : function(term) {
				eat_term = term;
			},
			eat_setRecipient : function(recipient) {
				eat_recipient = recipient;
			},
			eat_setIPMask : function(ipMask) {
				eat_ipMask = ipMask;
			},
			eat_setDebug : function(debug) {
				eat_debug = debug;
			},
			eat_setEatStatus : function(status) {
				eat_eatStatus = status;
			},
			eat_setCookieStatus : function(status) {
				eat_cookieStatus = status;
			},
			eat_setPluginStatus : function(status) {
				eat_pluginStatus = status;
			},
			eat_setThirdParty : function(flag) {
				eat_thirdParty = flag;
			},
			eat_setTagServerHost : function(host) {
				eat_req = host;
			},
			eat_setSendBrowserInfo : function(value) {
				eat_sendBrowserInfo = value;
			},
			eat_setPluginFlash : function(value) {
				eat_pluginFlash = value;
			},
			eat_setPluginWMedia : function(value) {
				eat_pluginWMedia = value;
			},
			eat_set_ignoreEvent : function(value) {
				eat_ignEvent = value;
			},
			eat_setPageLoadTimeStart : function(value) {
				eat_page_load_start = value;
			},
			eat_setPageLoadTimeEnd : function(value) {
				eat_page_load_end = value;
			}
		};

	}()); 
	
	

( function() {

		function eat_do() {
			var func, optionalParams;

			for (var i = 0; i < arguments.length; i++) {
				optionalParams = arguments[i];
				func = optionalParams.shift();
			}

			try {
				eAT[func].apply(eAT, optionalParams);
			} catch(e) {
				//alert("Function: " + func + " caused an error: " + e);
			}
		}

		function Wrapper() {
			return {
				push : eat_do
			}
		};

		for (var i = 0; i < eat_async.length; i++) {
			eat_do(eat_async[i]);
		}
		eat_async = new Wrapper();


   
	}());

	