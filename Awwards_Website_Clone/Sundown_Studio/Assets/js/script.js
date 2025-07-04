const scroll = new LocomotiveScroll({
    el: document.querySelector("#main"),
    smooth: true,
});

function hoverLinks() {
    var fixed = document.querySelector("#fixed");
    var elemContainer = document.querySelector(".element-container");

    elemContainer.addEventListener("mouseenter", function () {
        fixed.style.display = "block";
    });

    elemContainer.addEventListener("mouseleave", function () {
        fixed.style.display = "none";
    });

    var elements = document.querySelectorAll(".element");
    elements.forEach(function (e) {
        e.addEventListener("mouseenter", function () {
            var imgAttribute = e.getAttribute("data-image");
            fixed.style.backgroundImage = `url(${imgAttribute})`;
        });
    });
}

function swiperFn() {
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: "auto",
        centeredSlides: false,
        spaceBetween: 0,
    });
}

function page4effect() {
    var page4img = document.querySelector(".page4-right");
    var page4h2 = document.querySelectorAll(".test");
    var imgDesc = document.querySelector(".desc");

    page4h2.forEach(function (e) {
        e.addEventListener("click", function () {
            var pg4imgAttrib = e.getAttribute("data-image-page4");
            var pg4imgDesc = e.getAttribute("data-img-desc");

            // ✅ Remove 'active' from all h2s
            page4h2.forEach((h) => h.classList.remove("active"));

            // ✅ Add 'active' to the clicked one
            e.classList.add("active");

            // ✅ Update image and description
            imgDesc.textContent = pg4imgDesc;
            page4img.style.backgroundImage = `url(${pg4imgAttrib})`;
        });
    });

    // Set first h2 as active on page load
    page4h2[0].classList.add("active");
}

hoverLinks();
swiperFn();
page4effect();

let loader = document.querySelector("#loader");
setTimeout(() => {
    loader.style.top = "-100%"
}, 4000);