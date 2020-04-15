<?php
    require "classes/Page.php";

    class DodajOsobu extends Page
    {
        protected function GetContent()
        {

            $this->HandleFormData();
            $output = '';

            $output .= '<h2>Dodaj novi kontakt</h2>';
            $output .= '<form method="POST">';
            $output .= 'Naziv kontakta:<input type="text" class="form-control" name="name"/>';
            $output .= '<input type="submit" name="btnSub" class="btn btn-info" value="Dodaj"/>';
            $output .= '';

            return $output;
        }
        private function HandleFormData()
        {
            if(!isset($_POST["btnSub"])) return;
            
            $name = $_POST["name"];
            $ownerId = $this->_authenticator->GetCurrentUserId();

            $q = "INSERT INTO persons (name, ownerId) VALUES (:name, :ownerId)";

            if($stmt = $this->_database->prepare($q))
            {
                $stmt->bindParam(":name", $name, PDO::PARAM_STR, 50);
                $stmt->bindParam(":ownerId", $ownerId, PDO::PARAM_INT);

                if($stmt->execute())
                {
                    $this->BackToLanding();
                }
            else
            {
                echo "Pogreska u izvrsavanju upita!";
            }
        }
        else
        {
            echo "Pogreska u pripremi upita";
        }
    }
    protected function PageRequiresAuthenticUser()
		{
			return true;
		}
    }
	//Stvaranje objekta klase Moje i prikaz sadržaja
	$site = new DodajOsobu();
	$site->Display('AlgebraBox Dodaj osobu');