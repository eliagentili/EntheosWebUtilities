<?php
App::uses('ModelBehavior', 'Model');

/**
 * Behavior per la gestione delle input vuote.
 *
 * Gestisce automaticamente le input lasciate vuote settando il valore a 0 o '' a seconda della tipologia del campo
 */
class DefaultableBehavior extends ModelBehavior {

/**
 * Inizializzo il behavior per il model con le impostazioni di default.
 *
 * Impostazioni:
 *
 * - fields: (array, optional) indentifca i campi del model da controllare
 * 	 e quindi da inizializzare se fossero vuoti. Specifica inoltre il valore con
 *   cui inizializzere il campo, secondo la seguente struttura:
 *
 * 		array fields[
 * 			'Model1' [
 *     	 		'nome_campo1' => 'valore_inizializzazione1'
 * 	 		    'nome_campo2' => 'valore_inizializzazione2'
 * 	 		],
 * 	 		'Model2' [
 * 	 			'nome_campo1' => 'valore_inizializzazione1'
 * 	 		]
 *     	]
 *   
 *   DEFAULTS TO: array()
 *
 * @param Model $Model Model che usa il behavior
 * @param array $settings Impostazioni da sovrascrivere per il model.
 * @return void
 */
	public function setup(Model $Model, $settings = array()) {
		// Impostazioni di default se non ne vengono passate altre
		if (!isset($this->settings)) {
			$this->settings = array('fields' => array());
		}
		$this->settings = array_merge($this->settings, $settings);
	}

/**
 * Eseguita prima di una save(). Controlla se i campi specificati nelle impostazioni
 * sono vuoti e in tal caso li inizializza con il rispettivo valore.
 *
 * @param Model $Model Model che usa il behavior
 * @param array $options
 * @return array
 */
	public function beforeValidate(Model $Model, $options = array()) {
		// Scorro i model presenti
	    foreach($Model->data as $m => $data)
	    {
	        // Controllo se vi sono campi per questo model
	        if(isset($this->settings['fields'][$m]))
	        {
	            // Scorro i fields per questo model nei settings
	            foreach($this->settings['fields'][$m] as $field => $value)
	            {
	                // Controllo se il campo esiste nel data ed è vuoto
	                if (array_key_exists($field, $data) && (!$data[$field] || is_null($data[$field])))
	                {
	                	debug('modifico campo: '.$data[$field]);
	                    $Model->data[$m][$field] = $value;
	                }
	            }
	        }
	    }
	    return parent::beforeValidate($Model, $options);
	}
}