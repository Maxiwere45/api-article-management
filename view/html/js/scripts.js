/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
//
/* Ne fonctionne pas en local
let div = document.getElementById("result_search");
div.style.display = "none";

let searchBTN = document.getElementById("searchbtn");
if (searchBTN != null) {
    // Ajoute un événement de clic au bouton
    searchBTN.onclick = function() {
        const id = document.getElementById("searchArticle").value;
        const auteur = document.getElementById("auteurAR");
        const contenu = document.getElementById("contenuAR");
        const date = document.getElementById("dateAR");
        const searchBTN = document.getElementById("searchbtn");


        // Création de l'objet XMLHttpRequest
        const xhr = new XMLHttpRequest();

        // Configuration de la requête AJAX
        xhr.open('GET', `http://localhost/api-article-management/controller/server-api.php?id=${id}`);

        // Envoi de la requête AJAX
        xhr.send();

        // Attente de la réponse de la requête AJAX
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                // Récupération de chaque bloc
                const data = response.data;
                auteur.innerHTML = data.author;
                contenu.innerHTML = data.content;
                date.innerHTML = data.date_add;
                div.style.display = "block";
            } else {
                searchBTN.style.backgroundColor = "red";
            }
        };
    }
}
*/
window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});
