<?php

class Catspro extends PreferredHandler {

	public function handle(){
		// TODO add actions to take here (Handle both GET and POST methods)
		// Create Dracula instance and execute the query method
		 switch ($this->getAction()){

             case 'fetch':
                 (new Logger())->json(
                     (new Dracula(null))->query(
                         "SELECT count(*) FROM produit NATURAL JOIN categorie",
                         null
                     )
                 );

		}
	}

}