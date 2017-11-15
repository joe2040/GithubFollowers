<html>
<title>Find Git Hub Followers</title>
<head></head>
<script>
function display(jobj) {
	if(jobj.status == 'true') {
			document.getElementById('followers').innerHTML ='No of followers :'+jobj.count+'<br/>';
			var page= jobj.page;
			document.getElementById('followers').innerHTML +='Page :'+page+'<br/>';
			document.getElementById('followers').innerHTML +='<div id="list">';
			for (var i=0; i < jobj.avatars.length; i++) {
				document.getElementById('followers').innerHTML +=jobj.avatars[i]+'<br/>';
			}
			document.getElementById('followers').innerHTML +='</div>';
		if(jobj.next != 'false') {
			document.getElementById('btnLoad').innerHTML  ='<button id="load" name="load" onclick="return loadmore(\''+jobj.username+'\',\''+jobj.next+'\');">Load more</button>';
		}else {
			document.getElementById('btnLoad').innerHTML  =' ';
		}
	} else {
		document.getElementById('followers').innerHTML=jobj.response;
	}
}
function followerslist(user) {
var url = "<?php echo site_url(['follower','get_github_followers']);?>";
var username1=document.getElementById('username').value;
var params="username="+username1;
var http=new XMLHttpRequest();
http.open("GET",url+"?"+params, true);
//Send the proper header information along with the request
http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
http.onreadystatechange = function() {//Call a function when the state changes.
	if(http.readyState == 4 && http.status == 200) {
		var jobj = JSON.parse(http.responseText);
		display(jobj);
	}
}
http.send(params);
return false;
}
function loadmore(username,page_id) {
	var url = "<?php echo site_url(['follower','get_github_followers']);?>";
	var params="username="+username+"&&page_id="+page_id;
	var http=new XMLHttpRequest();
	http.open("GET",url+"?"+params, true);
	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			var jobj = JSON.parse(http.responseText);
			display(jobj);
		}
	}
	http.send(params);
	return false;
}
</script>
<body>
<form method="get" onsubmit="return followerslist(this);">
Enter Github Username :  <input type="text" id="username" name="username" required>
<input type="submit" id="btnSearch" name="btnSearch"  value="Search" />
</form>
<div id="followers">
</div>
<div id="btnLoad">
</div>
</body>
</html>
