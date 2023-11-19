const w = window,
	  d = document;

let sections = document.querySelectorAll('section');
let navLinks = document.querySelectorAll('header nav a');
let menuIcon = document.querySelector('.ltt');
let navar = document.querySelector('.navbar');

export default function fLinks(){


	window.onscroll = () => {

 		sections.forEach(sec=>{

	 		let top = window.scrollY;
	 		let offset = sec.offsetTop - 100;
	 		let height = sec.offsetHeight;
	 		let id = sec.getAttribute('id');

	 		if(top >= offset && top<offset+height){

	 			navLinks.forEach(links=>{
	 				links.classList.remove('active');
	 				let l = document.querySelector('header nav a[href*='+id+']').classList.add('active');
	 			});

	 		}
	
 		});

		let header = document.querySelector('header');

		header.classList.toggle('sticky', window.scrollY > 100);

		menuIcon.classList.remove('ltt-desactive');
		navar.classList.remove('active');
	}

}	