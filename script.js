function choice_made(event) {
	document.getElementById('perso').disabled = false;
	var button = document.getElementById('startbutton');
	button.disabled = false;
	button.style.cursor = "initial";
	var truc = document.getElementById('hidden');
	truc.value = event.target.value;
	console.log(truc.value);
}

function save_pic() {
	var data = document.getElementById("result").src;
	var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest();
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return;
	}
	xhr.open("POST", "save.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("img=" + data);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
				alert("L'image a correctement été sauvegardée.");
	};
}

function like(event) {
	var id = event.target.id;
	id = id.substr(7);
	var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest();
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return;
	}
	xhr.open("POST", "like.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("img_id=" + id);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
		{
			var list = document.getElementById('likes_' + id);
			var child = document.createElement('li');
			var response = xhr.responseText;
			child.id = response.split(" ")[0];
			child.appendChild(document.createTextNode(response.split(" ")[1]));
			list.appendChild(child);
			var button = document.getElementById("button_" + id);
			button.removeChild(button.childNodes[0]);
			button.appendChild(document.createTextNode("Ne plus aimer"));
			if (button.onclick != null)
				button.onclick = null;
			button.removeEventListener("click", like);
			button.addEventListener("click", unlike);
		}
	}
}

function unlike(event) {
	var id = event.target.id;
	id = id.substr(7);
	var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest();
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return;
	}
	xhr.open("POST", "unlike.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("img_id=" + id);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
		{
			var list = document.	getElementById('likes_' + id);
			var response = xhr.responseText;
			list.removeChild(document.getElementById(response.split(" ")[0]));
			var button = document.getElementById("button_" + id);
			button.removeChild(button.childNodes[0]);
			button.appendChild(document.createTextNode("Aimer"));
			button.removeEventListener("click", unlike);
			button.addEventListener("click", like);
		}
	}
}

function delete_pic(event) {
	var id = event.target.id;
	id = id.substr(7);
	var xhr = null;
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest();
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return;
	}
	xhr.open("POST", "delete.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send("img_id=" + id);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
		{
			if (xhr.responseText == "OK")
			{
				var node = document.getElementById('delete_' + id);
				node.parentNode.removeChild(node);
				node = document.getElementById('button_' + id);
				node.parentNode.removeChild(node);
				node = document.getElementById(id);
				node.parentNode.parentNode.removeChild(node.parentNode);
				node = document.getElementById('likes_' + id);
				node.parentNode.removeChild(node);
			}
		}
	}
}

function perso(event) {
	var div = document.getElementById('perso');
	var file = div.files[0];
	var reader = new FileReader();
	reader.addEventListener('load', function () {
		var xhr = null;
		var data = reader.result;
		if (window.XMLHttpRequest || window.ActiveXObject) {
			if (window.ActiveXObject) {
				try {
					xhr = new ActiveXObject("Msxml2.XMLHTTP");
				} catch(e) {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
			} else {
				xhr = new XMLHttpRequest();
			}
		} else {
			alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
			return;
		}
		xhr.open("POST", "fusion.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var filter = encodeURIComponent(document.getElementById('hidden').value);
		var image = data;
		xhr.send("filter=" + filter + "&img=" + data);
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)){
				var truc = document.getElementById('result');
				truc.src = xhr.responseText;
				truc.style.visibility = "visible";
				document.getElementById('savebutton').style.visibility = "visible";
			}
		};
	});
	reader.readAsDataURL(file);
}
