<?php
	require "classes/Page.php";
	
	//Klasa Index nasljeđuje klasu Page
	//Znači da sadrži sve njezine varijable i funkcije
	class Index extends Page
	{
		//Funkcija za ispis sadržaja stranice
		protected function GetContent()
		{
			$output = '';
			
			$output .= '<h1>Dobrodošli u AlgebraContacts</h1>';
			$output .= '<p>Pohranite svoje datoteke kod nas.</p>';
      $output .= '<img style="width:200px;" src="https://www.studiosudar.com/wp-content/uploads/2018/05/algebra_34.jpg">';
			
			return $output;
		}
		
		protected function PageRequiresAuthenticUser()
		{
			//Ova stranica ne zahtijeva da je korisnik prijavljen
			return false;
		}
	}

	//Stvaramo objekt klase Index
	$site = new Index();
	//Pozivamo funkciju Display definiranu u klase Page
	//Ova funkcija će pozvati funkcije GetHead, GetContent, GetNavigation
	$site->Display('AlgebraBox Index');