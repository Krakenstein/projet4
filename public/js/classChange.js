var reporting = document.getElementsByClassName("reporting");

for (var i = 0; i < reporting.length; i++) {
	reporting[i].addEventListener("click", function() {
				  
        this.innerText = "Commentaire signalé";
        this.className = "reported";
	});
}