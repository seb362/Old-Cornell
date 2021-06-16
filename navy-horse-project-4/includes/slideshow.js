function openSlideshow() {
  document.getElementById('slideshow').style.display = "block";
}

function closeSlideshow() {
  document.getElementById('slideshow').style.display = "none";
}

var slideIndex = 0;

function nextSlide() {
  showSlides(slideIndex += 1);
}

function prevSlide() {
  showSlides(slideIndex -= 1);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var slides = document.getElementsByClassName("slide");
  var descriptions = document.getElementsByClassName("slideshowDescription");
  if (n > slides.length - 1) { slideIndex = 0 }
  if (n < 0) { slideIndex = slides.length - 1 }
  var i;
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
    descriptions[i].style.display = "none";
  }
  slides[slideIndex].style.display = "block";
  descriptions[slideIndex].style.display = "block";
}
