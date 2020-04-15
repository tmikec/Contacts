<?php
	//Klasa je predložak za sve stranice koje ćemo imati
	session_start();

	require "AuthSystem.php";

	//abstract označava da se ne mogu stvarati objekti klase
	//Ona služi kao predložak za nasljeđivanje
	abstract class Page
	{
		//Varijable u koje ćemo spremiti objekte klase AuthSystem i PDO spoj na bazu
		public $_authenticator;
		public $_database;

		//Konstruktor u kojem se spajamo na bazu i stvaramo objekt klase AuthSystem
		public function __construct()
		{
			//Podaci za spajanje
			$dsn = "mysql:host=localhost;dbname=contacts";
			$user = "root";
			$pass = "";

			//Stvaranje objekata
			//Pomoću objekta $this->_authenticator možemo pozivati sve funkcije iz AuthSystem
			$this->_authenticator = new AuthSystem($dsn, $user, $pass, null);

			//Spoj na bazu (kao $con u ranijim primjerima)
			$this->_database = new PDO($dsn, $user, $pass, null);
		}

		//Funkcija za prikaz HTML sadržaja
		//Ona će se pozivati pri stvaranju bilo koje web stranice
		//Parametar $title će biti naslov stranice
		public function Display($title)
		{
			//Provjera zahtijeva li stranica da korisnik bude prijavljen
			//Provjera je li korisnik prijavljen
			//Ako stranica zahtijeva prijavu, a korisnik nije prijavljen preusmj. na početnu st.
			if($this->PageRequiresAuthenticUser() && !$this->UserIsAuthenticated())
				$this->BackToLanding();


			print('<!DOCTYPE html>');
			print('<html lang="hr">');
			//Dohvaća zaglavlje HEAD
			print($this->GetHead($title));
			print('<body>');
			//Dohvaća izbornik za navigaciju
			print($this->GetNavigation());
			//Dohvaća sadržaj stranice
			print($this->GetContent());
			print('</body>');
			print('</html>');
		}

		//Preusmjeri na početnu index.php
		public function BackToLanding()
		{
			//PHP naredba za preusmjeravanje
			header("Location: index.php");
		}

		//Provjerava je li korisnik prijavljen
		private function UserIsAuthenticated()
		{
			//Pozivamo funkciju iz klase AuthSystem
			return $this->_authenticator->UserIsAuthentic();
		}

		//Funkcija za ispis zaglavlja
		private function GetHead($title)
		{
			//Dodajemo boostrap datoteke, css i js
			$output = '';
			$output .= '<head>';
			$output .= '<meta charset="utf-8">';
			$output .= '<title>'.$title.'</title>';
			$output .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">';
			$output .= '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">';
			$output .= '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>';
			$output .= '</head>';

			//Vraća sadržaj kao rezultat funkcije
			return $output;
		}

		//Funkcija koja vraća izbornik (navigaciju)
		private function GetNavigation()
		{
			$output = "";

      //$output .= '<nav class="navbar navbar-inverse">';
			$output .= "<div class='container-fluid'><ul class='nav navbar-nav'>";
			$output .= '<li><a href="index.php">Pocetna</a></li>';

			//Izbornik za prijavljene korisnike
			if($this->UserIsAuthenticated())
			{
				$output .= '<li><a href="moje.php">Moji kontakti</a></li>';
				$output .= '<li><a href="postavke.php">Moje postavke</a></li>';
				$output .= '<li><a href="odjava.php">Odjava</a></li>';
			}
			//Izbornik za neprijavljene korisnike
			else
			{
				$output .= '<li><a href="prijava.php">Prijava</a></li>';
				$output .= '<li><a href="registracija.php">Registracija</a></li>';
			}
			$output .= "</ul></div>";
      //$output .= "/nav>";
			return $output;
		}

		//Funkcije će se definirati samo u klasama koje nasljeđuju klasu Page
		abstract protected function PageRequiresAuthenticUser();

		abstract protected function GetContent();
	}
