function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
function horaires(){
    var date = new Date();
    var jour = date.getDay();
    
    var h = date.getHours();
    var m = date.getMinutes();
    m = checkTime(m);
    h = checkTime(h);
    var heureActuelle = h+"."+ m;
    
    var semaine = [
        ["Dimanche"],
        // 0	   1     2     3       4     //
        ["Lundi", 8.30, 9.00, 17.30, 18.00],
        ["Mardi", 8.30, 9.00, 17.30, 18.00],
        ["Mercredi", 8.30, 9.00, 17.30, 18.00],
        ["Jeudi", 8.30, 9.00, 17.30, 18.00],
        ["Vendredi", 8.30, 9.00, 17.30, 18.00],
        ["Samedi", 8.30, 9.00, 17.30, 18.00] 
    ];

    var jourSemaine = semaine[jour];
    // var heureHtml = h+":"+m;
    var texteHtml2= " ";

if (heureActuelle >= jourSemaine[1] && heureActuelle < jourSemaine[2]){
    texteHtml2 = " Ouvre bientôt... <i class='fa-regular fa-clock'></i>";
    document.querySelector("#statutOuverture").setAttribute("style", "color:green");	
}
else if (heureActuelle >= jourSemaine[3] && heureActuelle < jourSemaine[4]){
    texteHtml2 = " Ferme bientôt... <i class='fa-regular fa-clock'></i>";
    document.querySelector("#statutOuverture").setAttribute("style", "color:orange");
}


if (heureActuelle >= jourSemaine[2] && heureActuelle < jourSemaine[4]) {
    
    texteHtml = " Actuellement ouvert! <i id='shopOuvert' class='fa-solid fa-shop'></i>"
    document.querySelector("#horaires").setAttribute("style", "color:green");
}
 else {
    texteHtml = " Actuellement fermé. <i id='shopFerme' class='fa-solid fa-shop-lock'></i></i>"
    document.querySelector("#horaires").setAttribute("style", "color:red");
}
document.querySelector("#statutOuverture").innerHTML = texteHtml2;
document.querySelector("#horaires").innerHTML = texteHtml;
}
setTimeout(horaires, 0)
setInterval(horaires,5000 )