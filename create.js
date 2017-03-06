var video = document.getElementById('video');
var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
var chosen = null;
var image = new Image();

if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) 
{
	navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) 
	{
		video.src = window.URL.createObjectURL(stream);
		video.play();
	});
}

document.getElementById("snap").addEventListener("click", function()
{
	if (chosen)
	{
		context.drawImage(video, 0, 0, 640, 480);
		image.src = canvas.toDataURL('image/png');
		document.getElementById("imgdata").value = canvas.toDataURL('image/png').substr(22);
		document.getElementById("framesrc").value = chosen.src;
		document.getElementById("sendimg").submit();
	}
	else
		window.alert("Select a frame first !");
});

document.getElementById("submit").addEventListener("click", function()
{ 
	if (chosen)
	{
		document.getElementById("frame").value = chosen.src;
		document.getElementById("uploadimg").submit();
	}
	else
		window.alert("Select a frame first !");
});

function lay(img)
{
	if (chosen && chosen != img)
		chosen.removeAttribute("style");
	if (chosen != img)
	{
		chosen = img;
		chosen.id = "chosen";
		chosen.style.border = "3px solid blue";
	}
}