<?php

class plgSystemSimple_Template_Engine extends JPlugin {

	public function onAfterInitialise () {
		JLoader::register('SimpleTemplateEngine', __DIR__ . '/helper.php');
	}

}
