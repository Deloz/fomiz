var request;
var rspText;
function runAjax(accessUrl, data_string)
{
	request = getHTTPObject();
	request.onreadystatechange = sendData;
	request.open('POST', accessUrl, true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencode;charset=utf-8'); 
	request.send(data_string);	
}

function sendData()
{
	switch ( request.readyState )
	{
		case 4:
			if ( request.status == 200 )
			{
				rspText = request.responseText;
			}
			else if ( request.status == 404 )
			{
				rspText = 'request url does not exist...';
			}
			else
			{
				rspText = 'tha page your request is not good...';
			}
			
			break;
		case 1:
			rspText = '<div class="comment-list"><ol><li><p style="color:#f50;font-weight:bold;">sending data...</p></li></ol></div>';
			break;
	}
	document.getElementById('textHint').style ='display:block';
	document.getElementById('textHint').innerHTML = rspText;

}

function getHTTPObject()
{
	var request = false;
	if ( window.XMLHttpRequest )
	{
		request = new XMLHttpRequest();
	}
	else if ( window.ActiveXObject ) 
	{
		try
		{
			request = new ActiveXObject('Msxml2.XMLHTTP');
		}
		catch ( e )
		{
			try
			{
				request = new ActiveXObject('Microsoft.XMLHTTP');
			}
			catch ( e )
			{
				request = false;
			}
		}
	}
	return request;
}

function checkData()
{
	var f = document.forms['commentform'];
	var data = new Object;
	data.name = f['name'].value;
	data.email = f['email'].value;
	data.url = f['url'].value;
	data.comment = f['comment'].value;
	data.comment_post_id = f['comment_post_id'].value;
	data.inputT = new Date().getTime();
	data_string = JSON.stringify(data);
	runAjax(accessUrl, data_string);
}
