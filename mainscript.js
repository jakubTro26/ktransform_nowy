
window.onload = function() {

  $ = document.querySelector.bind(document)
console.log('loadedd');


document.querySelector('.register').onclick=function(){

  document.querySelector('#register').scrollIntoView();
  
  
  }


if(window.location.href=="https://kongrestransformacji.pl/"){

$('#ChildTab-1 > div > div > div > div:nth-child(5)').style.display="none";

}

if(window.location.href=="https://kongrestransformacji.pl/kontakt/")
{
$('#post-2032 > div > div > div > div > div > div > div:nth-child(2) > div > p:nth-child(2)').style.margin="0px";
$('#post-2032 > div > div > div > div > div > div > div:nth-child(2) > div > p:nth-child(3)').style.margin="0px";
$('#post-2032 > div > div > div > div > div > div > div:nth-child(2) > div > p:nth-child(5)').style.margin="0px";
$('#post-2032 > div > div > div > div > div > div > div:nth-child(2) > div > p:nth-child(6)').style.margin="0px";
$('#post-2032 > div > div > div > div > div > div > div:nth-child(2) > div > p:nth-child(7)').style.margin="0px";
$('#post-2032 > div > div > div > div > div > div > div:nth-child(2) > div > p:nth-child(9)').style.margin="0px";
$('#post-2032 > div > div > div > div > div > div > div:nth-child(2) > div > p:nth-child(10)').style.margin="0px";
$('#post-2032 > div > div > div > div > div > div > div:nth-child(2) > div > p:nth-child(11)').style.margin="0px";

document.querySelector('body > div.jx-ievent-slider.header-1.kontakt > header > div > div > div > div > div.jx-ievent-logo.left > a > img').src="https://kongrestransformacji.pl/wp-content/uploads/2019/04/cyfrowa_transformacja_logo-1.png";
//background:url(https://kongresksiegowych.pl/wp-content/uploads/2018/04/kamil-gliwinski-568269-unsplash.jpg); background-position:center
document.querySelector('#home > div.page-titlebar-bg.parallax-no').style.background='url(https://kongresksiegowych.pl/wp-content/uploads/2018/04/kamil-gliwinski-568269-unsplash.jpg)';
document.querySelector('#home > div.page-titlebar-bg.parallax-no').style.backgroundPosition="center";
$('body > div.jx-ievent-summary-info > div > ul > li:nth-child(2)').style.width="426px";

}

if(window.location.href=="https://kongrestransformacji.pl/" || window.location.href=="https://kongrestransformacji.pl/#schedule" || window.location.href=="https://kongrestransformacji.pl/#about" || window.location.href=="https://kongrestransformacji.pl/#speakers"  ){


$('body > div.jx-ievent-slider.header-1.home-count-down > div.jx-ievent-main-slider.jx-ievent-parallax-fullwidth > div.parallax-no.bg-pos-center').style.background=" url('https://kongrestransformacji.pl/wp-content/uploads/2018/12/image.jpg')";

 $('#speakers > div.container > div > div > div > div:nth-child(1) > div:nth-child(3) > div > div > div > div > p:nth-child(2)').style.margin="0px";
$('body > div.jx-ievent-slider.header-1.home-count-down > div.jx-ievent-main-slider.jx-ievent-parallax-fullwidth > div.jx-ievent-slider-content > div.container > div > div.jx-ievent-event-date').style.background="rgb(214 0 127)";
$('body > div.jx-ievent-slider.header-1.home-count-down > header > div > div > div > div > div.jx-ievent-logo.left > a > img').src="https://kongrestransformacji.pl/wp-content/uploads/2019/04/cyfrowa_transformacja_logo-1.png";  

$('#speakers > div.container > div > div > div > div:nth-child(1) > div:nth-child(4) > div > div > div > div > p:nth-child(2)').style.margin="0px";

}

if(window.location.href=="https://kongrestransformacji.pl/partnerzy/")
{

document.querySelector('body > div.jx-ievent-slider.header-1.partnerzy > header > div > div > div > div > div.jx-ievent-logo.left > a > img').src="https://kongrestransformacji.pl/wp-content/uploads/2019/04/cyfrowa_transformacja_logo-1.png";

document.querySelector('#home > div.page-titlebar-bg.parallax-no').style.background='url(https://kongresksiegowych.pl/wp-content/uploads/2018/04/kamil-gliwinski-568269-unsplash.jpg)';
document.querySelector('#home > div.page-titlebar-bg.parallax-no').style.backgroundPosition="center";
document.querySelector('body > div.jx-ievent-summary-info > div > ul > li:nth-child(2)').style.width="426px";
}

if(window.screen.availWidth>480 && window.screen.availWidth<767){
document.querySelector('.four.columns').style.width="420px";


}

if(window.screen.availWidth<480 ){
document.querySelector('.four.columns').style.width="300px";


}


document.querySelector('.four.columns').style.width="1200px";

var countDownDate = new Date("Jun 30, 2021 7:00:00").getTime();

var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
 document.querySelector('.days.count').innerHTML=days;

document.querySelector('.hours.count').innerHTML=hours;

document.querySelector('.minutes.count').innerHTML=minutes;

document.querySelector('.seconds.count').innerHTML=seconds;
    
  // If the count down is over, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);



if(window.location.href=="https://kongrestransformacji.pl/kontakt/"){
document.querySelector('#speakers > div.container > div > div > div > div:nth-child(1) > div:nth-child(3) > div > div > div > div > p:nth-child(2)').style.margin="0px";

document.querySelector('body > div.jx-ievent-slider.header-1.home-count-down > header > div > div > div > div > div.jx-ievent-logo.left > a > img').src="https://kongrestransformacji.pl/wp-content/uploads/2019/04/cyfrowa_transformacja_logo-1.png";

document.querySelector('.jx-ievent-event-date').style.background="#d6007f";

document.querySelector('#ParentTab > ul > li > div.jx-ievent-tab-day.jx-ievent-uppercase').innerText="30/06/2021";

document.querySelector('#ChildTab-1 > div > div > div > div:nth-child(5) > div.left-position > div:nth-child(2)').style.display="none";

document.querySelector('#ChildTab-1 > div > div > div > div:nth-child(5)').style.display="none";

document.querySelector('#sponsors > div > div > div > div > div:nth-child(2) > div > div > div > div.wpb_text_column.wpb_content_element > div > center > a:nth-child(1)').style.display="none";

document.querySelector('#sponsors > div > div > div > div > div:nth-child(2) > div > div > div > div.wpb_text_column.wpb_content_element > div > center > a:nth-child(2)').style.display="none";
}
dodajimg1();
dodajimg2();


function dodajimg1(){
var a = document.createElement('a');
a.href="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-20.png";
var aimg = document.createElement('img');
aimg.style.width="200px";
aimg.src="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-20.png";
a.insertAdjacentElement('afterbegin',aimg);
document.querySelector('#sponsors > div > div > div > div > div:nth-child(2) > div > div > div > div.wpb_text_column.wpb_content_element > div > center').insertAdjacentElement('afterBegin',a)

}

function dodajimg2(){
var a = document.createElement('a');
a.href="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-31.png";
var aimg = document.createElement('img');
aimg.style.width="200px";
aimg.src="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-31.png";
a.insertAdjacentElement('afterbegin',aimg);
document.querySelector('#sponsors > div > div > div > div > div:nth-child(2) > div > div > div > div.wpb_text_column.wpb_content_element > div > center').insertAdjacentElement('afterBegin',a)

}

var maindiv = document.querySelector('#sponsors > div > div > div > div > div:nth-child(3) > div > div > div > div.wpb_text_column.wpb_content_element > div > center');

for(i =0; i<maindiv.childElementCount;i++){
maindiv.children[i].style.display="none";
}


wspimg = function(){
var b = document.createElement('a');
b.style.padding="20px";
b.href="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-21.png";
var bimg=document.createElement('img');
bimg.style.width="200px";
bimg.src="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-21.png";
b.insertAdjacentElement('afterBegin', bimg);
document.querySelector('#sponsors > div > div > div > div > div:nth-child(3) > div > div > div > div.wpb_text_column.wpb_content_element > div > center').insertAdjacentElement('afterBegin',b);

var c = document.createElement('a');
c.style.padding="20px";
c.href="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-22.png";
var cimg=document.createElement('img');
cimg.style.width="200px";
cimg.src="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-22.png";
c.insertAdjacentElement('afterBegin', cimg);
document.querySelector('#sponsors > div > div > div > div > div:nth-child(3) > div > div > div > div.wpb_text_column.wpb_content_element > div > center').insertAdjacentElement('afterBegin',c);

var d = document.createElement('a');
d.style.padding="20px";
d.href="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-23.png";
var dimg=document.createElement('img');
dimg.style.width="200px";
dimg.src="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-23.png";
d.insertAdjacentElement('afterBegin', dimg);
document.querySelector('#sponsors > div > div > div > div > div:nth-child(3) > div > div > div > div.wpb_text_column.wpb_content_element > div > center').insertAdjacentElement('afterBegin',d);

var e = document.createElement('a');
e.style.padding="20px";
e.href="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-24.png";
var eimg=document.createElement('img');
eimg.style.width="200px";
eimg.src="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-24.png";
e.insertAdjacentElement('afterBegin', eimg);
document.querySelector('#sponsors > div > div > div > div > div:nth-child(3) > div > div > div > div.wpb_text_column.wpb_content_element > div > center').insertAdjacentElement('afterBegin',e);

var f = document.createElement('a');
f.style.padding="20px";
f.href="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-30.png";
var fimg=document.createElement('img');
fimg.style.width="200px";
fimg.src="https://kongrestransformacji.pl/wp-content/uploads/2021/05/image-30.png";
f.insertAdjacentElement('afterBegin', fimg);
document.querySelector('#sponsors > div > div > div > div > div:nth-child(3) > div > div > div > div.wpb_text_column.wpb_content_element > div > center').insertAdjacentElement('afterBegin',f);


}





wspimg();

agenda();
};

var starzynskiOpis = "&bull; Kompleksowość vs integracja wewnętrzna w obliczu zmian technologicznych i rosnącej dynamiki otoczenia.<br>&bull; Cyfryzacja prac biurowych i terenowych - jakie technologie będą dawać realne przewagi konkurencyjne.<br>&bull; Transformacja cyfrowa ale jaka? Doraźna vs. zaplanowana, wewnętrznie rozwijana vs. gotowe rozwiązania";



function agenda(){

var opisDiv=document.createElement('div');

opisDiv.innerHTML=starzynskiOpis;

var starzynski = document.querySelector('#ChildTab-1 > div > div > div > div:nth-child(3) > div.right-position');

starzynski.insertAdjacentElement('beforeEnd',opisDiv);

}

