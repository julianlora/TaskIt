const w = window,
	  d = document;


export default function toogleI(){

	 let menuIcon = document.querySelector('.ltt '),
 		 navar = document.querySelector('.navbar');

	menuIcon.onclick = () =>{

		menuIcon.classList.toggle('ltt-desactive');
		navar.classList.toggle('active');		
	};

}	  