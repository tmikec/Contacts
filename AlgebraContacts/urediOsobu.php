<?php
	require "classes/Page.php";
	
    class UrediOsobu extends Page
    {
        protected function GetContent()
        {
                $this->HandleFormData();

                if(!isset($_GET["id"]) || $this->NotContactOwner($_GET["id"]))
                $this->BackToLanding();

            $osobaId = $_GET["id"];

            $q = "SELECT name FROM persons WHERE id = $osobaId ;";

            foreach($this->_database->query($q) as $row)
            {
                $nazivOsobe = $row["name"];
            }
            
            $output = "<h4>Uredi kontakt osobu <b> $nazivOsobe </b></h4>";

            $output .= '<form method="POST"';
            $output .= '<table><tr>';
            $output .= '<th>Ime osobe:</th><th><input type="text" name="name" class="form-control" value="'.$nazivOsobe.'"/></th>';
            $output .= '<th><input type="submit" name="btnSub" class="btn btn-info" value="Preimenuj"/></th>';
            $output .= '</tr></table>';
            $output .= '<input type="hidden" name="osobaId" value="'.$osobaId.'"/>';
            $output .= '<input type="hidden" name="oldName" value="'.$nazivOsobe.'"/>';
            $output .= '</form>';

            return $output;
        }

        private function NotContactOwner($idContact)
        {
            $ownerId = $this->_authenticator->GetCurrentUserId();

            $q = "SELECT 1 FROM persons WHERE id = $idContact AND ownerId = $ownerId;";
            $count = 0;

            foreach($this->_database->query($q) as $row)
            {
                $count++;
            }
            return $count === 0;
        }

        private function HandleFormData()
        {
            if(!isset($_POST["btnSub"])) return;

            $newName = $_POST["name"];
            $oldName = $_POST["oldName"];
            $id = $_POST["osobaId"];

            $q = "UPDATE persons SET name=:name WHERE id=:id;";

            if($stmt = $this->_database->prepare($q))
            {
                $stmt->bindParam(":name", $newName, PDO::PARAM_STR, 255);
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);

                if($stmt->execute())
                {
                    $this->BackToLanding();
                }
                else{
                    echo "Pogreska u izvrsavanju upita!";
                }
            }
            else{
                echo "Pogreska u pripremi upita!";
            }
        }
    

		protected function PageRequiresAuthenticUser()
		{
			return true;
		}
	}

	$site = new urediOsobu();
	$site->Display('AlgebraContacts moji konkatki');