let modal;
let modalImg;
let caption;
let pictureid;
let selectedPicId;
let photoDir = "../picuploadw600h400/";

window.onload = function(){
    modal = document.getElementById("myModal");
    modalImg = document.getElementById("modalImg");
    caption = document.getElementById("caption");
    pictureid = document.getElementById("pictureid");
    let allThumbs = document.getElementById("gallery").getElementsByTagName("img");
    let thumbCount = allThumbs.length;
    for(let i = 0; i < thumbCount; i ++){
        allThumbs[i].addEventListener("click", openModal);
    }
    document.getElementById("close").addEventListener("click", closeModal);
    document.getElementById("storeRating").addEventListener("click", storeRating);
}

function openModal(e){
    //console.log("tere");
    //console.log(e.target.getAttribute("photoid"));
    selectedPicId = e.target.getAttribute("photoid");
    modalImg.src = photoDir + e.target.dataset.fn;
    caption.innerHTML = "<p>" + e.target.alt + "</p>";
    pictureid.innerHTML = "<p>" + e.target.getAttribute("photoid") + "</p>";
    modal.style.display = "block";
}

function closeModal(){
    modal.style.display = "none";
}

function storeRating() {
    let rating = 0;
    for (let i = 1; i <= 5; i++) {
        if(document.getElementById("rate" + i).checked){
            //rating = document.getElementById("rate" + i).value;
            rating = i;
        }
    }
    if (rating > 0){
        //ajax
        let webRequest = new XMLHttpRequest();
        webRequest.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                //console.log(this.responseText);
                document.getElementById("avgRating").innerHTML = "Keskmine hinne: " + this.responseText;
                document.getElementById("score" + selectedPicId).innerHTML = "Hinne: " + this.responseText;
            }
        }
        webRequest.open("GET", "savepicrating.php?rating=" + rating + "&photoId="+selectedPicId, true);
        webRequest.send();

    }

}
