<?php

    require "classes/page.php";

    class ObrisiKontakt extends page
    {
        protected function GetContent()
        {
            $this->HandleFormData();
            
            if(!isset($_GET["id"]) || $this->NotContactOwner($_GET["id"]))
            $this->BackToLanding();

            $id = $_GET["id"];
            $name = $this->GetPersonName($id);
            $type = $this->GetContactType($id);
            $output = '';

            $output .= '<h3>Jeste li sigurni da želite izbrisati kontakt tipa '.$type.' i sve vezane kontakte?</h3>';
			$output .= '<form method="POST">';
			$output .= '<input type="submit" class="btn btn-danger" name="btnSub" value="Da"/>';
			$output .= '<input type="hidden" name="id" value="'.$id.'"/>';
			$output .= '</form>';
			$output .= '<a href="moje.php">Povratak</a>';
            return $output;       
        }

        private function GetContactType($id)
        {
            $q = "SELECT contactType FROM contacts WHERE id = $id";

            foreach($this->_database->query($q) as $row)
            return $row["contactType"];
        }

        private function GetPersonName($id)
        {
            $q = "SELECT p.name FROM persons p JOIN contacts c on c.personId = p.id WHERE c.id = $id";

            foreach($this->_database->query($q) as $row)
            {
                return $row["name"];
            }
        }

        private function NotContactOwner($contactId)
        {
            $ownerId = $this->_authenticator->GetCurrentUserId();

            $q = "SELECT 1 FROM persons p JOIN contacts c on c.personId = p.id WHERE c.id = $contactId AND p.ownerId = $ownerId;";
            $count = 0;

            foreach($this->_database->query($q) as $row)
            {
                $count++;
            }
            return $count ===0;
        }
        private function HandleFormData()
        {
            if(!isset($_POST["btnSub"])) return;

            $contactId = $_POST["id"];

            $q = "DELETE FROM contacts WHERE id = $contactId";

            $this->_database->beginTransaction();

            if($this->_database->exec($q) !==1)
            {
                echo "Pogreska pri brisanju kontakta!";
                $this->_database->rollBack();
                return;
            }
            $this->_database->commit();

            $this->BackToLanding();
        }
        protected function PageRequiresAuthenticUser()		
        {			
            return true;		
        }

    }

    $site = new obrisiKontakt();
    $site->Display('AlgebraContacts obrisi kontakt');
    