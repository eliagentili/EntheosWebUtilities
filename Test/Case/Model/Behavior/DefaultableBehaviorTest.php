<?php
App::uses('DefaultableBehavior', 'EntheosWebUtilities.Model/Behavior');
require_once dirname(dirname(__FILE__)) . DS . 'models.php';

/**
 * DefaultableBehavior Test Case
 *
 */
class DefaultableBehaviorTest extends CakeTestCase {

/**
 * fixtures property
 *
 * @var array
 */
        public $fixtures = array('plugin.entheos_web_utilities.blank');

/**
 * Variabile che imposta i campi dei model da controllare nel before validate
 * @var array
 */
	public $settings = array(
                'fields' => array(
			'Model1' => array(
				'campo1' => 'init1',
				'campo2' => 'init2'
			),
			'Model2' => array(
				'campo1' => 'init1',
				'campo2' => 'init2',
				'campo3' => 'init3'
			)
		)
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Defaultable = new DefaultableBehavior();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Defaultable);
		parent::tearDown();
	}

/**
 * Funzione di test per la beforeValidate
 * @param  Model  $Model
 * @param  array  $options
 * @return void
 */
	public function testBeforeValidate() {
		// istanzio il model di test
		$TestModel = new Blank();
		// e attacco il behavior blank fields
		$TestModel->Behaviors->load('Defaultable', $this->settings);

		debug('Settings:');
		debug($this->settings);

        //imposto il data con dei valori di prova
        $data = array(
        	'Model1' => array(
        		'campo1' => 'testo',
        		'campo2' => ''
        	),
        	'Model2' => array(
        		'campo1' => 0,
        		'campo2' => '0',
                        'campo3' => null
        	),
        	'Model3' => array(
        		'campo1' => 0,
        		'campo2' => '0',
        		'campo3' => '',
        		'campo4' => 'testo'
        	)
        );
        $TestModel->data = $data;
        debug('Data:');
        debug($data);
        
        $result = $TestModel->Behaviors->Defaultable->beforeValidate($TestModel);
        $this->assertTrue($result);


        $expected = array(
        	'Model1' => array(
        		'campo1' => 'testo',
        		'campo2' => 'init2'
        	),
        	'Model2' => array(
        		'campo1' => 'init1',
        		'campo2' => 'init2',
                        'campo3' => 'init3'
        	),
        	'Model3' => array(
        		'campo1' => 0,
        		'campo2' => '0',
        		'campo3' => '',
        		'campo4' => 'testo'
        	)
        );
        $result = $TestModel->data;
        debug('Expected:');
        debug($expected);
        debug('Result:');
        debug($result);
        
        $this->assertEquals($expected, $result);
	}
}