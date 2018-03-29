var imageDeletes = document.getElementsByClassName("imageDelete")

var deleteImage = function(image) {
	var file = image.target.parentNode.children[1].value;
	if (!window.confirm("Are you sure you want to delete this image?")) {
		return;
	};
	var xhttp = new XMLHttpRequest();
	xhttp.open("POST", "deleteUpload", true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			image.target.parentNode.outerHTML = "";
		}
	};
	var params = "file=" + file;
	xhttp.send(params);
};

for (var i = 0; i < imageDeletes.length; i++) {
    imageDeletes[i].addEventListener("click", deleteImage, false);
}

var imageCopyURLs = document.getElementsByClassName("imageCopyURL")

var copyImageURL = function(image) {
	var imageURL = image.target.parentNode.children[0];
	imageURL.style.display = "block"; 
	imageURL.select();
	
	try {
		document.execCommand('copy');
	} catch (err) {
		console.log('Oops, unable to copy');
	}
	imageURL.style.display = "none"; 
};

for (var i = 0; i < imageCopyURLs.length; i++) {
    imageCopyURLs[i].addEventListener("click", copyImageURL, false);
}