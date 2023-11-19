const w = window,
	  d = document;


export default function sticky(){


	w.onscroll = () => {
		let header = d.querySelector('header');
		header.classList.toggle('sticky', w.scrollY > 100);
	}


}