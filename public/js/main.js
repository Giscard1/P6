

let items = document.querySelectorAll('.carousel .carousel-item');

items.forEach((el) => {
    const minPerSlide = 4;
    let next = el.nextElementSibling;
    for (var i=1; i<minPerSlide; i++) {
        if (!next) {
            // wrap carousel by using first child
            next = items[0]
        }
        let cloneChild = next.cloneNode(true);
        el.appendChild(cloneChild.children[0]);
        next = next.nextElementSibling
    }
});


$(document).ready(function () {

});

$("#carousel-1").carousel({
    interval: 3000
});

$(function () {
    $("#carousel-multiple").on("slide.bs.carousel", function (e) {
        var itemsPerSlide = parseInt($(this).attr('data-maximum-items-per-slide')),
            totalItems = $(".carousel-item", this).length,
            reserve = 1,//do not change
            $itemsContainer = $(".carousel-inner", this),
            it = (itemsPerSlide + reserve) - (totalItems - e.to);

        if (it > 0) {
            for (var i = 0; i < it; i++) {
                $(".carousel-item", this)
                    .eq(e.direction == "left" ? i : 0)
                    // append slides to the end/beginning
                    .appendTo($itemsContainer);
            }
        }
    });
});


/*
{% block javascripts %}
    <script>
        const containerComments = document.querySelector('#container-comment');
        const buttonElement = document.querySelector('#load-more-comment');
        buttonElement.addEventListener('click', (e) => {
            const urlToCall = buttonElement.dataset.url;
            const nextPage = buttonElement.dataset.nextpage;
            fetch(`${urlToCall}?page=${nextPage}`)
                .then(resp => resp.json())
                .then(data => {
                    if (data.code === 200) {
                        containerComments.innerHTML += data.html;
                        buttonElement.dataset.nextpage++;
                    }
                })
        })
    </script>
{% endblock %}

 */

