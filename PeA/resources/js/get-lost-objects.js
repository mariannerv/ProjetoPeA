function getLostObjects() {
    $.ajax({
        url: '{{ route("lost-objects.get") }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            let counter = 0;
            for (let i = 0; i < response.data.length; i++) {
            const item = response.data[i];
            
            if ( item.ownerEmail == '{{auth()->user()->email}}' && item.status === "Lost"){
                if (counter % 3 === 0 || counter === 0){
                html += "<div class = 'row'>";
                }
                html += "<div class = 'col-4 border'>";
                html += "<div class = 'row'>"; 
                html += "<div class= 'col'><br>";
                html += "<p>Categoria: " + item.category + "</p>";
                html += "</div>";
                html += "<div class= 'col-auto'>";
                html += "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#lostObject' onclick='setID(\"" + item._id + "\")'> \
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash2' viewBox='0 0 16 16'>\
                            <path d='M14 3a.7.7 0 0 1-.037.225l-1.684 10.104A2 2 0 0 1 10.305 15H5.694a2 2 0 0 1-1.973-1.671L2.037 3.225A.7.7 0 0 1 2 3c0-1.105 2.686-2 6-2s6 .895 6 2M3.215 4.207l1.493 8.957a1 1 0 0 0 .986.836h4.612a1 1 0 0 0 .986-.836l1.493-8.957C11.69 4.689 9.954 5 8 5s-3.69-.311-4.785-.793'/>\
                        </svg>\
                        </button>";
                html += "</div>";  
                html += "</div>";  
                html += "<p>Marca: " + item.brand + "</p>";
                html += "<p>Cor: " + item.color + "</p>";
                html += "<p>Data do desaparecimento: " + item.date_lost + "</p>";
                html += "<a class='btn btn-secondary' href={{ route('lost-object.get', '') }}/" + item._id + ">Ver Objeto </a><br><br>"
                // Add a horizontal line between each object
                counter+=1
                html += "</div>";
                if (counter % 3 === 0 && counter !== 0){
                html += "</div><br>";
                }
                
            }
            }
            $('#lost_objects').html(html); // Insert the generated HTML into the DOM
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

