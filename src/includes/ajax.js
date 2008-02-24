/*
 * FreqStore - ajax.js
 * Copyright (C) 2008 Justin Richards
 * Released under the GNU General Public License v3
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

var boxed = false;
var formVars = "";

function startXMLHttp() {
	var xmlhttp = false;
	
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		try {
			xmlhttp = new XMLHttpRequest();
		} catch (e) {
			xmlhttp = false;
		}
	}
	
	if (!xmlhttp) {
		alert("Didnt Start");
	}
	
	return xmlhttp;
}

function setVarsForm(vars){
	formVars  = vars;
}

function textBoxIt(objID) {
	var field = document.getElementById(objID);
	var content = field.innerHTML;
	var length = content.length + 2;
	if (!boxed) {
		field.innerHTML = "<input id='"+field.id+"' type='text' size='"+length+"' value='"+content+"' onfocus='this.select(); highLight(this);' onkeypress='textEvent(this, \""+field.id+"\", event);' onblur='update(this, \""+field.id+"\");'></input>";
		boxed = true;
	}
	field.firstChild.focus();
}

function shareBox(objID) {
	var field = document.getElementById(objID);
	var share = field.textContent;
	if (!boxed) {
		var xmlhttp = startXMLHttp();
		xmlhttp.open("GET", 'dbupdate.php?generate=sharebox&share='+encodeURI(share), false);
		xmlhttp.send("");
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			field.innerHTML = xmlhttp.responseText;
			boxed = true;
		}
	field.firstChild.focus();
	}
}

function update(input, field, share) {
	var xmlhttp = startXMLHttp();
	xmlhttp.open("GET", 'dbupdate.php?fieldname='+encodeURI(input.id)+'&content='+input.value+'&'+formVars, true);
	xmlhttp.send("");
	backToText(field);
	
	if (share) {
		refreshShare();
	} else {
		refreshdb();
	}
}

function refreshShare() {
	var xmlhttp = startXMLHttp();
	var share = document.getElementById("share");
	xmlhttp.open("GET", "userdb.php?nosharetemp=1&"+formVars, false);
	xmlhttp.send("");
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		share.innerHTML = xmlhttp.responseText;
	}
}

function refreshdb() {
	var xmlhttp = startXMLHttp();
	var db = document.getElementById("table");
	xmlhttp.open("GET", "userdb.php?notemplate=1&"+formVars, false);
	xmlhttp.send("");
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		db.innerHTML = xmlhttp.responseText;
	}
}

function textEvent(input, field, event) {
	if (event.keyCode == 13) {
		update(input, field);
		// split id up and get the type and number
		var splitID = input.id.split(".");
		//alert(splitID[0]);
		if (splitID[0] == "f") {
			textBoxIt("a."+splitID['1']);
		} else if (splitID[0] == "a") {
			textBoxIt("d."+splitID['1']);
		} else if (splitID[0] == "d") {
			splitID['1']++;
			textBoxIt("f."+splitID['1']);
		}
	}
}

function backToText(objID) {
	var field = document.getElementById(objID);
	var content = field.value;
	field.parentNode.innerHTML = content;
	boxed = false;
	//alert(field.id + " " + content);
}

function stateSelect(objID) {
	var field = document.getElementById(objID);
	var state = field.innerHTML;
	if (!boxed) {
		var xmlhttp = startXMLHttp();
		xmlhttp.open("GET", "dbupdate.php?generate=50sdd&selstate="+encodeURI(state), false);
		xmlhttp.send("");
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			field.innerHTML = xmlhttp.responseText;
			boxed = true;
		}
	field.firstChild.focus();
	}
}

function highLight(field) {
	field.style.border = "2px solid #339900";
}