//window.alert("Gotem working");
//console.log("Gotem working");

window.onload = function () {
    document.getElementById("submitPic").disabled = true;
    document.getElementById("notice").innerHTML = "Vali üleslaadimiseks pilt!";
    document.getElementById("fileToUpload").addEventListener("change", checkSize);
}

function checkSize() {
    //console.log(document.getElementById("fileToUpload").files[0])
    if(document.getElementById("fileToUpload").files[0].size <= 2500000){
        document.getElementById("submitPic").disabled = false;
    }
    else{
        document.getElementById("notice").innerHTML = "Valitud pilt on liiga suur!";
        document.getElementById("submitPic").disabled = true;
    }
}