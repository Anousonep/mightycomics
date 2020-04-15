// optional
$('#newsCarousel').carousel({
    interval: 6000
});

$('#loveCarousel').carousel({
    interval: 7000
});

//quantity button
$(document).ready(function(){
    $('.count').prop('disabled', true);
    $(document).on('click','.plus',function(){
        $('.count').val(parseInt($('.count').val()) + 1 );
    });
    $(document).on('click','.minus',function(){
        $('.count').val(parseInt($('.count').val()) - 1 );
        if ($('.count').val() == 0) {
            $('.count').val(1);
        }
    });
});


// SANS RAFRAICHIR LA PAGE
// delete partie admin
const deleteButtons = document.querySelectorAll('.delete-button');
for (const button of deleteButtons){
    button.addEventListener('click', (e) =>{
        e.preventDefault(); //annule le comportement par default
        const reponse = window.confirm('Vous êtes sur le point de supprimer cet élément?');
        if (reponse){
            const url = e.currentTarget.href;
            $.post(url, (id) =>{
                const trElement = document.getElementById(id);
                trElement.parentNode.removeChild(trElement);

                $('.deleteMessage').removeClass("d-none");
            });
        }
    });
}

//pagination
// const paginationButtons = document.querySelectorAll('.paginationRefresh');
// for (const button of paginationButtons){
//     button.addEventListener('click', (e) =>{
//         e.preventDefault(); //annule le comportement par default
//     });
// }

// FIN